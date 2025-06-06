<?php

use App\Http\Controllers\MovimientoController;

Route::get('/importar', function () {
    return view('importar');
});

Route::post('/importar', [MovimientoController::class, 'importar'])->name('movimientos.importar');
