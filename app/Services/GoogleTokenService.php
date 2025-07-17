<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class GoogleTokenService
{

    public function getValidAccessToken(User $user)
    {

        /**
         * Check if user doesn't have a token, or it's expired
         * if no token or token is expired or token expired refresh it
         */

        if (!$user->token || !$user->expires_in || now()->greaterThanOrEqualTo($user->expires_in)) {

            return $this->refreshToken($user);
        }

        // Token is still valid so return it
        return $user->token;
    }

    public function refreshToken(User $user)
    {
        // Send POST request to Google to refresh the access token
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $user->refresh_token,
            'client_id' => ENV('GOOGLE_CLIENT_ID'),
            'client_secret' => ENV('GOOGLE_CLIENT_SECRET'),

        ]);

        // If Google responds successfully
        if ($response->successful()) {

            // Extract JSON response and Contains 'access_token', 'expires_in', etc.
            $data = $response->json();

            // Update user's access token and expiry time in the database
            $user->update([
                'token' => $data['access_token'],
                'expires_in' => now()->addSeconds($data['expires_in']),
            ]);
            
            // Return the new access token
            return $data['access_token'];
        }
        // If request failed, throw an error with the response body
        throw new \Exception('Failed to refresh token: ' . $response->body());
    }
}
