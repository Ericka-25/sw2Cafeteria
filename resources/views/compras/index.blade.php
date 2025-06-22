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
                        Compras
                    </h2>
                </div>
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <a href="{{ route('compras.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Crear Nueva Compra
                    </a>
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
                            <h3 class="card-title">Listado De Compras</h3>
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
                                        <th>Fecha</th>
                                        <th>Total</th>     
                                        <th>Sucursal</th>                                   
                                        <th>Proveedor</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($compras as $compra)
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
                                                <a href="{{ route('compras.show', $compra->id) }}" title="Ver">
                                                    <i class="ti ti-eye"></i>
                                                </a>                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-muted">Mostrando {{ $compras->firstItem() }} a {{ $compras->lastItem() }} de
                                {{ $compras->total() }} registros</p>
                            <ul class="pagination m-0 ms-auto">
                                @if ($compras->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">‹</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $compras->previousPageUrl() }}" tabindex="-1"
                                            aria-disabled="true">‹</a>
                                    </li>
                                @endif

                                @foreach ($compras->getUrlRange(1, $compras->lastPage()) as $page => $url)
                                    <li class="page-item {{ $page == $compras->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                @if ($compras->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $compras->nextPageUrl() }}">›</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">›</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
