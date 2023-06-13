<?php

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
Route::get('gateway', [App\Http\Controllers\APIGatewayController::class, 'index']); // tim 10, 11, 12
Route::post('gateway2', [App\Http\Controllers\APIGatewayController::class, 'isi_data_pasien_administrasi']); // tim 10
Route::post('gateway3', [App\Http\Controllers\APIGatewayController::class, 'isi_data_pasien_poliklinik']); // tim 10. 11
Route::post('gateway4/{id_pasien}', [App\Http\Controllers\APIGatewayController::class, 'isi_data_keterangan_pasien_poliklinik']); // tim 11
Route::post('gateway5', [App\Http\Controllers\APIGatewayController::class, 'isi_data_resep_apoteker']); // tim 11, 12