<?php
session_start();
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit();
}

include('conexao.php');


mysqli_query($conexao, "DELETE FROM `saida.detalhes`");


mysqli_query($conexao, "DELETE FROM saida");


mysqli_query($conexao, "DELETE FROM entrada");

if (mysqli_query($conexao, "DELETE FROM produto")) {
    echo "<script>alert('Todos os produtos e históricos foram removidos com sucesso!'); window.location.href='estoque.php';</script>";
} else {
    echo "Erro ao limpar estoque: " . mysqli_error($conexao);
}
