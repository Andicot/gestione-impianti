<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', [\App\Http\Controllers\Backend\DashboardController::class, 'show']);
    Route::get('backend/select2', [\App\Http\Controllers\Backend\Select2::class, 'response']);
    Route::get('/logout', [\App\Http\Controllers\LogOut::class, 'logOut']);
    Route::get('/metronic/{cosa}', [\App\Http\Controllers\LogOut::class, 'metronic']);
});

Route::get('/test', \App\Http\Controllers\TestController::class);
