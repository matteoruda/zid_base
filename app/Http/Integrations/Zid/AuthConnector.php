<?php

namespace App\Http\Integrations\Zid;

use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Http\Connector;
use Saloon\Traits\OAuth2\AuthorizationCodeGrant;
use Saloon\Traits\Plugins\AcceptsJson;

class AuthConnector extends Connector
{
    use AcceptsJson;
    use AuthorizationCodeGrant;
    /**
     * The Base URL of the API
     *
     * @return string
     */
    public function resolveBaseUrl(): string
    {
        return 'https://oauth.zid.sa';
    }

    /**
     * Default headers for every request
     *
     * @return string[]
     */
    protected function defaultHeaders(): array
    {
        return [];
    }

    /**
     * Default HTTP client options
     *
     * @return OAuthConfig
     */
    protected function defaultOauthConfig(): OAuthConfig
    {
        return OAuthConfig::make()
            ->setClientId(config('services.zid.client_id'))
            ->setClientSecret(config('services.zid.client_secret'))
            ->setRedirectUri(url()->route('callback'))
            ->setTokenEndpoint('/oauth/token')
            ->setAuthorizeEndpoint('/oauth/authorize')
            ;
    }
}
