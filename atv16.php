<?php

if (php_sapi_name() !== 'cli') {
    header('Content-Type: text/plain; charset=utf-8');
}

// Conta as letras maiúsculas
function contarMaiusculas($senha)
{
    return preg_match_all('/\p{Lu}/u', $senha);
}

function contarMinusculas($senha)
{
    return preg_match_all('/\p{Ll}/u', $senha);
}

function contarNumeros($senha)
{
    return preg_match_all('/[0-9]/', $senha);
}

function contarEspeciais($senha)
{
    return preg_match_all('/[^\p{L}\p{N}\s]/u', $senha);
}

function classificarSenha($tamanho, $maiusculas, $minusculas, $numeros, $especiais)
{
    $pontos = 0;

    if ($tamanho >= 8) {
        $pontos++;
    }
    if ($maiusculas > 0) {
        $pontos++;
    }
    if ($minusculas > 0) {
        $pontos++;
    }
    if ($numeros > 0) {
        $pontos++;
    }
    if ($especiais > 0) {
        $pontos++;
    }

    if ($pontos <= 2) {
        return 'Fraca';
    } elseif ($pontos == 3) {
        return 'Média';
    } elseif ($pontos == 4) {
        return 'Forte';
    } else {
        return 'Muito Forte';
    }
}

function analisarSenha($senha)
{
    $tamanho = mb_strlen($senha);
    $maiusculas = contarMaiusculas($senha);
    $minusculas = contarMinusculas($senha);
    $numeros = contarNumeros($senha);
    $especiais = contarEspeciais($senha);

    return [
        'maiusculas' => $maiusculas,
        'minusculas' => $minusculas,
        'numeros'    => $numeros,
        'especiais'  => $especiais,
        'tamanho'    => $tamanho,
        'nivel'      => classificarSenha($tamanho, $maiusculas, $minusculas, $numeros, $especiais),
    ];
}

$senhas = ['abc', 'senha123', 'Senha123', 'Senha@123'];

foreach ($senhas as $senha) {
    echo "Senha: $senha\n";
    print_r(analisarSenha($senha));
    echo "\n";
}