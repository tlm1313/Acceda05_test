<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Foto;
use App\Models\Registro;
use Carbon\Carbon;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

        $usuarios = User::with(['role', 'foto'])->paginate(8); // 8 usuarios por página
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
 $entrada = $request->except('_token');

    // Usa el nuevo nombre 'foto'
    if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
        $archivo = $request->file('foto');
        $nombreArchivo = time().'_'.$archivo->getClientOriginalName();

        // Mover a public/fotos
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
         $request->validate([
        'email' => [
            'required',
            'email',
            Rule::unique('users')->ignore($usuario->id),
        ],
    ]);
    try{
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
    } catch (\Illuminate\Database\QueryException $e) {
        $errorCode = $e->errorInfo[1];

        if($errorCode == 1062) {
            return back()->withInput()->with('error', 'El email o DNI ya están registrados');
        }

        return back()->withInput()->with('error', 'Error al actualizar el usuario');
    }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $usuario = User::findOrFail($id);
       // Eliminar la foto del servidor
        if($usuario->foto && $usuario->foto == null) {
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

    /**
     * Show registros of all users.
     *
     */


    public function allRegisters(Request $request)
    {
        $query = Registro::with('user')->latest();

        // Filtro 1: Todos los registros (no necesita condición adicional)

        // Filtro 2: Por mes
        if ($request->filled('mes') && $request->filled('anio')) {
            $query->whereMonth('fecha_hora', $request->mes)
                ->whereYear('fecha_hora', $request->anio);
        }

        // Filtro 3: Personalizado (rango de fechas)
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin') && !$request->filled('dni')) {
            $query->whereBetween('fecha_hora', [
                Carbon::parse($request->fecha_inicio)->startOfDay(),
                Carbon::parse($request->fecha_fin)->endOfDay()
            ]);
        }

        // Filtro 4: Por DNI con rango de fechas
        if ($request->filled('dni')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('Dni', $request->dni);
            });

            // Añadir rango de fechas si están presentes
            if ($request->filled('fecha_inicio_dni') && $request->filled('fecha_fin_dni')) {
                $query->whereBetween('fecha_hora', [
                    Carbon::parse($request->fecha_inicio_dni)->startOfDay(),
                    Carbon::parse($request->fecha_fin_dni)->endOfDay()
                ]);
            }
        }

        $registros = $query->paginate(10)->appends($request->query());

        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        return view('admin.all-registers', compact('registros', 'meses'));
    }

 /**
     * Funcion para exportar Pdfs.
     *
     */

public function exportPdf(Request $request)
{
    // Reutilizamos la lógica de filtrado del método allRegisters
    $query = Registro::with('user')->latest();

    if ($request->filled('mes') && $request->filled('anio')) {
        $query->whereMonth('fecha_hora', $request->mes)
              ->whereYear('fecha_hora', $request->anio);
    }

    if ($request->filled('fecha_inicio') && $request->filled('fecha_fin') && !$request->filled('dni')) {
        $query->whereBetween('fecha_hora', [
            Carbon::parse($request->fecha_inicio)->startOfDay(),
            Carbon::parse($request->fecha_fin)->endOfDay()
        ]);
    }

    if ($request->filled('dni')) {
        $query->whereHas('user', function($q) use ($request) {
            $q->where('Dni', $request->dni);
        });

        if ($request->filled('fecha_inicio_dni') && $request->filled('fecha_fin_dni')) {
            $query->whereBetween('fecha_hora', [
                Carbon::parse($request->fecha_inicio_dni)->startOfDay(),
                Carbon::parse($request->fecha_fin_dni)->endOfDay()
            ]);
        }
    }

    $registros = $query->get();
    $filtros = $request->all();

     // Definir el array de meses
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];


    $pdf = PDF::loadView('admin.exports.registros-pdf', [
        'registros' => $registros,
        'filtros' => $filtros,
        'meses' => $meses // Pasamos la variable a la vista
    ])->setOptions([
        'encoding' => 'UTF-8',
        'margin-top' => 15,
        'margin-bottom' => 15,
        'margin-left' => 10,
        'margin-right' => 10
    ]);

    return $pdf->download('registros-'.now()->format('Y-m-d').'.pdf');
}





}
