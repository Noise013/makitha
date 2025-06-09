<?php

use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\EventoController;

Route::get('/importar', function () {
    return view('importar');
});


Route::post('/importar/{evento}', [MovimientoController::class, 'importar'])->name('movimientos.importar');
Route::get('/importar/{evento}', [MovimientoController::class, 'mostrarForm'])->name('importar.form');

Route::get('/eventos/crear', [EventoController::class, 'crear'])->name('eventos.crear');
Route::post('/eventos/guardar', [EventoController::class, 'guardar'])->name('eventos.guardar');
