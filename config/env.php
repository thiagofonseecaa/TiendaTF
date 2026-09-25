<?php
$ruta_env = __DIR__ . '/../.env';

if (!is_file($ruta_env)) {
    exit('Falta el archivo .env. Copiá .env.example como .env y completá los datos.');
}

$configuracion = parse_ini_file($ruta_env, false, INI_SCANNER_RAW);

if ($configuracion === false) {
    exit('No se pudo leer el archivo .env. Revisá su formato.');
}

$nombre_sitio = $configuracion['APP_NAME'] ?? 'TF Clothing';
$email_contacto = $configuracion['APP_EMAIL'] ?? '';
$entorno = $configuracion['APP_ENV'] ?? 'local';