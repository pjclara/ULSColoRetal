<?php

use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::resource('users', UserController::class)->only(['store']);
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';

// Utente Module
Route::middleware('auth')->group(function () {
    Route::get('/utentes', [\App\Http\Controllers\UtenteController::class, 'index'])
        ->middleware('can:utente.view')
        ->name('utentes.index');

    Route::get('/utentes/create', [\App\Http\Controllers\UtenteController::class, 'create'])
        ->middleware('can:utente.create')
        ->name('utentes.create');

    Route::post('/utentes', [\App\Http\Controllers\UtenteController::class, 'store'])
        ->middleware('can:utente.create')
        ->name('utentes.store');

    Route::get('/utentes/{utente}', [\App\Http\Controllers\UtenteController::class, 'show'])
        ->middleware('can:utente.view')
        ->name('utentes.show');

    Route::get('/utentes/{utente}/edit', [\App\Http\Controllers\UtenteController::class, 'edit'])
        ->middleware('can:utente.update')
        ->name('utentes.edit');

    Route::put('/utentes/{utente}', [\App\Http\Controllers\UtenteController::class, 'update'])
        ->middleware('can:utente.update')
        ->name('utentes.update');

    Route::delete('/utentes/{utente}', [\App\Http\Controllers\UtenteController::class, 'destroy'])
        ->middleware('can:utente.delete')
        ->name('utentes.destroy');
});


// Gestão de RBAC (roles & permissions)
Route::middleware('auth')->group(function () {
    Route::get('/access-control', [RolePermissionController::class, 'index']);

    Route::post('/access-control/roles', [RolePermissionController::class, 'storeRole'])
        ->middleware('can:users.manage');
    Route::put('/access-control/roles/{role}', [RolePermissionController::class, 'updateRole'])
        ->middleware('can:users.manage');
    Route::delete('/access-control/roles/{role}', [RolePermissionController::class, 'destroyRole'])
        ->middleware('can:users.manage');

    Route::post('/access-control/permissions', [RolePermissionController::class, 'storePermission'])
        ->middleware('can:users.manage');
    Route::put('/access-control/permissions/{permission}', [RolePermissionController::class, 'updatePermission'])
        ->middleware('can:users.manage');
    Route::delete('/access-control/permissions/{permission}', [RolePermissionController::class, 'destroyPermission'])
        ->middleware('can:users.manage');

    Route::resource('users', UserController::class)->only(['index', 'store', 'update']);
});

// Internamento Module
Route::middleware('auth')->group(function () {
    Route::get('/internamentos', [\App\Http\Controllers\InternamentoController::class, 'index'])
        ->middleware('can:internamento.view')
        ->name('internamentos.index');

    Route::get('/internamentos/create', [\App\Http\Controllers\InternamentoController::class, 'create'])
        ->middleware('can:internamento.create')
        ->name('internamentos.create');

    Route::post('/internamentos', [\App\Http\Controllers\InternamentoController::class, 'store'])
        ->middleware('can:internamento.create')
        ->name('internamentos.store');

    Route::get('/internamentos/{internamento}', [\App\Http\Controllers\InternamentoController::class, 'show'])
        ->middleware('can:internamento.view')
        ->name('internamentos.show');

    Route::get('/internamentos/{internamento}/edit', [\App\Http\Controllers\InternamentoController::class, 'edit'])
        ->middleware('can:internamento.update')
        ->name('internamentos.edit');

    Route::put('/internamentos/{internamento}', [\App\Http\Controllers\InternamentoController::class, 'update'])
        ->middleware('can:internamento.update')
        ->name('internamentos.update');

    Route::delete('/internamentos/{internamento}', [\App\Http\Controllers\InternamentoController::class, 'destroy'])
        ->middleware('can:internamento.delete')
        ->name('internamentos.destroy');
});
