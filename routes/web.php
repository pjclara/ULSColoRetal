<?php

use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

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

// Auditoria Module
Route::middleware('auth')->group(function () {
    Route::get('/auditoria', [\App\Http\Controllers\AuditoriaController::class, 'index'])
        ->middleware('can:auditoria.view')
        ->name('auditoria.index');
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

    Route::post('/internamentos/{internamento}/diagnosticos', [\App\Http\Controllers\InternamentoController::class, 'addDiagnostico'])
        ->middleware('can:internamento.update') 
        ->name('internamentos.addDiagnostico');

    Route::delete('/internamentos/{internamento}/diagnosticos', [\App\Http\Controllers\InternamentoController::class, 'removeDiagnostico'])
        ->middleware('can:internamento.update')
        ->name('internamentos.removeDiagnostico');

    Route::post('/internamentos/{internamento}/complicacoes', [\App\Http\Controllers\InternamentoController::class, 'addComplicacao'])
        ->middleware('can:internamento.update')
        ->name('internamentos.addComplicacao');

    Route::delete('/internamentos/{internamento}/complicacoes', [\App\Http\Controllers\InternamentoController::class, 'removeComplicacao'])
        ->middleware('can:internamento.update')
        ->name('internamentos.removeComplicacao');
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

// Complicacao Module
Route::middleware('auth')->group(function () {
    Route::get('/complicacaos', [\App\Http\Controllers\ComplicacaoController::class, 'index'])
        ->middleware('can:complicacao.view')
        ->name('complicacaos.index');

    Route::get('/complicacaos/create', [\App\Http\Controllers\ComplicacaoController::class, 'create'])
        ->middleware('can:complicacao.create')
        ->name('complicacaos.create');

    Route::post('/complicacaos', [\App\Http\Controllers\ComplicacaoController::class, 'store'])
        ->middleware('can:complicacao.create')
        ->name('complicacaos.store');

    Route::get('/complicacaos/{complicacao}', [\App\Http\Controllers\ComplicacaoController::class, 'show'])
        ->middleware('can:complicacao.view')
        ->name('complicacaos.show');

    Route::get('/complicacaos/{complicacao}/edit', [\App\Http\Controllers\ComplicacaoController::class, 'edit'])
        ->middleware('can:complicacao.update')
        ->name('complicacaos.edit');

    Route::put('/complicacaos/{complicacao}', [\App\Http\Controllers\ComplicacaoController::class, 'update'])
        ->middleware('can:complicacao.update')
        ->name('complicacaos.update');

    Route::delete('/complicacaos/{complicacao}', [\App\Http\Controllers\ComplicacaoController::class, 'destroy'])
        ->middleware('can:complicacao.delete')
        ->name('complicacaos.destroy');
});

// CentroDeReferencia Module
Route::middleware('auth')->group(function () {
    Route::get('/centro-de-referencias', [\App\Http\Controllers\CentroDeReferenciaController::class, 'index'])
        ->middleware('can:centro-de-referencium.view')
        ->name('centro-de-referencias.index');

    Route::get('/centro-de-referencias/create', [\App\Http\Controllers\CentroDeReferenciaController::class, 'create'])
        ->middleware('can:centro-de-referencium.create')
        ->name('centro-de-referencias.create');

    Route::post('/centro-de-referencias', [\App\Http\Controllers\CentroDeReferenciaController::class, 'store'])
        ->middleware('can:centro-de-referencium.create')
        ->name('centro-de-referencias.store');

    Route::get('/centro-de-referencias/{centroDeReferencia}', [\App\Http\Controllers\CentroDeReferenciaController::class, 'show'])
        ->middleware('can:centro-de-referencium.view')
        ->name('centro-de-referencias.show');

    Route::get('/centro-de-referencias/{centroDeReferencia}/edit', [\App\Http\Controllers\CentroDeReferenciaController::class, 'edit'])
        ->middleware('can:centro-de-referencium.update')
        ->name('centro-de-referencias.edit');

    Route::put('/centro-de-referencias/{centroDeReferencia}', [\App\Http\Controllers\CentroDeReferenciaController::class, 'update'])
        ->middleware('can:centro-de-referencium.update')
        ->name('centro-de-referencias.update');

    Route::delete('/centro-de-referencias/{centroDeReferencia}', [\App\Http\Controllers\CentroDeReferenciaController::class, 'destroy'])
        ->middleware('can:centro-de-referencium.delete')
        ->name('centro-de-referencias.destroy');
});

// OrigemDaReferenciacao Module
Route::middleware('auth')->group(function () {
    Route::get('/origem-da-referenciacaos', [\App\Http\Controllers\OrigemDaReferenciacaoController::class, 'index'])
        ->middleware('can:origem-da-referenciacao.view')
        ->name('origem-da-referenciacaos.index');

    Route::get('/origem-da-referenciacaos/create', [\App\Http\Controllers\OrigemDaReferenciacaoController::class, 'create'])
        ->middleware('can:origem-da-referenciacao.create')
        ->name('origem-da-referenciacaos.create');

    Route::post('/origem-da-referenciacaos', [\App\Http\Controllers\OrigemDaReferenciacaoController::class, 'store'])
        ->middleware('can:origem-da-referenciacao.create')
        ->name('origem-da-referenciacaos.store');

    Route::get('/origem-da-referenciacaos/{origemDaReferenciacao}', [\App\Http\Controllers\OrigemDaReferenciacaoController::class, 'show'])
        ->middleware('can:origem-da-referenciacao.view')
        ->name('origem-da-referenciacaos.show');

    Route::get('/origem-da-referenciacaos/{origemDaReferenciacao}/edit', [\App\Http\Controllers\OrigemDaReferenciacaoController::class, 'edit'])
        ->middleware('can:origem-da-referenciacao.update')
        ->name('origem-da-referenciacaos.edit');

    Route::put('/origem-da-referenciacaos/{origemDaReferenciacao}', [\App\Http\Controllers\OrigemDaReferenciacaoController::class, 'update'])
        ->middleware('can:origem-da-referenciacao.update')
        ->name('origem-da-referenciacaos.update');

    Route::delete('/origem-da-referenciacaos/{origemDaReferenciacao}', [\App\Http\Controllers\OrigemDaReferenciacaoController::class, 'destroy'])
        ->middleware('can:origem-da-referenciacao.delete')
        ->name('origem-da-referenciacaos.destroy');
});

// ListaDeEspera Module
Route::middleware('auth')->group(function () {
    Route::get('/lista-de-esperas', [\App\Http\Controllers\ListaDeEsperaController::class, 'index'])
        ->middleware('can:lista-de-espera.view')
        ->name('lista-de-esperas.index');

    Route::get('/lista-de-esperas/create', [\App\Http\Controllers\ListaDeEsperaController::class, 'create'])
        ->middleware('can:lista-de-espera.create')
        ->name('lista-de-esperas.create');

    Route::post('/lista-de-esperas', [\App\Http\Controllers\ListaDeEsperaController::class, 'store'])
        ->middleware('can:lista-de-espera.create')
        ->name('lista-de-esperas.store');

    Route::get('/lista-de-esperas/{listaDeEspera}', [\App\Http\Controllers\ListaDeEsperaController::class, 'show'])
        ->middleware('can:lista-de-espera.view')
        ->name('lista-de-esperas.show');

    Route::get('/lista-de-esperas/{listaDeEspera}/edit', [\App\Http\Controllers\ListaDeEsperaController::class, 'edit'])
        ->middleware('can:lista-de-espera.update')
        ->name('lista-de-esperas.edit');

    Route::put('/lista-de-esperas/{listaDeEspera}', [\App\Http\Controllers\ListaDeEsperaController::class, 'update'])
        ->middleware('can:lista-de-espera.update')
        ->name('lista-de-esperas.update');

    Route::delete('/lista-de-esperas/{listaDeEspera}', [\App\Http\Controllers\ListaDeEsperaController::class, 'destroy'])
        ->middleware('can:lista-de-espera.delete')
        ->name('lista-de-esperas.destroy');
});

// Agendamento Module
Route::middleware('auth')->group(function () {
    Route::get('/agendamentos', [\App\Http\Controllers\AgendamentoController::class, 'index'])
        ->middleware('can:agendamento.view')
        ->name('agendamentos.index');

    Route::get('/agendamentos/create', [\App\Http\Controllers\AgendamentoController::class, 'create'])
        ->middleware('can:agendamento.create')
        ->name('agendamentos.create');

    Route::post('/agendamentos', [\App\Http\Controllers\AgendamentoController::class, 'store'])
        ->middleware('can:agendamento.create')
        ->name('agendamentos.store');

    Route::get('/agendamentos/{agendamento}', [\App\Http\Controllers\AgendamentoController::class, 'show'])
        ->middleware('can:agendamento.view')
        ->name('agendamentos.show');

    Route::get('/agendamentos/{agendamento}/edit', [\App\Http\Controllers\AgendamentoController::class, 'edit'])
        ->middleware('can:agendamento.update')
        ->name('agendamentos.edit');

    Route::put('/agendamentos/{agendamento}', [\App\Http\Controllers\AgendamentoController::class, 'update'])
        ->middleware('can:agendamento.update')
        ->name('agendamentos.update');

    Route::delete('/agendamentos/{agendamento}', [\App\Http\Controllers\AgendamentoController::class, 'destroy'])
        ->middleware('can:agendamento.delete')
        ->name('agendamentos.destroy');
});

// EstadoDeAgendamento Module
Route::middleware('auth')->group(function () {
    Route::get('/estado-de-agendamentos', [\App\Http\Controllers\EstadoDeAgendamentoController::class, 'index'])
        ->middleware('can:estado-de-agendamento.view')
        ->name('estado-de-agendamentos.index');

    Route::get('/estado-de-agendamentos/create', [\App\Http\Controllers\EstadoDeAgendamentoController::class, 'create'])
        ->middleware('can:estado-de-agendamento.create')
        ->name('estado-de-agendamentos.create');

    Route::post('/estado-de-agendamentos', [\App\Http\Controllers\EstadoDeAgendamentoController::class, 'store'])
        ->middleware('can:estado-de-agendamento.create')
        ->name('estado-de-agendamentos.store');

    Route::get('/estado-de-agendamentos/{estadoDeAgendamento}', [\App\Http\Controllers\EstadoDeAgendamentoController::class, 'show'])
        ->middleware('can:estado-de-agendamento.view')
        ->name('estado-de-agendamentos.show');

    Route::get('/estado-de-agendamentos/{estadoDeAgendamento}/edit', [\App\Http\Controllers\EstadoDeAgendamentoController::class, 'edit'])
        ->middleware('can:estado-de-agendamento.update')
        ->name('estado-de-agendamentos.edit');

    Route::put('/estado-de-agendamentos/{estadoDeAgendamento}', [\App\Http\Controllers\EstadoDeAgendamentoController::class, 'update'])
        ->middleware('can:estado-de-agendamento.update')
        ->name('estado-de-agendamentos.update');

    Route::delete('/estado-de-agendamentos/{estadoDeAgendamento}', [\App\Http\Controllers\EstadoDeAgendamentoController::class, 'destroy'])
        ->middleware('can:estado-de-agendamento.delete')
        ->name('estado-de-agendamentos.destroy');
});

// SalaDeAgendamento Module
Route::middleware('auth')->group(function () {
    Route::get('/sala-de-agendamentos', [\App\Http\Controllers\SalaDeAgendamentoController::class, 'index'])
        ->middleware('can:sala-de-agendamento.view')
        ->name('sala-de-agendamentos.index');

    Route::get('/sala-de-agendamentos/create', [\App\Http\Controllers\SalaDeAgendamentoController::class, 'create'])
        ->middleware('can:sala-de-agendamento.create')
        ->name('sala-de-agendamentos.create');

    Route::post('/sala-de-agendamentos', [\App\Http\Controllers\SalaDeAgendamentoController::class, 'store'])
        ->middleware('can:sala-de-agendamento.create')
        ->name('sala-de-agendamentos.store');

    Route::get('/sala-de-agendamentos/{salaDeAgendamento}', [\App\Http\Controllers\SalaDeAgendamentoController::class, 'show'])
        ->middleware('can:sala-de-agendamento.view')
        ->name('sala-de-agendamentos.show');

    Route::get('/sala-de-agendamentos/{salaDeAgendamento}/edit', [\App\Http\Controllers\SalaDeAgendamentoController::class, 'edit'])
        ->middleware('can:sala-de-agendamento.update')
        ->name('sala-de-agendamentos.edit');

    Route::put('/sala-de-agendamentos/{salaDeAgendamento}', [\App\Http\Controllers\SalaDeAgendamentoController::class, 'update'])
        ->middleware('can:sala-de-agendamento.update')
        ->name('sala-de-agendamentos.update');

    Route::delete('/sala-de-agendamentos/{salaDeAgendamento}', [\App\Http\Controllers\SalaDeAgendamentoController::class, 'destroy'])
        ->middleware('can:sala-de-agendamento.delete')
        ->name('sala-de-agendamentos.destroy');
});

// LocalDeAgendamento Module
Route::middleware('auth')->group(function () {
    Route::get('/local-de-agendamentos', [\App\Http\Controllers\LocalDeAgendamentoController::class, 'index'])
        ->middleware('can:local-de-agendamento.view')
        ->name('local-de-agendamentos.index');

    Route::get('/local-de-agendamentos/create', [\App\Http\Controllers\LocalDeAgendamentoController::class, 'create'])
        ->middleware('can:local-de-agendamento.create')
        ->name('local-de-agendamentos.create');

    Route::post('/local-de-agendamentos', [\App\Http\Controllers\LocalDeAgendamentoController::class, 'store'])
        ->middleware('can:local-de-agendamento.create')
        ->name('local-de-agendamentos.store');

    Route::get('/local-de-agendamentos/{localDeAgendamento}', [\App\Http\Controllers\LocalDeAgendamentoController::class, 'show'])
        ->middleware('can:local-de-agendamento.view')
        ->name('local-de-agendamentos.show');

    Route::get('/local-de-agendamentos/{localDeAgendamento}/edit', [\App\Http\Controllers\LocalDeAgendamentoController::class, 'edit'])
        ->middleware('can:local-de-agendamento.update')
        ->name('local-de-agendamentos.edit');

    Route::put('/local-de-agendamentos/{localDeAgendamento}', [\App\Http\Controllers\LocalDeAgendamentoController::class, 'update'])
        ->middleware('can:local-de-agendamento.update')
        ->name('local-de-agendamentos.update');

    Route::delete('/local-de-agendamentos/{localDeAgendamento}', [\App\Http\Controllers\LocalDeAgendamentoController::class, 'destroy'])
        ->middleware('can:local-de-agendamento.delete')
        ->name('local-de-agendamentos.destroy');
});

// TipoDeAgendamento Module
Route::middleware('auth')->group(function () {
    Route::get('/tipo-de-agendamentos', [\App\Http\Controllers\TipoDeAgendamentoController::class, 'index'])
        ->middleware('can:tipo-de-agendamento.view')
        ->name('tipo-de-agendamentos.index');

    Route::get('/tipo-de-agendamentos/create', [\App\Http\Controllers\TipoDeAgendamentoController::class, 'create'])
        ->middleware('can:tipo-de-agendamento.create')
        ->name('tipo-de-agendamentos.create');

    Route::post('/tipo-de-agendamentos', [\App\Http\Controllers\TipoDeAgendamentoController::class, 'store'])
        ->middleware('can:tipo-de-agendamento.create')
        ->name('tipo-de-agendamentos.store');

    Route::get('/tipo-de-agendamentos/{tipoDeAgendamento}', [\App\Http\Controllers\TipoDeAgendamentoController::class, 'show'])
        ->middleware('can:tipo-de-agendamento.view')
        ->name('tipo-de-agendamentos.show');

    Route::get('/tipo-de-agendamentos/{tipoDeAgendamento}/edit', [\App\Http\Controllers\TipoDeAgendamentoController::class, 'edit'])
        ->middleware('can:tipo-de-agendamento.update')
        ->name('tipo-de-agendamentos.edit');

    Route::put('/tipo-de-agendamentos/{tipoDeAgendamento}', [\App\Http\Controllers\TipoDeAgendamentoController::class, 'update'])
        ->middleware('can:tipo-de-agendamento.update')
        ->name('tipo-de-agendamentos.update');

    Route::delete('/tipo-de-agendamentos/{tipoDeAgendamento}', [\App\Http\Controllers\TipoDeAgendamentoController::class, 'destroy'])
        ->middleware('can:tipo-de-agendamento.delete')
        ->name('tipo-de-agendamentos.destroy');
});

// BlocoOperatorio Module
Route::middleware('auth')->group(function () {
    Route::get('/bloco-operatorios', [\App\Http\Controllers\BlocoOperatorioController::class, 'index'])
        ->middleware('can:bloco-operatorio.view')
        ->name('bloco-operatorios.index');

    Route::get('/bloco-operatorios/create', [\App\Http\Controllers\BlocoOperatorioController::class, 'create'])
        ->middleware('can:bloco-operatorio.create')
        ->name('bloco-operatorios.create');

    Route::post('/bloco-operatorios', [\App\Http\Controllers\BlocoOperatorioController::class, 'store'])
        ->middleware('can:bloco-operatorio.create')
        ->name('bloco-operatorios.store');

    Route::get('/bloco-operatorios/{blocoOperatorio}', [\App\Http\Controllers\BlocoOperatorioController::class, 'show'])
        ->middleware('can:bloco-operatorio.view')
        ->name('bloco-operatorios.show');

    Route::get('/bloco-operatorios/{blocoOperatorio}/edit', [\App\Http\Controllers\BlocoOperatorioController::class, 'edit'])
        ->middleware('can:bloco-operatorio.update')
        ->name('bloco-operatorios.edit');

    Route::put('/bloco-operatorios/{blocoOperatorio}', [\App\Http\Controllers\BlocoOperatorioController::class, 'update'])
        ->middleware('can:bloco-operatorio.update')
        ->name('bloco-operatorios.update');

    Route::delete('/bloco-operatorios/{blocoOperatorio}', [\App\Http\Controllers\BlocoOperatorioController::class, 'destroy'])
        ->middleware('can:bloco-operatorio.delete')
        ->name('bloco-operatorios.destroy');
});

// BlocoOperatorioCRS Module
Route::middleware('auth')->group(function () {
    Route::get('/bloco-operatorio-c-rs', [\App\Http\Controllers\BlocoOperatorioCRSController::class, 'index'])
        ->middleware('can:bloco-operatorio-c-r.view')
        ->name('bloco-operatorio-c-rs.index');

    Route::get('/bloco-operatorio-c-rs/create', [\App\Http\Controllers\BlocoOperatorioCRSController::class, 'create'])
        ->middleware('can:bloco-operatorio-c-r.create')
        ->name('bloco-operatorio-c-rs.create');

    Route::post('/bloco-operatorio-c-rs', [\App\Http\Controllers\BlocoOperatorioCRSController::class, 'store'])
        ->middleware('can:bloco-operatorio-c-r.create')
        ->name('bloco-operatorio-c-rs.store');

    Route::get('/bloco-operatorio-c-rs/{blocoOperatorioCRS}', [\App\Http\Controllers\BlocoOperatorioCRSController::class, 'show'])
        ->middleware('can:bloco-operatorio-c-r.view')
        ->name('bloco-operatorio-c-rs.show');

    Route::get('/bloco-operatorio-c-rs/{blocoOperatorioCRS}/edit', [\App\Http\Controllers\BlocoOperatorioCRSController::class, 'edit'])
        ->middleware('can:bloco-operatorio-c-r.update')
        ->name('bloco-operatorio-c-rs.edit');

    Route::put('/bloco-operatorio-c-rs/{blocoOperatorioCRS}', [\App\Http\Controllers\BlocoOperatorioCRSController::class, 'update'])
        ->middleware('can:bloco-operatorio-c-r.update')
        ->name('bloco-operatorio-c-rs.update');

    Route::delete('/bloco-operatorio-c-rs/{blocoOperatorioCRS}', [\App\Http\Controllers\BlocoOperatorioCRSController::class, 'destroy'])
        ->middleware('can:bloco-operatorio-c-r.delete')
        ->name('bloco-operatorio-c-rs.destroy');
});

// IntervencaoDescricao Module (descrição da anastomose de uma intervenção concreta num bloco operatório)
Route::middleware('auth')->group(function () {
    Route::post('/intervencao-descricaos', [\App\Http\Controllers\IntervencaoDescricaoController::class, 'store'])
        ->middleware('can:intervencao-descricao.create')
        ->name('intervencao-descricaos.store');

    Route::put('/intervencao-descricaos/{intervencaoDescricao}', [\App\Http\Controllers\IntervencaoDescricaoController::class, 'update'])
        ->middleware('can:intervencao-descricao.update')
        ->name('intervencao-descricaos.update');

    Route::delete('/intervencao-descricaos/{intervencaoDescricao}', [\App\Http\Controllers\IntervencaoDescricaoController::class, 'destroy'])
        ->middleware('can:intervencao-descricao.delete')
        ->name('intervencao-descricaos.destroy');
});

// Intervencao Module
Route::middleware('auth')->group(function () {
    Route::get('/intervencaos', [\App\Http\Controllers\IntervencaoController::class, 'index'])
        ->middleware('can:intervencao.view')
        ->name('intervencaos.index');

    Route::get('/intervencaos/create', [\App\Http\Controllers\IntervencaoController::class, 'create'])
        ->middleware('can:intervencao.create')
        ->name('intervencaos.create');

    Route::post('/intervencaos', [\App\Http\Controllers\IntervencaoController::class, 'store'])
        ->middleware('can:intervencao.create')
        ->name('intervencaos.store');

    Route::get('/intervencaos/{intervencao}', [\App\Http\Controllers\IntervencaoController::class, 'show'])
        ->middleware('can:intervencao.view')
        ->name('intervencaos.show');

    Route::get('/intervencaos/{intervencao}/edit', [\App\Http\Controllers\IntervencaoController::class, 'edit'])
        ->middleware('can:intervencao.update')
        ->name('intervencaos.edit');

    Route::put('/intervencaos/{intervencao}', [\App\Http\Controllers\IntervencaoController::class, 'update'])
        ->middleware('can:intervencao.update')
        ->name('intervencaos.update');

    Route::delete('/intervencaos/{intervencao}', [\App\Http\Controllers\IntervencaoController::class, 'destroy'])
        ->middleware('can:intervencao.delete')
        ->name('intervencaos.destroy');
});
