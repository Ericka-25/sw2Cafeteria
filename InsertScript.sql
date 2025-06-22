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
('Coca-Cola 500ml', 5.50, 10.00, 4),
('Coca-Cola 1L', 8.00, 15.00, 4),
('Sprite 500ml', 5.50, 10.00, 4),
('Fanta 500ml', 5.50, 10.00, 4),
('Agua Vital 600ml', 4.00, 8.00, 4),

-- Productos de Fridolin (masas)
('Croissant de mantequilla', 3.00, 7.00, 5),
('Empanada de queso', 4.00, 8.00, 5),
('Tarta de frutilla', 12.00, 25.00, 5),
('Pan de chocolate', 5.00, 10.00, 5),

-- Productos de Hipermaxi (café, azúcar, leche, etc.)
('Capucchino', 35.00, 40.00, 6),
('Frapuchino', 35.00, 40.00, 6);

insert into compras(fecha,total,idproveedor,idsucursal) values
('01-05-2024',5400,6,3),
('01-06-2024',5800,6,3);

insert into det_compras(idproducto,idcompra,cantidad,total) values
(32,3,300,2400),
(33,3,300,3000),
(32,4,350,2800),
(33,4,300,3000);

insert into recepciones(idcompra,idusuario,fecha,tiempo) values
(3,1,'10-05-2024',10),
(4,1,'12-06-2024',12);

insert into ventas(fecha,total,idsucursal,idusuario) values
('11-05-2024',135,3,1),
('12-05-2024',135,3,1),
('13-05-2024',135,3,1),
('14-05-2024',135,3,1),
('15-05-2024',135,3,1),
('16-05-2024',135,3,1),
('17-05-2024',135,3,1),
('18-05-2024',135,3,1),
('19-05-2024',135,3,1),
('20-05-2024',135,3,1),
('30-05-2024',600,3,2),
('13-06-2024',155,3,1),
('14-06-2024',155,3,1),
('15-06-2024',155,3,1),
('16-06-2024',155,3,1),
('17-06-2024',155,3,1),
('18-06-2024',155,3,1),
('19-06-2024',155,3,1),
('20-06-2024',155,3,1),
('21-06-2024',155,3,1),
('22-06-2024',155,3,1),
('27-06-2024',600,3,2),
('01-07-2024',250,3,1),
('08-07-2024',150,3,1),
('01-07-2024',150,3,2),
('08-07-2024',120,3,2),
('15-06-2024',60,3,2);

insert into det_ventas(idventa,idproducto,cantidad,total) values
(1,32,15,180),
(1,33,15,225),
(2,32,15,180),
(2,33,15,225),
(3,32,15,180),
(3,33,15,225),
(4,32,15,180),
(4,33,15,225),
(5,32,15,180),
(5,33,15,225),
(6,32,15,180),
(6,33,15,225),
(7,32,15,180),
(7,33,15,225),
(8,32,15,180),
(8,33,15,225),
(9,32,15,180),
(9,33,15,225),
(10,32,15,180),
(10,33,15,225),
(11,32,150,1500),
(11,33,150,1800),
(12,32,20,240),
(12,33,15,225),
(13,32,20,240),
(13,33,15,225),
(14,32,20,240),
(14,33,15,225),
(15,32,20,240),
(15,33,15,225),
(16,32,20,240),
(16,33,15,225),
(17,32,20,240),
(17,33,15,225),
(18,32,20,240),
(18,33,15,225),
(19,32,20,240),
(19,33,15,225),
(20,32,20,240),
(20,33,15,225),
(21,32,20,240),
(21,33,15,225),
(22,32,150,1500),
(22,33,150,1800),
(23,32,50,1250),
(24,33,30,600),
(25,32,5,320),
(26,33,4,400),
(27,32,15,225);


    
