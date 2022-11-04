<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use Laravel\Passport\Passport;

class LoginController extends Controller
{
    public function login(LoginUserRequest $request)
    {
        $validatedUser = $request->validated();
        $user = collect($validatedUser);

        // after validation, doesnt need anymore
        $user->forget('grant_type');
        $user->forget('client_id');
        $user->forget('client_secret');
        $user->forget('remember_me');

        if (!auth()->attempt($user->toArray())) {
            return response()->json(['message' => 'Hibás bejelentkezési adatok!',], 401);
        }
        if ($validatedUser['remember_me'])
            Passport::personalAccessTokensExpireIn(now()->addWeek(3));

        $user = auth()->user();
        $generatedToken = $user->createToken('accessToken');

        return response()->json([
            'access_token' => $generatedToken->accessToken,
            'expires_at' => $generatedToken->token->expires_at,
        ]);
    }
    public function logout()
    {
        $user = auth()->user();
        $user->token()->revoke();
        return response()->json(['message' => 'Sikeres kijelentkezés!']);
    }
}
