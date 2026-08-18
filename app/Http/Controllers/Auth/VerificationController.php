<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mews\Captcha\Facades\Captcha;

class VerificationController extends Controller
{
    public function show(Request $request)
    {
        return view('auth.verify-code', ['email' => $request->email]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'verification_code' => 'required|numeric',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('status', 'Email already verified. Please login.');
        }

        if ($user->verification_code !== $request->verification_code) {
            return back()->withErrors(['verification_code' => 'Invalid verification code.']);
        }

        if (Carbon::now()->isAfter($user->verification_code_expires_at)) {
            // TODO: allow resending code
            return back()->withErrors(['verification_code' => 'Verification code has expired.']);
        }

        $user->markEmailAsVerified();
        $user->verification_code = null;
        $user->verification_code_expires_at = null;
        $user->save();

        return redirect()->route('login')->with('status', 'Email successfully verified. You can now login.');
    }

    public function reloadCaptcha()
    {
        return response()->json(['captcha'=> Captcha::img('flat')]);
    }
}
