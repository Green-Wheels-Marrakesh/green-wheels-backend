<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleAttachmentController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\BikeController;
use App\Http\Controllers\BikeVariantController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ReferenceController;
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
        'persons' => PersonController::class,
        'admins' => AdminController::class,
        'employees' => EmployeeController::class,
        'clients' => ClientController::class,
        'operations' => OperationController::class,
        'bookings' => BookingController::class,
        'bikes' => BikeController::class,
        'bikeVariants' => BikeVariantController::class,
        'articles' => ArticleController::class,
        'references' => ReferenceController::class,
        'attachments' => AttachmentController::class,
        'articleAttachments' => ArticleAttachmentController::class,
    ]);
});
require __DIR__.'/auth.php';
