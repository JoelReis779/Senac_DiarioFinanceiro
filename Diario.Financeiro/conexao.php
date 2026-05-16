<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "diario_financeiro";

// Criando a conexão
$conn = new mysqli($host, $user, $pass, $db);

// Verifica erros
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>