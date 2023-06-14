<?php

namespace App\Http\Integrations\Zid;

use Saloon\Http\Auth\AccessTokenAuthenticator;

class ZidAccessTokenAuthenticator extends AccessTokenAuthenticator
{
    public function __construct(
        readonly public string              $accessToken,
        readonly public string              $managerToken,
        readonly public ?string             $refreshToken = null,
        readonly public ?\DateTimeImmutable $expiresAt = null,
    ) {
        //
    }

    public function getManagerToken(): string
    {
        return $this->managerToken;
    }
}
