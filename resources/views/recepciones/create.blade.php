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
                        Recepcionar Compra
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('recepciones.store') }}">
                                @csrf
                                <h2 style="text-align: center; padding-top: 20px">Compra</h2>
                                <input type="hidden" name="idcompra" value="{{ $compra->id }}">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fechac" class="form-label">fecha compra</label>
                                        <input id="fechac" type="date" class="form-control" name="fechac"
                                            value="{{ $compra->fecha }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="proveedor" class="form-label">proveedor</label>
                                        <input id="proveedor" type="text" class="form-control" name="proveedor"
                                            value="{{ $compra->proveedor->nombre }}" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="total" class="form-label">total</label>
                                        <input id="total" type="text" class="form-control" name="total"
                                            value="{{ $compra->total }}" readonly>
                                    </div>
                                </div>
                                <h2 style="text-align: center; padding-top: 20px">Detalle De Compra</h2>
                                <input type="hidden" id="productos" name="productos">
                                <table class="table mt-3">
                                    <thead>
                                        <tr>
                                            <th hidden>ID</th>
                                            <th>Nombre Tela</th>
                                            <th>Precio Compra</th>
                                            <th>Cantidad</th>
                                            <th>Importe</th>                                            
                                        </tr>
                                    </thead>
                                    <tbody class="tabla-productos">
                                        @foreach ($detcompras as $detalle)
                                            <tr>
                                                <td hidden>{{ $detalle->idproducto }}</td>
                                                <td>{{ $detalle->producto->nombre }}</td>
                                                <td>{{ $detalle->total / $detalle->cantidad }}</td>
                                                <td>{{ $detalle->cantidad }}</td>
                                                <td>{{ $detalle->total }}</td>                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <h2 style="text-align: center; padding-top: 20px">Recepcion De Compra</h2>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha" class="form-label">fecha recepcion</label>
                                        <input id="fecha" type="date" class="form-control" name="fecha"
                                            value="{{ old('fecha') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">                                        
                                        @error('almacen')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            /*INPUT FECHA*/
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0'); // Enero es 0!
            const dd = String(today.getDate()).padStart(2, '0');

            // Formatea la fecha en el formato YYYY-MM-DD
            const formattedToday = `${yyyy}-${mm}-${dd}`;

            // Establece la fecha actual como valor por defecto
            const fechaInput = document.getElementById('fecha');
            if (!fechaInput.value) {
                fechaInput.value = formattedToday;
            }
        });
    </script>
@endsection
