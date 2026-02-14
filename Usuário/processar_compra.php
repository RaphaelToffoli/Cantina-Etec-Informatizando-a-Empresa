<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("config.php"); 


function debug_saida($mensagem, $redirecionar = true) {
    if ($redirecionar) {
        $_SESSION['mensagem_alerta'] = $mensagem;
        header("Location: carrinho.php");
        exit();
    } else {
        echo "DEBUG PAROU: " . $mensagem;
        die();
    }
}



if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    debug_saida("Acesso inválido ao script de processamento.");
}

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario']['id'])) {
    debug_saida("Erro: Usuário não logado.", true); 
}

if (empty($_SESSION['carrinho'])) {
    debug_saida("Erro: Carrinho vazio.", true); 
}

$user_id = $_SESSION['usuario']['id'];
$carrinho = $_SESSION['carrinho'];


$metodo_pagamento = $_POST['metodo_pagamento'] ?? 'desconhecido';
$total_compra = floatval($_POST['total_compra'] ?? 0); 
$data_saida = date('Y-m-d'); 


$pdo->beginTransaction();

try {
    
    $sql_saida = "INSERT INTO `saida` (`id.usuario`, `data_saida`, `metodo_pagamento`, `valor_total`) VALUES (?, ?, ?, ?)";
    $stmt_saida = $pdo->prepare($sql_saida);
    
    if (!$stmt_saida->execute([$user_id, $data_saida, $metodo_pagamento, $total_compra])) {
        $pdo->rollBack();
        debug_saida("ERRO SQL: Falha ao inserir na tabela `saida`. Verifique os nomes das colunas.", true);
    }
    
    $id_saida = $pdo->lastInsertId();

    
    foreach ($carrinho as $item) {
        $id_produto = $item['id'];
        $quantidade_comprada = $item['quantidade'];
        $preco_unitario = $item['preco']; 
        
        
        $sql_estoque = "SELECT quantidade_estoque FROM produto WHERE `id.produto` = ?";
        $stmt_estoque = $pdo->prepare($sql_estoque);
        $stmt_estoque->execute([$id_produto]);
        $produto_db = $stmt_estoque->fetch(PDO::FETCH_ASSOC);

        if (!$produto_db || $produto_db['quantidade_estoque'] < $quantidade_comprada) {
            $pdo->rollBack();
            $estoque_disp = $produto_db ? $produto_db['quantidade_estoque'] : 0;
            debug_saida("ERRO DE ESTOQUE: Produto '{$item['descricao']}' tem estoque insuficiente. Disponível: {$estoque_disp}.", true);
        }
        
        
        $sql_detalhes = "INSERT INTO `saida.detalhes` (`id.saida`, `id.produto`, `quantidade`, `preco_unitario`) VALUES (?, ?, ?, ?)";
        $stmt_detalhes = $pdo->prepare($sql_detalhes);
        
        if (!$stmt_detalhes->execute([$id_saida, $id_produto, $quantidade_comprada, $preco_unitario])) {
             $pdo->rollBack();
             debug_saida("ERRO SQL: Falha ao inserir detalhes na tabela `saida.detalhes` para o produto {$id_produto}.", true);
        }

        
        $sql_update_estoque = "UPDATE produto SET quantidade_estoque = quantidade_estoque - ? WHERE `id.produto` = ?";
        $stmt_update_estoque = $pdo->prepare($sql_update_estoque);
        
        if (!$stmt_update_estoque->execute([$quantidade_comprada, $id_produto])) {
             $pdo->rollBack();
             debug_saida("ERRO SQL: Falha ao atualizar o estoque para o produto {$id_produto}.", true);
        }
    }

    
    $pdo->commit();

    
    unset($_SESSION['carrinho']);
    $_SESSION['mensagem_sucesso'] = "Sua compra foi realizada com sucesso! ID do Pedido: #{$id_saida}.";
    header("Location: compra_sucesso.php");
    exit();

} catch (Exception $e) {
    
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Erro durante o processamento da compra: " . $e->getMessage());
    
    $_SESSION['mensagem_alerta'] = "Houve um erro grave ao processar sua compra. Por favor, tente novamente ou entre em contato. Erro Técnico: " . $e->getMessage();
    header("Location: carrinho.php");
    exit();
}
?>