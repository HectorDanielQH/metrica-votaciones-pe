<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CandidacyController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SurveyController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Observer\DashboardController as ObserverDashboardController;
use App\Http\Controllers\PublicDashboardController;
use App\Http\Controllers\Surveyor\VoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicDashboardController::class, 'index'])->name('public.dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/acceso-interno', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/acceso-interno', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.attempt');
    Route::get('/login', fn () => redirect()->route('public.dashboard'));
});

Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::middleware(['auth', 'role:administrador'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
    Route::get('/usuarios/crear', [UserController::class, 'create'])->name('users.create');
    Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');
    Route::get('/usuarios/{user}/editar', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/permisos', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('/candidaturas', [CandidacyController::class, 'index'])->name('candidacies.index');
    Route::get('/candidaturas/crear', [CandidacyController::class, 'create'])->name('candidacies.create');
    Route::post('/candidaturas', [CandidacyController::class, 'store'])->name('candidacies.store');
    Route::get('/candidaturas/{candidacy}/editar', [CandidacyController::class, 'edit'])->name('candidacies.edit');
    Route::put('/candidaturas/{candidacy}', [CandidacyController::class, 'update'])->name('candidacies.update');
    Route::get('/encuestas', [SurveyController::class, 'index'])->name('surveys.index');
    Route::get('/encuestas/{survey}/editar', [SurveyController::class, 'edit'])->name('surveys.edit');
    Route::put('/encuestas/{survey}', [SurveyController::class, 'update'])->name('surveys.update');
    Route::get('/reportes', [ReportController::class, 'index'])->name('reports.index');
});

Route::middleware(['auth', 'role:encuestador'])->prefix('encuestador')->name('surveyor.')->group(function () {
    Route::get('/', [VoteController::class, 'create'])->name('dashboard');
    Route::get('/votacion', [VoteController::class, 'create'])->name('votes.create');
    Route::post('/votacion', [VoteController::class, 'store'])->name('votes.store');
});

Route::middleware(['auth', 'role:veedor|observador'])->prefix('veedor')->name('observer.')->group(function () {
    Route::get('/', [ObserverDashboardController::class, 'index'])->name('dashboard');
});
