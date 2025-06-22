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
                        Roles de usuario
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <a href="{{ route('roles.create') }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>
                        Nuevo Rol
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
                            <h3 class="card-title">Lista de roles</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th class="w-1">#</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($roles as $rol)
                                        <tr>
                                            <td><span class="text-muted">{{ $loop->iteration }}</span></td>
                                            <td>{{ $rol->name }}</td>
                                            <td>
                                                @if ($rol->name == 'Gerente')
                                                    Administrador del sistema
                                                @elseif ($rol->name == 'Almacenista')
                                                    Encargado de los Almacenes
                                                @elseif ($rol->name == 'Vendedor')
                                                    Encargado de las Ventas
                                                @else
                                                    Sin descripción
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('roles.show', $rol->id) }}" title="Ver">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                                <a href="{{ route('roles.edit', $rol->id) }}" title="Editar">
                                                    <i class="ti ti-edit"></i>
                                                </a>
                                                <form action="{{ route('roles.destroy', $rol->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este rol?');" style="background:none; border:none; padding:0; margin:0; cursor:pointer;">
                                                        <i class="ti ti-trash" style="color: #0054a6"></i>
                                                    </button>
                                                </form>
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
