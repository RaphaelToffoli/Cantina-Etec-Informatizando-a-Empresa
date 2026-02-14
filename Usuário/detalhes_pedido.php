<?php
session_start();
include("config.php"); 


if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario']['id'])) {
    $_SESSION['mensagem_alerta'] = "Você precisa estar logado para ver os detalhes do pedido.";
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['usuario']['id'];
$id_pedido = $_GET['id'] ?? 0;
$detalhes = [];
$pedido_info = null;
$mensagem_erro = '';

if (!is_numeric($id_pedido) || $id_pedido <= 0) {
    $_SESSION['mensagem_alerta'] = "ID do pedido inválido.";
    header("Location: historico_pedidos.php");
    exit();
}

try {
    
    $sql_info = "SELECT `id.saida`, `id.usuario`, data_saida, metodo_pagamento, valor_total 
                 FROM `saida` 
                 WHERE `id.saida` = ? AND `id.usuario` = ?";
    $stmt_info = $pdo->prepare($sql_info);
    $stmt_info->execute([$id_pedido, $user_id]);
    $pedido_info = $stmt_info->fetch(PDO::FETCH_ASSOC);

    if (!$pedido_info) {
        $_SESSION['mensagem_alerta'] = "Pedido não encontrado ou você não tem permissão para visualizá-lo.";
        header("Location: historico_pedidos.php");
        exit();
    }

    
    $sql_detalhes = "SELECT
                        sd.quantidade,
                        sd.preco_unitario,
                        p.nome AS nome_produto
                     FROM 
                        `saida.detalhes` sd
                     INNER JOIN 
                        `produto` p ON sd.`id.produto` = p.`id.produto`
                     WHERE 
                        sd.`id.saida` = ?";
                        
    $stmt_detalhes = $pdo->prepare($sql_detalhes);
    $stmt_detalhes->execute([$id_pedido]);
    $detalhes = $stmt_detalhes->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Erro ao buscar detalhes do pedido: " . $e->getMessage());
    $mensagem_erro = "Erro ao carregar os detalhes do pedido. Tente novamente.";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Pedido #<?= htmlspecialchars($id_pedido) ?></title>
    <link rel="stylesheet" href="styles/detalhespedidos.css">
</head>
<body>

<div class="container">
    <h1>Detalhes do Pedido #<?= htmlspecialchars($id_pedido) ?></h1>

    <?php if (!empty($mensagem_erro)): ?>
        <div class="alerta"><?= htmlspecialchars($mensagem_erro) ?></div>
    <?php endif; ?>

    <?php if ($pedido_info): ?>
        <div class="info-box">
            <p><strong>Data do Pedido:</strong> <?= date('d/m/Y', strtotime($pedido_info['data_saida'])) ?></p>
            <p><strong>Método de Pagamento:</strong> <?= htmlspecialchars(ucfirst($pedido_info['metodo_pagamento'])) ?></p>
            <p><strong>Valor Total:</strong> R$ <?= number_format($pedido_info['valor_total'], 2, ',', '.') ?></p>
        </div>

        <h2>Itens Comprados</h2>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th style="width: 15%;">Preço Unitário</th>
                    <th style="width: 10%;">Quantidade</th>
                    <th style="width: 15%;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php $subtotal_calculado = 0; ?>
                <?php foreach ($detalhes as $item): ?>
                    <?php $subtotal = $item['quantidade'] * $item['preco_unitario']; $subtotal_calculado += $subtotal; ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nome_produto']) ?></td>
                        <td>R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($item['quantidade']) ?></td>
                        <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="3">Total Pago</td>
                    <td>R$ <?= number_format($pedido_info['valor_total'], 2, ',', '.') ?></td>
                </tr>
            </tbody>
        </table>

    <?php else: ?>
        <p>Não foi possível carregar os detalhes do pedido.</p>
    <?php endif; ?>
    
    <p style="margin-top: 20px;"><a class="botao-voltar" href="historico_pedido.php">← Voltar para o Histórico</a></p>
</div>

</body>
</html>