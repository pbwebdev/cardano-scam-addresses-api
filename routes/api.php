<?php

use App\Http\Controllers\WalletController;
use App\Http\Controllers\WebsiteController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'permission:write_wallet'])->apiResource('addresses', WalletController::class)
    ->except(['index', 'show']);
Route::apiResource('addresses', WalletController::class)->only(['index', 'show']);

Route::middleware(['auth:sanctum', 'permission:write_website'])->apiResource('websites', WebsiteController::class)
    ->except(['index', 'show']);
Route::apiResource('websites', WebsiteController::class)->only(['index', 'show']);
