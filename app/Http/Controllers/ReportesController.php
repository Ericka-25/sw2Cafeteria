<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sucursales;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:reportes.formdemandas')->only(['formdemandas', 'demandas']);                
    }

    public function formdemandas()
    {
        $sucursales = Sucursales::where('estado', true)->orderby('id')->get();
        return view('reportes.formdemandas', compact('sucursales'));
    }

    public function demandas(Request $request)
    {
        $sucursales = Sucursales::where('estado', true)->get();
        $query = "
        SELECT 
    productos_sucursales.idtela, 
    t.nombre as tela, 
    telas_sucursales.idsucursal, 
    s.direccion as sucursal, 
    COALESCE(SUM(dv.cantidad), 0) as demanda
FROM 
    (SELECT 
        t.id as idtela, 
        s.id as idsucursal 
    FROM 
        telas t, 
        sucursales s) telas_sucursales
LEFT JOIN ventas v ON v.idsucursal = telas_sucursales.idsucursal 
                    AND v.fecha BETWEEN ? AND ?
LEFT JOIN det_ventas dv ON dv.idventa = v.id 
                         AND dv.idtela = telas_sucursales.idtela
LEFT JOIN sucursales s ON s.id = telas_sucursales.idsucursal
LEFT JOIN telas t ON t.id = telas_sucursales.idtela
GROUP BY 
    telas_sucursales.idtela, 
    t.nombre, 
    telas_sucursales.idsucursal, 
    s.direccion
ORDER BY 
    telas_sucursales.idtela, 
    telas_sucursales.idsucursal;        
        ";

        $telas = DB::select($query, [$request->fechaini, $request->fechafin]);

        return view('reportes.demandas', compact('sucursales', 'telas', 'request'));
    }
}
