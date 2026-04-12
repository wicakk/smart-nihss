<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\Admin\FormBuilderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root → redirect to dashboard (auth middleware will redirect to login if unauthenticated)
Route::get('/', fn() => redirect()->route('dashboard'));
Route::get('/nihss', fn() => view('welcome-nihss'))->name('welcome-nihss');
Route::get('/nihss/calculator', fn() => view('calculator'))->name('nihss.calculator');
Route::get('/nihss/map-calculator', fn() => view('map-calculator'))->name('nihss.map-calculator');

// ── Authenticated routes ──────────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ── Patients ──────────────────────────────────────────────────────────
    Route::resource('patients', PatientController::class);

    // ── Assessments (nested: create/store under patient) ──────────────────
    Route::prefix('patients/{patient}/assessments')->name('patients.assessments.')->group(function () {
        Route::get('/create', [AssessmentController::class, 'create'])->name('create');
        Route::post('/',      [AssessmentController::class, 'store'])->name('store');
    });

    // ── Assessments (standalone: show/delete) ─────────────────────────────
    Route::get('/assessments/{assessment}',    [AssessmentController::class, 'show'])->name('assessments.show');
    Route::delete('/assessments/{assessment}', [AssessmentController::class, 'destroy'])->name('assessments.destroy');

    // ── Real-time score API ────────────────────────────────────────────────
    Route::post('/api/score', [AssessmentController::class, 'calculateScore'])->name('assessments.calculate');

    // ── Admin: Form Builder ────────────────────────────────────────────────
    Route::prefix('admin/form-builder')->name('admin.form-builder.')->group(function () {
        Route::get('/', [FormBuilderController::class, 'index'])->name('index');

        Route::post('/sections',             [FormBuilderController::class, 'storeSection'])->name('sections.store');
        Route::put('/sections/{section}',    [FormBuilderController::class, 'updateSection'])->name('sections.update');
        Route::delete('/sections/{section}', [FormBuilderController::class, 'destroySection'])->name('sections.destroy');

        Route::post('/questions',              [FormBuilderController::class, 'storeQuestion'])->name('questions.store');
        Route::put('/questions/{question}',    [FormBuilderController::class, 'updateQuestion'])->name('questions.update');
        Route::delete('/questions/{question}', [FormBuilderController::class, 'destroyQuestion'])->name('questions.destroy');

        Route::post('/options',            [FormBuilderController::class, 'storeOption'])->name('options.store');
        Route::put('/options/{option}',    [FormBuilderController::class, 'updateOption'])->name('options.update');
        Route::delete('/options/{option}', [FormBuilderController::class, 'destroyOption'])->name('options.destroy');
    });

});

// ── Auth routes (login, logout) ───────────────────────────────────────────
require __DIR__.'/auth.php';
