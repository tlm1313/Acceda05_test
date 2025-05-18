<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {

    $user=Auth::user();

    if($user->esAdmin()){


        echo "Eres administrador";
    }else{  

        echo "Eres usuario";


    }
    return view('zonaAd');
    //return view('welcome');

  /*  if(Auth::check()){

        return view('welcome');
    }else{

        return "no estas logeado";
    }*/
});

//Auth::routes();
Auth::routes([

    'register' => false, // Desactiva el registro de usuarios
]);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
