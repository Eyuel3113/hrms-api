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
        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password',
        ], 401);
    }

    // Delete ALL old tokens (clean start)
    $user->tokens()->delete();

    // Create token that expires in 2 hours
    $token = $user->createToken('web-login', ['*'], now()->addHours(2))->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Login successful',
        'user'    => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
       
        ],
    ])->cookie(
        'token',                         // name
        $token,                          // value
        60 * 24 * 365,                   // cookie lives 1 year (refresh token)
        '/',                             // path
        null,                            // domain
        env('APP_ENV') === 'production', // secure on Railway
        true,                            // HttpOnly → XSS safe
        false,
        'lax'
    );
}

    public function logout(Request $request)
{
    // Delete ALL tokens (not just current one)
    $request->user()->tokens()->delete();

    return response()->json([
        'success' => true,
        'message' => 'Logged out successfully'
    ])->withCookie(cookie()->forget('hr_token')); // ← clears the cookie
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
