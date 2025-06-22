@extends('tablar::page')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        Recepciones De Compras
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Listado De Compras Recepcionadas</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th class="w-1">#
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-sm text-dark icon-thick" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <polyline points="6 15 12 9 18 15" />
                                            </svg>
                                        </th>
                                        <th>Fecha Compra</th>
                                        <th>Fecha Recepcion</th>                                        
                                        <th>Total</th>                                        
                                        <th>Proveedor</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($comprasr as $compra)
                                        <tr>
                                            <td><span class="text-muted">{{ $compra->id }}</span></td>
                                            <td>
                                                {{ $compra->fecha }}
                                            </td>
                                            <td>
                                                {{ $compra->recepcion->fecha }}
                                            </td>
                                            <td>
                                                {{ $compra->total }}
                                            </td>
                                            <td>
                                                {{ $compra->proveedor->nombre }}
                                            </td>
                                            <td>
                                                <a href="{{ route('recepciones.show', $compra->id) }}"
                                                    title="Ver Compra Recepcionada">
                                                    <i class="ti ti-eye"></i>
                                                </a>
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

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Listado De Compras Sin Recepcionar</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th class="w-1">#
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-sm text-dark icon-thick" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <polyline points="6 15 12 9 18 15" />
                                            </svg>
                                        </th>
                                        <th>Fecha Compra</th>
                                        <th>Total</th>
                                        <th>Sucursal</th>
                                        <th>Proveedor</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($comprasnr as $compra)
                                        <tr>
                                            <td><span class="text-muted">{{ $compra->id }}</span></td>
                                            <td>
                                                {{ $compra->fecha }}
                                            </td>
                                            <td>
                                                {{ $compra->total }}
                                            </td>
                                            <td>
                                                {{ $compra->sucursal->direccion }}
                                            </td>
                                            <td>
                                                {{ $compra->proveedor->nombre }}
                                            </td>
                                            <td>
                                                <a href="{{ route('recepciones.create', $compra->id) }}"
                                                    title="recepcionar compra">
                                                    <i class="ti ti-book"></i>
                                                </a>
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
