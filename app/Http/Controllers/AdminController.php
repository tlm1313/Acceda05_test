<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Foto;
use Carbon\Carbon;
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
      /*   $usuarios = User::all();
        return view('admin.zonaAd', compact('usuarios'));// zonaAd es la vista que se va a mostrar */

        $usuarios = User::with(['role', 'foto'])->paginate(3); // 10 usuarios por página
        return view('admin.zonaAd', compact('usuarios'));
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

       public function details(Request $request, $id)
{
    $user = User::with(['role', 'foto'])->findOrFail($id);

    // Parámetros de filtrado
    $tipoFiltro = $request->input('tipo', 'semana'); // semana/mes/personalizado
    $mes = $request->input('mes', date('m'));
    $anio = $request->input('anio', date('Y'));
    $fechaInicio = $request->input('fecha_inicio');
    $fechaFin = $request->input('fecha_fin');

    // Consulta base
    $registros = $user->registros()->latest();

    // Aplicar filtros según el tipo
    switch ($tipoFiltro) {
        case 'semana':
            $registros->where('fecha_hora', '>=', now()->subDays(7));
            break;

        case 'mes':
            $registros->whereMonth('fecha_hora', $mes)
                     ->whereYear('fecha_hora', $anio);
            break;

        case 'personalizado':
            if ($fechaInicio && $fechaFin) {
                $registros->whereBetween('fecha_hora', [
                    Carbon::parse($fechaInicio)->startOfDay(),
                    Carbon::parse($fechaFin)->endOfDay()
                ]);
            }
            break;
    }

    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];

    return view('admin.userDetails', compact(
        'user',
        'registros',
        'meses',
        'mes',
        'anio',
        'fechaInicio',
        'fechaFin',
        'tipoFiltro'
    ));


}
}
