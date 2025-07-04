<?php

namespace Kernel\Auth\Guards;

use Kernel\Adapters\Adapter;
use Kernel\Contracts\Auth\Guard;

class TokenSSOGuard extends Adapter implements Guard
{

    public function getLoginUrl(){
        $loginUrl  = $this->config['login_url'] ?? '';
        $clientId  = $this->config['client_id'] ?? '';
        $redirect  = $this->getRedirectUrl();
        $state     = $this->getState();
        return replacePlaceholders($loginUrl, [
            'clientId'    => $clientId,
            'redirectUri' => rawurlencode($redirect),
            'state'       => $state,
        ]);
    }
    public function check(): bool
    {
        $user = wp_get_current_user();

        if ($user && $user->ID) {
            $expiresAt = (int) get_user_meta($user->ID, 'sso_expires_at', true);

            if (time() >= $expiresAt) {
                $this->refreshToken($user);
            }
            return true;
        }

        return false;
    }


    public function user() {
        $user = wp_get_current_user();
        return ($user && $user->ID) ? $user : null;
    }

    public function login($user)
    {
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);
    }


    public function logout()
    {
        $user = wp_get_current_user();
        if ($user && $user->ID) {
            delete_user_meta($user->ID, 'sso_access_token');
            delete_user_meta($user->ID, 'sso_refresh_token');
            delete_user_meta($user->ID, 'sso_expires_at');
            $this->revokeToken($user);
        }

        wp_logout();
    }



    public function attempt(array $credential)
    {
        $api_url = $this->config['validate_url'] ?? '';
        $clientId = $this->config['client_id'] ?? '';

        if(isset($credential['state']) && !$this->verifyState($credential['state'])){
            return false;
        }

        appLogger(json_encode($credential));
        // Exchange code for token
        $response = wp_remote_post($api_url, [
            'body' => [
                'grant_type'   => 'authorization_code',
                'client_id'    => $clientId,
                'scope'        => 'openid profile',
                'code'         => $credential['code'],
                'redirect_uri' => $this->getRedirectUrl(),
                'state'        => $credential['state'] ?? '',
            ],
        ]);

        appLogger(json_encode($response));

        if (is_wp_error($response)) {
            appLogger('error in response');
            return false;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        appLogger(json_encode($body));
        if (!isset($body['access_token'])) {
            return false;
        }

        $jwt = $body['access_token'];
        $payload = $this->decodeJwt($jwt);
        if (!$payload || !isset($payload['sub'])) {
            return false;
        }

        $globalId = $payload['sub'];

        // Check for existing user by meta
        $users = get_users([
            'meta_key' => 'sso_global_id',
            'meta_value' => $globalId,
            'number' => 1,
            'count_total' => false,
        ]);

        if (!empty($users)) {
            $user = $users[0];
        } else {
            // Create user
            $firstName = sanitize_text_field($payload['given_name'] ?? '');
            $lastName = sanitize_text_field($payload['family_name'] ?? '');
            $displayName = trim($firstName . ' ' . $lastName);

            $username = sanitize_user($payload['preferred_username'] ?? 'user_' . wp_generate_password(5, false));
            $email = sanitize_email($payload['email'] ?? $username . '@donap.ir');

            $user_id = wp_create_user($username, wp_generate_password(), $email);
            if (is_wp_error($user_id)) {
                return false;
            }
            wp_update_user([
                'ID' => $user_id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'display_name' => $displayName,
            ]);

            update_user_meta($user_id, 'sso_global_id', $globalId);
            update_user_meta($user_id, 'sso_access_token', $body['access_token']);
            update_user_meta($user_id, 'sso_refresh_token', $body['refresh_token']);
            update_user_meta($user_id, 'sso_expires_at', time() + $body['exp']);
            update_user_meta($user_id, 'sso_mobile_number', $body['mobileNumber']);
            update_user_meta($user_id, 'sso_national_id', $body['nationalId']);

            $user = get_user_by('id', $user_id);
        }

        $this->login($user);
        $this->getUserInfo($user);

        return $user;
    }

    public function refreshToken($user)
    {
        $refreshToken = get_user_meta($user->ID, 'sso_refresh_token', true);
        if (!$refreshToken) {
            return false;
        }

        $validateUrl = $this->config['validate_url'] ?? '';
        $clientId   = $this->config['client_id'] ?? '';
        $response = wp_remote_post($validateUrl, [
            'body' => [
                'grant_type' => 'refresh_token',
                'client_id' => $clientId,
                'refresh_token' => $refreshToken,
            ],
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (!isset($body['access_token'])) {
            return false;
        }

        update_user_meta($user->ID, 'sso_access_token', $body['access_token']);
        update_user_meta($user->ID, 'sso_refresh_token', $body['refresh_token']);
        update_user_meta($user->ID, 'sso_expires_at', time() + $body['exp']);

        return true;
    }
    private function decodeJwt($jwt)
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            return null;
        }

        $header    = json_decode(base64_decode(strtr($parts[0], '-_', '+/')), true);
        $payload   = base64_decode(strtr($parts[1], '-_', '+/'));
        $signature = base64_decode(strtr($parts[2], '-_', '+/'));

        if (!empty($this->config['public_key'])) {
            $data  = $parts[0] . '.' . $parts[1];
            $pub   = openssl_pkey_get_public($this->config['public_key']);
            if (!$pub || openssl_verify($data, $signature, $pub, OPENSSL_ALGO_SHA256) !== 1) {
                return null;
            }
        }

        return json_decode($payload, true);
    }

    public function getRedirectUrl()
    {
        return $this->config['redirect_url'] ?? home_url();
    }

    public function getState()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $state = wp_generate_password(12, false);
        $_SESSION['sso_state'] = $state;
        return $state;
    }

    public function verifyState($state)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['sso_state']) && hash_equals($_SESSION['sso_state'], $state);
    }

    public function getUserInfo($user)
    {
        $url = $this->config['user_info_url'] ?? '';
        if (!$url) {
            return null;
        }
        $token = get_user_meta($user->ID, 'sso_access_token', true);
        $response = wp_remote_get($url, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);
        if (is_wp_error($response)) {
            return null;
        }
        return json_decode(wp_remote_retrieve_body($response), true);
    }

    public function revokeToken($user)
    {
        $logoutUrl = $this->config['logout_url'] ?? '';
        if (!$logoutUrl) {
            return;
        }
        $token = get_user_meta($user->ID, 'sso_refresh_token', true);
        if (!$token) {
            return;
        }
        wp_remote_post($logoutUrl, [
            'body' => [
                'token' => $token,
            ],
        ]);
    }
}
