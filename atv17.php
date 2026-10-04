<?php

function removerEspacosDuplicados(string $texto): string
{
    return trim(preg_replace('/\s+/', ' ', $texto));
}

function separarPalavras(string $texto): array
{
    preg_match_all("/[A-Za-zÀ-ÿ0-9]+(?:['’][A-Za-zÀ-ÿ0-9]+)*/u", $texto, $resultado);
    return $resultado[0];
}

function contarFrases(string $texto): int
{
    preg_match_all('/[.!?]+/', $texto, $resultado);
    return count($resultado[0]);
}

function tamanhoTexto(string $texto): int
{
    return preg_match_all('/./us', $texto) ?: 0;
}

function encontrarMaiorEMenorPalavra(array $palavras): array
{
    if (count($palavras) == 0) {
        return ['maior' => '', 'menor' => ''];
    }

    $maior = $palavras[0];
    $menor = $palavras[0];

    foreach ($palavras as $palavra) {
        if (tamanhoTexto($palavra) > tamanhoTexto($maior)) {
            $maior = $palavra;
        }
        if (tamanhoTexto($palavra) < tamanhoTexto($menor)) {
            $menor = $palavra;
        }
    }

    return ['maior' => $maior, 'menor' => $menor];
}

function contarPalavrasRepetidas(array $palavras): int
{
    $palavrasMinusculas = array_map('strtolower', $palavras);
    $frequencias = array_count_values($palavrasMinusculas);
    $repetidas = 0;

    foreach ($frequencias as $quantidade) {
        if ($quantidade > 1) {
            $repetidas += $quantidade - 1;
        }
    }

    return $repetidas;
}

function obterCincoMaisFrequentes(array $palavras): array
{
    $palavrasMinusculas = array_map('strtolower', $palavras);
    $frequencias = array_count_values($palavrasMinusculas);
    arsort($frequencias);
    return array_slice($frequencias, 0, 5, true);
}

function processarTexto(string $texto): array
{
    $textoSemEspacosDuplicados = removerEspacosDuplicados($texto);
    $palavras = separarPalavras($textoSemEspacosDuplicados);
    $extremos = encontrarMaiorEMenorPalavra($palavras);

    return [
        'caracteres' => tamanhoTexto($texto),
        'quantidade_palavras' => count($palavras),
        'frases' => contarFrases($texto),
        'palavra_mais_longa' => $extremos['maior'],
        'palavra_mais_curta' => $extremos['menor'],
        'palavras_repetidas' => contarPalavrasRepetidas($palavras),
        'mais_frequentes' => obterCincoMaisFrequentes($palavras),
        'texto_sem_espacos_duplicados' => $textoSemEspacosDuplicados,
        'texto_formatado' => ucwords(strtolower($textoSemEspacosDuplicados))
    ];
}

$texto = 'PHP é uma linguagem versátil. PHP permite criar páginas dinâmicas e organizar funções.';
$resultado = processarTexto($texto);

echo "Texto analisado: " . $texto . "<br><br>";
echo "Quantidade de caracteres: " . $resultado['caracteres'] . "<br>";
echo "Quantidade de palavras: " . $resultado['quantidade_palavras'] . "<br>";
echo "Quantidade de frases: " . $resultado['frases'] . "<br>";
echo "Palavra mais longa: " . $resultado['palavra_mais_longa'] . "<br>";
echo "Palavra mais curta: " . $resultado['palavra_mais_curta'] . "<br>";
echo "Quantidade de palavras repetidas: " . $resultado['palavras_repetidas'] . "<br>";
echo "Cinco palavras mais frequentes:<br>";

foreach ($resultado['mais_frequentes'] as $palavra => $quantidade) {
    echo $palavra . ': ' . $quantidade . "<br>";
}

echo "Texto sem espaços duplicados: " . $resultado['texto_sem_espacos_duplicados'] . "<br>";
echo "Texto formatado: " . $resultado['texto_formatado'] . "<br>";