<?php
include_once("conexao.php");

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

$ano_atual = date('Y');

 
$sql = "SELECT u.*, 
        (SELECT SUM(valor) FROM gastos_extras WHERE id_usuario = u.id) AS total_extras 
        FROM usuarios u 
        ORDER BY u.nome ASC";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diário Financeiro - Projeções <?php echo $ano_atual; ?></title>
    <link rel="stylesheet" href="cadastrar_pagina.css">
    <link rel="stylesheet" href="listar_perfis.css">
    <link rel="stylesheet" href="projecoes.css?v=1.2">
</head>
<body>

    <div class="container">
        <aside class="sidebar">
            <div class="perfil">
                <img src="cofrinho.png" alt="Ícone Perfil">
            </div>
            <nav class="menu">
                <ul>
                    <li><a href="index.html">Início</a></li>
                    <li><a href="cadastrar_pagina.php">Cadastrar Perfil</a></li>
                    <li><a href="listar_perfis.php">Perfis Cadastrados</a></li>
                    <li><a href="historico_gastos.php">Histórico de gastos</a></li>
                    <li><a href="projecoes.php">Projeções futuras</a></li>
                </ul>
            </nav>
        </aside>

        <main class="conteudo">
            <h1>Projeções Financeiras</h1>
            <h2 class="subtitulo">Decomposição de Gastos para <?php echo $ano_atual; ?></h2>

            <div class="tabela-container">
                <table class="tabela-dados">
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>Gasto Médio/Dia</th>
                            <th>Total/Mês</th>
                            <th>Total/Ano</th>
                            <th>Saldo Final (Ano)</th>
                            <th>Status Previsto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($resultado && $resultado->num_rows > 0) {
                            while($row = $resultado->fetch_assoc()) {
                                
                               
                                $renda_mensal = $row['renda'];
                                
                                // soma apenas as colunas que permaneceram no banco
                                $gastos_fixos = $row['moradia'] + $row['alimentacao'] + $row['transporte'] + $row['lazer'];
                                
                                //  trata nulo como 0 
                                $gastos_extras = $row['total_extras'] ?? 0;
                                
                                // O gasto mensal real agora considera fixos + extras
                                $gasto_mensal = $gastos_fixos + $gastos_extras;
                                
                                // Cálculo Diário (Mês comercial de 30 dias)
                                $gasto_diario = $gasto_mensal / 30;
                                
                                // Cálculo Anual (12 meses)
                                $gasto_anual = $gasto_mensal * 12;
                                $renda_anual = $renda_mensal * 12;
                                $saldo_anual = $renda_anual - $gasto_anual;
                                
                                // Configuração visual
                                $classe_valor = ($saldo_anual >= 0) ? "valor-positivo" : "valor-negativo";
                                $label_status = ($saldo_anual >= 0) ? "meta-poupanca" : "alerta-gastos";
                                $texto_status = ($saldo_anual >= 0) ? "Economia Positiva" : "Dívida Prevista";

                                echo "<tr>";
                                echo "<td><strong>" . htmlspecialchars($row['nome']) . "</strong></td>";
                                echo "<td class='coluna-dia'>R$ " . number_format($gasto_diario, 2, ',', '.') . "</td>";
                                echo "<td>R$ " . number_format($gasto_mensal, 2, ',', '.') . "</td>";
                                echo "<td>R$ " . number_format($gasto_anual, 2, ',', '.') . "</td>";
                                echo "<td class='$classe_valor' style='font-size: 1.1em; font-weight: bold;'>R$ " . number_format($saldo_anual, 2, ',', '.') . "</td>";
                                echo "<td><span class='$label_status'>$texto_status</span></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='sem-dados'>Nenhum dado para calcular projeções.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="card-projecao">
                <h3>💡 Entenda os Cálculos</h3>
                <p>A coluna <strong>Gasto Médio/Dia</strong> mostra quanto seu estilo de vida custa a cada 24 horas, incluindo seus gastos extras. A projeção de <strong>Saldo Final</strong> indica o acumulado após 12 meses. Lembre-se: pequenas economias diárias podem mudar drasticamente o resultado anual!</p>
            </div>
        </main>
    </div>
</body>
</html>