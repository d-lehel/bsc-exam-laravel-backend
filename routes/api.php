<?php

use App\Http\Controllers\api\CoordinatesController;
use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\RegisterController;
use App\Http\Controllers\api\MessageController;
use App\Http\Controllers\api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// IF UNAUTHENTICATED
Route::post('login', [LoginController::class, 'login']);                               // user login
Route::post('register', [RegisterController::class, 'register']);                      // user registration

// IF AUTHENTICATED
Route::middleware('auth:api')->group(function () {

    // AUTHENTICATION
    Route::post('logout', [LoginController::class, 'logout']);                         // user logout

    // USER PROFILE
    Route::get('users/profile',[UserController::class,'show']);                        // show profile
    Route::put('users/profile',[UserController::class,'update']);                      // update profile

    // COORDINATE
    Route::post('users/coordinate', [CoordinatesController::class, 'store']);          // set coordinates

    // PRODUCTS
    Route::post('products/filtered', [ProductController::class, 'index']);             // show all product
    Route::get('products/{id}',[ProductController::class,'show']);                     // show one product
    Route::post('products', [ProductController::class, 'store']);                      // create one product
    Route::put('products/{id}', [ProductController::class, 'update']);                 // update one product
    Route::delete('products/{id}', [ProductController::class, 'destroy']);             // delete one product

    // MESSAGES
    Route::get('messages/inbox',[MessageController::class,'indexInbox']);              // show all inbox
    Route::get('messages/outbox',[MessageController::class,'indexOutbox']);            // show all outbox
    Route::get('messages/inbox/{id}', [MessageController::class, 'showInbox']);        // show one inbox element
    Route::get('messages/outbox/{id}', [MessageController::class, 'showOutbox']);      // show one outbox element
    Route::post('messages/request', [MessageController::class, 'storeRequest']);       // create one request
    Route::post('messages/response', [MessageController::class, 'storeResponse']);     // create one response
    Route::delete('messages/{id}', [MessageController::class, 'destroy']);             // delete one message

    // IF ADMIN
    Route::middleware('admin')->group(function () {
        Route::delete('users/profile',[UserController::class,'destroy']);              // delete profile
    });
});
