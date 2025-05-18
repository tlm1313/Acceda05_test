<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsuController;

Route::get('/', function () {
  /* if (Auth::check()) {
        $user = Auth::user();

        if ($user->esAdmin()) {
            return view('zonaAd');
        } else {
            return view('zonaUsuario');
        }
    } else {
        return redirect()->route('login');
    }*/
    return redirect()->route('login');
});

// Desactiva el registro
Auth::routes([
    'register' => true,
]);

Route::get('/home', [HomeController::class, 'index'])->name('home');

//Route::get('/admin', [AdminController::class, 'index'])->middleware(\App\Http\Middleware\EsAdmin::class);

Route::resource('admin', AdminController::class)->middleware(\App\Http\Middleware\EsAdmin::class);
Route::resource('user', UsuController::class)->middleware(\App\Http\Middleware\EsAdmin::class);
