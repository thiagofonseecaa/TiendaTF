-- Ejecutar una sola vez, después de los scripts del TP6.
USE tf_tienda_tp6;

CREATE TABLE producto_talles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    talle VARCHAR(10) NOT NULL,
    UNIQUE (producto_id, talle),
    CONSTRAINT fk_talles_producto FOREIGN KEY (producto_id) REFERENCES productos(id)
        ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB;

-- La compra como invitado no crea una cuenta ni modifica usuarios existentes.
-- El contacto se conserva con el pedido que realizó esa persona.
ALTER TABLE pedidos
    MODIFY usuario_id INT NULL,
    ADD nombre_cliente VARCHAR(200) NULL,
    ADD email_cliente VARCHAR(254) NULL,
    ADD telefono_cliente VARCHAR(30) NULL;
