@extends('tablar::page')
@section('content')    
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Vista Producto
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
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input id="nombre" type="text" class="form-control" name="nombre"
                                        value="{{ $producto->nombre }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="precio_compra" class="form-label">Precio Compra</label>
                                    <input id="precio_compra" type="text" class="form-control" name="precio_compra"
                                        value="{{ number_format($producto->precio_compra, 2) }}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="rop" class="form-label">Rop</label>
                                    <input id="rop" type="text" class="form-control" name="rop"
                                        value="{{ number_format($producto->rop, 2) }}" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="precio_venta" class="form-label">Precio Venta</label>
                                    <input id="precio_venta" type="text" class="form-control" name="precio_venta"
                                        value="{{ number_format($producto->precio_venta, 2) }}" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label">Stock Total</label>
                                    <input id="stock" type="text" class="form-control" name="stock"
                                        value="{{ $producto->stock }}" readonly>
                                </div>                                
                            </div>                            
                            <a href="{{ route('productos.index') }}" class="btn btn-primary">Volver</a>
                        </div>                        
                        <div class="card-header">
                            <h3 class="card-title">Stock En Sucursales</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th class="w-1">#
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-sm text-dark icon-thick" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <polyline points="6 15 12 9 18 15" />
                                            </svg>
                                        </th>
                                        <th>Nombre Sucursal</th>
                                        <th>Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($suc as $sucursal)
                                        <tr>
                                            <td>
                                                <span class="text-muted">
                                                    {{ $sucursal->idsucursal }}</span>
                                            </td>
                                            <td>
                                                <span class="flag flag-country-us"></span>
                                                {{ $sucursal->sucursal->direccion }}
                                            </td>
                                            <td data-stocks="{{ $sucursal->stock }}">
                                                {{ $sucursal->stock }}
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
    {{-- <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            let totalStocks = 0;
            document.querySelectorAll('td[data-stocks]').forEach((cell) => {
                totalStocks += parseFloat(cell.getAttribute('data-stocks'));
            });
            document.getElementById('totals').textContent = totalStocks;

            let totalStocka = 0;
            document.querySelectorAll('td[data-stocka]').forEach((cell) => {
                totalStocka += parseFloat(cell.getAttribute('data-stocka'));
            });
            document.getElementById('totala').textContent = totalStocka;
        });
    </script> --}}
@endsection
