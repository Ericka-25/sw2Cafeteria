@extends('tablar::page')
@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Vista Venta
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="fecha" class="form-label">Fecha</label>
                                <input id="fecha" type="date" class="form-control" name="fecha"
                                    value="{{ $venta->fecha }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="total" class="form-label">total</label>
                                <input id="total" type="text" class="form-control" name="total"
                                    value="{{ $venta->total }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="idusuario" class="form-label">vendedor</label>
                                <input id="idusuario" type="text" class="form-control" name="idusuario"
                                    value="{{ $venta->usuario->name }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="idsucursal" class="form-label">sucursal</label>
                                <input id="idsucursal" type="text" class="form-control" name="idsucursal"
                                    value="{{ $venta->sucursal->direccion }}" readonly>
                            </div>
                            
                            <a href="{{ route('ventas.index') }}" class="btn btn-primary">Volver</a>
                        </div>
                        <div class="card-header">
                            <h3 class="card-title">Detalle De Venta</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>NombreProducto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Importe</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detventas as $detventa)
                                        <tr>
                                            <td>
                                                <span class="flag flag-country-us"></span>
                                                {{ $detventa->producto->nombre }}
                                            </td>
                                            <td>
                                                {{ $detventa->cantidad }}
                                            </td>
                                            <td>
                                                {{ $detventa->total / $detventa->cantidad }}
                                            </td>
                                            <td>
                                                {{ $detventa->total }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
