<?php

session_start();

// --- CONEXÃO COM O BANCO ---
include_once("conexao.php");

// função auxiliar  
function prepararValor($valor) {
    if (empty($valor)) return 0.00;
    // Remove pontos de milhar e troca a vírgula pelo ponto decimal
    $valor_limpo = str_replace('.', '', $valor);
    $valor_limpo = str_replace(',', '.', $valor_limpo);
    return (float)$valor_limpo;
}

// --- RECEBIMENTO E TRATAMENTO DOS DADOS ---

$nome = $conn->real_escape_string($_POST['nome']);

// Aplica a limpeza em todos os campos financeiros
$renda = prepararValor($_POST['renda']);


$moradia      = prepararValor($_POST['moradia']);
$alimentacao  = prepararValor($_POST['alimentacao']);
$transporte   = prepararValor($_POST['transporte']);
$lazer        = prepararValor($_POST['lazer']);

// --- COMANDO SQL PARA O USUÁRIO  ---
$sql = "INSERT INTO usuarios (nome, renda, moradia, alimentacao, transporte, lazer) 
        VALUES ('$nome', '$renda', '$moradia', '$alimentacao', '$transporte', '$lazer')";

// --- EXECUÇÃO E LÓGICA DE GASTOS EXTRAS ---
if ($conn->query($sql) === TRUE) {
    
 
    $id_usuario_recem_criado = $conn->insert_id;

    //  se a lista de extras foi enviada
    if (isset($_POST['extra_desc']) && isset($_POST['extra_valor'])) {
        $descricoes = $_POST['extra_desc'];
        $valores    = $_POST['extra_valor'];
        $data_hoje  = date('Y-m-d');

        
        foreach ($descricoes as $index => $desc) {
            $valor_extra_bruto = $valores[$index];
            $valor_extra_limpo = prepararValor($valor_extra_bruto);

            // SÓ SALVA SE A DESCRIÇÃO NÃO ESTIVER VAZIA E O VALOR FOR MAIOR QUE ZERO
            if (!empty(trim($desc)) && $valor_extra_limpo > 0) {
                
                $desc_limpa = $conn->real_escape_string($desc);

                $sqlExtra = "INSERT INTO gastos_extras (id_usuario, descricao, valor, data_gasto) 
                             VALUES ('$id_usuario_recem_criado', '$desc_limpa', '$valor_extra_limpo', '$data_hoje')";
                
                $conn->query($sqlExtra);
            }
        }
    }

    // --- LIMPA A SESSÃO DE CAMPOS EXTRAS ---
   
    unset($_SESSION['qtd_extras']);

     
    header("Location: listar_perfis.php?status=sucesso");
    exit; 

} else {
    echo "Erro ao cadastrar perfil: " . $conn->error;
}

$conn->close();
?>