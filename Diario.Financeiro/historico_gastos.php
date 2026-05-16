<?php
include_once("conexao.php");

//    para entender que estamos no Brasil
setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

// Tradução dos meses
$mes_atual_ingles = date('F'); 
$meses = [
    'January' => 'Janeiro', 'February' => 'Fevereiro', 'March' => 'Março',
    'April' => 'Abril', 'May' => 'Maio', 'June' => 'Junho',
    'July' => 'Julho', 'August' => 'Agosto', 'September' => 'Setembro',
    'October' => 'Outubro', 'November' => 'Novembro', 'December' => 'Dezembro'
];
$nome_mes = $meses[$mes_atual_ingles];

/**
 * BUSCA OS DADOS COM SOMA DE EXTRAS
 */
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
    <title>Histórico de Gastos - <?php echo $nome_mes; ?></title>
    <link rel="stylesheet" href="cadastrar_pagina.css">
    <link rel="stylesheet" href="listar_perfis.css">
    <link rel="stylesheet" href="historico_gastos.css"> 
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
            <h1>Histórico de Gastos</h1>
            <h2 class="subtitulo">Resumo Detalhado por Usuário - <?php echo $nome_mes; ?></h2>

            <div class="lista-usuarios-graficos">
                <?php 
                if ($resultado && $resultado->num_rows > 0): 
                    while($user = $resultado->fetch_assoc()):
                       
                        $categorias = [
                            'Moradia' => (float)$user['moradia'],
                            'Alimentação' => (float)$user['alimentacao'],
                            'Transporte' => (float)$user['transporte'],
                            'Lazer' => (float)$user['lazer'],
                            'Extras' => (float)($user['total_extras'] ?? 0)
                        ];

                        $total_gastos = array_sum($categorias);
                        $saldo = $user['renda'] - $total_gastos;
                        $porcentagem = ($user['renda'] > 0) ? ($total_gastos / $user['renda']) * 100 : 0;

                       
                        $cores = [
                            'Moradia' => '#3b82f6', 
                            'Alimentação' => '#f59e0b', 
                            'Transporte' => '#8b5cf6', 
                            'Lazer' => '#ec4899', 
                            'Extras' => '#ef4444'
                        ];

                        // (Pizza)
                        $conic_parts = [];
                        $acumulado = 0;
                        foreach ($categorias as $label => $valor) {
                            if ($valor > 0 && $total_gastos > 0) {
                                $fatia = ($valor / $total_gastos) * 100;
                                $fim = $acumulado + $fatia;
                                $conic_parts[] = "{$cores[$label]} $acumulado% $fim%";
                                $acumulado = $fim;
                            }
                        }
                        $estilo_pizza = !empty($conic_parts) ? "background: conic-gradient(" . implode(', ', $conic_parts) . ");" : "background: #e2e8f0;";
                ?>

                <section class="card-usuario-detalhe">
                    <div class="cabecalho-card">
                        <h3><?php echo htmlspecialchars($user['nome']); ?></h3>
                        <span class="badge-renda">Renda: R$ <?php echo number_format($user['renda'], 2, ',', '.'); ?></span>
                    </div>

                    <div class="corpo-card">
                        <div class="pizza-pequena" style="<?php echo $estilo_pizza; ?>"></div>

                        <div class="legenda-detalhada">
                            <?php foreach ($categorias as $label => $valor): if($valor > 0): ?>
                                <div class="item-legenda">
                                    <span class="ponto" style="background: <?php echo $cores[$label]; ?>"></span>
                                    <span class="label"><?php echo $label; ?>:</span>
                                    <span class="valor">R$ <?php echo number_format($valor, 2, ',', '.'); ?></span>
                                </div>
                            <?php endif; endforeach; ?>
                            
                            <hr>
                            
                            <div class="resumo-financeiro">
                                <div class="linha-resumo">
                                    <span>Total Gastos:</span>
                                    <strong class="text-danger">R$ <?php echo number_format($total_gastos, 2, ',', '.'); ?></strong>
                                </div>
                                <div class="linha-resumo">
                                    <span>Saldo:</span>
                                    <strong class="<?php echo ($saldo >= 0) ? 'text-success' : 'text-danger'; ?>">
                                        R$ <?php echo number_format($saldo, 2, ',', '.'); ?>
                                    </strong>
                                </div>
                                <div class="linha-resumo">
                                    <span>Uso da Renda:</span>
                                    <strong style="<?php echo ($porcentagem > 100) ? 'color: #ef4444;' : ''; ?>">
                                        <?php echo number_format($porcentagem, 1, ',', '.'); ?>%
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <?php 
                    endwhile; 
                else: 
                ?>
                    <p class="sem-dados">Nenhum perfil cadastrado para exibir gráficos.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>