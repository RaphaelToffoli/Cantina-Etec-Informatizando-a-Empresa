<?php
session_start();


if (isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}


$_SESSION['mensagem_carrinho'] = "O carrinho foi esvaziado com sucesso!";


header('Location: carrinho.php');
exit();
?>