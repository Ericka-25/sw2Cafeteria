@extends('tablar::page')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Crear Venta
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
                            <form method="POST" action="{{ route('ventas.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fecha" class="form-label">fecha</label>
                                        <input id="fecha" type="date"
                                            class="form-control @error('fecha') is-invalid @enderror" name="fecha"
                                            value="{{ old('fecha') }}" required autofocus>
                                        @error('fecha')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="total" class="form-label">total</label>
                                        <div class="input-group">
                                            <input id="total" type="text"
                                                class="form-control @error('total') is-invalid @enderror" name="total"
                                                value="0" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">Bs</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="sucursal" class="form-label">sucursales</label>
                                        <select id="sucursal" class="form-control @error('sucursal') is-invalid @enderror"
                                            name="idsucursal" required>
                                            <option value="0">Seleccione una sucursal</option>
                                            @foreach ($sucursales as $sucursal)
                                                <option value="{{ $sucursal->id }}"
                                                    {{ old('sucursal') == $sucursal->id ? 'selected' : '' }}>
                                                    {{ $sucursal->direccion }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <h2 style="text-align: center; padding-top: 20px">Detalles de la venta</h2>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="producto" class="form-label">Producto</label>
                                        <select id="producto" class="form-control" name="idproducto">
                                            <option value="0">Seleccione un producto</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="stock" class="form-label">stock</label>
                                        <div class="input-group">
                                            <input id="stock" type="text" class="form-control" name="stock"
                                                value="0" readonly autofocus>
                                            <div class="input-group-append">
                                                <span class="input-group-text">mts</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="precio" class="form-label">Precio Venta</label>
                                        <input id="precio" type="text"
                                            class="form-control @error('precio') is-invalid @enderror" name="precio"
                                            value="{{ old('precio') }}" autofocus>
                                        @error('precio')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cantidad" class="form-label">Cantidad</label>
                                        <input id="cantidad" type="text"
                                            class="form-control @error('cantidad') is-invalid @enderror" name="cantidad"
                                            value="{{ old('cantidad') }}" autofocus>
                                        @error('cantidad')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" id="agregar-producto">Agregar</button>
                                <br>

                                <input type="hidden" id="productos" name="productos">
                                <input type="hidden" id="ganancias" name="ganancias" value="0">
                                <table class="table mt-3">
                                    <thead>
                                        <tr>
                                            <th>Nombre Producto</th>
                                            <th>Precio Venta</th>
                                            <th>Cantidad</th>
                                            <th>Importe</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabla-productos">
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-primary">Registrar Venta</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productoSelect = document.getElementById('producto');            
            const precioInput = document.getElementById('precio');
            const cantidadInput = document.getElementById('cantidad');
            const agregarButton = document.getElementById('agregar-producto');
            const tablaProductos = document.getElementById('tabla-productos');
            const productosInput = document.getElementById('productos');            
            const totalInput = document.getElementById('total');            
            const sucursalSelect = document.getElementById('sucursal');
            const stockInput = document.getElementById('stock');

            function eventProductoSelectChange() {
                const selectedOption = productoSelect.options[productoSelect.selectedIndex];
                precioInput.value = selectedOption.getAttribute('data-precio');
                const stock = selectedOption.getAttribute('data-stock');
                stockInput.value = stock ? stock : '0';
            }

            productoSelect.addEventListener('change', eventProductoSelectChange);

            function validarDatos(idProducto, precioVenta, cantidad) {


                if (productoSelect.value == '0' || cantidadInput.value == '') {
                    alert('Por favor complete todos los campos.');
                    return false;
                }

                if (isNaN(parseFloat(precioVenta)) || isNaN(parseFloat(cantidad))) {
                    alert('Por favor ingrese un número válido.');
                    return false;
                }

                if (parseFloat(cantidad) > parseFloat(stockInput.value)) {
                    alert('La cantidad supera el stock disponible.');
                    return false;
                }

                if (parseFloat(precioVenta) < precioInput.value) {
                    alert('El precio de venta no puede ser menor al precio de compra.');
                    return false;
                }

                if (existeProducto(idProducto)) {
                    alert('La producto ya ha sido agregada.');
                    return false;
                }

                return true;

            }

            agregarButton.addEventListener('click', function() {
                const selectedOption = productoSelect.options[productoSelect.selectedIndex];
                const idProducto = selectedOption.getAttribute('data-id');
                const nombreProducto = selectedOption.getAttribute('data-nombre');
                const precioVenta = precioInput.value;
                const cantidad = cantidadInput.value;

                if (!validarDatos(idProducto, precioVenta, cantidad)) {
                    return;
                }

                const nuevaFila = document.createElement('tr');

                nuevaFila.innerHTML = `                    
                    <td hidden>${idProducto}</td>
                    <td>${nombreProducto}</td>
                    <td>${precioVenta}</td>
                    <td>${cantidad}</td>                    
                    <td>${parseFloat(precioVenta) * parseFloat(cantidad)}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm borrar-fila">Eliminar</button>
                    </td>
                    `;

                tablaProductos.appendChild(nuevaFila);

                nuevaFila.querySelector('.borrar-fila').addEventListener('click', function() {
                    nuevaFila.remove();
                    actualizarCampoProductos();
                });

                productoSelect.value = '0';
                precioInput.value = '0';                
                cantidadInput.value = '';
                stockInput.value = '0';
                actualizarCampoProductos();
            });

            function existeProducto(idProducto) {
                const filas = tablaProductos.querySelectorAll('tr');
                for (const fila of filas) {
                    const celdas = fila.querySelectorAll('td');
                    const idProductoExistente = celdas[0].textContent;
                    if (idProductoExistente == idProducto) {
                        return true;
                    }
                }
                return false;
            }

            function actualizarCampoProductos() {
                const filas = tablaProductos.querySelectorAll('tr');
                const productos = [];
                totalInput.value = 0;                
                filas.forEach((fila) => {
                    const celdas = fila.querySelectorAll('td');
                    const idProducto = celdas[0].textContent;
                    const nombreProducto = celdas[1].textContent;
                    const precioVenta = celdas[2].textContent;
                    const cantidad = celdas[3].textContent;                    
                    const importe = celdas[4].textContent;                    
                    totalInput.value = parseFloat(totalInput.value) + parseFloat(precioVenta) * parseFloat(
                        cantidad);                    

                    productos.push({
                        idProducto,
                        nombreProducto,
                        precioVenta,
                        cantidad,
                        importe
                    });
                });

                productosInput.value = JSON.stringify(productos);
            }

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

            //Cambio En La Selección De Sucursal
            function onchangeSucursal() {
                const selectedSucursalId = sucursalSelect.value;
                const productosPorSucursal = {!! $productosPorSucursalJson !!};
                console.log('Productos por sucursal:', selectedSucursalId);
                if (selectedSucursalId == '0') {
                    productoSelect.disabled = true;
                    console.log('No hay sucursal seleccionada');
                } else {
                    console.log('Sucursal seleccionada:', selectedSucursalId);
                    productoSelect.disabled = false;
                    // Limpiar las opciones actuales del selector de productos
                    productoSelect.innerHTML = '<option value="0">Seleccione una producto</option>';

                    // Añadir las nuevas opciones basadas en la sucursal seleccionada
                    if (selectedSucursalId && productosPorSucursal[selectedSucursalId]) {
                        productosPorSucursal[selectedSucursalId].forEach(producto => {                                                   
                            const option = document.createElement('option');
                            option.value = producto.id;
                            option.textContent = producto.nombre;
                            option.setAttribute('data-stock', producto.stock);
                            option.setAttribute('data-precio', parseFloat(producto.precio_venta).toFixed(2));
                            option.setAttribute('data-nombre', producto.nombre);
                            option.setAttribute('data-id', producto.id);
                            productoSelect.appendChild(option);
                        });
                    }
                    eventProductoSelectChange();
                }
                totalInput.value = 0;                
                productoSelect.value = '0';
                borrarTodasLasFilas();
            }

            // Función para borrar todas las filas de la tabla
            function borrarTodasLasFilas() {
                // Selecciona todas las filas de la tabla
                const filas = tablaProductos.querySelectorAll('tr');

                // Recorre todas las filas y las elimina
                filas.forEach(fila => fila.remove());
            }

            // Initial check when the page loads
            onchangeSucursal();

            // Add event listener to the sucursal select
            sucursalSelect.addEventListener('change', onchangeSucursal);
        });
    </script>
@endsection
