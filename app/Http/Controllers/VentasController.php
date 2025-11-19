<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DetVentas;
use App\Models\Sucursales;
use App\Models\SucursalesProductos;
use App\Models\Productos;
use App\Models\Ventas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:ventas.index')->only(['index', 'show']);
        $this->middleware('can:ventas.create')->only(['create', 'store']);
        $this->middleware('can:ventas.edit')->only(['edit', 'update']);
        $this->middleware('can:ventas.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ventas = Ventas::where('estado', true)->paginate(10);
        return view('ventas.index', compact('ventas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = DB::table('sucursalesproductos as st')
            ->join('sucursales as s', function ($join) {
                $join->on('s.id', '=', 'st.idsucursal')
                    ->where('s.estado', true);
            })
            ->join('productos as t', function ($join) {
                $join->on('t.id', '=', 'st.idproducto')
                    ->where('t.estado', true);
            })
            ->where('st.stock', '>', 0)
            ->select('st.*', 't.*')
            ->orderBy('t.nombre')
            ->get();

        $productosPorSucursal = [];
        foreach ($productos as $producto) {
            $productosPorSucursal[$producto->idsucursal][] = $producto;
        }
        // Convertir a JSON para usar en JavaScript
        $productosPorSucursalJson = json_encode($productosPorSucursal);

        $sucursales = Sucursales::where('estado', true)->get();
        return view('ventas.create', compact('sucursales', 'productosPorSucursalJson'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        session()->forget('data');
        $venta = new Ventas();
        $venta->fecha = $request->fecha;
        $venta->total = $request->total;
        $venta->idsucursal = $request->idsucursal;
        $venta->idusuario = Auth::user()->id;
        $venta->save();

        $productos = is_string($request->productos) ? json_decode($request->productos, true) : $request->productos;

        foreach ($productos as $producto) {
            $detventa = new DetVentas();
            $detventa->idventa = $venta->id;
            $detventa->idproducto = $producto['idProducto'];
            $detventa->cantidad = $producto['cantidad'];
            $detventa->total = $producto['importe'];
            $detventa->save();

            // Actualizar stock
            SucursalesProductos::where('idsucursal', $venta->idsucursal)
                ->where('idproducto', $producto['idProducto'])
                ->decrement('stock', $producto['cantidad']);

            //Si tu stock es menor o igual al rop, que notifique al gerente
            $this->notificarGerente($producto['idProducto']);

        }
        return redirect()->route('ventas.index');
    }

    private function notificarGerente($idproducto)
    {
        $producto = Productos::findOrFail($idproducto);
        $tockSucursales = SucursalesProductos::where('idproducto', $producto->id)->sum('stock');
        if($producto->rop >= $tockSucursales){
            // Crear la nueva notificación
            $data = [
                'idproducto' => $producto->id,
                'nombre' => $producto->nombre,
                'stock' => "{$tockSucursales}",
                'rop' => $producto->rop
            ];

            // Guardar la lista de notificaciones en la sesión
            //$request->session()->put('data', $data);
            session()->push('data', $data);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $venta = Ventas::findOrFail($id);
        $detventas = DetVentas::where('idventa', $id)->get();

        return view('ventas.show', compact('venta', 'detventas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }



    //TODO: CONSUMI DE AI

}