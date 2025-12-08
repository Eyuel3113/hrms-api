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

    $token = $user->createToken('auth_token', ['*'], now()->addMinutes(30))->plainTextToken;
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
    ->cookie('auth_token', $token, 30, '/', null, $isProduction, true, false, 'lax')
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
        $user = $request->user();
        
        // Do NOT delete all tokens. Just issue a new access token.
        // Ideally, we should check if the current token capability includes 'refresh_token' if we used capabilities.
        // For now, we assume the middleware 'auth:sanctum' let us in.

        $newToken = $user->createToken('auth_token', ['*'], now()->addMinutes(30))->plainTextToken;

        $isProduction = env('APP_ENV') === 'production';

        return response()->json(['success' => true, 'token' => $newToken])
            ->cookie('auth_token', $newToken, 30, '/', null, $isProduction, true, false, 'lax');
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
}
