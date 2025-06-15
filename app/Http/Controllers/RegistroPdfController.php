<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Str;

class RegistroPdfController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    private function validarFiltros(Request $request): array
    {
        return $request->validate([
            'tipo' => 'sometimes|in:semana,mes,personalizado',
            'mes' => 'sometimes|numeric|between:1,12',
            'anio' => 'sometimes|numeric|min:2020',
            'fecha_inicio' => 'sometimes|date|required_if:tipo,personalizado',
            'fecha_fin' => 'sometimes|date|after_or_equal:fecha_inicio|required_if:tipo,personalizado'
        ]);
    }

    public function download(Request $request)
{
    try {
        $user = Auth::user();



        $filtros = $request->only(['tipo', 'mes', 'anio', 'fecha_inicio', 'fecha_fin']);
        $registros = $this->obtenerRegistros($user, $filtros);

        if ($registros->isEmpty()) {
            return back()->with('error', 'No hay registros para el período seleccionado');
        }

        $pdf = PDF::loadView('user.pdf.registros', [
            'user' => $user,
            'registros' => $registros,
            'titulo' => $this->generarTitulo($filtros),
            'fechaGeneracion' => now()->format('d/m/Y H:i'),
            'total' => $registros->count()
        ]);

        return $pdf->download($this->nombreArchivo($filtros));

    } catch (\Exception $e) {
        Log::error('Error generando PDF: ' . $e->getMessage());
        return back()->with('error', 'Error al generar el PDF: ' . $e->getMessage());
    }
}





    private function obtenerRegistros(User $user, array $filtros)
    {
        $query = $user->registros()->with('user')->orderBy('fecha_hora', 'desc');

        return match ($filtros['tipo'] ?? 'semana') {
            'semana' => $query->where('fecha_hora', '>=', now()->subDays(7))->get(),
            'mes' => $query->whereMonth('fecha_hora', $filtros['mes'])
                          ->whereYear('fecha_hora', $filtros['anio'])
                          ->get(),
            'personalizado' => $query->whereBetween('fecha_hora', [
                Carbon::parse($filtros['fecha_inicio'])->startOfDay(),
                Carbon::parse($filtros['fecha_fin'])->endOfDay()
            ])->get(),
            default => $query->limit(100)->get(),
        };
    }


    private function nombreArchivo(array $filtros): string
    {
        return 'registros-'.Str::slug($this->generarTitulo($filtros)).'-'.now()->format('Ymd-His').'.pdf';
    }

    private function generarTitulo(array $filtros): string
    {
        return match ($filtros['tipo'] ?? 'semana') {
            'semana' => 'Registros de la última semana',
            'mes' => 'Registros de '.$this->nombreMes($filtros['mes']).' '.$filtros['anio'],
            'personalizado' => 'Registros del '.Carbon::parse($filtros['fecha_inicio'])->format('d/m/Y').' al '.Carbon::parse($filtros['fecha_fin'])->format('d/m/Y'),
            default => 'Registros recientes',
        };
    }

    private function nombreMes(int $mes): string
    {
        return [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ][$mes];
    }
}
