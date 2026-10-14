<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AdminAuthController;
use App\Http\Controllers\Web\ModeracionWebController;



Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->group(function () {

    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/moderacion', [ModeracionWebController::class, 'index'])->name('admin.moderacion.index');
        Route::get('/moderacion/historial', [ModeracionWebController::class, 'historial'])->name('admin.moderacion.historial');
        Route::get('/moderacion/{id}', [ModeracionWebController::class, 'show'])->name('admin.moderacion.show');
        Route::post('/moderacion/{id}', [ModeracionWebController::class, 'action'])->name('admin.moderacion.action');
    });


});
