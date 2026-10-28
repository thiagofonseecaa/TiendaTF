# Pruebas del TP8

Prueba local realizada en XAMPP el 28/10/2026 con PHP, PDO y la base del proyecto. Se usaron cuentas y pedidos temporales, que se retiraron al finalizar. Los registros previos se comprobaron antes y después.

## Resultados

1. Visitante redirigido antes de entrar a páginas privadas; cookie HttpOnly/SameSite y archivos privados bloqueados.
2. Registro valida nombres, correo, contraseñas, XSS y CSRF; correo único, bcrypt y rol cliente fijo.
3. Registro simultáneo con el mismo correo no duplica usuarios.
4. Login rechaza contraseña incorrecta, correo inexistente, token inválido y credenciales públicas de práctica.
5. Login regenera el id, conserva el carrito, guarda las variables requeridas y bloquea la gestión al cliente.
6. Compra vinculada al usuario de la sesión; cada cliente ve solo sus pedidos, sin suplantación por POST o URL.
7. La compra como invitado sigue funcionando sin crear una cuenta.
8. Administrador accede al CRUD y a pedidos; retirar su rol en MySQL bloquea la misma sesión.
9. Logout por POST elimina la cookie y la sesión; sesión vencida y acceso posterior rechazados.
10. Se limitan los intentos repetidos dentro de la misma sesión.

## Cookies y sesiones

Se comprobó en HTTP local la cookie TFSESSID con HttpOnly, SameSite=Lax y duración de sesión. En una ejecución CGI con HTTPS se comprobó además Secure. Se rechazaron identificadores de sesión nuevos elegidos desde la cookie o desde la URL.

Se verificó que el identificador cambie después del login, que el identificador anterior no permita entrar y que se guarden usuario_id, usuario_nombre y rol en el servidor. También se comprobó el vencimiento por inactividad.

## Datos conservados

Los productos, categorías, talles, roles, usuarios y pedidos que ya existían conservaron sus valores. La asignación del administrador se probó con una cuenta temporal propia del ensayo. No se modificaron los permisos de las cuentas anteriores.

## Cómo repetir una comprobación manual

Crear una cuenta, ingresar, agregar una prenda al carrito y confirmar un pedido. Revisarlo en Mis pedidos. En otro navegador o con otra cuenta, intentar abrir su id: no debe mostrarse. Intentar entrar a Gestión como cliente: debe responder acceso denegado. Ingresar con la cuenta administradora para comprobar el CRUD y el listado general. Finalmente cerrar sesión y volver a abrir una página privada.

La restricción por rol se prueba escribiendo la URL y enviando solicitudes: no alcanza con comprobar que el enlace esté oculto.
