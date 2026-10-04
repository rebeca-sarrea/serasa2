<?php

if (php_sapi_name() !== 'cli') {
    header('Content-Type: text/plain; charset=utf-8');
}

function contarCaracteres($texto)
{
    return mb_strlen($texto);
}

function separarPalavras($texto)
{
    $texto = mb_strtolower($texto);
    return preg_split('/[^\p{L}\p{N}]+/u', $texto, -1, PREG_SPLIT_NO_EMPTY);
}

function contarFrases($texto)
{
    $frases = preg_split('/[.!?]+/', $texto, -1, PREG_SPLIT_NO_EMPTY);
    $quantidade = 0;

    foreach ($frases as $frase) {
        if (trim($frase) != '') {
            $quantidade++;
        }
    }

    return $quantidade;
}