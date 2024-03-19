<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::middleware([
    'auth:sanctum',
])->group(function () {
    Route::get('user', function (Request $request) {
        return Auth::user();
    });
    /**
     * DEFINE CUSTOM ROUTES
     */
    /**
     * DEFINE RESOURCES ROUTES
     */
    Route::apiResources([
        'users' => UserController::class,
        'clients' => ClientController::class,
    ]);
});
require __DIR__.'/auth.php';
