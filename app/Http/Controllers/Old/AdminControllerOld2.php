<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Foto;
class AdminController extends Controller
{

    
        //
    
        public function __construct(){
    
          
            $this->middleware('EsAdmin');
    
    
        }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::all();
        return view('admin.zonaAd', compact('usuarios'));// zonaAd es la vista que se va a mostrar  
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
        return view('admin.create');// zonaAd es la vista que se va a mostrar  
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
      /*   User::create( $request->all());
        return redirect()->route('admin.index')->with('success', 'Usuario creado correctamente'); */
        $entrada = $request->all();
        if($archivo = $request->file('foto_id')){
            $nombreArchivo = $archivo->getClientOriginalName();
            $archivo->move(public_path('fotos'), $nombreArchivo);
            $foto = Foto::create(['foto' => $nombreArchivo]);
            $entrada['foto_id'] = $foto->id;
            
        }
            User::create($entrada);
            return redirect()->route('admin.index')->with('success', 'Usuario creado correctamente');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $usuario = User::findOrFail($id);
        return view('admin.edit', compact('usuario'));// zonaAd es la vista que se va a mostrar
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        $usuario = User::findOrFail($id);
        $entrada = $request->all();
        if($archivo = $request->file('foto_id')){
            $nombreArchivo = $archivo->getClientOriginalName();
            $archivo->move(public_path('fotos'), $nombreArchivo);
            $foto = Foto::create(['foto' => $nombreArchivo]);
            $entrada['foto_id'] = $foto->id;
            
        }
        $usuario->update($entrada);
        // Si se ha subido una nueva foto, eliminar la foto anterior
        return redirect()->route('admin.index')->with('success', 'Usuario creado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $usuario = User::findOrFail($id);
        $usuario->delete();
        return redirect()->route('admin.index')->with('success', 'Usuario eliminado correctamente');
    }
}
