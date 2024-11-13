<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransferController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/user', [UserController::class, 'createUser']);
Route::post('user/login', [AuthController::class, 'login']);
Route::get('/users', [UserController::class, 'getUsers']);
Route::get('/connected-users', [UserController::class, 'getConnectedUsers'])->middleware('auth:api');



Route::post('/transaction/transfert/multiple', [TransactionController::class, 'transferMultiple'])->middleware('auth:api');
Route::post('/transaction/transfert/simple', [TransactionController::class, 'transferSimple']);
Route::post('/transaction/transfert/planifie', [TransactionController::class, 'transferPlanifie']);
Route::post('/transaction/transfert/cancel', [TransactionController::class, 'cancelTransaction']);
Route::get('/transaction/transfert/historique', [TransactionController::class, 'getTransferHistory'])->middleware('auth:api');



Route::middleware('auth:api')->group(function () {
    Route::get('/transfers', [TransferController::class, 'index'])->name('transfers.index');
    Route::get('/transfers/create', [TransferController::class, 'create'])->name('transfers.create');
    Route::post('/transfers/planified', [TransferController::class, 'PlanifierTransfer'])->name('transfers.store');
    Route::delete('/transfers/{id}', [TransferController::class, 'cancel'])->name('transfers.cancel');
});



Route::get('/test-qrcode', [UserController::class, 'testQrCode']);