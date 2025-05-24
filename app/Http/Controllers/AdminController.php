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
        $entrada = $request->except('_token', 'foto'); // Excluye el campo foto

    if ($request->hasFile('foto_id') && $request->file('foto_id')->isValid()) {
        $archivo = $request->file('foto_id');
        $nombreArchivo = time().'_'.$archivo->getClientOriginalName();
        $archivo->move(public_path('fotos'), $nombreArchivo);

        $foto = Foto::create(['foto' => $nombreArchivo]);
        $entrada['foto_id'] = $foto->id; // Asigna solo el ID
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
    $entrada = $request->except(['password']); // Excluimos password inicialmente

    // Solo actualizar password si se proporcionó
    if (!empty($request->password)) {
        $entrada['password'] = bcrypt($request->password);
    }

    if($archivo = $request->file('foto_id')) {
        $nombreArchivo = $archivo->getClientOriginalName();
        $archivo->move(public_path('fotos'), $nombreArchivo);
        $foto = Foto::create(['foto' => $nombreArchivo]);
        $entrada['foto_id'] = $foto->id;
    }

    $usuario->update($entrada);
    return redirect()->route('admin.index')->with('success', 'Usuario actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $usuario = User::findOrFail($id);
       // Eliminar la foto del servidor
        if($usuario->foto) {
            $fotoPath = public_path('fotos/'.$usuario->foto->foto);
            if(file_exists($fotoPath)) {
                unlink($fotoPath);
            }
            $usuario->foto->delete();
        }
        // Eliminar el usuario
        $usuario->delete();
        return redirect()->route('admin.index')->with('success', 'Usuario eliminado correctamente');
    }



    /**
     * Show the details of a specific user.
     */

       public function details($id) // Cambiamos de User $user a $id
{
    // Obtener usuario con relaciones
    $user = User::with(['role', 'registros', 'foto'])->find($id);

    if (!$user) {
        abort(404, 'Usuario no encontrado');
    }

    // Verificar rol (opcional, según tus requisitos)
    if ($user->role_id !== 2) { // 2 = Usuario
        abort(403, 'Solo se pueden ver detalles de usuarios normales');
    }

    // Obtener registros
    $registros = $user->registros()
                     ->orderBy('fecha_hora', 'desc')
                     ->take(20)
                     ->get();

    return view('admin.userDetails', compact('user', 'registros'));
}
}
