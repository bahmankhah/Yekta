# Yekta WordPress Plugin

This plugin integrates Yekta SSO authentication with WordPress.
It provides a token-based login adapter and a simple admin interface for configuring SSO endpoints.

## Installation

1. Copy or clone the plugin folder into `wp-content/plugins/sso-wp` so that `sso-wp.php` is located directly inside the directory.
2. (Optional) Launch the provided Docker setup for a local test environment using:
   ```bash
   docker compose up
   ```
3. Activate **Yekta** from the WordPress **Plugins** page.

## Configuring SSO

After activation a new **SSO Settings** page appears under **Settings**. Fill in the following fields:

1. **Client ID** – OAuth client identifier.
2. **Login URL** – authorization endpoint of the SSO provider.
3. **Validate URL** – endpoint used to exchange the authorization code for tokens.
4. **Token Endpoint URL** – password or client credentials token endpoint.
5. **Secret Key** – the client secret for your SSO provider.
6. **Redirect URL** – where users should be redirected once authenticated.
7. **Logout URL** – optional single logout endpoint.
8. **Public Key** – RSA public key used to verify JWT signatures.
9. **User Info URL** – endpoint returning additional profile information.

Save the changes to update the stored options.

## Adapter settings

Underlying SSO behaviour is configured in `src/configs/adapters.php`. The default adapter is token based and defined as:

```php
return [
    'auth' => [
        'default' => 'sso',
        'contexts' => [
            'sso' => [
                'context'       => Kernel\Auth\Guards\TokenSSOGuard::class,
                'login_url'     => get_option('my_sso_login_url'),
                'client_id'     => get_option('my_sso_client_id'),
                'validate_url'  => get_option('my_sso_validate_url'),
                'redirect_url'  => get_option('my_sso_redirect_url'),
                'public_key'    => get_option('my_sso_public_key'),
            ]
        ]
    ],
];
```

To introduce another SSO provider, add a new context to the `contexts` array and change the `default` key. Example:

```php
'another' => [
    'context'       => Kernel\Auth\Guards\TokenSSOGuard::class,
    'login_url'     => get_option('another_login_url'),
    'client_id'     => get_option('another_client_id'),
    'validate_url'  => get_option('another_validate_url'),
    'redirect_url'  => get_option('another_redirect_url'),
],
```

Set `'default' => 'another'` to make it active.
