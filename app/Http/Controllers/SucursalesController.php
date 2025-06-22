<?php

namespace App\Http\Controllers;

use App\Models\Sucursales;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\String_;

class SucursalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:sucursales.index')->only(['index', 'show']);
        $this->middleware('can:sucursales.create')->only(['create', 'store']);
        $this->middleware('can:sucursales.edit')->only(['edit', 'update']);
        $this->middleware('can:sucursales.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sucursales = Sucursales::where('estado',true)->orderBy('id', 'asc')->paginate(10);
        return view('sucursales.index', compact('sucursales'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sucursales.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {        
        Sucursales::create($request->all());
        
        return redirect()->route('sucursales.index')
            ->with('success', 'Sucursal creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $sucursal = Sucursales::with('sucursalesproductos.producto')->find($id);        
        return view('sucursales.show', compact('sucursal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $id)
    {
        $sucursal = Sucursales::find($id);
        return view('sucursales.edit', compact('sucursal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {        
        $sucursal = Sucursales::find($id);
        $sucursal->update($request->all());

        return redirect()->route('sucursales.index')
            ->with('success', 'Sucursal actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $sucursal = Sucursales::find($id);
        $sucursal->estado = false;
        $sucursal->save();
        return redirect()->route('sucursales.index')
            ->with('success', 'Sucursal eliminada exitosamente.');
    }
}
