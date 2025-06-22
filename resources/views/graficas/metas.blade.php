@extends('tablar::page')

@section('content')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <div id="chart_div" style="height: 500px"></div>
    <script>
        google.charts.load('current', {
            packages: ['corechart', 'bar']
        });
        google.charts.setOnLoadCallback(drawStacked);

        function drawStacked() {
            var data = google.visualization.arrayToDataTable([
            ['Vendedor', '1ra Semana', '2da Semana', '3ra Semana', '4ta Semana', 'Meta Restante'],
                @foreach($usuarios as $usuario)
                ['{{ $usuario->name }}',
                {{ $usuario->sem1 }},
                {{ $usuario->sem2 }},
                {{ $usuario->sem3 }},
                {{ $usuario->sem4 }},
                {{ $usuario->metas }}
                ],
                @endforeach
            ]);

            var options = {
                title: 'Ventas Del Mes',
                chartArea: {
                    width: '50%'
                },
                isStacked: true,
                hAxis: {
                    title: 'Total Ventas (Bs)',
                    minValue: 0,
                },
                vAxis: {
                    title: 'Vendedores'
                }
            };
            var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
            chart.draw(data, options);
        }
        
    </script>
@endsection
