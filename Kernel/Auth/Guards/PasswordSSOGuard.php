<?php

namespace Kernel\Auth\Guards;

use Kernel\Adapters\Adapter;
use Kernel\Contracts\Auth\Guard;

class PasswordSSOGuard extends Adapter implements Guard
{
    public function getLoginUrl()
    {
        return $this->config['redirect_url'] ?? home_url();
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
        $apiUrl = $this->config['token_endpoint'] ?? '';
        $secret = $this->config['secret_key'] ?? '';

        $response = wp_remote_post($apiUrl, [
            'body' => [
                'grant_type' => 'password',
                'client_secret' => $secret,
                'username' => $credential['username'] ?? '',
                'password' => $credential['password'] ?? '',
            ],
        ]);

        if (is_wp_error($response)) {
            return false;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (!isset($body['access_token'])) {
            return false;
        }

        $payload = $this->decodeJwt($body['access_token']);
        if (!$payload || !isset($payload['sub'])) {
            return false;
        }

        $globalId = $payload['sub'];
        $users = get_users([
            'meta_key' => 'sso_global_id',
            'meta_value' => $globalId,
            'number' => 1,
            'count_total' => false,
        ]);

        if (!empty($users)) {
            $user = $users[0];
        } else {
            $firstName = sanitize_text_field($payload['given_name'] ?? '');
            $lastName = sanitize_text_field($payload['family_name'] ?? '');
            $displayName = trim($firstName . ' ' . $lastName);
            $username = sanitize_user($payload['preferred_username'] ?? 'user_' . wp_generate_password(5, false));
            $email = sanitize_email($payload['email'] ?? $username . '@donap.ir');
            $userId = wp_create_user($username, wp_generate_password(), $email);
            if (is_wp_error($userId)) {
                return false;
            }
            wp_update_user([
                'ID' => $userId,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'display_name' => $displayName,
            ]);

            update_user_meta($userId, 'sso_global_id', $globalId);
            update_user_meta($userId, 'sso_access_token', $body['access_token']);
            update_user_meta($userId, 'sso_refresh_token', $body['refresh_token']);
            update_user_meta($userId, 'sso_expires_at', time() + $body['exp']);
            update_user_meta($userId, 'sso_mobile_number', $body['mobileNumber'] ?? '');
            update_user_meta($userId, 'sso_national_id', $body['nationalId'] ?? '');

            $user = get_user_by('id', $userId);
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

        $response = wp_remote_post($this->config['token_endpoint'] ?? '', [
            'body' => [
                'grant_type' => 'refresh_token',
                'client_secret' => $this->config['secret_key'] ?? '',
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

    public function getUserInfo($user)
    {
        $url = $this->config['user_info_url'] ?? '';
        if (!$url) {
            return null;
        }
        $token = get_user_meta($user->ID, 'sso_access_token', true);
        $resp = wp_remote_get($url, [
            'headers' => [ 'Authorization' => 'Bearer ' . $token ],
        ]);
        if (is_wp_error($resp)) {
            return null;
        }
        return json_decode(wp_remote_retrieve_body($resp), true);
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
        wp_remote_post($logoutUrl, ['body' => ['token' => $token]]);
    }
}
