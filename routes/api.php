<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/user', [UserController::class, 'createUser']);
Route::post('user/login', [AuthController::class, 'login']);



Route::post('/transaction/transfert/multiple', [TransactionController::class, 'transferMultiple']);
Route::post('/transaction/transfert/simple', [TransactionController::class, 'transferSimple']);
Route::post('/transaction/transfert/planifie', [TransactionController::class, 'transferPlanifie']);
Route::post('/transaction/transfert/cancel', [TransactionController::class, 'cancelTransaction']);
Route::get('/transaction/transfert/historique', [TransactionController::class, 'getTransferHistory']);
