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

Route::middleware('guest')->group(function () {
    Route::view('/login', 'login')->name('login');   // <-- ajouter ceci
    Route::post('/login', [AuthController::class, 'login']);
});
// Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    // Route::apiResource('records', MedicalRecordController::class);
    // Page d'accueil avec la liste des dossiers
    Route::get('/', [MedicalRecordController::class, 'index'])->name('records.index');
    Route::get('/records', [MedicalRecordController::class, 'index']);

    // Formulaire de création
    Route::get('/records/create', [MedicalRecordController::class, 'create'])->name('records.create');

    // Sauvegarde d'un nouveau dossier
    Route::post('/records', [MedicalRecordController::class, 'store'])->name('records.store');

    // Affichage d'un dossier spécifique
    Route::get('/records/{record}', [MedicalRecordController::class, 'show'])->name('records.show');

    // Export PDF
    Route::get('/records/{record}/export-pdf', [MedicalRecordController::class, 'exportPdf'])->name('records.export-pdf');
});

// Toute autre URL : on sert l’appli Vue
Route::get('/{any?}', function () {
    return view('app');
})->where('any', '.*')->middleware('auth');  // protégée

