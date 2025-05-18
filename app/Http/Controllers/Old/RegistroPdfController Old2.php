<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\User;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF; // Cambio clave aquí
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistroPdfController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function download(Request $request)
    {
        try {
            $user = Auth::user();
            $filtros = $this->validarFiltros($request);

            $registros = $this->obtenerRegistros($user, $filtros);

            if ($registros->isEmpty()) {
                return back()->with('error', 'No hay registros para el período seleccionado');
            }

            return $this->generarPdf($user, $registros, $filtros);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el PDF: '.$e->getMessage());
        }
    }

    private function validarFiltros(Request $request): array
    {
        return [
            'tipo' => $request->validate(['tipo' => 'sometimes|in:semana,mes,personalizado'])['tipo'] ?? 'semana',
            'mes' => $request->validate(['mes' => 'sometimes|numeric|between:1,12'])['mes'] ?? date('m'),
            'anio' => $request->validate(['anio' => 'sometimes|numeric|min:2020'])['anio'] ?? date('Y'),
            'fecha_inicio' => $request->validate(['fecha_inicio' => 'sometimes|date'])['fecha_inicio'] ?? null,
            'fecha_fin' => $request->validate(['fecha_fin' => 'sometimes|date|after_or_equal:fecha_inicio'])['fecha_fin'] ?? null,
        ];
    }

    private function obtenerRegistros(User $user, array $filtros)
    {
        $query = $user->registros()->orderBy('fecha_hora', 'desc');

        return match ($filtros['tipo']) {
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

    private function generarPdf(User $user, $registros, array $filtros)
    {
        $titulo = $this->generarTitulo($filtros);
        $nombreArchivo = 'registros-'.Str::slug($titulo).'-'.now()->format('Ymd-His').'.pdf';

        // Configuración específica para Snappy
        return PDF::loadView('pdf.registros', [
            'user' => $user,
            'registros' => $registros,
            'titulo' => $titulo,
            'fechaGeneracion' => now()->format('d/m/Y H:i')
        ])
        ->setOption('margin-top', 10)
        ->setOption('margin-bottom', 10)
        ->setOption('margin-left', 10)
        ->setOption('margin-right', 10)
        ->setOption('enable-local-file-access', true) // Opción clave para assets locales
        ->setOption('encoding', 'utf-8')
        ->download($nombreArchivo);
    }

    private function generarTitulo(array $filtros): string
    {
        return match ($filtros['tipo']) {
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
