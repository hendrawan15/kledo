<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\KasbonController;


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

//pegawai
Route::get('pegawai',[PegawaiController::class,'index'])->name('pegawai.index');
Route::post('pegawai',[PegawaiController::class,'store'])->name('pegawai.store');

//kasbon
Route::get('kasbon',[KasbonController::class,'index'])->name('kasbon.index');
Route::post('kasbon',[KasbonController::class,'store'])->name('kasbon.store');
Route::patch('kasbon/setujui/{id}',[KasbonController::class,'setujui']);
Route::post('kasbon/setujui-masal',[KasbonController::class,'setujuiMasal']);