<?php
include_once("conexao.php");

if(!empty($_GET['id'])) {
    $id = $_GET['id'];
    $sqlDelete = "DELETE FROM usuarios WHERE id = $id";

    if($conn->query($sqlDelete) === TRUE) {
        header("Location: listar_perfis.php");
    } else {
        echo "Erro ao excluir registro: " . $conn->error;
    }
} else {
    header("Location: listar_perfis.php");
}
$conn->close();
?>