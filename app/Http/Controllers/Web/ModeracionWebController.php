<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Reporte;
use App\Models\Moderacion;

class ModeracionWebController extends Controller
{
    public function index()
    {
        $pendientes = Reporte::with(['usuario','area','tipo'])
            ->where('estado', 'pendiente')
            ->orderByDesc('fecha_reporte')
            ->get();

        return view('admin.moderacion.index', compact('pendientes'));
    }

    public function show($id)
    {
        $reporte = Reporte::with(['usuario','area','tipo'])
            ->findOrFail($id);

        return view('admin.moderacion.show', compact('reporte'));
    }

    public function action(Request $request, $id)
    {
        $request->validate([
            'accion' => 'required|in:aprobado,rechazado',
            'motivo' => 'nullable|string'
        ]);

        $reporte = Reporte::findOrFail($id);

        $admin = Auth::guard('admin')->user();

        Moderacion::create([
            'id_reporte' => $reporte->id_reporte,
            'id_admin'   => $admin->id_usuario,
            'accion'     => $request->accion,
            'motivo'     => $request->motivo
        ]);

        $reporte->estado = $request->accion;
        $reporte->save();

        return redirect()->route('admin.moderacion.index')
            ->with('ok', 'Moderación realizada correctamente');
    }


    public function historial()
    {
        $historial = Moderacion::with([
                'reporte.usuario',
                'reporte.tipo',
                'admin'
            ])
            ->orderByDesc('fecha_modificacion') 
            ->get();

        return view('admin.moderacion.historial', compact('historial'));
    }
}
