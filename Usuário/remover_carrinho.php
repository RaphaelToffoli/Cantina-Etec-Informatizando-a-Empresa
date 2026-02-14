<?php
session_start();

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    foreach ($_SESSION['carrinho'] as $key => $item) {
        if ($item['id'] == $id) {
            unset($_SESSION['carrinho'][$key]);
            break;
        }
    }
}

header('Location: carrinho.php');
exit();
?>