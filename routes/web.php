<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TableroController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    

    Route::get('/importar', function () {
        return view('importar');
    });

    Route::post('/importar/{evento}', [MovimientoController::class, 'importar'])->name('movimientos.importar');
    Route::get('/importar/{evento}', [MovimientoController::class, 'mostrarForm'])->name('importar.form');

    Route::get('/eventos/crear', [EventoController::class, 'crear'])->name('eventos.crear');
    Route::post('/eventos/guardar', [EventoController::class, 'guardar'])->name('eventos.guardar');
    Route::get('/eventos/nuevo', [EventoController::class, 'crear'])->name('eventos.nuevo');
    Route::get('/evento', [EventoController::class, 'ver'])->name('evento.ver');

    /*Route::get('/tablero', function () {
        return view('tablero');
    })->name('tablero');*/

    Route::get('/tableros/crear', [TableroController::class, 'crear'])->name('tableros.crear');
    Route::post('/tableros/guardar', [TableroController::class, 'guardar'])->name('tableros.guardar');
    Route::get('/tablero/crearDatos/{id}', [TableroController::class, 'datos'])->name('tableros.datos');
    Route::post('/tableros/guardarDatos/{id}', [TableroController::class, 'guardarDatos'])->name('tableros.guardarDatos');
    Route::get('/tablero', [TableroController::class, 'index'])->name('tablero');






});

require __DIR__.'/auth.php';




