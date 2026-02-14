<?php
include('config.php');

header('Content-Type: application/json'); 

if(!isset($_GET['categoria'])) {
    echo json_encode(["erro" => "Categoria não fornecida"]);
    exit;
}

$categoria = $_GET['categoria'];

try {
    $stmt = $pdo->prepare("SELECT `id.produto`, nome, descricao, imagem, preco FROM produto WHERE categoria = ?");
    $stmt->execute([$categoria]);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($produtos);

} catch (PDOException $e) {
    echo json_encode(["erro" => "Erro no banco: ".$e->getMessage()]);
}