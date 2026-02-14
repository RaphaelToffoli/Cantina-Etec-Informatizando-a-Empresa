<?php
include('config.php'); 
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $pdo->prepare("SELECT `id.produto`, nome, imagem, descricao, quantidade_estoque, preco
                           FROM produto 
                           WHERE `id.produto` = ?");
    $stmt->execute([$id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produto) {
        echo json_encode([
            "id" => $produto['id.produto'],
            "nome" => $produto['nome'],
            "imagem" => $produto['imagem'],
            "descricao" => $produto['descricao'],
            "quantidade" => $produto['quantidade_estoque'],
            "preco" => $produto['preco'],
        ]);
    } else {
        echo json_encode(["erro" => "Produto não encontrado."]);
    }
} else {
    echo json_encode(["erro" => "ID inválido."]);
}