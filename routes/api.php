<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\Auth\UserRegistrationController;
use App\Http\Controllers\Api\v1\Auth\UserLoginController;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\RoleController;
use App\Http\Controllers\Api\v1\ProfileController;
use App\Enums\TokenAbility;
use App\Http\Controllers\Api\v1\ProductLabelController;
use App\Http\Controllers\Api\v1\ProductController;


/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/


Route::get('/health-check', function () {
    return response()->json([
        'status' => 200,
        'message' => 'ok',
        'data' => null
    ]);
});


/*Route::post('/logout', function (Request $request) {
    return response()->json([
        'success' => true
    ]);
});*/

Route::group(['prefix' => 'v1'], function (){

    Route::post('/register', [UserRegistrationController::class, 'register'])->name('auth.register');

    Route::post('/login', [UserLoginController::class, 'login'])->name('auth.login');

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [UserLoginController::class, 'logout']);

        Route::resources([

            'users' => UserController::class,

            'roles' => RoleController::class,

            'tags' => ProductLabelController::class,

            'products' => ProductController::class,

        ]);

        Route::put('/profile/{id}', [ProfileController::class, 'updateProfile'])->name('profile.update');

    });


    Route::middleware('auth:sanctum', 'ability:' . TokenAbility::ISSUE_ACCESS_TOKEN->value)->group(function () {
        Route::get('/auth/refresh-token', [UserLoginController::class, 'refreshToken']);
    });

});




