<?php

namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;

use Illuminate\Support\Facades\Auth;



class GoogleLoginController extends Controller
{
    public function redirectToGoogle()
    {
        // Redirect to Google for authentication

        return Socialite::driver('google')
            ->scopes(['openid','profile','email',
                'https://www.googleapis.com/auth/youtube',
            ])->with([
                'access_type' => 'offline',  // to get refresh token
                 'prompt' => 'consent', ])
            ->redirect();
    }

    /**
     * Handles the callback from Google and
     * redirects the user to the dashboard.
     */
    public function callback(Request $request)
    {

        // use  updateOrCreate

        try {

            $user = Socialite::driver('google')->user();
            $finduser = User::where('social_id', $user->id)
                ->orWhere('email', $user->email)->first();
                // dd($user);

            if ($finduser) {

                $finduser->update([
                    'social_id' => $user->id,
                    'token' => $user->token, // update this
                       // Only update refresh token if it's provided (won't overwrite existing)
                // 'refresh_token' => $googleUser->refreshToken ?? $existingUser->refresh_token,
                // 'expires_in' => $googleUser->expiresIn,
                ]);

                Auth::login($finduser);
            } else {
                $newUser = User::create([

                    'name' => $user->name,
                    'email' => $user->email,
                    'social_id' => $user->id,
                    'avatar'  => $user->avatar,
                    'password' => bcrypt('my-password'),
                    'token' => $user->token,
                    'refresh_token' => $user->refreshToken ?? null,
                    'expires_in' => $user->expiresIn ?? null,

                ]);
                Auth::login($newUser);
            }
            return redirect()->intended('dashboard');
        } catch (Exception $e) {

            return redirect()->route('login')->with('error', $e->getMessage());
        }
    }
}
