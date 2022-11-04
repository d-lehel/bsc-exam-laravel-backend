<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;


class RegisterController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $validatedUser = $request->validated();
        $validatedUser['password'] = bcrypt($validatedUser['password']);
        $validatedUser['role'] = "user";

        User::create($validatedUser);

        return response()->json(
            ['message' => 'Sikeres regisztráció!',], 201
        );
    }
}


