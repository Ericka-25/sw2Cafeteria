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
                        Sucursales
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <!-- Botón para registrar una nueva sucursal -->
                    <a href="{{ route('sucursales.create') }}" class="btn btn-primary">
                        <!-- Icono de agregar sucursal -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Nueva Sucursal
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
                            <h3 class="card-title">Lista de sucursales</h3>
                        </div>
                        {{-- <div class="card-body border-bottom py-3">
                            <div class="d-flex">
                                <div class="text-muted">
                                    Show
                                    <div class="mx-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm" value="5"
                                            size="3" aria-label="Sucursales count">
                                    </div>
                                    entries
                                </div>
                                <div class="ms-auto text-muted">
                                    Search:
                                    <div class="ms-2 d-inline-block">
                                        <input type="text" class="form-control form-control-sm"
                                            aria-label="Search sucursal">
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="table-responsive">
                            <table class="table table-vcenter table-nowrap">
                                <thead>
                                    <tr>
                                        <th class="text-muted">ID</th>
                                        <th class="text-muted">Direccion</th>
                                        <th class="text-muted">Zona</th>
                                        <th class="text-muted">Celular</th>
                                        <th class="text-muted">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sucursales as $sucursal)
                                        <tr>
                                            <td>{{ $sucursal->id }}</td>
                                            <td>{{ $sucursal->direccion }}</td>
                                            <td>{{ $sucursal->zona }}</td>
                                            <td>{{ $sucursal->celular }}</td>
                                            <td>
                                                <a href="{{ route('sucursales.show', $sucursal->id) }}" title="Ver">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('sucursales.edit', $sucursal) }}" title="Editar">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('sucursales.destroy', $sucursal->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Eliminar"
                                                        onclick="return confirm('¿Estás seguro de eliminar este elemento?');"
                                                        style="background:none; border:none; padding:0; margin:0; cursor:pointer;">
                                                        <i class="ti ti-trash" style="color: #0054a6"></i>
                                                    </button>
                                                </form>
                                                {{-- <a href="{{ route('sucursales.edit', $sucursal) }}"
                                                    class="btn btn-sm btn-primary">Editar</a>
                                                <form action="{{ route('sucursales.destroy', $sucursal) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                                                </form> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-muted">Mostrando {{ $sucursales->firstItem() }} a
                                {{ $sucursales->lastItem() }} de {{ $sucursales->total() }} registros</p>
                            <ul class="pagination m-0 ms-auto">
                                @if ($sucursales->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">‹</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $sucursales->previousPageUrl() }}" tabindex="-1"
                                            aria-disabled="true">‹</a>
                                    </li>
                                @endif

                                @foreach ($sucursales->getUrlRange(1, $sucursales->lastPage()) as $page => $url)
                                    <li class="page-item {{ $page == $sucursales->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                @if ($sucursales->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $sucursales->nextPageUrl() }}">›</a>
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
