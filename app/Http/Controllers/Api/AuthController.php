<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SystemNotification;

class AuthController extends Controller
{

    /**
     * Login
     * 
     * Authenticate a user and return access tokens.
     * 
     * @group Authentication
     * @bodyParam email string required The user's email. Example: user@example.com
     * @bodyParam password string required The user's password. Example: password
     * @response 200 {
     *  "success": true,
     *  "message": "Login successful",
     *  "user": { ... },
     *  "token": "...",
     *  "refresh_token": "..."
     * }
     */
    public function login(LoginRequest $request)
{
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $user->tokens()->delete();

    $token = $user->createToken('auth_token', ['*'], now()->addHours(24))->plainTextToken;
    $refresh = $user->createToken('refresh_token', ['*'], now()->addDays(365))->plainTextToken;

    $isProduction = env('APP_ENV') === 'production';

    // Return tokens in response body for Bearer auth AND set as cookies for cookie-based auth
    return response()->json([
        'success' => true,
        'message' => 'Login successful',
        'user' => $user->only(['id', 'name', 'email']),
        'token' => $token,  // For Bearer token authentication
        'refresh_token' => $refresh,  // For Bearer token refresh
    ])
    ->cookie('auth_token', $token, 60*24, '/', null, $isProduction, true, false, 'lax')
    ->cookie('refresh_token', $refresh, 60*24*365, '/', null, $isProduction, true, false, 'lax');
}

    /**
     * Logout
     * 
     * Revoke the current access token.
     * 
     * @group Authentication
     * @response 200 {
     *  "message": "Logged out"
     * }
     */
    public function logout(Request $request)
{
    $request->user()->tokens()->delete();

    return response()->json(['message' => 'Logged out'])
        ->withCookie(cookie()->forget('auth_token'))
        ->withCookie(cookie()->forget('refresh_token'));
}

    /**
     * Get User Profile
     * 
     * Get the authenticated user's profile.
     * 
     * @group Authentication
     * @response 200 {
     *  "id": "uuid",
     *  "name": "John Doe",
     *  "email": "john@example.com",
     *  ...
     * }
     */
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    /**
     * Refresh Token
     * 
     * Refresh the authentication token using a valid refresh token.
     * 
     * @group Authentication
     * @response 200 {
     *  "success": true
     * }
     */
    public function refresh(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');

        if (!$refreshToken) {
            return response()->json(['message' => 'Refresh token missing'], 401);
        }

        // Find the token record (including revoked check)
        $tokenRecord = PersonalAccessToken::findToken($refreshToken);
        if (!$tokenRecord) {
            return response()->json(['message' => 'Invalid refresh token'], 401);
        }

        $user = $tokenRecord->tokenable;

        // Issue a new access token (do not revoke existing tokens)
        $newToken = $user->createToken('auth_token', ['*'], now()->addHours(24))->plainTextToken;

        $isProduction = env('APP_ENV') === 'production';

        return response()->json(['success' => true, 'token' => $newToken])
            ->cookie('auth_token', $newToken, 60*24, '/', null, $isProduction, true, false, 'lax');
    }


      /**
     * Forget-password
     * 
     * Authenticate a user and return access tokens.
     * 
     * @group Authentication
     * @bodyParam email string required The user's email. Example: user@example.com
     * @response 200 {
     *  "success": true,
     *  "message": "forget password link sent",
     *  "user": { ... },
     *  "token": "...",
     *  "refresh_token": "..."
     * }
     */

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
  /**
     * Reset Password
     * 
     * Authenticate a user and return access tokens.
     * 
     * @group Authentication
     * @bodyParam token string required The password reset token. Example: tokenstring
     * @bodyParam email string required The user's email. Example:
     * @response 200 {
     *  "success": true,
     *  "message": "Login successful",
     *  "user": { ... },
     *  "token": "...",
     *  "refresh_token": "..."
     * }
     */
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

/**
 * Change Password
 *
 * Allow authenticated admin to change their password.
 *
 * @group Settings
 * @authenticated
 * @bodyParam current_password string required Current password.
 * @bodyParam new_password string required New password (min 4 characters).
 * @bodyParam new_password_confirmation string required Must match new_password.
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function changePassword(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:4|confirmed',
    ]);

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            'message' => 'Current password is incorrect'
        ], 400);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

           // Notify HR (Placeholder: first user)
        $admin = User::first();
        if ($admin) {
            $admin->notify(new SystemNotification(
                'Password Changed',
                "You Changed Your Password Successfully.",
                'info',
                "/settings"
            ));
        }

    return response()->json([
        'message' => 'Password changed successfully'
    ]);
}

/**
 * Change Email
 *
 * Allow authenticated user to change their email address.
 * Requires current password for security.
 *
 * @group Settings
 * @authenticated
 * @bodyParam current_password string required Current password for verification.
 * @bodyParam new_email string required New email address.
 * @bodyParam new_email_confirmation string required Must match new_email.
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function changeEmail(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'current_password' => 'required',
        'new_email' => 'required|email|unique:users,email,' . $user->id,
        'new_email_confirmation' => 'required|same:new_email',
    ]);

    // Verify current password
    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            'message' => 'Current password is incorrect'
        ], 400);
    }

    // Update email
    $oldEmail = $user->email;
    $user->email = $request->new_email;
    $user->save();

       // Notify HR (Placeholder: first user)
        $admin = User::first();
        if ($admin) {
            $admin->notify(new SystemNotification(
                'Email Address Changed',
                "You Changed Your Email Adress to {$user->email}.",
                'info',
                "/settings"
            ));
        }
    // $user->notify(new EmailChangedNotification($oldEmail, $request->new_email));

    return response()->json([
        'message' => 'Email changed successfully',
        'new_email' => $user->email
    ]);
}

}
