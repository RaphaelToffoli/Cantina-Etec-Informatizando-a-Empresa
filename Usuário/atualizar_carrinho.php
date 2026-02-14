<?php
session_start();
include("config.php"); 


if (isset($_POST['id']) && isset($_POST['quantidade'])) {
    
    $id = (int) $_POST['id'];
    $nova_quantidade = (int) $_POST['quantidade'];
    
   
    if ($nova_quantidade < 1) {
        echo json_encode(['erro' => 'A quantidade mínima é 1.', 'status' => 'erro']);
        exit;
    }

    
    try {
        
        $stmt = $pdo->prepare("SELECT quantidade_estoque FROM produto WHERE `id.produto` = :id");
        $stmt->execute([':id' => $id]);
        $produto_db = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produto_db) {
            echo json_encode(['erro' => 'Produto não encontrado.', 'status' => 'erro']);
            exit;
        }

        $estoque_disponivel = (int) $produto_db['quantidade_estoque'];

    } catch (PDOException $e) {
        echo json_encode(['erro' => 'Erro ao consultar o estoque.', 'status' => 'erro']);
        exit;
    }
    
    
    
    if ($nova_quantidade > $estoque_disponivel) {
        
        echo json_encode([
            'erro' => "Não é possível ter {$nova_quantidade} unidades. O estoque disponível é de apenas **{$estoque_disponivel}** unidades.",
            'limite_estoque' => $estoque_disponivel, 
            'status' => 'erro'
        ]);
        exit;
    }

    
    $atualizado = false;
    foreach ($_SESSION['carrinho'] as &$item) {
        if ((int)$item['id'] === $id) {
            $item['quantidade'] = $nova_quantidade;
            $atualizado = true;
            break;
        }
    }
    unset($item); 

    if ($atualizado) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Carrinho atualizado!', 'status' => 'sucesso']);
    } else {
        echo json_encode(['erro' => 'Produto não encontrado no carrinho.', 'status' => 'erro']);
    }

} else {
    echo json_encode(['erro' => 'Dados inválidos para a atualização.', 'status' => 'erro']);
}
?>