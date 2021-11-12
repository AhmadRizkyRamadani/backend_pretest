<?php

use App\Http\Controllers\BillController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogicTestController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get("/", [Controller::class, "index"])->name("login");
Route::get("/set_session", [Controller::class, "set_session"]);
Route::middleware("CheckStatusLogin")->group(function(){
    Route::get("/logout", [Controller::class, "logout"]);
    Route::get("/dashboard", [LogicTestController::class, "index"]);

    /** ========== PRODUCT =========== */
    Route::get("/product", [Controller::class, "product"]);
    Route::post("/product/store", [Controller::class, "product_store"]);
    Route::get("/product/{id}/delete", [Controller::class, "product_delete"]);
    Route::post("/product/{id}/update", [Controller::class, "product_update"]);

    /** ========== INVOICE =========== */
    Route::get("/invoice", [InvoiceController::class, "index"]);
    Route::get("/invoice/create", [InvoiceController::class, "create"]);
    Route::post("/invoice/store", [InvoiceController::class, "store"]);
    Route::get("/invoice/{id}/detail", [InvoiceController::class, "detail"]);

    /** ========== BILL ============== */
    Route::get("/bill", [BillController::class, "index"]);
    Route::get("/bill/create", [BillController::class, "create"]);
    Route::post("/bill/store", [BillController::class, "store"]);
    Route::get("/bill/{id}/detail", [BillController::class, "detail"]);
    Route::get("/bill/{id}/payment", [BillController::class, "payment"]);
    Route::post("/bill/{id}/pay", [BillController::class, "pay"]);
    Route::get("/bill/pay", [BillController::class, "pay_index"]);
    Route::get("/bill/{id}/download_pdf", [BillController::class, "download_pdf"]);

    /** ========== BILL ============== */
    Route::get("/transaction_report", [BillController::class, "index"]);

    Route::get("/logic_test", [LogicTestController::class, "check"]);
});