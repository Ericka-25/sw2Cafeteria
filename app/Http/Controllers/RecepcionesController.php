<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sucursales;
use App\Models\Compras;
use App\Models\DetCompras;
use App\Models\DetVentas;
use App\Models\Recepciones;
use App\Models\SucursalesProductos;
use App\Models\Productos;
use App\Models\Ventas;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecepcionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:recepciones.index')->only(['index', 'show']);
        $this->middleware('can:recepciones.create')->only(['create', 'store']);                
    }

    public function index()
    {
        $comprasnr = Compras::leftJoin('recepciones', 'compras.id', '=', 'recepciones.idcompra')
            ->whereNull('recepciones.idcompra')
            ->select('compras.*')
            ->get();
        $comprasr = Compras::join('recepciones', 'compras.id', '=', 'recepciones.idcompra')
            ->select('compras.*')
            ->get();
        return view('recepciones.index', compact('comprasnr', 'comprasr'));
    }

    public function create($idcompra)
    {
        $compra = Compras::find($idcompra);
        $detcompras = DetCompras::where('idcompra', $idcompra)->get();
        $sucursales = Sucursales::where('estado', true)->get();
        return view('recepciones.create', compact('compra', 'detcompras', 'sucursales'));
    }

    public function store(Request $request)
    {
        $compra = Compras::find($request->idcompra);
        $recepcion = new Recepciones();
        $recepcion->idcompra = $request->idcompra;        
        $recepcion->fecha = $request->fecha;
        $recepcion->idusuario = Auth::user()->id;
        $fechaCompra = new DateTime($compra->fecha);
        $fechaRecepcion = new DateTime($request->fecha);
        $intervalo = $fechaCompra->diff($fechaRecepcion);
        $recepcion->tiempo = $intervalo->days;
        $recepcion->save();

        $detcompras = DetCompras::where('idcompra', $request->idcompra)->get();
        foreach ($detcompras as $detcompra) {
            //$this->actualizarCostoPromedioPonderado($detcompra);
            SucursalesProductos::where('idproducto', $detcompra->idproducto)
                ->where('idsucursal', $compra->idsucursal)
                ->increment('stock', $detcompra->cantidad);
            $this->actualizarROP($detcompra);
        }

        return redirect()->route('recepciones.index');
    }

    public function actualizarROP(DetCompras $detcompra){
        // Calcular el tiempo de entrega promedio de la producto
        $tiempoEntregaPromedio = Compras::join('det_compras as dc', 'compras.id', '=', 'dc.idcompra')
        ->join('recepciones as r', 'compras.id', '=', 'r.idcompra')
        ->where('dc.idproducto', $detcompra->idproducto)
        ->avg('r.tiempo');        

        // Calcular el periodo de tiempo entre la primera y última venta de la producto
        $venta = Ventas::whereHas('detVentas', function($query) use ($detcompra) {
            $query->where('idproducto', $detcompra->idproducto);
        })->latest('fecha')->first();
        if ($venta === null) {            
            return;
        }
        $fechaUltimaVenta = new DateTime($venta->fecha);
        $fechaPrimeraVenta = new DateTime(Ventas::whereHas('detVentas', function($query) use ($detcompra) {
            $query->where('idproducto', $detcompra->idproducto);
        })->oldest('fecha')->first()->fecha);        
        $intervalo = $fechaUltimaVenta->diff($fechaPrimeraVenta);
        $periodo = $intervalo->days; 
        
        $periodo = $periodo * 0.857143; // aqui se le corta al periodo porque no se atiende los domingos        

        //Calcular la demanda histórica de producto
        $historialDeDemanda = DetVentas::where('idproducto', $detcompra->idproducto)->sum('cantidad'); // 500

        //Calcular la demanda promedio de producto
        $demandaPromedio = $historialDeDemanda / $periodo; //500 / 7 = 71 /dia

        $producto = Productos::find($detcompra->idproducto);
        // Calcular el punto de reorden de la producto
        $rop = $demandaPromedio * $tiempoEntregaPromedio + $producto->seguridad;        
        // Actualizar el punto de reorden en la base de datos
        $producto->rop = $rop;
        $producto->seguridad = $rop * 0.1;        
        $producto->save();
    }

    /* public function actualizarCostoPromedioPonderado(DetCompras $detcompra){
        $producto = Productos::find($detcompra->idproducto);
        // Obtener la sumatoria del stock en sucursales y sucursales para la producto específica
        $stockSucursales = SucursalesProductos::where('idproducto', $detcompra->idproducto)->sum('stock');
        $stockSucursales = SucursalesProductos::where('idproducto', $detcompra->idproducto)->sum('stock');
        
        // Sumar el stock de sucursales y sucursales
        $stockInventario = $stockSucursales + $stockSucursales;
        
        // Calcular el costo del inventario actual
        $costoInventario = $stockInventario * $producto->precioxcompra;

         // Calcular el costo total de la compra actual, incluyendo el total y cualquier gasto adicional
        $costoCompra = $detcompra->total + $detcompra->totalag;
        
        // Calcular el costo total combinando el costo del inventario actual y el costo de la compra
        $costoTotal = $costoInventario + $costoCompra;

        // Calcular el nuevo stock total combinando el stock del inventario actual y la cantidad de la compra
        $stockTotal = $stockInventario + $detcompra->cantidad;

        // Calcular el costo unitario promedio ponderado
        $costoUnitarioPromedioPonderado = $costoTotal / $stockTotal;

        // Actualizar el costo unitario promedio ponderado en la base de datos
        $producto->precioxcompra = $costoUnitarioPromedioPonderado;
        $producto->save();
    } */

    public function show($id){
        $compra = Compras::find($id);
        $recepcion = Recepciones::where('idcompra', $id)->first();
        $detcompras = DetCompras::where('idcompra', $id)->get();
        return view('recepciones.show', compact('compra', 'recepcion', 'detcompras'));
    }
}
