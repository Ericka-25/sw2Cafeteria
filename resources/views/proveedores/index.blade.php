@extends('tablar::page')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        Proveedores
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <!-- Botón para registrar una nueva proveedor -->
                    <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
                        <!-- Icono de agregar proveedor -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Nuevo Proveedor
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
                            <h3 class="card-title">Lista De Proveedores</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter table-nowrap">
                                <thead>
                                    <tr>
                                        <th class="text-muted">#</th>
                                        <th class="text-muted">Nombre</th>
                                        <th class="text-muted">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($proveedores as $proveedor)
                                        <tr>
                                            <td>{{ $proveedor->id }}</td>
                                            <td>{{ $proveedor->nombre }}</td>
                                            <td>
                                                <a href="{{ route('proveedores.edit', $proveedor) }}" title="Editar">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('proveedores.destroy', $proveedor->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Eliminar"
                                                        onclick="return confirm('¿Estás seguro de eliminar este elemento?');"
                                                        style="background:none; border:none; padding:0; margin:0; cursor:pointer;">
                                                        <i class="ti ti-trash" style="color: #0054a6"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-muted">Mostrando {{ $proveedores->firstItem() }} a
                                {{ $proveedores->lastItem() }} de {{ $proveedores->total() }} registros</p>
                            <ul class="pagination m-0 ms-auto">
                                @if ($proveedores->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">‹</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $proveedores->previousPageUrl() }}" tabindex="-1"
                                            aria-disabled="true">‹</a>
                                    </li>
                                @endif

                                @foreach ($proveedores->getUrlRange(1, $proveedores->lastPage()) as $page => $url)
                                    <li class="page-item {{ $page == $proveedores->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                @if ($proveedores->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $proveedores->nextPageUrl() }}">›</a>
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
