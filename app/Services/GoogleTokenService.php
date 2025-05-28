<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class GoogleTokenService
{

    public function getValidAccessToken(User $user)
    {
        if (!$user->token || !$user->expires_in || now()->greaterThanOrEqualTo($user->expires_in)) {
            return $this->refreshToken($user);
        }
        return $user->token;
    }
   
    public function refreshToken(User $user)
    {
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $user->refresh_token,
            'client_id' => ENV('GOOGLE_CLIENT_ID'),
            'client_secret' => ENV('GOOGLE_CLIENT_SECRET'),
            
        ]);

        if ($response->successful()) {
            $data = $response->json(); // Contains 'access_token', 'expires_in', etc.
               // Update user tokens in the database
        $user->update([
            'token' => $data['access_token'],
            'expires_in' => now()->addSeconds($data['expires_in']),
        ]);
           return $data['access_token'];

        }
        throw new \Exception('Failed to refresh token: ' . $response->body());
        
    }
}
