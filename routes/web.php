<?php

use App\Http\Controllers\MedicalRecordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;



// Guest routes
Route::middleware('guest')->group(function () {
    Route::view('/login', 'login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
// Authenticated routes
Route::middleware('auth')->group(function () {
    //  Route::get('/users/create', function () {
    //     return view('admin.new-user');
    // });

    Route::post('/users/save', [UserController::class, 'store'])->name('admin.users.store');
    // Route::get('/{any?}', fn () => view('layouts.app'))->where('any', '^(?!users).*$');
});
Route::get('/home', function () {
    return view('home');
})->middleware('auth')->name('home');
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [MedicalRecordController::class, 'index'])->name('records.index');
    Route::get('/records', [MedicalRecordController::class, 'index']);
    Route::get('/records/create', [MedicalRecordController::class, 'create'])->name('records.create');
    Route::post('/records', [MedicalRecordController::class, 'store'])->name('records.store');
    Route::get('/records/{record}', [MedicalRecordController::class, 'show'])->name('records.show');
    Route::get('/records/{record}/export-pdf', [MedicalRecordController::class, 'exportPdf'])->name('records.export-pdf');
});
Route::middleware('auth')->get('/user', fn () => auth()->user());
// Toute autre URL : on sert l’appli Vue
Route::middleware('auth')->get('/{any?}', fn () => view('layouts.app'))
     ->where('any', '.*');  // protégée
