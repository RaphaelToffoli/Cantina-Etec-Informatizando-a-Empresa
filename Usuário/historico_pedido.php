<?php
session_start();
include("config.php"); 


if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario']['id'])) {
    $_SESSION['mensagem_alerta'] = "Você precisa estar logado para ver seu histórico de pedidos.";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['usuario']['id'];
$pedidos = [];
$mensagem_erro = '';

try {
    
    $sql_pedidos = "SELECT 
                        `id.saida` AS id_pedido, 
                        data_saida, 
                        metodo_pagamento, 
                        valor_total 
                    FROM 
                        `saida` 
                    WHERE 
                        `id.usuario` = ? 
                    ORDER BY 
                        data_saida DESC";
                        
    $stmt_pedidos = $pdo->prepare($sql_pedidos);
    $stmt_pedidos->execute([$user_id]);
    $pedidos = $stmt_pedidos->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Erro ao buscar histórico de pedidos: " . $e->getMessage());
    $mensagem_erro = "Erro ao carregar seu histórico de pedidos. Tente novamente.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Pedidos</title>
<style>
body {
    font-family: Arial, sans-serif; 
    background-color: #f4f4f4; 
    margin: 20px; }
.container {
    max-width: 1000px; 
    margin: auto; 
    background: white; 
    padding: 20px; 
    border-radius: 8px; 
    box-shadow: 0 0 10px rgba(0,0,0,0.1); 
}
h1 {
    text-align: center; 
    color: #333; 
}
.pedido {
    border: 1px solid #ccc; 
    padding: 15px; 
    margin-bottom: 20px; 
    border-radius: 6px; 
}
.pedido-header {
    display: flex; 
    justify-content: space-between; 
    font-weight: bold; 
    border-bottom: 1px solid #eee; 
    padding-bottom: 10px; 
    margin-bottom: 10px; 
}
.pedido-details { 
    margin-top: 10px; 
}
.btn-detalhes { 
    background-color: #007bff;
    color: white; 
    padding: 5px 10px; 
    text-decoration: none; 
    border-radius: 4px; 
}
.alerta { 
    padding: 10px; 
    background-color: #f8d7da; 
    color: #721c24; 
    border: 1px solid #f5c6cb; 
    border-radius: 4px; 
    margin-bottom: 15px; 
}
.botao-voltar{
    color: var(--cor-gray-600) ;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    margin-bottom: 20px;
    font-size: 14px;
}
</style>
</head>
<body>

<div class="container">
    <h1>Histórico de Pedidos</h1>

    <?php if (!empty($mensagem_erro)): ?>
        <div class="alerta"><?= htmlspecialchars($mensagem_erro) ?></div>
    <?php endif; ?>

    <?php if (empty($pedidos)): ?>
        <p>Você ainda não tem pedidos no seu histórico.</p>
        <p><a href="index.php">Continuar Comprando</a></p>
    <?php else: ?>
        
        <?php foreach ($pedidos as $pedido): ?>
        <div class="pedido">
            <div class="pedido-header">
                <span>Pedido #<?= htmlspecialchars($pedido['id_pedido']) ?></span>
                <span>Data: <?= date('d/m/Y', strtotime($pedido['data_saida'])) ?></span>
                <span>Total: R$ <?= number_format($pedido['valor_total'], 2, ',', '.') ?></span>
            </div>
            <div class="pedido-details">
                <p>Método de Pagamento: <?= htmlspecialchars(ucfirst($pedido['metodo_pagamento'])) ?></p>
                <a href="detalhes_pedido.php?id=<?= htmlspecialchars($pedido['id_pedido']) ?>" class="btn-detalhes">Ver Detalhes</a>
            </div>
        </div>
        <?php endforeach; ?>

    <?php endif; ?>
    
    <a href="index.php" class="botao-voltar" > 
        <i data-lucide="chevron-left" style="width: 20px; height: 20px; margin-right: 5px;"></i> 
        ← Voltar para a loja
    </a>
</div>

</body>
</html>