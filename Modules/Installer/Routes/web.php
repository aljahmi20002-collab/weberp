<?php

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

use Illuminate\Support\Facades\Route;
use Modules\Installer\Http\Controllers\InstallerController;

Route::group(['middleware'=>['IsNotInstalled','XSS']] ,function () {
    Route::get('install',  [InstallerController::class,'index'])->name('install.index');
});


Route::group(['middleware'=>['XSS']],function () {
    Route::post('installing',     [InstallerController::class,'installing'])->name('installing');
    Route::get('final',           [InstallerController::class,'finish'])->name('final');
});
