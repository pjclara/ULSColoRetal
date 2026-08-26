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


// EstadoDaAlta Module
Route::middleware('auth')->group(function () {
    Route::get('/estado-da-altas', [\App\Http\Controllers\EstadoDaAltaController::class, 'index'])
        ->middleware('can:estado-da-altum.view')
        ->name('estado-da-altas.index');

    Route::get('/estado-da-altas/create', [\App\Http\Controllers\EstadoDaAltaController::class, 'create'])
        ->middleware('can:estado-da-altum.create')
        ->name('estado-da-altas.create');

    Route::post('/estado-da-altas', [\App\Http\Controllers\EstadoDaAltaController::class, 'store'])
        ->middleware('can:estado-da-altum.create')
        ->name('estado-da-altas.store');

    Route::get('/estado-da-altas/{estadoDaAlta}', [\App\Http\Controllers\EstadoDaAltaController::class, 'show'])
        ->middleware('can:estado-da-altum.view')
        ->name('estado-da-altas.show');

    Route::get('/estado-da-altas/{estadoDaAlta}/edit', [\App\Http\Controllers\EstadoDaAltaController::class, 'edit'])
        ->middleware('can:estado-da-altum.update')
        ->name('estado-da-altas.edit');

    Route::put('/estado-da-altas/{estadoDaAlta}', [\App\Http\Controllers\EstadoDaAltaController::class, 'update'])
        ->middleware('can:estado-da-altum.update')
        ->name('estado-da-altas.update');

    Route::delete('/estado-da-altas/{estadoDaAlta}', [\App\Http\Controllers\EstadoDaAltaController::class, 'destroy'])
        ->middleware('can:estado-da-altum.delete')
        ->name('estado-da-altas.destroy');
});

// OrigemDoInternamento Module
Route::middleware('auth')->group(function () {
    Route::get('/origem-do-internamentos', [\App\Http\Controllers\OrigemDoInternamentoController::class, 'index'])
        ->middleware('can:origem-do-internamento.view')
        ->name('origem-do-internamentos.index');

    Route::get('/origem-do-internamentos/create', [\App\Http\Controllers\OrigemDoInternamentoController::class, 'create'])
        ->middleware('can:origem-do-internamento.create')
        ->name('origem-do-internamentos.create');

    Route::post('/origem-do-internamentos', [\App\Http\Controllers\OrigemDoInternamentoController::class, 'store'])
        ->middleware('can:origem-do-internamento.create')
        ->name('origem-do-internamentos.store');

    Route::get('/origem-do-internamentos/{origemDoInternamento}', [\App\Http\Controllers\OrigemDoInternamentoController::class, 'show'])
        ->middleware('can:origem-do-internamento.view')
        ->name('origem-do-internamentos.show');

    Route::get('/origem-do-internamentos/{origemDoInternamento}/edit', [\App\Http\Controllers\OrigemDoInternamentoController::class, 'edit'])
        ->middleware('can:origem-do-internamento.update')
        ->name('origem-do-internamentos.edit');

    Route::put('/origem-do-internamentos/{origemDoInternamento}', [\App\Http\Controllers\OrigemDoInternamentoController::class, 'update'])
        ->middleware('can:origem-do-internamento.update')
        ->name('origem-do-internamentos.update');

    Route::delete('/origem-do-internamentos/{origemDoInternamento}', [\App\Http\Controllers\OrigemDoInternamentoController::class, 'destroy'])
        ->middleware('can:origem-do-internamento.delete')
        ->name('origem-do-internamentos.destroy');
});

// ClavienDindo Module
Route::middleware('auth')->group(function () {
    Route::get('/clavien-dindos', [\App\Http\Controllers\ClavienDindoController::class, 'index'])
        ->middleware('can:clavien-dindo.view')
        ->name('clavien-dindos.index');

    Route::get('/clavien-dindos/create', [\App\Http\Controllers\ClavienDindoController::class, 'create'])
        ->middleware('can:clavien-dindo.create')
        ->name('clavien-dindos.create');

    Route::post('/clavien-dindos', [\App\Http\Controllers\ClavienDindoController::class, 'store'])
        ->middleware('can:clavien-dindo.create')
        ->name('clavien-dindos.store');

    Route::get('/clavien-dindos/{clavienDindo}', [\App\Http\Controllers\ClavienDindoController::class, 'show'])
        ->middleware('can:clavien-dindo.view')
        ->name('clavien-dindos.show');

    Route::get('/clavien-dindos/{clavienDindo}/edit', [\App\Http\Controllers\ClavienDindoController::class, 'edit'])
        ->middleware('can:clavien-dindo.update')
        ->name('clavien-dindos.edit');

    Route::put('/clavien-dindos/{clavienDindo}', [\App\Http\Controllers\ClavienDindoController::class, 'update'])
        ->middleware('can:clavien-dindo.update')
        ->name('clavien-dindos.update');

    Route::delete('/clavien-dindos/{clavienDindo}', [\App\Http\Controllers\ClavienDindoController::class, 'destroy'])
        ->middleware('can:clavien-dindo.delete')
        ->name('clavien-dindos.destroy');
});

