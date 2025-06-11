<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\MovimientoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
});

require __DIR__.'/auth.php';




