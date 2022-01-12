<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;

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

Route::get('/ping', function (Request $request) {
    return ['pong'=>true];
});

Route::post('/createUser', [UserController::class, 'createUser']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/user/{userId}/avatar', [UserController::class, 'updateAvatar']);

Route::get('/{accountNumber}/saldo', [AccountController::class, 'getSaldo']);

Route::post('/{accountNumber}/deposit', [TransactionController::class, 'deposit']);
Route::post('/{accountNumber}/withdraw', [TransactionController::class, 'withdraw']);
Route::get('/{accountNumber}/transactions', [TransactionController::class, 'getAllTransactions']);