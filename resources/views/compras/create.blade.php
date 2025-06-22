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
                        Registrar Compra
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <form method="POST" action="{{ route('compras.store') }}">
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
                                        <input id="total" type="text" class="form-control" name="total"
                                            value="0">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="proveedor" class="form-label">proveedor</label>
                                        <select id="proveedor" class="form-control @error('proveedor') is-invalid @enderror"
                                            name="idproveedor" required>
                                            <option value="0">Seleccione un proveedor</option>
                                            @foreach ($proveedores as $proveedor)
                                                <option value="{{ $proveedor->id }}"
                                                    {{ old('proveedor') == $proveedor->id ? 'selected' : '' }}>
                                                    {{ $proveedor->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="sucursal" class="form-label">sucursal</label>
                                        <select id="sucursal" class="form-control @error('sucursal') is-invalid @enderror"
                                            name="idsucursal" required>
                                            <option value="0">Seleccione un sucursal</option>
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
                                <h2 style="text-align: center; padding-top: 20px">Detalles de la compra</h2>
                                <div class="mb-3">
                                    <label for="producto" class="form-label">Producto</label>
                                    <select id="producto" class="form-control @error('producto') is-invalid @enderror"
                                        name="idproducto">
                                        <option value="0">Seleccione una producto</option>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="precio" class="form-label">precio Compra</label>
                                        <div class="input-group">
                                            <input id="precio" type="text"
                                                class="form-control @error('precio') is-invalid @enderror" name="precio"
                                                value="" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">Bs</span>
                                            </div>
                                        </div>
                                        @error('precio')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cantidad" class="form-label">cantidad</label>
                                        <div class="input-group">
                                            <input id="cantidad" type="text"
                                                class="form-control @error('cantidad') is-invalid @enderror" name="cantidad"
                                                value="" autofocus>
                                            <div class="input-group-append">
                                                <span class="input-group-text">mts</span>
                                            </div>
                                        </div>
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
                                <table class="table mt-3">
                                    <thead>
                                        <tr>
                                            <th>Nombre Producto</th>
                                            <th>Precio Compra</th>
                                            <th>Cantidad</th>
                                            <th>Importe</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tabla-productos">
                                    </tbody>
                                </table>
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

            // Atributos
            const productoSelect = document.getElementById('producto');
            const totalInput = document.getElementById('total');
            const tablaProductos = document.getElementById('tabla-productos');
            const proveedorSelect = document.getElementById('proveedor');
            const agregarButton = document.getElementById('agregar-producto');
            const productosInput = document.getElementById('productos');
            const precioInput = document.getElementById('precio');
            const cantidadInput = document.getElementById('cantidad');

            //metodos
            function agregarProducto() {
                const idproducto = productoSelect.value;
                const producto = productoSelect.options[productoSelect.selectedIndex].textContent;
                const precio = precioInput.value;
                const cantidad = cantidadInput.value;
                const importe = precio * cantidad;
                if (validarDatos(idproducto, precio, cantidad)) {
                    return;
                }

                const nuevaFila = document.createElement('tr');

                nuevaFila.innerHTML = `
                    <td hidden>${idproducto}</td>
                    <td>${producto}</td>
                    <td>${precio}</td>
                    <td>${cantidad}</td>
                    <td>${importe}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm borrar-fila">Eliminar</button>
                    </td>
                `;

                tablaProductos.appendChild(nuevaFila);

                let productosArray = JSON.parse(productosInput.value || '[]');
                productosArray.push({
                    idproducto: parseInt(idproducto),
                    precio: parseFloat(precio),
                    cantidad: parseFloat(cantidad),
                    total: parseFloat(importe)
                });
                productosInput.value = JSON.stringify(productosArray);
                nuevaFila.querySelector('.borrar-fila').addEventListener('click', function() {
                    totalInput.value = parseFloat(totalInput.value) - parseFloat(importe);
                    productosArray = productosArray.filter(producto => !(producto.idproducto ===
                        idproducto));
                    productosInput.value = JSON.stringify(productosArray);
                    nuevaFila.remove();
                    //tablaProductos.removeChild(nuevaFila);                    
                });

                totalInput.value = parseFloat(totalInput.value) + parseFloat(importe);
                productoSelect.value = '0';
                precioInput.value = '';
                cantidadInput.value = '';
            }

            function validarDatos(idproducto, precio, cantidad) {
                if (idproducto == 0) {
                    alert('Seleccione una producto');
                    return true;
                }
                if (precio == '') {
                    alert('Ingrese el precio de la producto');
                    return true;
                }
                if (cantidad == '') {
                    alert('Ingrese la cantidad de producto');
                    return true;
                }
                if (isNaN(parseFloat(precio)) || isNaN(parseFloat(cantidad))) {
                    alert('Por favor ingrese un número válido.');
                    return false;
                }
                if (parseFloat(cantidad) <= 0) {
                    alert('La cantidad de producto debe ser mayor a 0');
                    return true;
                }
                /* if (parseFloat(precio) <= 0) {
                    alert('El precio de la producto debe ser mayor a 0');
                    return true;
                } */
                if (existeProducto(idproducto)) {
                    alert('La producto ya fue agregada');
                    return true;
                }
                return false;
            }

            function existeProducto(idproducto) {
                const filas = tablaProductos.querySelectorAll('tr');
                for (const fila of filas) {
                    const celdas = fila.querySelectorAll('td');
                    const idProductoExistente = celdas[0].textContent;
                    if (idProductoExistente == idproducto) {
                        return true;
                    }
                }
                return false;
            }

            agregarButton.addEventListener('click', agregarProducto);

            function changeProveedor() {
                const idproveedor = proveedorSelect.value;
                const productosPorProveedor = {!! $productosPorProveedorJson !!};

                if (idproveedor == 0) {
                    productoSelect.disabled = true;
                } else {
                    productoSelect.disabled = false;
                    productoSelect.innerHTML = '<option value="0">Seleccione una producto</option>';

                    if (idproveedor && productosPorProveedor[idproveedor]) {
                        productosPorProveedor[idproveedor].forEach(producto => {
                            const option = document.createElement('option');
                            option.value = producto.id;
                            option.textContent = producto.nombre;
                            productoSelect.appendChild(option);
                        });
                    }
                }
                totalInput.value = '0';
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

            changeProveedor();

            function onchangeproducto() {
                const idproducto = productoSelect.value;
                const idproveedor = proveedorSelect.value;
                const productosPorProveedor = {!! $productosPorProveedorJson !!};
                productosPorProveedor[idproveedor].forEach(producto => {
                    if (producto.id == idproducto) {
                        console.log(producto);
                        precioInput.value = producto.precio_compra;
                    }
                });
            }

            productoSelect.addEventListener('change', onchangeproducto);
            proveedorSelect.addEventListener('change', changeProveedor);
        });
    </script>
@endsection
