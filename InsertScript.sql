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
('Starbucks'),
('Cafe Capital'),
('Cofi');

INSERT INTO sucursales(direccion,zona,celular) VALUES 
('Local F3-F4 Acronal','Feria Barrio Lindo','73143557'),
('C/Parabano #315','Comercial Ramada','72170941'),
 ('C/Tucabaca #2135','San Martin','76651553');

 
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
('Capucchino', 35.00, 40.00, 5),
('Frapuchino', 35.00, 40.00, 5);

UPDATE proveedores SET estado = true WHERE id = 5;
UPDATE productos SET estado = true WHERE id BETWEEN 1 AND 11;



--SELECT * FROM sucursalesproductos ORDER BY idsucursal, idproducto;

--delete from compras where (fecha,total,idproveedor,idsucursal) in(
--('01-04-2024',5400,5,3),
--('01-05-2024',5400,5,3),
--('01-06-2024',5800,5,3)
--);
INSERT INTO compras(fecha,total,idproveedor,idsucursal) VALUES
('2024-04-01',5400,4,2),
('2024-05-01',5800,5,3),
('2024-06-01',6000,4,3),
('2025-04-01',5500,5,2),
('2025-05-01',5700,4,3),
('2025-06-01',5900,5,2);

--delete from det_compras where (idproducto,idcompra,cantidad,total) in (
--(21,1,300,2400),
--(21,2,200,1800),
--(22,2,250,1200) );

INSERT INTO det_compras(idproducto,idcompra,cantidad,total) VALUES
(1,1,300,2400),
(2,2,200,1800),
(3,3,250,1200),
(4,4,280,2200),
(5,5,260,2100),
(6,6,240,2000);

INSERT INTO recepciones(idcompra,idusuario,fecha,tiempo) VALUES
(1,1,'2024-04-05',8),
(4,1,'2025-04-05',9);

INSERT INTO ventas(fecha,total,idsucursal,idusuario) VALUES
-- AÑO 2024 (sucursal 3)
('2024-04-01',135,3,1),
('2024-04-02',135,3,1),
('2024-04-03',135,3,1),
('2024-04-04',135,3,1),
('2024-04-05',135,3,1),
('2024-04-06',135,3,1),
('2024-04-07',135,3,1),
('2024-04-08',135,3,1),
('2024-04-09',135,3,1),
('2024-04-10',135,3,1),
('2024-04-11',600,3,2),
('2024-04-12',155,3,1),
('2024-04-13',155,3,1),

-- AÑO 2025 (sucursal 2)
('2025-04-01',155,2,1),
('2025-04-02',155,2,1),
('2025-04-03',155,2,1),
('2025-04-04',155,2,1),
('2025-04-05',155,2,1),
('2025-04-06',155,2,1),
('2025-04-07',155,2,1),
('2025-04-08',155,2,1),
('2025-04-09',250,2,1),
('2025-04-10',150,2,1),
('2025-04-11',150,2,2),
('2025-04-12',120,2,2),
('2025-04-13',60,2,2);

-- Insertar registros sin conflictos de clave
INSERT INTO det_ventas(idventa,idproducto,cantidad,total) VALUES
(1,10,15,180),
(1,11,15,225),
(2,10,15,180),
(2,11,15,225),
(3,9,15,180),
(4,9,15,180),
(4,8,15,225),
(5,8,15,180),
(5,7,15,225),
(6,7,15,180),
(6,6,15,225),
(7,6,15,180),
(7,11,15,225),
(8,2,15,180),
(8,1,15,225),
(9,2,15,180),
(9,1,15,225),
(10,4,15,180),
(10,5,15,225),
(11,6,150,1500),
(11,7,150,1800),
(12,8,20,240),
(12,9,15,225),
(13,10,20,240),
(13,8,15,225),
(14,9,20,240),
(14,7,15,225),
(15,6,20,240),
(15,3,15,225),
(16,2,20,240),
(16,1,15,225),
(17,11,20,240),
(17,2,15,225),
(18,2,20,240),
(18,1,15,225),
(19,10,20,240),
(19,11,15,225),
(20,10,20,240),
(21,1,20,240),
(21,2,15,225),
(22,8,150,1500),
(22,9,150,1800),
(23,6,50,1250),
(24,7,30,600),
(25,10,5,320),
(26,11,4,400);
-- ==========================================
-- ACTUALIZAR STOCK INICIAL (EVITA STOCK = 0)
-- ==========================================
UPDATE sucursalesproductos
SET stock = 200;
