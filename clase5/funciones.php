<?php

function obtener_campo(array $datos, string $campo): string
{
    if (!isset($datos[$campo]) || !is_string($datos[$campo])) {
        return '';
    }

    return trim($datos[$campo]);
}

function escapar(string $texto): string
{
    return htmlspecialchars(
        $texto,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}