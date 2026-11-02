CREATE DATABASE atelier CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE atelier;

SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE usuarios (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 rol ENUM('cliente','admin') NOT NULL DEFAULT 'cliente',
 creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE categorias (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(80) NOT NULL,
 slug VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE productos (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 categoria_id INT UNSIGNED NULL,
 nombre VARCHAR(180) NOT NULL,
 descripcion TEXT NOT NULL,
 precio DECIMAL(10,2) NOT NULL,
 img_url VARCHAR(1000) NOT NULL,
 tallas VARCHAR(100) NOT NULL DEFAULT 'XS,S,M,L,XL',
 stock INT UNSIGNED NOT NULL DEFAULT 0,
 nuevo TINYINT(1) NOT NULL DEFAULT 0,
 activo TINYINT(1) NOT NULL DEFAULT 1,
 creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_products_category FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
 INDEX idx_products_active (activo),
 INDEX idx_products_category (categoria_id)
) ENGINE=InnoDB;

CREATE TABLE ventas (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 usuario_id INT UNSIGNED NOT NULL,
 nombrecomp VARCHAR(160) NOT NULL,
 telefono VARCHAR(40) NOT NULL,
 direcion VARCHAR(255) NOT NULL,
 ciudad VARCHAR(120) NOT NULL,
 CP VARCHAR(12) NOT NULL,
 notas TEXT NULL,
 subtotal DECIMAL(10,2) NOT NULL,
 total DECIMAL(10,2) NOT NULL,
 estado ENUM('pendiente','confirmado','enviado','completado','cancelado') NOT NULL DEFAULT 'pendiente',
 creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_ventas_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE RESTRICT,
 INDEX idx_ventas_usuario (usuario_id),
 INDEX idx_ventas_status (estado)
) ENGINE=InnoDB;

CREATE TABLE detalles_ventas (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 venta_id INT UNSIGNED NOT NULL,
 producto_id INT UNSIGNED NULL,
 nom_producto VARCHAR(180) NOT NULL,
 talla VARCHAR(20) NOT NULL,
 cantidad INT UNSIGNED NOT NULL,
 precio_unidad DECIMAL(10,2) NOT NULL,
 line_total DECIMAL(10,2) NOT NULL,
 CONSTRAINT fk_items_ventas FOREIGN KEY (venta_id) REFERENCES ventas(id) ON DELETE CASCADE,
 CONSTRAINT fk_items_producto FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT INTO categorias (id,nombre,slug) VALUES
(1,'Mujer','mujer'),(2,'Hombre','hombre'),(3,'Accesorios','accesorios');

INSERT INTO productos (categoria_id,nombre,descripcion,precio,img_url,tallas,stock,nuevo,activo) VALUES
(1,'Blazer estructurado','Blazer de corte recto con solapa clásica. Una pieza versátil para elevar cualquier conjunto.',1899.00,'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=900&q=85','XS,S,M,L,XL',18,1,1),
(1,'Camisa de lino','Camisa ligera de textura natural y silueta relajada. Ideal para combinar durante todo el año.',899.00,'https://images.unsplash.com/photo-1598554747436-c9293d6a588f?auto=format&fit=crop&w=900&q=85','XS,S,M,L,XL',24,1,1),
(1,'Pantalón sastre','Pantalón de tiro alto y pierna recta con caída fluida y acabado limpio.',1299.00,'https://images.unsplash.com/photo-1506629905607-d9d7a3a8c9e3?auto=format&fit=crop&w=900&q=85','XS,S,M,L,XL',16,0,1),
(1,'Vestido minimal','Vestido de líneas simples y silueta contemporánea para ocasiones especiales o el día a día.',1599.00,'https://images.unsplash.com/photo-1595777457583-95e059d581b8?auto=format&fit=crop&w=900&q=85','XS,S,M,L',10,1,1),
(2,'Camisa Oxford','Camisa de algodón con cuello clásico y una estructura cómoda, pensada para combinar sin esfuerzo.',999.00,'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?auto=format&fit=crop&w=900&q=85','S,M,L,XL,XXL',22,0,1),
(2,'Sobrecamisa texturizada','Capa ligera de tacto suave, con bolsillos funcionales y un corte relajado.',1499.00,'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=900&q=85','S,M,L,XL,XXL',13,1,1),
(2,'Pantalón recto','Pantalón de uso diario con líneas limpias y ajuste cómodo.',1199.00,'https://images.unsplash.com/photo-1517438476312-10d79c077509?auto=format&fit=crop&w=900&q=85','S,M,L,XL,XXL',17,0,1),
(3,'Bolsa de hombro','Bolsa de silueta esencial, acabado sobrio y espacio para tus objetos cotidianos.',1399.00,'https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=900&q=85','UNITALLA',9,1,1);