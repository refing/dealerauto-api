<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleTransactionController;
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


Route::post('login', [UserController::class, 'authenticate']);
Route::post('register', [UserController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [UserController::class, 'logout']);
    Route::get('user', [UserController::class, 'get_user']);

    Route::get('vehicles', [VehicleController::class, 'index']);
    Route::get('vehicles/id/{id}', [VehicleController::class, 'show']);
    Route::post('vehicles/create', [VehicleController::class, 'store']);
    Route::put('vehicles/update/{id}',  [VehicleController::class, 'update']);
    Route::delete('vehicles/delete/{id}',  [VehicleController::class, 'destroy']);
    Route::get('vehicles/stock',  [VehicleController::class, 'showstock']);
    

    Route::get('transaction', [VehicleTransactionController::class, 'index']);
    Route::get('transaction/id/{idtransaction}', [VehicleTransactionController::class, 'show']);
    Route::post('transaction/buy/{idvehicle}', [VehicleTransactionController::class, 'store']);
    Route::get('transaction/report',  [VehicleTransactionController::class, 'showreport']);
});