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

// All installer routes are protected by IsNotInstalled so they cannot be
// reached once the application has been installed. This prevents a serious
// security issue where an attacker could wipe the database by hitting
// /installing or /final after installation.
Route::group(['middleware' => ['IsNotInstalled', 'XSS']], function () {
    Route::get('install',     [InstallerController::class, 'index'])->name('install.index');
    Route::post('installing', [InstallerController::class, 'installing'])->name('installing');
    Route::get('final',       [InstallerController::class, 'finish'])->name('final');
});
