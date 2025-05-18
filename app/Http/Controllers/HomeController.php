<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Registro;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

// app/Http/Controllers/HomeController.php

public function index(Request $request)
{
    $user = Auth::user()->load(['role', 'foto']);

    if ($user->esAdmin()) {
        return redirect()->route('admin.index');
    }

    // Obtener parámetros de filtrado
    $mes = $request->input('mes', date('m'));
    $anio = $request->input('anio', date('Y'));
    $fechaInicio = $request->input('fecha_inicio');
    $fechaFin = $request->input('fecha_fin');

    // Consulta base
    $registrosQuery = $user->registros()->latest();

    // Registros semanales (últimos 7 días)
    $registrosSemanales = clone $registrosQuery;
    $registrosSemanales = $registrosSemanales->where('fecha_hora', '>=', now()->subDays(7))->get();

    // Registros mensuales
    $registrosMensuales = clone $registrosQuery;
    $registrosMensuales = $registrosMensuales->whereMonth('fecha_hora', $mes)
                                ->whereYear('fecha_hora', $anio)
                                ->get();

    // Registros por rango personalizado
    $registrosPersonalizados = collect();
    if ($fechaInicio && $fechaFin) {
        $registrosPersonalizados = $registrosQuery->whereBetween('fecha_hora', [
            \Carbon\Carbon::parse($fechaInicio)->startOfDay(),
            \Carbon\Carbon::parse($fechaFin)->endOfDay()
        ])->get();
    }

    $estadoActual = $this->getEstadoActual($user);
    $meses = [
        1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
        5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
        9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
    ];

    return view('user.zonaUsuario', compact(
        'user',
        'estadoActual',
        'registrosSemanales',
        'registrosMensuales',
        'registrosPersonalizados',
        'meses',
        'mes',
        'anio',
        'fechaInicio',
        'fechaFin'
    ));
}

    public function registrarEntrada($userId)
    {
        return $this->crearRegistro($userId, 'entrada');
    }

    public function registrarSalida($userId)
    {
        return $this->crearRegistro($userId, 'salida');
    }

    private function crearRegistro($userId, $tipo)
    {
        if (Auth::id() != $userId) {
            abort(403);
        }

        $ultimoRegistro = Registro::where('user_id', $userId)
                                ->latest()
                                ->first();

        // Validaciones
        if ($ultimoRegistro) {
            if ($ultimoRegistro->tipo === $tipo) {
                $mensaje = ($tipo === 'entrada')
                    ? 'No puedes registrar una entrada sin haber registrado la salida anterior'
                    : 'No puedes registrar una salida sin haber registrado una entrada previa';

                return back()->with('error', $mensaje);
            }

            if ($ultimoRegistro->created_at->diffInMinutes(now()) < 5) {
                return back()->with('error', 'Debes esperar al menos 5 minutos entre registros');
            }
        } elseif ($tipo === 'salida') {
            return back()->with('error', 'Debes registrar una entrada primero');
        }

        Registro::create([
            'user_id' => $userId,
            'tipo' => $tipo,
            'fecha_hora' => now() // Asegura que sea un objeto Carbon
        ]);

        return back()->with('success', ucfirst($tipo) . ' registrada correctamente');
    }

    private function getEstadoActual(User $user)
    {
        if ($user->registros->isEmpty()) {
            return 'Sin registros';
        }

        return ($user->registros->first()->tipo === 'entrada')
            ? 'Dentro del sistema'
            : 'Fuera del sistema';
    }
}
