<?php

use App\Http\Controllers\MedicalRecordController;
use Illuminate\Support\Facades\Route;

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