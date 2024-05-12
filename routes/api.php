<?php

use App\Http\Controllers\AD\LogController;
use App\Http\Controllers\AD\ManageDataController;
use App\Http\Controllers\AD\ManageLogController;
use App\Http\Controllers\AD\ManageServiceController;
use App\Http\Controllers\AD\ManageUserController;
use App\Http\Controllers\AD\ThongKeController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\US\DataController;
use App\Http\Controllers\US\HistoryController;
use App\Http\Controllers\US\AuthController;
use App\Http\Controllers\US\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post("/login", [AuthController::class, "login"]);
Route::post("/login", [AuthController::class, "register"]);
Route::get("/datas", [DataController::class, "getData"]);
Route::get('/file/{folder?}/{path?}/{name?}', [FileController::class, 'getFile']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    // Route::post('/service/update', [ServiceController::class, "HandleService"])->name("updateService");
    // Route::post('/service/add/{type}', [ServiceController::class, "addService"])->name("addService");
    // Route::post('/service/delete', [ServiceController::class, "deleteService"])->name("deleteService");
    // Route::post('/service/show/{id}', [ServiceController::class, "detailService"])->name("detailService");

    // Route::get('/settings', [ServiceController::class, 'Settings']);
    // Route::get('/notify', [ServiceController::class, 'notify']);
    // Route::get('/thong-ke', [ThongKeController::class, 'ThongKeDoanhThu']);
    // Route::get('/lich_su_hoat_dong', [ServiceController::class, 'LichSuHoatDong']);
    // Route::get('/trans', [ServiceController::class, 'CongTruTien']);
    // Route::post('/trans', [ServiceController::class, 'HandleCongTruTien']);
    // Route::get('/users', [ManageUserController::class, 'ManageUsers']);
    // Route::post('/users/detail', [ManageUserController::class, 'detailUser']);
    // Route::get('/history-bank', [ServiceController::class, 'viewHistoryBank']);

    Route::get("/deposit-history", [HistoryController::class, "getDepositHistory"]);
    Route::get("/order-history", [OrderController::class, "getOrders"]);
    Route::get("/order/{order}/view", [OrderController::class, "getOrderDetail"]);
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum', 'ability:can-any']], function () {
    Route::apiResource('service', ManageServiceController::class);
    Route::apiResource('note', ManageLogController::class)->except(['store']);
    Route::apiResource('data', ManageDataController::class);
});
