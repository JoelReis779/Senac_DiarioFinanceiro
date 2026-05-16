<?php
// ---  CONSULTA AO BANCO DE DADOS ---
include_once("conexao.php");

// Busca todos os usuários cadastrados, ordenando pelos mais recentes (ID decrescente)
$sql = "SELECT * FROM usuarios ORDER BY id DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <!--   para  a parte mobile  -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Diário Financeiro - Perfis Cadastrados</title>
    <link rel="stylesheet" href="listar_perfis.css">
</head>
<body>

    <div class="container">
        <aside class="sidebar">
            <div class="perfil"><img src="cofrinho.png" alt="Ícone Perfil"></div>
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
            <h1>Diário Financeiro</h1>
            <h2 class="subtitulo">Usuários e Gastos Cadastrados</h2>

            <div class="tabela-container">
                <table class="tabela-dados">
                    <thead>
                        <tr>
                            <th class="coluna-nome">Nome</th>
                            <th class="alinhar-direita">Renda</th>
                            <th class="alinhar-direita">Moradia</th>
                            <th class="alinhar-direita">Alimentação</th>
                            <th class="alinhar-direita">Transporte</th>
                            <th class="alinhar-direita">Lazer</th>
                            <th class="alinhar-direita">Gastos Extras</th> 
                            <th class="coluna-acoes">Ações</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        //  se existem registros no banco
                        if ($resultado->num_rows > 0) {
                            
                            // percorre cada usuário encontrado
                            while($row = $resultado->fetch_assoc()) {
                                $id_usuario = $row['id'];
                                
                                echo "<tr>";
                                
                                echo "<td class='coluna-nome'>" . htmlspecialchars($row['nome']) . "</td>";
                               
                                echo "<td class='renda-destaque alinhar-direita'>R$ " . number_format($row['renda'], 2, ',', '.') . "</td>";
                                
                             
                                
                                echo "<td class='alinhar-direita'>R$ " . number_format($row['moradia'], 2, ',', '.') . "</td>";
                                echo "<td class='alinhar-direita'>R$ " . number_format($row['alimentacao'], 2, ',', '.') . "</td>";
                                echo "<td class='alinhar-direita'>R$ " . number_format($row['transporte'], 2, ',', '.') . "</td>";
                                echo "<td class='alinhar-direita'>R$ " . number_format($row['lazer'], 2, ',', '.') . "</td>";

                                // -BUSCA DE GASTOS EXTRAS-
                                echo "<td class='alinhar-direita'>";
                                // Para cada usuário, faz uma nova busca na tabela de extras
                                $sql_extras = "SELECT descricao, valor FROM gastos_extras WHERE id_usuario = $id_usuario";
                                $res_extras = $conn->query($sql_extras);
                                
                                if ($res_extras->num_rows > 0) {
                                    echo "<div class='container-extras'>";
                                    while($extra = $res_extras->fetch_assoc()) {
                                        echo "<div class='card-extra'>";
                                        echo "<span class='extra-label'>" . htmlspecialchars($extra['descricao']) . "</span>";
                                        echo "<span class='extra-valor'>R$ " . number_format($extra['valor'], 2, ',', '.') . "</span>";
                                        echo "</div>";
                                    }
                                    echo "</div>";
                                } else {
                                    echo "<span class='sem-extra'>Nenhum</span>";
                                }
                                echo "</td>";

                                // -BOTÕES  -
                                echo "<td class='coluna-acoes'>
                                        <div class='acoes-flex'>
                                            <a href='editar_perfil.php?id=" . $row['id'] . "' class='btn-acao btn-editar'>Editar</a>
                                            <a href='confirmar_exclusao.php?id=" . $row['id'] . "' class='btn-acao btn-excluir'>Excluir</a>
                                        </div>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                          
                            echo "<tr><td colspan='8' class='sem-dados'>Nenhum perfil encontrado.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>