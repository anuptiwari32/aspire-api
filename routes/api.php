<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('applyloan', [ApiController::class, 'createRequest']);
    Route::put('approveloan/{loan_id}', [ApiController::class, 'approveRequest'])->middleware('isAdmin');
    Route::get('getloans', [ApiController::class, 'getLoans']);
    Route::put('payloan/{pay_id}', [ApiController::class, 'payLoan']);
    
});