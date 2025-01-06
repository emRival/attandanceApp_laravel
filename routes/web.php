<?php

use App\Http\Controllers\FaceRecognationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // redirect to admin/login
    return redirect('/admin/login');
});

Route::get('/{role}/faces/{id}', [FaceRecognationController::class, 'index'])->middleware('auth')->name('faces.index');
Route::post('/{role}/faces', [FaceRecognationController::class, 'store'])->middleware('auth')->name('faces.store');
