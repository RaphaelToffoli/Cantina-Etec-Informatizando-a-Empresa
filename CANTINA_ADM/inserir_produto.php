<?php
include('conexao.php');

$produto = $_POST['produto'] ?? '';
$quantidade = (int)($_POST['quantidade'] ?? 0);

if ($produto && $quantidade > 0) {
    $data = date('Y-m-d');

    
    $sqlBusca = "SELECT `id.produto` AS id_produto, quantidade_estoque FROM produto WHERE nome = ?";
    $stmt = mysqli_prepare($conexao, $sqlBusca);
    mysqli_stmt_bind_param($stmt, "s", $produto);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($resultado)) {
        $idProduto = $row['id_produto'];
        $novaQuantidade = $row['quantidade_estoque'] + $quantidade;

        $sqlUpdate = "UPDATE produto SET quantidade_estoque = ? WHERE `id.produto` = ?";
        $stmt = mysqli_prepare($conexao, $sqlUpdate);
        mysqli_stmt_bind_param($stmt, "ii", $novaQuantidade, $idProduto);
        mysqli_stmt_execute($stmt);
    } else {
        $status = 1;
        $sqlInsert = "INSERT INTO produto (`nome`, `quantidade_estoque`, `data_criacao`, `status_produto`) 
                      VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexao, $sqlInsert);
        mysqli_stmt_bind_param($stmt, "sisi", $produto, $quantidade, $data, $status);
        mysqli_stmt_execute($stmt);
        $idProduto = mysqli_insert_id($conexao);
    }

    
    $sqlEntrada = "INSERT INTO entrada (`id.produto`, `data_entrada`, `quantidade`) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sqlEntrada);
    mysqli_stmt_bind_param($stmt, "isi", $idProduto, $data, $quantidade);
    mysqli_stmt_execute($stmt);

    header("Location: index.php");
    exit();
} else {
    echo "Produto ou quantidade inválidos.";
}
