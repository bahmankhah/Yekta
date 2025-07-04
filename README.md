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

1. **Token Endpoint URL** – the URL used to exchange the authorization code for access tokens.
2. **Secret Key** – the client secret for your SSO provider.
3. **Redirect URL (after login)** – where users should be redirected once authenticated.

Save the changes to update the stored options.

## Adapter settings

Underlying SSO behaviour is configured in `src/configs/adapters.php`. The default adapter is token based and defined as:

```php
return [
    'auth' => [
        'default' => 'sso',
        'contexts' => [
            'sso' => [
                'context' => Kernel\Auth\Guards\SSOGuard::class,
                'login_url' => 'https://tauth.platform.donap.ir/realms/donap/protocol/openid-connect/auth?client_id={clientId}&response_type=code',
                'client_id' => 'market',
                'validate_url' => 'https://tauth.platform.donap.ir/realms/donap/protocol/openid-connect/token'
            ]
        ]
    ],
];
```

To introduce another SSO provider, add a new context to the `contexts` array and change the `default` key. Example:

```php
'another' => [
    'context'     => Kernel\Auth\Guards\SSOGuard::class,
    'login_url'   => 'https://example.com/auth?client_id={clientId}&response_type=code',
    'client_id'   => 'my-client',
    'validate_url'=> 'https://example.com/token',
],
```

Set `'default' => 'another'` to make it active.
