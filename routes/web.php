<?php

use App\Http\Controllers\MedicalRecordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


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

// Route::get('/', function () {
//     return view('welcome');
// });

// Guest routes
Route::middleware('guest')->group(function () {
    Route::view('/login', 'login')->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
// Authenticated routes
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
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*')->middleware('auth');  // protégée

