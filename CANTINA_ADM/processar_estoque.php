<?php
session_start();
include('config.php'); 


if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['nivel_acesso'] !== 'administrador') {
    header("Location: login.php?modo=login");
    exit();
}

$id_produto = $_POST['id_produto'] ?? null;
$quantidade = intval($_POST['quantidade'] ?? 0);
$acao = $_POST['acao'] ?? ''; 

if (!$id_produto || $quantidade <= 0 || ($acao !== 'inserir' && $acao !== 'retirar')) {
    $_SESSION['mensagem'] = "Dados inválidos para a operação de estoque.";
    header("Location: index.php");
    exit();
}


$operacao = ($acao === 'inserir') ? '+' : '-';
$mensagem_sucesso = ($acao === 'inserir') ? "adicionado" : "retirado";

$pdo->beginTransaction();

try {
    
    $sql_update = "UPDATE produto SET quantidade_estoque = quantidade_estoque {$operacao} ? WHERE `id.produto` = ?";
    $stmt_update = $pdo->prepare($sql_update);
    
    if (!$stmt_update->execute([$quantidade, $id_produto])) {
        $pdo->rollBack();
        throw new Exception("Falha ao atualizar o estoque.");
    }

    
    $sql_registro = "INSERT INTO `entrada` (`id.produto`, data_entrada, quantidade) VALUES (?, NOW(), ?)";
    $stmt_registro = $pdo->prepare($sql_registro);
    
    
    $quantidade_registro = ($acao === 'retirar') ? -$quantidade : $quantidade;

    if (!$stmt_registro->execute([$id_produto, $quantidade_registro])) {
        $pdo->rollBack();
        throw new Exception("Falha ao registrar o movimento no estoque.");
    }
    
    $pdo->commit();
    
    $_SESSION['mensagem'] = "Estoque {$mensagem_sucesso} com sucesso! Quantidade: {$quantidade}.";
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
}

header("Location: index.php");
exit();
?>