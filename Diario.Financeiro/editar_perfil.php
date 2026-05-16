<?php
// --- LÓGICA DE BUSCA DOS DADOS ATUAIS ---
include_once("conexao.php");
session_start();

 
function exibirValor($valor) {
    return number_format((float)$valor, 2, ',', '');
}

if (!empty($_GET['id'])) {
    $id = $_GET['id'];

    /**
     * AJUSTE DE RESET DE SESSÃO:
    */
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        unset($_SESSION['extras_count']);
        unset($_SESSION['current_edit_id']);
    }

    // Busca os dados principais do usuário
     
    $sqlSelect = "SELECT * FROM usuarios WHERE id=$id";
    $result = $conn->query($sqlSelect);

    if ($result && $result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        $nome = $user_data['nome'];
        $renda = $user_data['renda'];
        
     
        $moradia = $user_data['moradia'];
        $alimentacao = $user_data['alimentacao'];
        $transporte = $user_data['transporte'];
        $lazer = $user_data['lazer'];

        // Busca os Gastos Extras vinculados no banco
        $extras_banco = [];
        $sqlExtras = "SELECT * FROM gastos_extras WHERE id_usuario=$id";
        $resultExtras = $conn->query($sqlExtras);
        while ($rowExtra = $resultExtras->fetch_assoc()) {
            $extras_banco[] = $rowExtra;
        }

        // Se a sessão não existe ou mudamos de usuário, sincroniza com o banco
        if (!isset($_SESSION['extras_count']) || $_SESSION['current_edit_id'] != $id) {
            $_SESSION['extras_count'] = count($extras_banco) > 0 ? count($extras_banco) : 1;
            $_SESSION['current_edit_id'] = $id;
        }

     
        if (isset($_POST['add_campo_edit'])) {
            $_SESSION['extras_count']++;
        }

    } else {
        header('Location: listar_perfis.php');
        exit;
    }
} else {
    header('Location: listar_perfis.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - Diário Financeiro</title>
    <link rel="stylesheet" href="cadastrar_pagina.css">
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
            <h1>Editar Perfil</h1>
            <h2 class="subtitulo">Atualize os dados de: <strong><?php echo htmlspecialchars($nome); ?></strong></h2>

            <form action="editar_perfil.php?id=<?php echo $id; ?>" method="POST" class="form-cadastro">
                
                <input type="hidden" name="id" value="<?php echo $id; ?>">

                <section class="card-form">
                    <h3 class="titulo-interno">Dados Principais</h3>
                    <div class="linha-campo">
                        <label>Nome Completo:</label>
                        <input type="text" name="nome" value="<?php echo $_POST['nome'] ?? htmlspecialchars($nome); ?>" required>
                    </div>
                    <div class="linha-campo">
                        <label>Renda Mensal:</label>
                        <input type="text" name="renda" inputmode="decimal" value="<?php echo $_POST['renda'] ?? exibirValor($renda); ?>" required>
                    </div>
                </section>

                <section class="card-form">
                    <h3 class="titulo-interno">Gastos Fixos</h3>
                    <div class="grid-gastos">
                        <div class="linha-campo">
                            <label>Moradia:</label>
                            <input type="text" name="moradia" value="<?php echo $_POST['moradia'] ?? exibirValor($moradia); ?>">
                        </div>
                        <div class="linha-campo">
                            <label>Alimentação:</label>
                            <input type="text" name="alimentacao" value="<?php echo $_POST['alimentacao'] ?? exibirValor($alimentacao); ?>">
                        </div>
                        <div class="linha-campo">
                            <label>Transporte:</label>
                            <input type="text" name="transporte" value="<?php echo $_POST['transporte'] ?? exibirValor($transporte); ?>">
                        </div>
                        <div class="linha-campo">
                            <label>Lazer:</label>
                            <input type="text" name="lazer" value="<?php echo $_POST['lazer'] ?? exibirValor($lazer); ?>">
                        </div>
                    </div>
                </section>

                <section class="card-form">
                    <h3 class="titulo-interno">Gastos Extras</h3>
                    <div class="lista-extras">
                        <?php 
                        $total_para_exibir = $_SESSION['extras_count'];

                        for($i = 0; $i < $total_para_exibir; $i++): 
                            // Lógica de preenchimento: POST 
                            if (isset($_POST['extra_desc'][$i])) {
                                $desc_f = htmlspecialchars($_POST['extra_desc'][$i]);
                                $valor_f = htmlspecialchars($_POST['extra_valor'][$i]);
                            } elseif (isset($extras_banco[$i])) {
                                $desc_f = htmlspecialchars($extras_banco[$i]['descricao']);
                                $valor_f = exibirValor($extras_banco[$i]['valor']);
                            } else {
                                $desc_f = '';
                                $valor_f = '';
                            }
                        ?>
                        <div class="item-extra">
                            <span class="idx"><?php echo $i + 1; ?>°</span>
                            <input type="text" name="extra_desc[]" placeholder="Descrição" value="<?php echo $desc_f; ?>">
                            <input type="text" name="extra_valor[]" placeholder="0,00" value="<?php echo $valor_f; ?>">
                        </div>
                        <?php endfor; ?>
                    </div>

                    <div class="area-acoes-extras" style="margin-top: 15px;">
                        <button type="submit" name="add_campo_edit" class="btn-secundario">
                            + Adicionar outro gasto extra
                        </button>
                    </div>
                </section>

                <div class="area-acoes" style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                    <a href="listar_perfis.php" style="margin-right: 20px; text-decoration: none; color: #64748b; align-self: center;">Cancelar</a>
                    <button type="submit" name="update" formaction="dadosatualizados.php" class="btn-principal">Salvar Alterações</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>