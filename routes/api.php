<?php

use App\Http\Controllers\AD\LogController;
use App\Http\Controllers\AD\ManageUserController;
use App\Http\Controllers\AD\ServiceController;
use App\Http\Controllers\AD\ThongKeController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('/service/update', [ServiceController::class, "HandleService"])->name("updateService");
    Route::post('/service/add/{type}', [ServiceController::class, "addService"])->name("addService");
    Route::post('/service/delete', [ServiceController::class, "deleteService"])->name("deleteService");
    Route::post('/service/show/{id}', [ServiceController::class, "detailService"])->name("detailService");

    Route::get('/settings', [ServiceController::class, 'Settings']);
    Route::get('/notify', [ServiceController::class, 'notify']);
    Route::get('/thong-ke', [ThongKeController::class, 'ThongKeDoanhThu']);
    Route::get('/lich_su_hoat_dong', [ServiceController::class, 'LichSuHoatDong']);


    Route::get('/trans', [ServiceController::class, 'CongTruTien']);
    Route::post('/trans', [ServiceController::class, 'HandleCongTruTien']);

    Route::get('/users', [ManageUserController::class, 'ManageUsers']);
    Route::post('/users/detail', [ManageUserController::class, 'detailUser']);

    // Route::view('/history-bank','Admin.history_bank');
    Route::get('/history-bank', [ServiceController::class, 'viewHistoryBank']);
    Route::group(['prefix' => 'manage'], function () {
        Route::get('/log', [LogController::class, 'viewIndex']);
        Route::post('/log/{log}/delete', [LogController::class, 'delete']);
    });
});
