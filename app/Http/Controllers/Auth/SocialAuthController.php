<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirectToProvider(string $provider)
    {
        if (!in_array($provider, ['facebook', 'google'])) {
            return redirect()->route('login')->with('error', 'Invalid social provider.');
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Social authentication is temporarily unavailable.');
        }
    }

    /**
     * Handle the callback from the provider.
     */
    public function handleProviderCallback(string $provider)
    {
        if (!in_array($provider, ['facebook', 'google'])) {
            return redirect()->route('login')->with('error', 'Invalid social provider.');
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Check if user already exists with this email
            $existingUser = User::where('email', $socialUser->getEmail())->first();
            
            if ($existingUser) {
                // Update social login information if not already set
                if (!$existingUser->{$provider . '_id'}) {
                    $existingUser->update([
                        $provider . '_id' => $socialUser->getId(),
                        $provider . '_token' => $socialUser->token,
                        $provider . '_refresh_token' => $socialUser->refreshToken,
                    ]);
                }
                
                Auth::login($existingUser);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'email_verified_at' => now(),
                    $provider . '_id' => $socialUser->getId(),
                    $provider . '_token' => $socialUser->token,
                    $provider . '_refresh_token' => $socialUser->refreshToken,
                    'avatar' => $socialUser->getAvatar(),
                ]);
                
                Auth::login($user);
            }
            
            return redirect()->intended('/dashboard');
            
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            return redirect()->route('login')->with('error', 'Authentication failed. Please try again.');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Social login failed: ' . $e->getMessage());
        }
    }
}
