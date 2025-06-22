<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ventas;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GraficasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:graficas.metas')->only(['metas']);        
    }

    public function metas()
    {
        $usuarios = User::where('estado',true)->where('metas', '>', 0)->get();
        $currentDate = Carbon::now();

        // Calcular las fechas de las semanas
        $semana1ini = $currentDate->copy()->startOfMonth();
        $semana1fin = $semana1ini->copy()->endOfWeek();

        $semana2ini = $semana1fin->copy()->addDay();
        $semana2fin = $semana2ini->copy()->endOfWeek();

        $semana3ini = $semana2fin->copy()->addDay();
        $semana3fin = $semana3ini->copy()->endOfWeek();

        $semana4ini = $semana3fin->copy()->addDay();
        $semana4fin = $currentDate->copy()->endOfMonth();
        foreach ($usuarios as $usuario) {
            $usuario->sem1 = Ventas::where('idusuario', $usuario->id)->whereBetween('fecha', [$semana1ini, $semana1fin])->sum('total');
            $usuario->sem2 = Ventas::where('idusuario', $usuario->id)->whereBetween('fecha', [$semana2ini, $semana2fin])->sum('total');
            $usuario->sem3 = Ventas::where('idusuario', $usuario->id)->whereBetween('fecha', [$semana3ini, $semana3fin])->sum('total');
            $usuario->sem4 = Ventas::where('idusuario', $usuario->id)->whereBetween('fecha', [$semana4ini, $semana4fin])->sum('total');
            $usuario->metas = $usuario->metas - ($usuario->sem1 + $usuario->sem2 + $usuario->sem3 + $usuario->sem4);
            if ($usuario->metas < 0) {
                $usuario->metas = 0;
            }
        }        
        return view('graficas.metas', compact('usuarios'));
    }
}
