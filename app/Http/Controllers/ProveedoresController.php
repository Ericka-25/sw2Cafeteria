<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Proveedores;
use Illuminate\Http\Request;

class ProveedoresController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:proveedores.index')->only(['index', 'show']);
        $this->middleware('can:proveedores.create')->only(['create', 'store']);
        $this->middleware('can:proveedores.edit')->only(['edit', 'update']);
        $this->middleware('can:proveedores.destroy')->only('destroy');
    }

    public function index()
    {
        $proveedores = Proveedores::where('estado', true)->orderby('id','asc')->paginate(10);
        return view('proveedores.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Proveedores::create($request->all());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $proveedor = Proveedores::find($id);
        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $proveedor = Proveedores::find($id);
        $proveedor->update($request->all());

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $proveedor = Proveedores::find($id);
        $proveedor->update(['estado' => false]);

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado correctamente.');
    }
}
