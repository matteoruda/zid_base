<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Zid\AuthConnector;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Saloon\Exceptions\InvalidResponseClassException;
use Saloon\Exceptions\InvalidStateException;
use Saloon\Exceptions\OAuthConfigValidationException;
use Saloon\Exceptions\PendingRequestException;

class ZidController extends Controller
{

    /**
     * @throws OAuthConfigValidationException
     */
    public function handleAuthorization(): RedirectResponse
    {
        $connector = new AuthConnector;

        $authorizationUrl = $connector->getAuthorizationUrl();

        Session::put('zidAuthState', $connector->getState());

        return redirect()->to($authorizationUrl);
    }

    /**
     * @throws InvalidResponseClassException
     * @throws OAuthConfigValidationException
     * @throws \ReflectionException
     * @throws InvalidStateException
     * @throws PendingRequestException
     */
    public function handleCallback(Request $request)
    {
        $code = $request->input('code');
        $state = $request->input('state');

        $expectedState = Session::pull('zidAuthState');

        $connector = new AuthConnector;

        $authorization = $connector->getAccessToken($code, $state, $expectedState);

        dd($authorization);

        return redirect()->route('home');
    }
}
