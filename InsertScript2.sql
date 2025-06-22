CREATE OR REPLACE FUNCTION insertar_en_sucursales()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO sucursalesproductos (idsucursal, idproducto)
    SELECT id, NEW.id
    FROM sucursales;    
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_insertar_en_sucursales
AFTER INSERT ON productos
FOR EACH ROW
EXECUTE FUNCTION insertar_en_sucursales();


CREATE OR REPLACE FUNCTION insertar_en_productos()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO sucursalesproductos (idsucursal, idproducto)
    SELECT NEW.id, id
    FROM productos;
    RETURN NEW;   
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_insertar_en_productos
AFTER INSERT ON sucursales
FOR EACH ROW
EXECUTE FUNCTION insertar_en_productos();

INSERT INTO proveedores(nombre) values
('Embol'),
('Fridolin'),
('Starbucks');

INSERT INTO sucursales(direccion,zona,celular) VALUES 
('Local F3-F4 Acronal','Feria Barrio Lindo','73143557'),
('C/Parabano #315','Comercial Ramada','72170941');
 
INSERT INTO productos(nombre, precio_compra, precio_venta, idproveedor) VALUES
-- Productos de Embol (Coca-Cola y similares)
('Coca-Cola 500ml', 5.50, 10.00, 7),
('Coca-Cola 1L', 8.00, 15.00, 7),
('Sprite 500ml', 5.50, 10.00, 7),
('Fanta 500ml', 5.50, 10.00, 7),
('Agua Vital 600ml', 4.00, 8.00, 7),

-- Productos de Fridolin (masas)
('Croissant de mantequilla', 3.00, 7.00, 8),
('Empanada de queso', 4.00, 8.00, 8),
('Tarta de frutilla', 12.00, 25.00, 8),
('Pan de chocolate', 5.00, 10.00, 8),

-- Productos de Hipermaxi (café, azúcar, leche, etc.)
('Capucchino', 35.00, 40.00, 9),
('Frapuchino', 35.00, 40.00, 9);

insert into compras(fecha,total,idproveedor,idsucursal) values
('01-05-2024',5400,9,5),
('01-06-2024',5800,9,5);

insert into det_compras(idproducto,idcompra,cantidad,total) values
(65,9,300,2400),
(66,9,300,3000),
(65,10,350,2800),
(66,10,300,3000);

insert into recepciones(idcompra,idusuario,fecha,tiempo) values
(9,1,'10-05-2024',10),
(10,1,'12-06-2024',12);

insert into ventas(fecha,total,idsucursal,idusuario) values
('11-05-2024',135,5,1),
('12-05-2024',135,5,1),
('13-05-2024',135,5,1),
('14-05-2024',135,5,1),
('15-05-2024',135,5,1),
('16-05-2024',135,5,1),
('17-05-2024',135,5,1),
('18-05-2024',135,5,1),
('19-05-2024',135,5,1),
('20-05-2024',135,5,1),
('30-05-2024',600,5,2),
('13-06-2024',155,5,1),
('14-06-2024',155,5,1),
('15-06-2024',155,5,1),
('16-06-2024',155,5,1),
('17-06-2024',155,5,1),
('18-06-2024',155,5,1),
('19-06-2024',155,5,1),
('20-06-2024',155,5,1),
('21-06-2024',155,5,1),
('22-06-2024',155,5,1),
('27-06-2024',600,5,2),
('01-07-2024',250,5,1),
('08-07-2024',150,5,1),
('01-07-2024',150,5,2),
('08-07-2024',120,5,2),
('15-06-2024',60,5,2);

insert into det_ventas(idventa,idproducto,cantidad,total) values
(1,65,15,180),
(1,66,15,225),
(2,65,15,180),
(2,66,15,225),
(3,65,15,180),
(3,66,15,225),
(4,65,15,180),
(4,66,15,225),
(5,65,15,180),
(5,66,15,225),
(6,65,15,180),
(6,66,15,225),
(7,65,15,180),
(7,66,15,225),
(8,65,15,180),
(8,66,15,225),
(9,65,15,180),
(9,66,15,225),
(10,65,15,180),
(10,66,15,225),
(11,65,150,1500),
(11,66,150,1800),
(12,65,20,240),
(12,66,15,225),
(13,65,20,240),
(13,66,15,225),
(14,65,20,240),
(14,66,15,225),
(15,65,20,240),
(15,66,15,225),
(16,65,20,240),
(16,66,15,225),
(17,65,20,240),
(17,66,15,225),
(18,65,20,240),
(18,66,15,225),
(19,65,20,240),
(19,66,15,225),
(20,65,20,240),
(20,66,15,225),
(21,65,20,240),
(21,66,15,225),
(22,65,150,1500),
(22,66,150,1800),
(23,65,50,1250),
(24,66,30,600),
(25,65,5,650),
(26,66,4,400),
(27,65,15,225);


    
