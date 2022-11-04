<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoordinatesController extends Controller
{
    public function store(Request $request)
    {
        // get the user
        $user = auth()->user();

        // set latitude and longitude
        $user->latitude=$request['latitude'];
        $user->longitude=$request['longitude'];
        $user->save();

        return response()->json([
            'message' => 'Sikeresen frissítve!',
        ], 200);
    }
}
