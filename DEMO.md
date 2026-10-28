# Demostración de TF Clothing

## Preparación

Iniciar Apache y MySQL en XAMPP. Abrir la tienda desde localhost. La configuración y la base ya deben estar cargadas.

## Recorrido para mostrar

1. Mostrar el inicio, el logo TF y las categorías.
2. Iniciar sesión con la cuenta administradora y entrar a Gestión de productos desde el pie. Crear un producto de práctica con categoría, precio, talles y una foto de la lista.
3. Ir al catálogo y buscar el nombre del producto. Mostrar que aparece con los datos recién guardados.
4. Elegir el talle y agregarlo al carrito. Cambiar la cantidad para mostrar cómo cambia el total.
5. Continuar la compra. Completar los datos de contacto, revisar el resumen y confirmar el pedido.
6. Mostrar el código del pedido y su detalle. Recargar: debe seguir siendo el mismo pedido.
7. Volver a Gestión de productos y entrar a Ver pedidos recibidos. Mostrar el pedido y el contacto del cliente.
8. En phpMyAdmin se pueden revisar pedidos y detalle_pedidos para comprobar que los datos quedaron guardados.

Para mostrar el trabajo de formularios también se puede abrir Contacto, enviar datos incompletos y luego un mensaje válido. Los textos con etiquetas se muestran escapados.

## Explicación sencilla

Gestión de productos y el catálogo usan la misma tabla productos de MySQL. PDO permite que PHP lea y escriba esos datos. Los talles se guardan en producto_talles, una fila por cada opción.

El carrito usa la sesión para recordar los ids de los productos, los talles y las cantidades. Los nombres y precios se consultan en el servidor. Al confirmar, se guarda una fila en pedidos y una por producto y talle en detalle_pedidos. El precio de la compra se conserva en el detalle aunque luego cambie el precio del catálogo.

## Archivos que intervienen

- config/conexion.php: conexión PDO y control del error de conexión.
- admin/crear.php y admin/editar.php: validación y escritura de productos.
- clase5/catalogo.php: lectura de productos y talles.
- clase5/buscar.php: búsqueda por GET.
- clase5/tienda.php y carrito.php: sesión, cantidades y totales.
- finalizar-compra.php: validación del contacto y guardado del pedido.
- pedido.php: comprobante de la sesión del cliente.
- admin/pedidos.php: consulta de pedidos desde la gestión.

## Alcance

La compra se registra como un pedido pendiente, con retiro en tienda y pago al retirar. No hay cobro online, descuento de stock ni envío automático de correos. Los precios son orientativos y las fotos de prendas son ilustrativas. La gestión ya requiere una sesión de administrador. El despliegue público debe configurar HTTPS y la conexión de la base en ese servidor.

El orden de instalación y los cambios de la base se explican en tienda/README.md.


## Recorrido del TP8

Crear una cuenta cliente, ingresar y mostrar Mi cuenta. Entrar a admin/index.php con esa cuenta para comprobar el rechazo por rol. Hacer un pedido y mostrarlo en Mis pedidos. Cerrar sesión y volver a abrir una página privada para comprobar la redirección al login. Finalmente ingresar con la cuenta administradora y mostrar Gestión de productos y los pedidos de la tienda.

En el código, seguir registro.php → login.php → auth_guard.php → cuenta.php → logout.php. La configuración de la sesión está en config/sesion.php. El registro asigna cliente; el rol de administrador se prepara desde la terminal con la herramienta del TP8.
