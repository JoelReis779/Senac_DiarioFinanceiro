<?php
include_once("conexao.php");

if(!isset($_GET['id'])) {
    header("Location: listar_perfis.php");
    exit;
}

$id = $_GET['id'];
$sql = "SELECT nome FROM usuarios WHERE id = $id";
$resultado = $conn->query($sql);
$usuario = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Exclusão</title>
    <link rel="stylesheet" href="confirmar_exclusao.css">
</head>
<body>
    <div class="caixa-confirmacao">
        <h1>Confirmação</h1>
        <p>Você tem certeza que deseja excluir permanentemente o perfil de: <strong><?php echo htmlspecialchars($usuario['nome']); ?></strong>?</p>
        
        <div class="botoes-area">
            <a href="excluir_perfil.php?id=<?php echo $id; ?>" class="btn btn-sim">Sim Deletar</a>
            <a href="listar_perfis.php" class="btn btn-nao">Não Voltar</a>
        </div>
    </div>
</body>
</html>