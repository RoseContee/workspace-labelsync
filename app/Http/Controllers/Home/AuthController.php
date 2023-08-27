<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login() {
        return view('auth.login');
    }

    public function postLogin(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials, !!$request['remember'])) {
            return redirect()->route(RouteServiceProvider::HOME);
        }
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }

    public function forgot() {
        return view('auth.forgot-password');
    }

    public function postForgot(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
        ]);
        $status = Password::sendResetLink($credentials);
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success_message' => __($status)])
            : back()->withErrors(['email' => __($status)])->onlyInput('email');
    }

    public function reset(string $token) {
        return view('auth.reset-password', [
            'token' => $token
        ]);
    }

    public function postReset(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );
        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success_message', __($status))
            : back()->withErrors(['email' => [__($status)]])->withInput();
    }
}
