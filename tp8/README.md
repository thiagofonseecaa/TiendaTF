# TP8 - Sesiones y autenticación de TF Clothing

La tienda permite crear una cuenta, iniciar sesión y cerrarla. El cliente consulta sus pedidos y el administrador entra a la gestión de productos y pedidos.

## Paso 1. Revisar la base de datos

Se usa la base tf_tienda_tp6, que ya tiene usuarios y roles. No hay que volver a importar el TP6 ni borrar tablas.

usuarios contiene id autoincremental, nombre, apellido, email único, password VARCHAR(255), rol_id y las fechas. rol_id es la clave foránea que relaciona cada usuario con roles.

La consigna muestra una estructura recomendada con rol dentro de usuarios. En TF se conserva el esquema normalizado del TP6: el rol se obtiene mediante INNER JOIN. La equivalencia es:

| roles.nombre en MySQL | $_SESSION['rol'] | Acceso |
| --- | --- | --- |
| cliente | usuario | Mi cuenta y sus propios pedidos |
| administrador | admin | Mi cuenta, productos y pedidos de la tienda |

tp8/verificar_base.sql permite revisar las columnas, el índice único del correo y los usuarios con su rol. Es un archivo de consulta; no cambia los datos.

## Paso 2. Iniciar la sesión

config/sesion.php configura e inicia la sesión antes de enviar HTML. El carrito y la cuenta usan la misma sesión. La cookie se llama TFSESSID y guarda un identificador; los datos de la cuenta quedan en el servidor.

Se activan HttpOnly, SameSite=Lax, el modo estricto y el uso de cookies para enviar el identificador. Secure se activa en HTTPS. En localhost por HTTP queda desactivado para poder probar; el despliegue público debe usar HTTPS.

La sesión de la cuenta vence después de 30 minutos sin actividad. El tiempo se comprueba en PHP. Al agregar este módulo se utiliza una cookie propia de TF, por lo que una selección de carrito de la versión anterior puede tener que armarse otra vez. Los productos y pedidos guardados en MySQL se conservan.

## Paso 3. Registrar un cliente

registro.php recibe nombre, apellido, correo, contraseña y su repetición por POST. Se comprueban el token del formulario, los nombres, el formato del correo y la contraseña. Los nombres aceptan letras, espacios, apóstrofes y guiones; los valores que vuelven al HTML se muestran con escapar().

El correo se normaliza a minúsculas y se consulta para evitar duplicados. El índice UNIQUE también protege contra dos registros simultáneos.

La contraseña se guarda con password_hash($password, PASSWORD_BCRYPT), con costo 12. Se pide una frase de al menos 15 caracteres y se rechazan entradas mayores a los 72 bytes admitidos por bcrypt. No se recorta la contraseña ni se conserva como texto legible.

El registro siempre asigna el rol cliente consultándolo en la base. Los valores rol o rol_id que alguien agregue al formulario no se utilizan para dar permisos.

Después de guardar, se redirige a login.php. La dirección anterior registrarse.php también lleva al registro nuevo.

## Paso 4. Iniciar sesión

login.php consulta el usuario y su rol por correo mediante PDO. password_verify() compara la contraseña ingresada con el hash guardado.

Cuando coincide:

1. Se ejecuta session_regenerate_id(true).
2. Se guardan $_SESSION['usuario_id'], $_SESSION['usuario_nombre'] y $_SESSION['rol'].
3. También se guardan el correo y el momento de la última actividad.
4. Se renueva el token del formulario.
5. El administrador va a admin/index.php y el cliente a cuenta.php.

El carrito de la sesión actual se conserva al ingresar. La contraseña no se guarda en la sesión. Un correo inexistente y una contraseña incorrecta muestran el mismo aviso. Se limita a cinco intentos fallidos por minuto dentro de la misma sesión; este límite simple no reemplaza una protección contra intentos distribuida por IP o cuenta para una publicación comercial.

Las cuentas ficticias del TP6 conservan sus registros para ese trabajo. Sus hashes de práctica, publicados en el repositorio, se rechazan para el ingreso. Para usar el TP8 se crea una cuenta propia.

## Paso 5. Proteger las páginas

auth_guard.php comprueba isset($_SESSION['usuario_id']) y el estado de la cuenta. Si no hay una sesión válida, redirige a login.php?error=acceso_denegado y detiene la ejecución.

En las páginas de administración se llama además a verificar_rol('admin'). Un cliente recibe HTTP 403 al intentar abrirlas, tanto por GET como por POST. El rol se consulta otra vez en MySQL: si se retira el permiso, la misma sesión deja de poder administrar.

| Página | Quién puede entrar |
| --- | --- |
| cuenta.php | Usuario autenticado |
| mis-pedidos.php | Usuario autenticado; consulta solo sus pedidos |
| admin/index.php | Administrador |
| admin/crear.php | Administrador |
| admin/editar.php | Administrador |
| admin/eliminar.php | Administrador |
| admin/pedidos.php | Administrador |
| tp7/prueba_conexion.php | Administrador |

