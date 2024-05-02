<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class GoogleLoginController extends Controller
{


    private function getClient(): \Google_Client
    {
        // load our config.json that contains our credentials for accessing google's api as a json string
        $configJson = base_path() . '/config.json';

        // define an application name
        $applicationName = 'myfancyapp';

        // create the client
        $client = new \Google_Client();
        $client->setApplicationName($applicationName);
        $client->setAuthConfig($configJson);
        $client->setAccessType('offline'); // necessary for getting the refresh token
        $client->setApprovalPrompt('force'); // necessary for getting the refresh token
        // scopes determine what google endpoints we can access. keep it simple for now.
        $client->setScopes(
            [
                \Google\Service\Oauth2::USERINFO_PROFILE,
                \Google\Service\Oauth2::USERINFO_EMAIL,
                \Google\Service\Oauth2::OPENID,
                \Google\Service\Drive::DRIVE_METADATA_READONLY // allows reading of google drive metadata
            ]
        );
        $client->setIncludeGrantedScopes(true);
        return $client;
    }
    public function getAuthUrl(Request $request): JsonResponse
    {
        /**
         * Create google client
         */
        $client = $this->getClient();

        /**
         * Generate the url at google we redirect to
         */
        $authUrl = $client->createAuthUrl();

        /**
         * HTTP 200
         * 
         */

        return response()->json($authUrl, 200);
    }


    public function handleGoogleCallback(Request $request)
    {
        // Get the authorization code from the query parameters
        $code = $request->query('code');
        // dd($code);
        // Exchange the authorization code for an access token
        $response = Http::post('https://oauth2.googleapis.com/token', [
            'code' => $code,
            'client_id' => '1009633525470-lf9cv7bkbutm1njopj63thdpspmcu983.apps.googleusercontent.com',
            'client_secret' => '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',
            'redirect_uri' => 'https://www.codingcommunity.in',
            'grant_type' => 'authorization_code',
        ]);



        $accessToken = $response->json()['access_token'];

        dd($accessToken);

        // Fetch user data from Google API
        $userDataResponse = Http::withToken($accessToken)->get('https://www.googleapis.com/oauth2/v3/userinfo');

        $userData = $userDataResponse->json();

        // Now $userData contains the user's profile information
        // You can process it as needed, e.g., store it in your database or use it for authentication

        // Redirect or do any further processing as needed
    }
}
