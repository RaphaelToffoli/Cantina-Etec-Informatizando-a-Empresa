<?php
session_start();
include("config.php"); 

if (!isset($_SESSION['usuario'])) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        echo json_encode(['erro' => 'Você precisa estar logado para adicionar produtos ao carrinho.', 'status' => 'erro']);
        exit;
    } else {
        header("Location: login.php");
        exit;
    }
}

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if (isset($_POST['id']) && isset($_POST['nome']) && isset($_POST['preco'])) {
    
   
    $id = (int) $_POST['id'];
    $nome = $_POST['nome'];
    $preco = (float) $_POST['preco'];
    $quantidade_a_adicionar = isset($_POST['quantidade']) ? (int) $_POST['quantidade'] : 1;

    
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
    
    
    $quantidade_atual_no_carrinho = 0;
    $item_no_carrinho = null;

    
    foreach ($_SESSION['carrinho'] as &$item) {
        
        if ((int)$item['id'] === $id) { 
            $quantidade_atual_no_carrinho = $item['quantidade'];
            $item_no_carrinho = &$item; 
            break;
        }
    }
    
    unset($item); 
    
    $quantidade_total_desejada = $quantidade_atual_no_carrinho + $quantidade_a_adicionar;

    
    if ($quantidade_total_desejada > $estoque_disponivel) {
        
        $pode_adicionar = $estoque_disponivel - $quantidade_atual_no_carrinho;
        
        if ($pode_adicionar > 0) {
             $mensagem_erro = "Não foi possível adicionar todos. Restam apenas **$pode_adicionar** unidades em estoque. Você pode adicionar esta quantidade.";
        } else {
             $mensagem_erro = "Não foi possível adicionar mais. Você já tem o total disponível em estoque (**$estoque_disponivel** unidades) no seu carrinho.";
        }
        
        echo json_encode(['erro' => $mensagem_erro, 'status' => 'erro']);
        exit;
    }
    
    

    if ($item_no_carrinho !== null) {
        $item_no_carrinho['quantidade'] += $quantidade_a_adicionar;
    } else {
        $_SESSION['carrinho'][] = [
            'id' => $id,
            'nome' => $nome,
            'preco' => $preco,
            'quantidade' => $quantidade_a_adicionar
        ];
    }

    echo json_encode(['sucesso' => true, 'mensagem' => 'Produto adicionado ao carrinho!', 'status' => 'sucesso']);
    exit;

} else {
    echo json_encode(['erro' => 'Dados inválidos. Verifique ID, nome e preço.', 'status' => 'erro']);
    exit;
}
?>