Los archivos auxiliares de admin también comprueban el acceso. El enlace Gestión de productos se muestra únicamente al administrador. La protección se realiza en PHP antes de procesar datos; ocultar el enlace es solo una parte de la presentación.

Las consultas de mis-pedidos.php filtran por el usuario_id de la sesión, incluso cuando se solicita el detalle por id. Un usuario no puede consultar el pedido de otro cambiando la URL.

## Paso 6. Cerrar la sesión

El botón Cerrar sesión de Mi cuenta envía un POST con token a logout.php. Se vacía $_SESSION, se elimina la cookie con su misma configuración y se ejecuta session_destroy(). Finalmente se redirige al login.

Como el carrito pertenece a esa sesión, al cerrar sesión también se vacía. Abrir logout.php por GET no cierra la cuenta.

## Cómo probarlo en la tienda

1. Iniciar Apache y MySQL en XAMPP.
2. Abrir http://localhost/TiendaTF-main/registro.php. También se puede entrar desde el icono de cuenta o Iniciar sesión en el pie y luego Crear cuenta.
3. Registrarse con un correo propio y una contraseña elegida en el formulario.
4. Iniciar sesión y comprobar Mi cuenta y Mis pedidos.
5. Intentar abrir /admin/index.php como cliente: debe mostrar acceso denegado.
6. Realizar un pedido con la sesión iniciada. Debe aparecer en Mis pedidos. La compra como invitado sigue disponible; sus pedidos anteriores no se asocian a una cuenta solo por coincidir el correo.
7. Cerrar sesión y volver a abrir cuenta.php: debe pedir ingresar otra vez.

## Preparar la cuenta del administrador

Primero se registra una cuenta propia desde la página. Después, en la terminal del proyecto, el dueño ejecuta:

```powershell
& "C:\xampp\php\php.exe" tp8/asignar_admin.php "correo-que-registraste"
```

La herramienta busca esa cuenta y le asigna el rol administrador. No recibe ni cambia contraseñas. Solo funciona en la terminal; por HTTP responde 404.

Al volver a iniciar sesión con esa cuenta, se abre Gestión de productos. Desde el icono de cuenta se puede entrar a Mi cuenta, consultar pedidos y cerrar sesión.

## Archivos

- config/sesion.php: configuración de cookies, sesión y vencimiento.
- config/autenticacion.php: comprobaciones compartidas, rol y token.
- registro.php: validación y alta con hash.
- login.php: comprobación de contraseña y apertura del acceso.
- auth_guard.php: protección de páginas y verificación de rol.
- cuenta.php y mis-pedidos.php: vistas privadas del cliente.
- logout.php: eliminación de la sesión y de su cookie.
- tp8/asignar_admin.php: asignación del administrador desde la terminal.
- tp8/verificar_base.sql: revisión del esquema ya existente.
- tp8/PRUEBAS.md: comprobaciones realizadas.

## Entrega e instalación

El módulo está integrado en las páginas de TF y comparte su conexión PDO, navegación y estilos. El código y los commits pertenecen al mismo repositorio del proyecto.

En otro equipo se configura .env a partir de .env.example. Se cargan los scripts del TP6 y la actualización de tienda siguiendo tienda/README.md. El TP8 utiliza esas tablas y no necesita una migración adicional.

APP_BASE_PATH indica la ruta de la aplicación: /TiendaTF-main/ en XAMPP, o / cuando el sitio se sirve desde la raíz del dominio. En un servidor público se necesita PHP 8.2 o posterior, MySQL/MariaDB y HTTPS. El .env local está excluido de Git; Apache también bloquea su descarga y el acceso a .git mediante .htaccess.

La comprobación de Secure en HTTPS se hizo en un entorno CGI de prueba. No se configuró un alojamiento público ni un certificado HTTPS local. La URL de funcionamiento local es http://localhost/TiendaTF-main/login.php.

## Material usado

Se siguió la teoría de Clase 8 sobre sesiones, autenticación y roles, adaptándola a los usuarios y pedidos de TF.

- [PHP: configuración de sesiones](https://www.php.net/manual/en/session.security.ini.php).
- [OWASP: almacenamiento de contraseñas](https://cheatsheetseries.owasp.org/cheatsheets/Password_Storage_Cheat_Sheet.html).
- [OWASP: autenticación](https://cheatsheetseries.owasp.org/cheatsheets/Authentication_Cheat_Sheet.html).

La consigna exige bcrypt. OWASP prioriza Argon2id para desarrollos nuevos; en este TP se mantuvo bcrypt para respetar el algoritmo indicado, con costo 12 y control de su límite de entrada.
