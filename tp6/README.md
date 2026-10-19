# TP6 - Base de datos de TF Clothing

TF Clothing es mi proyecto de tienda de ropa para hombre, mujer y niño/a, con accesorios. En este TP diseñé la base de datos para guardar usuarios, productos y pedidos.

## Archivos

| Archivo | Contenido |
| --- | --- |
| NORMALIZACION.md | Ejemplo sin normalizar, pasos de 1FN, 2FN y 3FN, y listado final con PK y FK. |
| schema.sql | Creación de la base, las siete tablas, sus relaciones y el índice. |
| consultas.sql | Datos de prueba y operaciones INSERT, SELECT, UPDATE y DELETE. |
| PRUEBAS.md | Resultados de la ejecución local. |

## Ejecución en XAMPP

1. Iniciar Apache y MySQL desde el panel de XAMPP.
2. Abrir http://localhost/phpmyadmin.
3. Importar schema.sql para crear la base tf_tienda_tp6 y sus tablas.
4. Importar consultas.sql completo para cargar los datos y ejecutar las consultas.
5. Seleccionar la base y revisar las tablas y los resultados.

Los archivos se ejecutan en ese orden, una vez sobre una base nueva. Si la base ya tiene las tablas o los datos, hay que revisar su contenido antes de volver a importar.

XAMPP incluye MariaDB. Los scripts se probaron en la versión 10.4.32, con los resultados registrados en PRUEBAS.md.

## Datos de prueba

Lucas Medina es el cliente y Martina Ríos es la administradora. Son personas ficticias y sus correos usan example.com.

Los cuatro productos se tomaron del catálogo de la página. Los precios son orientativos en pesos argentinos. El pedido TF-1001 contiene una remera azul de hombre y dos remeras infantiles, con un total de $58.700.

El UPDATE cambia el precio actual de la remera azul a $25.900. El precio de esa prenda en el pedido se mantiene en $24.900. El DELETE elimina únicamente el producto temporal TP6-BORRAR.

Las dos cuentas usan la clave de práctica TfDemo2026!. En usuarios.password se almacena su hash, generado con password_hash() de PHP.

## Algunas decisiones

Las variables que empiezan con @ en consultas.sql guardan los ids consultados en la base. Por eso el archivo se ejecuta completo en una misma conexión. LAST_INSERT_ID() devuelve el id del pedido recién creado.

Cada usuario tiene un rol y cada producto pertenece a una categoría. La tabla detalle_pedidos relaciona los pedidos con sus productos y guarda talle, cantidad y precio de venta.

## Alcance

El trabajo se basa en la teoría de la Clase 6 y utiliza ejemplos de mi tienda. En este TP se crea y prueba la base de datos. La página todavía usa su catálogo PHP y su carrito de sesión. La conexión con MySQL mediante PDO corresponde al TP7.
