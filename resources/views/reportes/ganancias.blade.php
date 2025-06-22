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
                type: 'pie'
            },
            title: {
                text: 'Ganancia Total en Ventas por Sucursal'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y}</b>',
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                        }
                    }
                }
            },
            series: [{
                name: 'Ganancias',
                colorByPoint: true,
                data: {!! $jsonData !!}
            }]
        });
    });
</script>
@endsection
