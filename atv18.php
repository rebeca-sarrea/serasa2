<?php

function ordenarConsultasPorHorario(array $consultas): array
{
    usort($consultas, function ($consulta1, $consulta2) {
        $dataHora1 = $consulta1['data'] . ' ' . $consulta1['horario'];
        $dataHora2 = $consulta2['data'] . ' ' . $consulta2['horario'];
        return strcmp($dataHora1, $dataHora2);
    });

    return $consultas;
}

function contarPacientesDiferentes(array $consultas): int
{
    $pacientes = [];
    foreach ($consultas as $consulta) {
        $pacientes[strtolower($consulta['paciente'])] = true;
    }

    return count($pacientes);
}

function contarPorEspecialidade(array $consultas): array
{
    $especialidades = [];
    foreach ($consultas as $consulta) {
        $especialidade = $consulta['especialidade'];
        if (isset($especialidades[$especialidade])) {
            $especialidades[$especialidade]++;
        } else {
            $especialidades[$especialidade] = 1;
        }
    }

    return $especialidades;
}

function pesquisarPaciente(array $consultas, string $nomePaciente): array
{
    $resultado = [];
    foreach ($consultas as $consulta) {
        if (strtolower($consulta['paciente']) == strtolower($nomePaciente)) {
            $resultado[] = $consulta;
        }
    }

    return $resultado;
}

function encontrarHorariosDuplicados(array $consultas): array
{
    $horarios = [];
    foreach ($consultas as $consulta) {
        $dataHora = $consulta['data'] . ' ' . $consulta['horario'];
        if (isset($horarios[$dataHora])) {
            $horarios[$dataHora]++;
        } else {
            $horarios[$dataHora] = 1;
        }
    }

    $duplicados = [];
    foreach ($horarios as $dataHora => $quantidade) {
        if ($quantidade > 1) {
            $duplicados[$dataHora] = $quantidade;
        }
    }

    return $duplicados;
}

function organizarAgenda(array $consultas, string $nomePaciente): array
{
    $consultasOrdenadas = ordenarConsultasPorHorario($consultas);
    $total = count($consultasOrdenadas);

    return [
        'total_consultas' => $total,
        'pacientes_diferentes' => contarPacientesDiferentes($consultasOrdenadas),
        'por_especialidade' => contarPorEspecialidade($consultasOrdenadas),
        'primeiro_atendimento' => $total > 0 ? $consultasOrdenadas[0] : null,
        'ultimo_atendimento' => $total > 0 ? $consultasOrdenadas[$total - 1] : null,
        'consultas_ordenadas' => $consultasOrdenadas,
        'pesquisa_paciente' => pesquisarPaciente($consultasOrdenadas, $nomePaciente),
        'horarios_duplicados' => encontrarHorariosDuplicados($consultasOrdenadas)
    ];
}

$consultas = [
    ['paciente' => 'Ana Souza', 'especialidade' => 'Cardiologia', 'data' => '2026-10-05', 'horario' => '08:00'],
    ['paciente' => 'Bruno Lima', 'especialidade' => 'Dermatologia', 'data' => '2026-10-05', 'horario' => '09:30'],
    ['paciente' => 'Ana Souza', 'especialidade' => 'Cardiologia', 'data' => '2026-10-05', 'horario' => '11:00'],
    ['paciente' => 'Carla Reis', 'especialidade' => 'Pediatria', 'data' => '2026-10-05', 'horario' => '09:30']
];

$nomePaciente = 'Ana Souza';
$resultado = organizarAgenda($consultas, $nomePaciente);

echo "Total de consultas: " . $resultado['total_consultas'] . "<br>";
echo "Pacientes diferentes: " . $resultado['pacientes_diferentes'] . "<br>";
echo "Primeiro atendimento: " . $resultado['primeiro_atendimento']['paciente'] . ' - ' . $resultado['primeiro_atendimento']['data'] . ' ' . $resultado['primeiro_atendimento']['horario'] . "<br>";
echo "Último atendimento: " . $resultado['ultimo_atendimento']['paciente'] . ' - ' . $resultado['ultimo_atendimento']['data'] . ' ' . $resultado['ultimo_atendimento']['horario'] . "<br><br>";

echo "Consultas por especialidade:<br>";
foreach ($resultado['por_especialidade'] as $especialidade => $quantidade) {
    echo $especialidade . ': ' . $quantidade . "<br>";
}

echo "<br>Agenda ordenada pelo horário:<br>";
foreach ($resultado['consultas_ordenadas'] as $consulta) {
    echo $consulta['paciente'] . ' - ' . $consulta['especialidade'] . ' - ' . $consulta['data'] . ' ' . $consulta['horario'] . "<br>";
}

echo "<br>Consultas de " . $nomePaciente . ":<br>";
if (count($resultado['pesquisa_paciente']) == 0) {
    echo "Nenhuma consulta encontrada.<br>";
} else {
    foreach ($resultado['pesquisa_paciente'] as $consulta) {
        echo $consulta['data'] . ' ' . $consulta['horario'] . ' - ' . $consulta['especialidade'] . "<br>";
    }
}

echo "<br>Horários duplicados:<br>";
if (count($resultado['horarios_duplicados']) == 0) {
    echo "Não existem horários duplicados.<br>";
} else {
    foreach ($resultado['horarios_duplicados'] as $dataHora => $quantidade) {
        echo $dataHora . ': ' . $quantidade . ' consultas<br>';
    }
}