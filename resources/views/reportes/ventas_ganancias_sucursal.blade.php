@extends('tablar::page')
@section('content')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<div class="container mt-5">
    <div class="row">
        <div class="col">
            <div id="container"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Total y Ganancias de Ventas por Sucursal',
                align: 'left'
            },
            xAxis: {
                categories: {!! json_encode($sucursales) !!},
                crosshair: true,
                accessibility: {
                    description: 'Sucursales'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Cantidad y Ganancias'
                }
            },
            tooltip: {
                shared: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Total Ventas',
                data: {!! json_encode($importes) !!}
            }]
        });
    });
</script>
@endsection
