@auth
    <div class="nav-item dropdown d-none d-md-flex me-3">
        <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
            <!-- Download SVG icon from http://tabler-icons.io/i/bell -->
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
            </svg>
            {{-- <span class="badge bg-red"></span> --}}
            <span id="bellicon" class="badge bg-gray"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Notificaciones</h3>
                </div>
                <div class="list-group list-group-flush list-group-hoverable">
                    {{-- <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto"><span
                                    class="status-dot status-dot-animated bg-red d-block"></span></div>
                            <div class="col text-truncate">
                                <a href="#" class="text-body d-block">Example 1</a>
                                <div class="d-block text-muted text-truncate mt-n1">
                                    Change deprecated html tags to text decoration classes (#29604)
                                </div>
                            </div>
                            <div class="col-auto">
                                <a href="#" class="list-group-item-actions">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted"
                                         width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path
                                            d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto"><span class="status-dot d-block"></span></div>
                            <div class="col text-truncate">
                                <a href="#" class="text-body d-block">Example 2</a>
                                <div class="d-block text-muted text-truncate mt-n1">
                                    justify-content:between ⇒ justify-content:space-between (#29734)
                                </div>
                            </div>
                            <div class="col-auto">
                                <a href="#" class="list-group-item-actions show">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-yellow"
                                         width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path
                                            d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto"><span class="status-dot d-block"></span></div>
                            <div class="col text-truncate">
                                <a href="#" class="text-body d-block">Example 3</a>
                                <div class="d-block text-muted text-truncate mt-n1">
                                    Update change-version.js (#29736)
                                </div>
                            </div>
                            <div class="col-auto">
                                <a href="#" class="list-group-item-actions">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted"
                                         width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path
                                            d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto"><span
                                    class="status-dot status-dot-animated bg-green d-block"></span>
                            </div>
                            <div class="col text-truncate">
                                <a href="#" class="text-body d-block">Example 4</a>
                                <div class="d-block text-muted text-truncate mt-n1">
                                    Regenerate package-lock.json (#29730)
                                </div>
                            </div>
                            <div class="col-auto">
                                <a href="#" class="list-group-item-actions">
                                    <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted"
                                         width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                         stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path
                                            d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/3.0.1/socket.io.js"
        integrity="sha512-vGcPDqyonHb0c11UofnOKdSAt5zYRpKI4ow+v6hat4i96b7nHSn8PQyk0sT5L9RECyksp+SztCPP6bqeeGaRKg=="
        crossorigin="anonymous"></script>
    <script>
        const apiUrl = 'http://localhost:8080/api/notificaciones';
        //const apiUrl = 'https://notificaciones-serve.onrender.com/api/notificaciones';
        fetch(apiUrl)
            .then(response => {
                // Verificar si la solicitud fue exitosa (código de estado 200)
                if (!response.ok) {
                    throw new Error(`Error de red - Código ${response.status}`);
                }
                // Parsear la respuesta JSON
                return response.json();
            })
            .then(data => {
                // Hacer algo con los datos obtenidos
                console.log(data);
                showNotification(data);
            })
            .catch(error => {
                // Manejar errores de red u otros errores
                console.error('Hubo un problema con la solicitud:', error);
            });

        // Conexión con el socket server
        const socket = io('http://localhost:8080');
        //const socket = io('https://notificaciones-serve.onrender.com');
        var baseUrl = window.location.protocol + "//" + window.location.host;
        document.addEventListener('DOMContentLoaded', function() {
            socket.on('notification_processed_user', (data) => {
                showNotification(data);
            });
        });

        function showNotification(data) {
            const bellicon = document.getElementById('bellicon');            
            if (data.length > 0) {
                bellicon.classList.remove('bg-gray');
                bellicon.classList.add('bg-red');
                var notificaciones = document.querySelector('.list-group');
                notificaciones.innerHTML = '';

                data.forEach(item => {
                    var newListItem = document.createElement('div');
                    var stock = parseFloat(item.stock);
                    var rop = parseFloat(item.rop);
                    newListItem.className = 'list-group-item';
                    newListItem.innerHTML = `
                        <div class="row align-items-center">
                            <div class="col-auto"><span class="status-dot status-dot-animated bg-green d-block"></span></div>
                            <div class="col text-truncate">
                                <a href="${baseUrl}/productos/${item.idproducto}" class="text-body d-block"> Se requiere reposicionar el Producto ${item.nombre}</a>
                                <div class="d-block text-muted text-truncate mt-n1">
                                    su stock de ${stock.toFixed(2)} es menor al ROP ${rop.toFixed(2)} se requiere reposicionar el producto
                                </div>
                            </div>
                        </div>`;
                    notificaciones.appendChild(newListItem);
                });
            }
        }
    </script>
    @endif
