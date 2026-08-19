<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Find existing user by socialite_id or email
            $user = User::where('socialite_id', $googleUser->id)->orWhere('email', $googleUser->email)->first();

            if ($user) {
                // Update socialite_id if it was null
                if (!$user->socialite_id) {
                    $user->update(['socialite_id' => $googleUser->id]);
                }
                Auth::login($user);
            } else {
                // Create a new user
                $newUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'socialite_id' => $googleUser->id,
                    'password' => bcrypt(str()->random(16)), // Dummy password
                    'is_active' => true,
                ]);

                $newUser->assignRole('User');
                Auth::login($newUser);
            }

            return redirect()->intended('/dashboard');

        } catch (Exception $e) {
            return redirect('/login')->with('error', 'Something went wrong during Google authentication.');
        }
    }
}
