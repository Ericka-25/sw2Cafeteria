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
                        Productos
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('productos.create') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                            Nuevo Producto
                        </a>
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
                            <h3 class="card-title">Lista de productos</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>                                        
                                        <th class="w-1">#
                                            <!-- Icono de orden -->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-sm text-dark icon-thick" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <polyline points="6 15 12 9 18 15" />
                                            </svg>
                                        </th>
                                        <th>Nombre</th>
                                        <th>Precio Compra</th>                                        
                                        <th>Precio Venta</th>
                                        <th>ROP</th>
                                        <th>Proveedor</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productos as $producto)
                                        <tr>
                                            <td>{{ $producto->id }}</td>
                                            <td>{{ $producto->nombre }}</td>
                                            <td>{{ $producto->precio_compra }}</td>
                                            <td>{{ $producto->precio_venta }}</td>
                                            <td>{{ $producto->rop }}</td>
                                            <td>{{ $producto->proveedor->nombre }}</td>
                                            <td>
                                                <a href="{{ route('productos.show', $producto->id) }}" title="Ver">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('productos.edit', $producto) }}" title="Editar">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('productos.destroy', $producto->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Eliminar"
                                                        onclick="return confirm('¿Estás seguro de eliminar este elemento?');"
                                                        style="background:none; border:none; padding:0; margin:0; cursor:pointer;">
                                                        <i class="ti ti-trash" style="color: #0054a6"></i>
                                                    </button>
                                                </form>
                                                {{-- <a href="{{ route('productos.edit', $producto->id) }}"
                                                    class="btn btn-sm btn-primary">Edit</a>
                                                    <form action="{{ route('productos.destroy', $producto->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                    </form> --}}

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer d-flex align-items-center">
                            <p class="m-0 text-muted">Mostrando {{ $productos->firstItem() }} a {{ $productos->lastItem() }} de
                                {{ $productos->total() }} registros</p>
                            <ul class="pagination m-0 ms-auto">
                                @if ($productos->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">‹</a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $productos->previousPageUrl() }}" tabindex="-1"
                                            aria-disabled="true">‹</a>
                                    </li>
                                @endif

                                @foreach ($productos->getUrlRange(1, $productos->lastPage()) as $page => $url)
                                    <li class="page-item {{ $page == $productos->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                @if ($productos->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $productos->nextPageUrl() }}">›</a>
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


    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Productos Que Se Necesita Reabastecer El Stock</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>                                        
                                        <th class="w-1">#
                                            <!-- Icono de orden -->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-sm text-dark icon-thick" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <polyline points="6 15 12 9 18 15" />
                                            </svg>
                                        </th>
                                        <th>Nombre</th>
                                        <th>Stock Total</th>
                                        <th>ROP</th>
                                        <th>Stock De Seguridad</th>                                        
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productos2 as $producto)
                                        <tr>
                                            <td>{{ $producto->id }}</td>
                                            <td>{{ $producto->nombre }}</td>
                                            <td>{{ $producto->stock }}</td>
                                            <td>{{ $producto->rop }}</td>
                                            <td>{{ $producto->seguridad }}</td>                                                                                        
                                            <td>
                                                <a href="{{ route('productos.show', $producto->id) }}" title="Ver">
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
@endsection
