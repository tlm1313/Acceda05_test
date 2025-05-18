<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    //

    public function __construct(){

        $this->middleware('EsAdmin');


    }

    public function index(){

        return view('zonaAd');// zonaAd es la vista que se va a mostrar
    }
}
