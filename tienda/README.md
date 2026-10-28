# Catálogo y pedidos de TF

El cliente puede elegir productos, armar su carrito y enviar un pedido con sus datos de contacto. La forma de entrega es retiro en tienda y el pago se hace al retirar. El pedido se guarda como pendiente para que la tienda pueda revisarlo y coordinar el retiro.

## Cómo probarlo

1. Iniciar Apache y MySQL en XAMPP y abrir la tienda.
2. Iniciar sesión como administrador y entrar a Gestión de productos desde el pie de la página. Agregar un producto con código, nombre, categoría, precio, talles y, si corresponde, una foto de la lista. Dejarlo activo.
3. Ir a Catálogo. El mismo producto debe aparecer con los datos que se guardaron. También se muestra en la categoría elegida.
4. Cerrar la sesión del administrador e ingresar como cliente o continuar como invitado. Elegir un talle y usar Agregar al carrito. Se puede modificar la cantidad o quitar el producto.
5. Usar Continuar compra, completar nombre, correo y teléfono, revisar el total y confirmar el pedido.
6. La pantalla muestra el código y el detalle del pedido. El carrito queda vacío.
7. Iniciar sesión como administrador y entrar a Ver pedidos recibidos. Ahí se puede consultar el pedido y los datos para contactar al cliente.

El recorrido registra un pedido en MySQL. No cobra tarjetas, no envía correos automáticos y no descuenta stock: esas funciones todavía no están implementadas. Los precios siguen siendo orientativos para la presentación. Desde el TP8, la gestión requiere una cuenta administradora y cada cliente puede consultar los pedidos realizados con su sesión iniciada. La preparación del acceso se explica en [TP8](../tp8/README.md).

## Qué se guarda

- productos: nombre, código, categoría, precio, imagen y estado. Es la misma tabla que usa Gestión de productos.
- producto_talles: una fila por producto y talle ofrecido. Así el dato se puede modificar desde la gestión sin guardar listas dentro de una columna.
- pedidos: código, estado y datos de contacto de la compra.
- detalle_pedidos: productos elegidos, talles, cantidades y precio unitario de ese momento.

La compra puede hacerse como invitado o con una cuenta cliente iniciada. La cuenta administradora se usa para gestionar productos y consultar pedidos recibidos; no tiene acceso al carrito ni a confirmar compras. El pedido usa el usuario_id de la sesión cuando corresponde; comprar no crea cuentas ni cambia los usuarios existentes. Por eso usuario_id permite NULL en los pedidos nuevos. El contacto que se ingresa queda asociado al pedido. Los pedidos anteriores del TP6 conservan su usuario.

## Archivos principales

| Archivo | Para qué sirve |
| --- | --- |
| clase5/catalogo.php | Lee los productos activos y sus talles con PDO. |
| clase5/tarjetas.php | Muestra la foto, el precio y el formulario para agregar al carrito. |
| clase5/tienda.php | Maneja la sesión, el carrito y sus totales. |
| carrito.php | Permite revisar, actualizar o quitar productos. |
| finalizar-compra.php | Valida el contacto y guarda el pedido completo. |
| pedido.php | Muestra el comprobante de la compra de esa sesión. |
| admin/pedidos.php | Permite consultar los pedidos desde la gestión. |

Los precios y los productos se comprueban otra vez en el servidor al confirmar. Si cambió el precio, se pide revisar el resumen. El pedido y sus detalles se guardan dentro de una transacción: se guardan juntos o se revierte la operación. Cada formulario de compra tiene una clave para evitar pedidos duplicados al repetir el envío. Los importes se suman en centavos.

Si un producto pasa a inactivo, deja de aparecer en el catálogo. Si estaba en el carrito, se informa que dejó de estar disponible. Los productos que tienen pedidos no se pueden borrar por la clave foránea que ya tenía la base.

## Instalación en otro equipo

En este equipo la actualización ya está aplicada. No hay que volver a importar el TP6.

En una instalación nueva:

1. Configurar el archivo .env con los datos de conexión.
2. Importar tp6/schema.sql y tp6/consultas.sql, en ese orden y una sola vez.
3. Importar tienda/actualizar.sql una sola vez. Agrega la tabla de talles y los campos de contacto del pedido.
4. Importar tienda/catalogo-inicial.sql. Incorpora los productos de la colección anterior que falten y sus talles iniciales. Conserva los precios de los productos que ya existen. Se usa al preparar la colección inicial, no después de editar los talles de la tienda.

Los archivos del TP6 se mantienen como entrega de ese trabajo. La ampliación para la compra se encuentra en esta carpeta.

## Prueba local realizada

Se comprobó un alta desde Gestión de productos y su aparición en el catálogo, categoría y búsqueda. Se probó la compra con cantidades, precio con centavos y cambio de precio antes de confirmar. El pedido y su detalle se guardaron; repetir el envío no creó otro pedido. También se comprobaron datos inválidos, token incorrecto, inactivación y acceso al comprobante desde otra sesión. Los registros temporales se retiraron al terminar y se conservaron los datos anteriores.
