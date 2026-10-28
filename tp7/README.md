# TP7 - Gestión de productos de TF Clothing

En este trabajo conecté PHP con la base tf_tienda_tp6 usando PDO. El módulo permite consultar, agregar, editar y eliminar productos de la tienda.

## Cómo abrirlo

1. Iniciar Apache y MySQL en XAMPP.
2. Abrir http://localhost/TiendaTF-main/admin/index.php.
3. También se puede entrar desde Gestión de productos, en el pie de la tienda.

La base es la creada en el TP6. Si ya está cargada, no hay que volver a importar sus scripts. En una instalación nueva, importar tp6/schema.sql y después tp6/consultas.sql, una vez y en ese orden.

## Archivos

| Archivo | Función |
| --- | --- |
| config/conexion.php | Crea la conexión PDO y controla los errores de conexión. |
| admin/index.php | Lista los productos y permite buscar por nombre. |
| admin/crear.php | Valida el formulario y guarda un producto nuevo. |
| admin/editar.php | Recupera un producto por su id y actualiza sus datos. |
| admin/eliminar.php | Muestra la confirmación y elimina mediante POST. |
| tp7/prueba_conexion.php | Permite comprobar la conexión desde el navegador. |

Las páginas usan el encabezado, la navegación, el pie y los estilos de TF. Se administra la tabla productos del TP6 y se consultan sus categorías existentes.

## Configuración

Copiar .env.example como .env y completar DB_HOST, DB_PORT, DB_NAME, DB_USER y DB_PASS para el equipo donde se ejecuta. La conexión usa esos valores. El .env local no se sube al repositorio.

## Recorrido para probar el CRUD

1. Abrir el listado y buscar remera.
2. Agregar un producto de práctica con un código nuevo, nombre, categoría, precio, talles y estado.
3. Comprobar que aparezca en el listado y en productos desde phpMyAdmin.
4. Abrir Editar, cambiar su precio y guardar. Recargar para comprobar que el cambio se conservó.
5. Abrir Eliminar y usar Cancelar: el producto debe seguir existiendo.
6. Abrir Eliminar otra vez y confirmar. El producto de práctica debe desaparecer del listado y de la base.

El código de producto no puede repetirse. El precio acepta coma o punto decimal, sin separadores de miles. El id y las fechas los administra MySQL. La foto es opcional y se puede elegir entre las imágenes de la tienda. También se seleccionan los talles ofrecidos.

## Seguridad y validación

Todas las consultas se ejecutan con prepare() y execute(). Los valores del formulario se envían como parámetros; no se concatenan dentro del SQL.

Los datos mostrados en HTML pasan por escapar(), que usa htmlspecialchars(). Se validan los campos en PHP y se conservan los valores si hay errores.

Los formularios que modifican datos usan POST y un token asociado a la sesión. Después de guardar o eliminar se redirige al listado para evitar repetir el envío al recargar.

Los errores internos se registran con error_log(). La pantalla muestra un mensaje comprensible, sin exponer el detalle de la consulta ni las credenciales.

## Productos incluidos en pedidos

La clave foránea del TP6 impide eliminar productos con pedidos asociados. En ese caso se muestra un aviso y un enlace a Editar para marcar el producto como inactivo, sin borrar sus ventas.

## Pruebas locales

Se comprobaron el listado y la búsqueda, el alta persistente, la edición y la eliminación por POST. También se probaron códigos repetidos, precios y categorías inválidas, ids inexistentes, texto con etiquetas HTML y formularios con token incorrecto.

El borrado se comprobó con registros temporales: abrir la confirmación y cancelar no eliminó datos; confirmar eliminó el producto libre y la base rechazó borrar el producto relacionado con un pedido. Los registros de prueba se retiraron al terminar.

## Qué muestra cada parte

La gestión de productos y el catálogo público consultan la misma base de MySQL. Como ampliación, el carrito permite confirmar pedidos con retiro en tienda y pago al retirar. El recorrido y la actualización de la base se explican en [Catálogo y pedidos](../tienda/README.md). El contenido del TP7 sigue centrado en la conexión PDO y el CRUD.

En la demostración, primero se muestra el listado, después se completa el alta, se modifica el producto creado y finalmente se confirma su eliminación. Al recargar el listado y consultar productos en phpMyAdmin, se comprueba la persistencia.
