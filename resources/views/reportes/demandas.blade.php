@extends('tablar::page')
@section('content')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<div class="container-xl">
    <div class="row row-deck row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Demanda De Telas Del {{ date('d/m/Y', strtotime($request->fechaini)) }} Al {{ date('d/m/Y', strtotime($request->fechafin)) }}</h3>
                    <button id="download-pdf" class="btn btn-primary float-right">Descargar Tabla en PDF</button>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap datatable" id="datatable">
                        <thead>
                            <tr>
                                <th style="width: 150px;">Nombre De Telas</th>
                                @foreach ($sucursales as $sucursal)
                                    <th style="width: 100px;">{{ $sucursal->direccion }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($telas as $tela)
                                <tr>
                                    <td>{{ $tela->tela }}</td>
                                    @foreach ($sucursales as $sucursal)
                                        @php
                                            $demanda = 0;
                                            foreach ($telas as $item) {
                                                if ($item->tela === $tela->tela && $item->sucursal === $sucursal->direccion) {
                                                    $demanda = $item->demanda;
                                                    break;
                                                }
                                            }
                                        @endphp
                                        <td>{{ $demanda }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row row-deck row-cards mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id="container"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        Highcharts.chart('container', {
            data: {
                table: 'datatable'
            },
            chart: {
                type: 'column'
            },
            title: {
                text: 'Demanda De Productos Por Sucursal'
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                allowDecimals: false,
                title: {
                    text: 'Cantidad'
                }
            },
            plotOptions: {
                series: {
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.0f}'
                    }
                }
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.y:.0f}</b>'
            },
            series: [{
                name: 'Demanda'
            }],
            exporting: {
                enabled: true,
                buttons: {
                    contextButton: {
                        menuItems: [
                            'printChart',
                            'separator',
                            'downloadPNG',
                            'downloadJPEG',
                            'downloadPDF',
                            'downloadSVG'
                        ]
                    }
                }
            }
        });

        document.getElementById('download-pdf').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF('p', 'pt', 'letter'); // Use 'letter' for a letter size page
            const elementHTML = document.querySelector('#datatable');
            const rows = Array.from(elementHTML.querySelectorAll('tr'));
            const header = rows.shift(); // Remove header from rows and save separately
            const pageHeight = doc.internal.pageSize.getHeight();
            const rowHeight = 30; // Approximate height of each row in points
            const margin = 40; // Margin in points

            function addTableToPDF(rowsToAdd, page) {
                return new Promise((resolve) => {
                    const table = document.createElement('table');
                    table.style.width = '100%';
                    table.border = '1';
                    table.style.borderCollapse = 'collapse';
                    table.style.tableLayout = 'fixed';

                    const thead = document.createElement('thead');
                    thead.appendChild(header.cloneNode(true));
                    table.appendChild(thead);

                    const tbody = document.createElement('tbody');
                    rowsToAdd.forEach(row => {
                        const clonedRow = row.cloneNode(true);
                        clonedRow.querySelectorAll('td').forEach(td => {
                            td.style.padding = '8px';  // Add padding
                        });
                        tbody.appendChild(clonedRow);
                    });
                    table.appendChild(tbody);

                    const tempDiv = document.createElement('div');
                    tempDiv.style.position = 'absolute';
                    tempDiv.style.top = '-99999px';
                    tempDiv.appendChild(table);
                    document.body.appendChild(tempDiv);

                    html2canvas(table, { scale: 2 }).then(canvas => {
                        const imgData = canvas.toDataURL('image/png');
                        const imgWidth = 595.28;
                        const imgHeight = canvas.height * imgWidth / canvas.width;

                        if (page > 0) {
                            doc.addPage();
                        }
                        doc.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
                        document.body.removeChild(tempDiv);
                        resolve();
                    });
                });
            }

            const calculateRowsPerPage = (doc, rowHeight, pageHeight, margin) => {
                return Math.floor((pageHeight - margin * 2) / rowHeight);
            };

            const rowsPerPage = calculateRowsPerPage(doc, rowHeight, pageHeight, margin);
            const promises = [];
            for (let i = 0; i < rows.length; i += rowsPerPage) {
                const rowsToAdd = rows.slice(i, i + rowsPerPage);
                promises.push(addTableToPDF(rowsToAdd, Math.floor(i / rowsPerPage)));
            }

            Promise.all(promises).then(() => {
                doc.save('tabla-demandas.pdf');
            });
        });
    });
</script>
@endsection
