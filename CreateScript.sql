alter table users 
add column estado boolean default true;

CREATE TABLE IF NOT EXISTS proveedores(
	id SERIAL PRIMARY KEY,
	nombre VARCHAR(100),
	estado boolean default true
);

CREATE TABLE IF NOT EXISTS sucursales(
	id SERIAL PRIMARY KEY,
	direccion VARCHAR(100),
	zona VARCHAR(100),
	celular VARCHAR(8),
	estado boolean default true
);

CREATE TABLE IF NOT EXISTS productos (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100),	    
    precio_compra DOUBLE PRECISION default 0,
    precio_venta DOUBLE PRECISION default 0,
    rop DOUBLE PRECISION default 0,
   	seguridad DOUBLE PRECISION default 0,
	idproveedor int,
	estado boolean default true,
	CONSTRAINT fk_proveedor FOREIGN KEY (idproveedor) REFERENCES proveedores(id) ON DELETE CASCADE ON UPDATE RESTRICT
);

CREATE TABLE IF NOT EXISTS sucursalesproductos (
    idsucursal INT,
    idproducto INT,
    stock DOUBLE PRECISION DEFAULT 0,    
    CONSTRAINT pk_sucursal_producto PRIMARY KEY (idsucursal, idproducto),
    CONSTRAINT fk_sucursal FOREIGN KEY (idsucursal) REFERENCES sucursales(id) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT fk_producto FOREIGN KEY (idproducto) REFERENCES productos(id) ON DELETE CASCADE ON UPDATE RESTRICT
);

CREATE TABLE IF NOT EXISTS ventas (
    id SERIAL PRIMARY KEY,
    fecha DATE default NOW(),    
    total DOUBLE PRECISION default 0,
    idsucursal INT,
    idusuario INT,
	estado boolean default true,
    CONSTRAINT fk_sucursal FOREIGN KEY (idsucursal) REFERENCES sucursales(id) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT fk_usuario FOREIGN KEY (idusuario) REFERENCES users(id) ON DELETE CASCADE ON UPDATE RESTRICT
);

CREATE TABLE IF NOT EXISTS det_ventas (
    idventa INT,
    idproducto INT,    
    cantidad DOUBLE PRECISION,
    total DOUBLE PRECISION,	
	estado boolean default true,
	CONSTRAINT pk_venta_producto PRIMARY KEY (idventa, idproducto),
    CONSTRAINT pk_venta FOREIGN KEY (idventa) REFERENCES ventas(id) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT pk_producto FOREIGN KEY (idproducto) REFERENCES productos(id) ON DELETE CASCADE ON UPDATE RESTRICT
);

CREATE TABLE IF NOT EXISTS compras(
    id SERIAL PRIMARY KEY,
    fecha DATE DEFAULT NOW (),
    total DOUBLE PRECISION,
    idsucursal int,
    estado boolean default true,
    idproveedor int,
    CONSTRAINT fk_proveedor FOREIGN KEY (idproveedor) REFERENCES proveedores(id) ON DELETE CASCADE ON UPDATE restrict,
    CONSTRAINT fk_sucursal FOREIGN KEY (idsucursal) REFERENCES sucursales(id) ON DELETE CASCADE ON UPDATE RESTRICT
);

CREATE TABLE IF NOT EXISTS det_compras(
	idcompra INT,
	idproducto INT,
	cantidad DOUBLE PRECISION,		
	total DOUBLE precision,
	estado boolean default true,
	CONSTRAINT pk_compra_producto PRIMARY KEY (idcompra, idproducto),
	CONSTRAINT fk_compra FOREIGN KEY (idcompra) REFERENCES compras(id) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT fk_producto FOREIGN KEY (idproducto) REFERENCES productos(id) ON DELETE CASCADE ON UPDATE RESTRICT
);

create table if not exists recepciones(
	idcompra int,	
	idusuario int,
	fecha date DEFAULT NOW (),
	tiempo int,
	CONSTRAINT fk_compra FOREIGN KEY (idcompra) REFERENCES compras(id) ON DELETE CASCADE ON UPDATE RESTRICT,
	CONSTRAINT fk_usuario FOREIGN KEY (idusuario) REFERENCES users(id) ON DELETE CASCADE ON UPDATE RESTRICT	
);