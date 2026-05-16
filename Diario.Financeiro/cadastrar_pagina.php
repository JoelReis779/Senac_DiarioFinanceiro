<?php
 
session_start();

/**
 * LÓGICA DE RESET:
 
 */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $_SESSION['qtd_extras'] = 1;
}


if (!isset($_SESSION['qtd_extras'])) {
    $_SESSION['qtd_extras'] = 1;
}

 
if (isset($_POST['add_campo'])) {
    $_SESSION['qtd_extras']++;
}

// Reseta manualmente para 1 campo caso necessário
if (isset($_POST['limpar_extras'])) {
    $_SESSION['qtd_extras'] = 1;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diário Financeiro - Cadastro</title>
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
            <h1>Diário Financeiro</h1>
            <h2 class="subtitulo">Cadastrar usuário e despesas</h2>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" class="form-cadastro">

                <section class="card-form">
                    <div class="linha-campo">
                        <label for="nome">Nome:</label>
                        <input type="text" id="nome" name="nome" placeholder="Digite seu nome" value="<?php echo $_POST['nome'] ?? ''; ?>" required>
                    </div>

                    <div class="linha-campo">
                        <label for="renda">Renda:</label>
                        <input type="text" id="renda" name="renda" inputmode="decimal" placeholder="0,00" value="<?php echo $_POST['renda'] ?? ''; ?>" required>
                    </div>
                </section>

                <section class="card-form">
                    <h3 class="titulo-interno">Gastos Fixos</h3>
                    <div class="grid-gastos">
                        <div class="linha-campo">
                            <label>Moradia:</label>
                            <input type="text" name="moradia" placeholder="0,00" value="<?php echo $_POST['moradia'] ?? ''; ?>">
                        </div>
                        <div class="linha-campo">
                            <label>Alimentação:</label>
                            <input type="text" name="alimentacao" placeholder="0,00" value="<?php echo $_POST['alimentacao'] ?? ''; ?>">
                        </div>
                        <div class="linha-campo">
                            <label>Transporte:</label>
                            <input type="text" name="transporte" placeholder="0,00" value="<?php echo $_POST['transporte'] ?? ''; ?>">
                        </div>
                        <div class="linha-campo">
                            <label>Lazer:</label>
                            <input type="text" name="lazer" placeholder="0,00" value="<?php echo $_POST['lazer'] ?? ''; ?>">
                        </div>
                    </div>
                </section>

                <section class="card-form">
                    <h3 class="titulo-interno">Gastos Extras</h3>
                    <div class="lista-extras">
                        <?php 
                         
                        for ($i = 1; $i <= $_SESSION['qtd_extras']; $i++): 
                            // Mantém o texto digitado caso a página recarregue ao adicionar novo campo
                            $desc_val = $_POST['extra_desc'][$i-1] ?? '';
                            $valor_val = $_POST['extra_valor'][$i-1] ?? '';
                        ?>
                        <div class="item-extra">
                            <span class="idx"><?php echo $i; ?>°</span>
                            <input type="text" name="extra_desc[]" placeholder="Descrição" value="<?php echo $desc_val; ?>">
                            <input type="text" name="extra_valor[]" placeholder="R$ 0,00" value="<?php echo $valor_val; ?>">
                        </div>
                        <?php endfor; ?>
                    </div>

                    <div class="area-acoes-extras" style="margin-top: 15px; display: flex; gap: 10px;">
                        <button type="submit" name="add_campo" class="btn-secundario">
                            + Adicionar outro campo
                        </button>
                    </div>

                    <div class="area-acoes" style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                        <button type="submit" formaction="processa_cadastro.php" class="btn-principal">
                            Finalizar e Salvar no Banco
                        </button>
                    </div>
                </section>
            </form>
        </main>
    </div>

</body>
</html>