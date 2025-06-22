@extends('tablar::page')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Editar Rol
                    </h2>
                </div>
                <div class="col-12 col-md-auto ms-auto d-print-none">
                    <a href="{{ route('roles.index') }}" class="btn btn-primary d-none d-sm-inline-block">
                        Volver a Roles
                    </a>
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
                            <h3 class="card-title">Editar Rol</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label">Nombre del Rol</label>
                                    <input type="text" class="form-control" name="name" value="{{ $role->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Permisos</label>
                                    @foreach ($permissions as $permission)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ $permission->description }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
