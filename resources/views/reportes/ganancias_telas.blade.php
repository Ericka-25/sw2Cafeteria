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
        const data = {!! $jsonData !!};

        Highcharts.chart('container', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Total de Ventas y Ganancias por Tela',
                align: 'left'
            },
            xAxis: {
                categories: data.categories,
                title: {
                    text: null
                },
                gridLineWidth: 1,
                lineWidth: 0
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Monto Totales',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                },
                gridLineWidth: 0
            },
            tooltip: {
                shared: true
            },
            plotOptions: {
                bar: {
                    borderRadius: '50%',
                    dataLabels: {
                        enabled: true
                    },
                    groupPadding: 0.1
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -40,
                y: 80,
                floating: true,
                borderWidth: 1,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Total Ventas (Mts)',
                data: data.total_ventas
            }, {
                name: 'Total Ganancias (Bs)',
                data: data.total_ganancias
            }]
        });
    });
</script>
@endsection
