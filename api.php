<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
    Route::get('/users', [\App\Http\Controllers\UserController::class, 'list']);
    Route::get('/products', [\App\Http\Controllers\Products\ProductController::class, 'list'])->name('product.list');

    Route::middleware('abilities:scan')->group(function () {
        Route::post('scan', fn() => response()->json(['result' => 'You can scan']));
    });
});

Route::post('/auth/token/scan', [\App\Http\Controllers\Auth\AuthController::class, 'scanTokenCreation']);

Route::prefix('import')->group(function () {
    Route::post('/', \App\Http\Controllers\MoySklad\WebhookController::class);
    Route::post('/image', [\App\Http\Controllers\ImportController::class, 'image']);
});

Route::prefix('passports')->group(function () {
    Route::post('generate-qr', [\App\Http\Controllers\Passports\GenerateQrController::class, 'valueInput'])->name('valueInput');
});
