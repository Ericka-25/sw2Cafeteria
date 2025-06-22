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
                        Demanda Productos
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
                            <form method="POST" action="{{ route('reportes.demandas') }}">
                                @csrf
                                <h2 style="text-align: center; padding-top: 20px">Filtros</h2>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fechaini" class="form-label">Desde</label>
                                        <input id="fechaini" type="date"
                                            class="form-control @error('fechaini') is-invalid @enderror" name="fechaini"
                                            value="{{ old('fechaini') }}" required autofocus>
                                        @error('fechaini')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="fechafin" class="form-label">Hasta</label>
                                        <input id="fechafin" type="date"
                                            class="form-control @error('fechafin') is-invalid @enderror" name="fechafin"
                                            value="{{ old('fechafin') }}" required autofocus>
                                        @error('fechafin')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>                                
                                <button type="submit" class="btn btn-primary">Generar PDF</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');

            const formattedToday = `${yyyy}-${mm}-${dd}`;

            const fechainiInput = document.getElementById('fechaini');
            const fechafinInput = document.getElementById('fechafin');
            if (!fechainiInput.value) {
                fechainiInput.value = formattedToday;
                fechafinInput.value = formattedToday;
            }
        });
    </script>    
@endsection
