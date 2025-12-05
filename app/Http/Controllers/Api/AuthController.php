<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // public function login(LoginRequest $request)
    // {
    //     $user = User::where('email', $request->email)->first();

    //     if (! $user || ! Hash::check($request->password, $user->password)) {
    //         return response()->json([
    //             'message' => 'Invalid email or password',
    //         ], 401);
    //     }

    //     // Delete old tokens (optional)
    //     $user->tokens()->delete();

    //     // Create API token
    //     $token = $user->createToken('api-token', ['*'])->plainTextToken;

    //     return response()->json([
    //         'message' => 'Login successful',
    //         'token'   => $token,
    //         'user'    => $user
    //     ]);
    // }

public function login(LoginRequest $request)
{
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Delete old tokens
    $user->tokens()->delete();

    // Access token: 2 hours
    $token = $user->createToken('token', ['*'], now()->addMinutes(30))->plainTextToken;

    // Refresh token: 1 year in HttpOnly cookie
    $refreshToken = $user->createToken('refresh-token', ['*'], now()->addYears(1))->plainTextToken;

    return response()->json([
        'success' => true,
        'accestoken' => $token,
        'token_type'   => 'Bearer',
        'expires_in'   => 30 * 60,
        'user'         => $user->only(['id', 'name', 'email']),
    ])->cookie(
    'refresh-token',                              // name
    $refreshToken,                                  // value
    60 * 24 * 365,                           // minutes (1 year)
    '/',                                     // path
    null,                                    // domain
    env('APP_ENV') === 'production',         // secure (true on Railway)
    true,                                    // HttpOnly → XSS safe
    false,                                   // raw
    'lax'                                    // sameSite
    );
}

public function logout(Request $request)
{
    $request->user()->tokens()->delete();

    return response()->json(['message' => 'Logged out'])
        ->withCookie(cookie()->forget('refresh_token'));
}

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

   public function forgotPassword(Request $request)
{
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
        ? response()->json(['message' => 'Reset link sent to your email'])
        : response()->json(['message' => 'Can not find this user'], 400);
}

public function resetPassword(Request $request)
{
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new \Illuminate\Auth\Events\PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? response()->json(['message' => 'Password has been successfully reset'])
        : response()->json(['message' => 'Invalid token or email'], 400);
}
}
