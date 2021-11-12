<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\UserTransactionController;

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

Route::group(["prefix" => "user"], function(){
    Route::post("/register", [LoginController::class, "register"]);
    Route::post("/login", [LoginController::class, "login"]);
    Route::post("/verify_email", [LoginController::class, "verify_email"]);
    Route::post("/resend_verification", [LoginController::class, "resend_verification"]);
    Route::post("/reset_password", [LoginController::class, "reset_password"]);
    Route::post("/request_reset_password", [LoginController::class, "request_reset_password"]);
});
Route::group(["prefix" => "user_transaction"], function(){
    Route::post("/top_up", [UserTransactionController::class, "top_up"]);
    Route::post("/withdraw", [UserTransactionController::class, "withdraw"]);
    Route::post("/transfer", [UserTransactionController::class, "transfer"]);
    Route::post("/get_mutation_report", [UserTransactionController::class, "get_mutation_report"]);
});