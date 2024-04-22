<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleAttachmentController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\BikeController;
use App\Http\Controllers\BikeVariantController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\SellingController;
use App\Http\Controllers\SellingDetailController;
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
    Route::group([
        'prefix' => 'dashboard',
    ], function () {
        Route::group([
            'prefix' => 'client',
        ], function () {
            Route::get('nb', [DashboardController::class, 'getNbClients']);
        });
        Route::group([
            'prefix' => 'booking',
        ], function () {
            Route::get('nb', [DashboardController::class, 'getNbBookings']);
            Route::get('price', [DashboardController::class, 'getTotalPriceBookings']);
            Route::group([
                'prefix' => 'chart',
            ], function () {
                Route::get('nb/{year}', [DashboardController::class, 'getNbBookingsByYear']);
                Route::get('price/{year}', [DashboardController::class, 'getTotalPriceBookingsByYear']);
            });
        });
        Route::group([
            'prefix' => 'selling',
        ], function () {
            Route::get('price', [DashboardController::class, 'getTotalPriceSellings']);
            Route::group([
                'prefix' => 'chart',
            ], function () {
                Route::get('price/{year}', [DashboardController::class, 'getTotalPriceSellingsByYear']);
            });
        });
        Route::group([
            'prefix' => 'operation',
        ], function () {
            Route::get('last', [DashboardController::class, 'getLastOperations']);
            Route::get('price', [DashboardController::class, 'getTotalPriceOperations']);
            Route::group([
                'prefix' => 'chart',
            ], function () {
                Route::get('price/{year}', [DashboardController::class, 'getTotalPriceOperationsByYear']);
            });
        });
        Route::group([
            'prefix' => 'bike',
        ], function () {
            Route::get('nb', [DashboardController::class, 'getNbBikes']);
            Route::get('availability', [DashboardController::class, 'getBookings']);
        });
    });
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
        'sellings' => SellingController::class,
        'sellingDetails' => SellingDetailController::class,
        'bikes' => BikeController::class,
        'bikeVariants' => BikeVariantController::class,
        'products' => ProductController::class,
        'productVariants' => ProductVariantController::class,
        'articles' => ArticleController::class,
        'references' => ReferenceController::class,
        'attachments' => AttachmentController::class,
        'articleAttachments' => ArticleAttachmentController::class,
    ]);
});
require __DIR__.'/auth.php';
