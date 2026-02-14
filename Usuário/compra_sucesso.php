<?php
session_start();

$mensagem_sucesso = $_SESSION['mensagem_sucesso'] ?? "Sua compra foi processada com sucesso.";


unset($_SESSION['mensagem_sucesso']); 

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Compra Concluída</title>
    <style>
        .sucesso-container { max-width: 600px; margin: 100px auto; padding: 40px; text-align: center; border: 2px solid #10b981; border-radius: 10px; background-color: #f0fff4; }
        .sucesso-container h1 { color: #10b981; font-size: 2em; margin-bottom: 20px; }
        .sucesso-container p { color: #333; margin-bottom: 30px; }
        .btn-home { padding: 10px 20px; background-color: #f97316; color: white; text-decoration: none; border-radius: 6px; }
        .btn-home:hover { background-color: #ea580c; }
    </style>
</head>
<body>
<div class="sucesso-container">
    <h1>🎉 Compra Concluída!</h1>
    <p><?= htmlspecialchars($mensagem_sucesso) ?></p>
    <a href="index.php" class="btn-home">Voltar para a Home</a>
</div>
</body>
</html>