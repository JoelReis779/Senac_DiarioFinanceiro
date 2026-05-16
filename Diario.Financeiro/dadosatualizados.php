<?php
// Inclui a conexão com o banco de dados
include_once('conexao.php');

// Converte o formato brasileiro  para o formato SQL  
function prepararValor($valor) {
    if (empty($valor)) return 0.00;
    // Remove o ponto que separa o milhar
    $valor_limpo = str_replace('.', '', $valor);
    // Troca a vírgula pelo ponto dec
    $valor_limpo = str_replace(',', '.', $valor_limpo);
    return (float)$valor_limpo;
}

//  processa o código se o formulário foi enviado através do botão  
if(isset($_POST['update'])) {
    
    
    $id = $_POST['id'];
    
    // protege o nome contra erros de aspas e ataques de SQL Injection
    $nome = $conn->real_escape_string($_POST['nome']);

    //   converte os valores financeiros restantes para o padrão do banco (float)
   
    $renda        = prepararValor($_POST['renda']);
    $moradia      = prepararValor($_POST['moradia']);
    $alimentacao  = prepararValor($_POST['alimentacao']);
    $transporte   = prepararValor($_POST['transporte']);
    $lazer        = prepararValor($_POST['lazer']);

    
    $sqlUpdate = "UPDATE usuarios SET 
        nome = '$nome', 
        renda = '$renda', 
        moradia = '$moradia', 
        alimentacao = '$alimentacao', 
        transporte = '$transporte', 
        lazer = '$lazer' 
        WHERE id = '$id'";

    
    if ($conn->query($sqlUpdate) === TRUE) {

        /**
         * LÓGICA DE GASTOS EXTRAS:
         * Remove os antigos vinculados ao usuário 
         * e depois inserimos os novos que vieram do formulário de edição.
         */
        $conn->query("DELETE FROM gastos_extras WHERE id_usuario = '$id'");

        // Verifica se existem gastos extras enviados no formulário
        if (isset($_POST['extra_desc']) && isset($_POST['extra_valor'])) {
            $descricoes = $_POST['extra_desc'];
            $valores    = $_POST['extra_valor'];
            $data_hoje  = date('Y-m-d');

            // percorre os arrays de descrição e valor para inserir cada um no banco
            foreach ($descricoes as $index => $desc) {
                $valor_extra = prepararValor($valores[$index]);

                // Só insere o registro se a descrição não estiver vazia e o valor for real
                if (!empty(trim($desc)) && $valor_extra > 0) {
                    $desc_limpa = $conn->real_escape_string($desc);
                    
                    $sqlExtra = "INSERT INTO gastos_extras (id_usuario, descricao, valor, data_gasto) 
                                 VALUES ('$id', '$desc_limpa', '$valor_extra', '$data_hoje')";
                    
                    $conn->query($sqlExtra);
                }
            }
        }

        //  redireciona para a lista de perfis com sucesso
        header('Location: listar_perfis.php?status=editado');
        exit;
        
    } else {
        // Caso ocorra algum erro no comando SQL principal
        echo "Erro ao atualizar banco de dados: " . $conn->error;
    }
    
} else {
    // Se alguém tentar acessar este arquivo diretamente volta para a lista
    header('Location: listar_perfis.php');
    exit;
}
?>