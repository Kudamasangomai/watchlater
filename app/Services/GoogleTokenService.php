<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleTokenService
{
    /**
     * Refresh Google OAuth 2.0 access token using a refresh token.
     *
     * @param string $refreshToken
     * @param string $clientId
     * @param string $clientSecret
     * @return array
     * @throws \Exception
     */
//     public function refreshAccessToken($refreshToken, $clientId, $clientSecret)
//     {
//         $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
//             'grant_type' => 'refresh_token',
//             'refresh_token' => $refreshToken,
//             'client_id' => $clientId,
//             'client_secret' => $clientSecret,
//         ]);

//         if ($response->successful()) {
//             return $response->json(); // Contains 'access_token', 'expires_in', etc.
//         } else {
//             throw new \Exception('Failed to refresh token: ' . $response->body());
//         }
//     }
 }
