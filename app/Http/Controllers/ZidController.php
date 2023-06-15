<?php

namespace App\Http\Controllers;

use App\Http\Integrations\Zid\AuthConnector;
use App\Http\Integrations\Zid\Requests\GetProfileRequest;
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


    public function handleCallback(Request $request)
    {
        $code = $request->input('code');
        $state = $request->input('state');

        $expectedState = Session::pull('zidAuthState');

        $connector = new AuthConnector;

        try {
            $authorization = $connector->getAccessToken($code, $state, $expectedState);

            $connector->headers()->add('Authorization', 'Bearer ' . $authorization->accessToken);
            $connector->headers()->add('X-Manager-Token', $authorization->managerToken);

            $profileRequest = new GetProfileRequest();
            dd($connector->headers()->all());
            $response = $connector->send($profileRequest);
            dd($response->json());
        } catch (\Exception $e) {
            dd($e->getMessage());
        }


        return redirect()->route('home');
    }
}
