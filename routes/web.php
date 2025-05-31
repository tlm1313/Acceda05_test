<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistroPdfController;
use Illuminate\Support\Facades\Auth;

// Redirección inicial
Route::redirect('/', '/login');

// Autenticación (routes/auth.php ya incluido por defecto)
Auth::routes(['register' => false]);

// Rutas para usuarios autenticados
Route::middleware('auth')->group(function () {
    // Ruta home (zona usuario normal)
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Registros de entrada/salida
    Route::post('/user/{user}/entrada', [HomeController::class, 'registrarEntrada'])
         ->name('user.entrada');
    Route::post('/user/{user}/salida', [HomeController::class, 'registrarSalida'])
         ->name('user.salida');

    // Generación de PDF
    Route::post('/registros/pdf', [RegistroPdfController::class, 'download'])
         ->name('registros.pdf');
});

// Rutas de ADMIN (requieren autenticación + middleware EsAdmin)
Route::middleware(['auth', 'EsAdmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/create', [AdminController::class, 'create'])->name('create');
    Route::post('/', [AdminController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AdminController::class, 'update'])->name('update');
    Route::patch('/{id}', [AdminController::class, 'update']);
    Route::delete('/{id}', [AdminController::class, 'destroy'])->name('destroy');
    // Ruta para ver detalles de un usuario

    Route::get('/users/{user}/details', [AdminController::class, 'details'])
         ->name('admin.users.details');

     // ... otras rutas ...
    Route::get('/all-registers', [AdminController::class, 'allRegisters'])->name('all.registers');
    Route::get('/admin/export-pdf', [AdminController::class, 'exportPdf'])
     ->name('export.pdf');


});

// Ruta de prueba PDF (opcional)
Route::get('/test-pdf', function() {
    try {
        $html = '<!DOCTYPE html><html><head><meta charset="utf-8"></head><body><h1>Test PDF</h1><p>'.now()->format('Y-m-d H:i:s').'</p></body></html>';
        $pdf = app('snappy.pdf.wrapper')->loadHTML($html);

        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="test.pdf"'
        ]);
    } catch(\Exception $e) {
        return response("Error: ".$e->getMessage(), 500);
    }
});
