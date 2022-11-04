<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // vissza adom a bejelenkezett user adatait // profil
    public function show()
    {
        return auth()->user(); // all information
    }

    // profil frissitese
    public function update(Request $request)
    {

        $user = auth()->user();

        if ($request['name']) {
            $user->name = $request['name'];
        }
        if ($request['email']) {
            $user->email = $request['email'];
        }
        if ($request['password'] && !$request['password_confirmation']) {
            return response()->json(['Password confirmation doesn\'t sent!']);
        }
        if ($request['password'] && $request['password_confirmation']) {
            if ($request['password'] != $request['password_confirmation']) {
                return response()->json(['Passwords doesn\'t match!']);
            } else {
                $user->password = bcrypt($request['password']);
            }
        }
        $user->save();

        return response()->json(
            ['messages' => 'Sikeres frissítés!']
        );
    }

    public
    function destroy()
    {
        return response()->json(['todo' => 'delete profile']);
    }
}
