<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\BatchController;
use App\Http\Controllers\API\StorageController;
use App\Http\Controllers\API\ProviderController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\SubcategoryController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\RefundController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', [UserController::class, 'login'])->name('login');
Route::post('logout', [UserController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth:sanctum'], function() {

    // products
    Route::group(['prefix' => 'products', 'as' => 'products.'], function() {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/', [ProductController::class, 'store'])->name('create');
        Route::get('/available', [ProductController::class, 'available'])->name('available');
    });

    // batches
    Route::group(['prefix' => 'batches', 'as' => 'batches.'], function() {
        Route::get('/', [BatchController::class, 'index'])->name('index');
        Route::post('/', [BatchController::class, 'store'])->name('create');
        Route::get('/profit', [BatchController::class, 'profit'])->name('profit');
    });

    // storages
    Route::group(['prefix' => 'storages', 'as' => 'storages.'], function() {
        Route::get('/', [StorageController::class, 'index'])->name('index');
        Route::post('/', [StorageController::class, 'store'])->name('create');
    });

    // providers
    Route::group(['prefix' => 'providers', 'as' => 'providers.'], function() {
        Route::get('/', [ProviderController::class, 'index'])->name('index');
        Route::post('/', [ProviderController::class, 'store'])->name('create');
    });

    // categories
    Route::group(['prefix' => 'categories', 'as' => 'categories.'], function() {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/', [CategoryController::class, 'store'])->name('create');
    });

    // subcategories
    Route::group(['prefix' => 'subcategories', 'as' => 'subcategories.'], function() {
        Route::get('/', [SubcategoryController::class, 'index'])->name('index');
        Route::post('/', [SubcategoryController::class, 'store'])->name('create');
    });

    // clients
    Route::group(['prefix' => 'clients', 'as' => 'clients.'], function() {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::post('/', [ClientController::class, 'store'])->name('create');
    });

    // orders
    Route::group(['prefix' => 'orders', 'as' => 'orders.'], function() {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/', [OrderController::class, 'store'])->name('create');
    });

    // refunds
    Route::group(['prefix' => 'refunds', 'as' => 'refunds.'], function() {
        Route::get('/', [RefundController::class, 'index'])->name('index');
        Route::post('/', [RefundController::class, 'store'])->name('create');
    });
});


// For any other incorrect requests
Route::any('{path}', function() {
    return response()->json([
        'message' => 'API method not found'
    ], 404);
})->where('path', '.*');
