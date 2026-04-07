<?php

namespace App\Http\Controllers;

use App\Models\FcmToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class NotifController extends Controller
{
    public function saveToken(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required'
            ]);

            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'error' => 'User belum login'
                ], 401);
            }

            FcmToken::updateOrCreate(
                ['token' => $request->token],
                ['user_id' => $user->id]
            );

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    private function getAccessToken()
    {
        $client = new \Google_Client();

        $client->setAuthConfig(storage_path('app/firebase.json'));
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $client->fetchAccessTokenWithAssertion();

        return $client->getAccessToken()['access_token'];
    }

    public function sendNotif($token, $title, $body)
    {
        $accessToken = $this->getAccessToken();

        $response = Http::withToken($accessToken)->post(
            'https://fcm.googleapis.com/v1/projects/kaih-96705/messages:send',
            [
                "message" => [
                    "token" => $token,
                    "notification" => [
                        "title" => $title,
                        "body" => $body
                    ]
                ]
            ]
        );

        return $response->json();
    }
}
