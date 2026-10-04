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
function acharMaisLonga($palavras)
{
    $maior = $palavras[0];

    foreach ($palavras as $palavra) {
        if (mb_strlen($palavra) > mb_strlen($maior)) {
            $maior = $palavra;
        }
    }

    return $maior;
}

function acharMaisCurta($palavras)
{
    $menor = $palavras[0];

    foreach ($palavras as $palavra) {
        if (mb_strlen($palavra) < mb_strlen($menor)) {
            $menor = $palavra;
        }
    }

    return $menor;
}

function contarRepetidas($palavras)
{
    $contagem = array_count_values($palavras);
    $repetidas = 0;

    foreach ($contagem as $quantidade) {
        if ($quantidade > 1) {
            $repetidas++;
        }
    }

    return $repetidas;
}

function cincoMaisFrequentes($palavras)
{
    $contagem = array_count_values($palavras);
    arsort($contagem); // ordena da maior para a menor quantidade

    return array_slice($contagem, 0, 5, true);
}

function removerEspacosDuplicados($texto)
{
    return preg_replace('/\s+/', ' ', trim($texto));
}

function formatarTexto($texto)
{
    return mb_convert_case($texto, MB_CASE_TITLE, 'UTF-8');
}

// Função principal
function processarTexto($texto)
{
    $palavras = separarPalavras($texto);

    if (count($palavras) == 0) {
        return ['erro' => 'O texto não possui palavras.'];
    }

    $textoLimpo = removerEspacosDuplicados($texto);

    return [
        'caracteres'            => contarCaracteres($texto),
        'palavras'              => count($palavras),
        'frases'                => contarFrases($texto),
        'palavra_mais_longa'    => acharMaisLonga($palavras),
        'palavra_mais_curta'    => acharMaisCurta($palavras),
        'palavras_repetidas'    => contarRepetidas($palavras),
        'cinco_mais_frequentes' => cincoMaisFrequentes($palavras),
        'texto_sem_espacos_duplicados' => $textoLimpo,
        'texto_formatado'       => formatarTexto($textoLimpo),
    ];
}

$texto = "O gato subiu no telhado.   O gato desceu do telhado! Será que o cachorro viu o gato?";

print_r(processarTexto($texto));