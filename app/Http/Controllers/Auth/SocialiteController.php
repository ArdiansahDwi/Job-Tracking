<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        }
        catch (\Exception $e) {
            return redirect('/login')->withErrors(['error' => 'Login with Google failed. Please try again.']);
        }

        $user = User::updateOrCreate(
        ['provider_id' => $googleUser->getId(), 'provider' => 'google'],
        [
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'avatar' => $googleUser->getAvatar(),
            'email_verified_at' => now(),
        ]
        );

        Auth::login($user, true);

        return redirect('/dashboard');
    }
}