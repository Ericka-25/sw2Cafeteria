<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Proveedores;
use App\Models\SucursalesProductos;
use App\Models\Productos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:productos.index')->only(['index', 'show']);
        $this->middleware('can:productos.create')->only(['create', 'store']);
        $this->middleware('can:productos.edit')->only(['edit', 'update']);
        $this->middleware('can:productos.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Productos::where('estado',true)->where('rop','>',0)->orderby('id', 'asc')->get();

        $productos2 = $productos->filter(function ($producto) {            
            $stock =  SucursalesProductos::where('idproducto', $producto->id)->sum('stock');            
            $producto->stock = $stock;
            return $producto->rop >= $stock;
        })->values();                
        
        $productos = Productos::where('estado',true)->orderby('id', 'asc')->paginate(10);  
        //dd($productos);      
        return view('productos.index', compact('productos','productos2'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proveedores = Proveedores::where('estado', true)->get();
        return view('productos.create', compact('proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Productos::create($request->all());
        return redirect()->route('productos.index')->with('success', 'Producto creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        session()->forget('data');
        $producto = Productos::find($id);
        $producto->stock = SucursalesProductos::where('idproducto', $id)->sum('stock');
        $suc = SucursalesProductos::where('idproducto', $id)->get();        
        $url = 'http://localhost:8080/api/notificaciones/';
        //$url = 'https://notificaciones-serve.onrender.com/api/notificaciones/';
        Http::delete($url . $id);
        return view('productos.show', compact('producto', 'suc'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $producto = Productos::find($id);
        $proveedores = Proveedores::where('estado', true)->get();
        return view('productos.edit', compact('producto', 'proveedores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $producto = Productos::find($id);
        $producto->update($request->all());
        return redirect()->route('productos.index')->with('success', 'Producto actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Productos::find($id);
        $producto->estado = false;
        $producto->save();
        return redirect()->route('productos.index')->with('success', 'Producto eliminada exitosamente.');
    }
}