// Destino Module
Route::middleware('auth')->group(function () {
    Route::get('/destinos', [\App\Http\Controllers\DestinoController::class, 'index'])
        ->middleware('can:destino.view')
        ->name('destinos.index');

    Route::get('/destinos/create', [\App\Http\Controllers\DestinoController::class, 'create'])
        ->middleware('can:destino.create')
        ->name('destinos.create');

    Route::post('/destinos', [\App\Http\Controllers\DestinoController::class, 'store'])
        ->middleware('can:destino.create')
        ->name('destinos.store');

    Route::get('/destinos/{destino}', [\App\Http\Controllers\DestinoController::class, 'show'])
        ->middleware('can:destino.view')
        ->name('destinos.show');

    Route::get('/destinos/{destino}/edit', [\App\Http\Controllers\DestinoController::class, 'edit'])
        ->middleware('can:destino.update')
        ->name('destinos.edit');

    Route::put('/destinos/{destino}', [\App\Http\Controllers\DestinoController::class, 'update'])
        ->middleware('can:destino.update')
        ->name('destinos.update');

    Route::delete('/destinos/{destino}', [\App\Http\Controllers\DestinoController::class, 'destroy'])
        ->middleware('can:destino.delete')
        ->name('destinos.destroy');
});

// CasoSocial Module
Route::middleware('auth')->group(function () {
    Route::get('/caso-socials', [\App\Http\Controllers\CasoSocialController::class, 'index'])
        ->middleware('can:caso-social.view')
        ->name('caso-socials.index');

    Route::get('/caso-socials/create', [\App\Http\Controllers\CasoSocialController::class, 'create'])
        ->middleware('can:caso-social.create')
        ->name('caso-socials.create');

    Route::post('/caso-socials', [\App\Http\Controllers\CasoSocialController::class, 'store'])
        ->middleware('can:caso-social.create')
        ->name('caso-socials.store');

    Route::get('/caso-socials/{casoSocial}', [\App\Http\Controllers\CasoSocialController::class, 'show'])
        ->middleware('can:caso-social.view')
        ->name('caso-socials.show');

    Route::get('/caso-socials/{casoSocial}/edit', [\App\Http\Controllers\CasoSocialController::class, 'edit'])
        ->middleware('can:caso-social.update')
        ->name('caso-socials.edit');

    Route::put('/caso-socials/{casoSocial}', [\App\Http\Controllers\CasoSocialController::class, 'update'])
        ->middleware('can:caso-social.update')
        ->name('caso-socials.update');

    Route::delete('/caso-socials/{casoSocial}', [\App\Http\Controllers\CasoSocialController::class, 'destroy'])
        ->middleware('can:caso-social.delete')
        ->name('caso-socials.destroy');
});

// Diagnostico Module
Route::middleware('auth')->group(function () {
    Route::get('/diagnosticos', [\App\Http\Controllers\DiagnosticoController::class, 'index'])
        ->middleware('can:diagnostico.view')
        ->name('diagnosticos.index');

    Route::get('/diagnosticos/create', [\App\Http\Controllers\DiagnosticoController::class, 'create'])
        ->middleware('can:diagnostico.create')
        ->name('diagnosticos.create');

    Route::post('/diagnosticos', [\App\Http\Controllers\DiagnosticoController::class, 'store'])
        ->middleware('can:diagnostico.create')
        ->name('diagnosticos.store');

    Route::get('/diagnosticos/{diagnostico}', [\App\Http\Controllers\DiagnosticoController::class, 'show'])
        ->middleware('can:diagnostico.view')
        ->name('diagnosticos.show');

    Route::get('/diagnosticos/{diagnostico}/edit', [\App\Http\Controllers\DiagnosticoController::class, 'edit'])
        ->middleware('can:diagnostico.update')
        ->name('diagnosticos.edit');

    Route::put('/diagnosticos/{diagnostico}', [\App\Http\Controllers\DiagnosticoController::class, 'update'])
        ->middleware('can:diagnostico.update')
        ->name('diagnosticos.update');

    Route::delete('/diagnosticos/{diagnostico}', [\App\Http\Controllers\DiagnosticoController::class, 'destroy'])
        ->middleware('can:diagnostico.delete')
        ->name('diagnosticos.destroy');
});

// Localizacao Module
Route::middleware('auth')->group(function () {
    Route::get('/localizacaos', [\App\Http\Controllers\LocalizacaoController::class, 'index'])
        ->middleware('can:localizacao.view')
        ->name('localizacaos.index');

    Route::get('/localizacaos/create', [\App\Http\Controllers\LocalizacaoController::class, 'create'])
        ->middleware('can:localizacao.create')
        ->name('localizacaos.create');

    Route::post('/localizacaos', [\App\Http\Controllers\LocalizacaoController::class, 'store'])
        ->middleware('can:localizacao.create')
        ->name('localizacaos.store');

    Route::get('/localizacaos/{localizacao}', [\App\Http\Controllers\LocalizacaoController::class, 'show'])
        ->middleware('can:localizacao.view')
        ->name('localizacaos.show');

    Route::get('/localizacaos/{localizacao}/edit', [\App\Http\Controllers\LocalizacaoController::class, 'edit'])
        ->middleware('can:localizacao.update')
        ->name('localizacaos.edit');

    Route::put('/localizacaos/{localizacao}', [\App\Http\Controllers\LocalizacaoController::class, 'update'])
        ->middleware('can:localizacao.update')
        ->name('localizacaos.update');

    Route::delete('/localizacaos/{localizacao}', [\App\Http\Controllers\LocalizacaoController::class, 'destroy'])
        ->middleware('can:localizacao.delete')
        ->name('localizacaos.destroy');
});
