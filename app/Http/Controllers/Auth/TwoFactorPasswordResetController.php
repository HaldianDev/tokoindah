<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

class TwoFactorPasswordResetController extends Controller
{
    public function show()
    {
        return view('auth.two-factor-password-reset');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'two_factor_code' => 'required|numeric',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->two_factor_secret) {
            return back()->withErrors(['email' => 'Invalid details or 2FA not enabled.']);
        }

        $provider = app(TwoFactorAuthenticationProvider::class);

        if (!$provider->verify(decrypt($user->two_factor_secret), $request->two_factor_code)) {
            return back()->withErrors(['two_factor_code' => 'Invalid two-factor authentication code provided.']);
        }

        // Manually log in the user
        Auth::login($user);
        
        // Create a password reset token
        $token = Password::createToken($user);

        // Redirect to the password reset form
        return redirect()->route('password.reset', ['token' => $token, 'email' => $user->email]);
    }
}
