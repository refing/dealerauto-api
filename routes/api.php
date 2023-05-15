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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Route::get('users', [UserController::class, 'index', 'test-user']);
// Route::post('users/create', [UserController::class, 'create', 'test-user']);
// Route::get('users/{id}', [UserController::class, 'show', 'test-user']);
// Route::get('users/delete/{id}', [UserController::class, 'destroy', 'test-user']);
// Route::post('users/update', [UserController::class, 'update', 'test-user']);

Route::post('login', [UserController::class, 'authenticate']);
Route::post('register', [UserController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [UserController::class, 'logout']);
    Route::get('get_user', [UserController::class, 'get_user']);

    Route::get('vehicles', [VehicleController::class, 'index']);
    Route::get('vehicles/{id}', [VehicleController::class, 'show']);
    Route::post('create', [VehicleController::class, 'store']);
    Route::put('update/{id}',  [VehicleController::class, 'update']);
    Route::delete('delete/{id}',  [VehicleController::class, 'destroy']);
    Route::get('stock',  [VehicleController::class, 'showstock']);
    

    Route::get('transaction', [VehicleTransactionController::class, 'index']);
    Route::get('transaction/{idtransaction}', [VehicleTransactionController::class, 'show']);
    Route::post('buy/{idvehicle}', [VehicleTransactionController::class, 'store']);
    Route::get('report',  [VehicleTransactionController::class, 'showreport']);
    // Route::put('update/{id}',  [VehicleController::class, 'update']);
    // Route::delete('delete/{id}',  [VehicleController::class, 'destroy']);
});