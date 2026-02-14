<?php
// ... [Início do Código, Sessão e Includes] ...
session_start();
include("config.php"); 

if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario']['id'])) {
    $_SESSION['mensagem_alerta'] = "Você precisa estar logado para finalizar a compra.";
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['carrinho'])) {
    $_SESSION['mensagem_alerta'] = "Seu carrinho está vazio. Adicione produtos antes de finalizar a compra.";
    header("Location: carrinho.php");
    exit();
}

$user_id = $_SESSION['usuario']['id'];
$carrinho = $_SESSION['carrinho'] ?? [];
$total = 0;
$carrinho_com_detalhes = [];
$mensagem_erro = '';

if (!empty($carrinho)) {
    $ids = array_unique(array_column($carrinho, 'id'));
    
    if (!empty($ids)) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        $sql = "SELECT `id.produto` AS id, descricao, preco, quantidade_estoque FROM produto WHERE `id.produto` IN ({$placeholders})";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($ids); 
            $detalhes_produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $detalhes_map = [];
            foreach ($detalhes_produtos as $detalhe) {
                
                $detalhes_map[$detalhe['id']] = $detalhe;
            }
            
            
            foreach ($carrinho as $item) {
                $id_produto = $item['id'];
                if (isset($detalhes_map[$id_produto])) {
                    $db_preco = $detalhes_map[$id_produto]['preco'];
                    $item_quantidade = $item['quantidade'];

                    
                    if ($detalhes_map[$id_produto]['quantidade_estoque'] < $item_quantidade) {
                        $mensagem_erro = "Atenção: A quantidade de '{$item['nome']}' no seu carrinho excede o estoque disponível. Ajuste a quantidade e tente novamente.";
                        
                        break; 
                    }
                    
                    
                    $item['preco'] = (float)$db_preco; 
                    $item['descricao'] = $detalhes_map[$id_produto]['descricao'];

                    $total += $item['preco'] * $item_quantidade;
                    $carrinho_com_detalhes[] = $item;
                }
            }

            if (empty($mensagem_erro)) {
                 $_SESSION['carrinho'] = $carrinho_com_detalhes; 
            }
            
        } catch (PDOException $e) {
            error_log("Erro ao buscar detalhes do produto no checkout: " . $e->getMessage());
            $mensagem_erro = "Erro ao carregar os detalhes dos produtos. Tente novamente.";
        }
    }
}


if (!empty($mensagem_erro)) {
    
    $_SESSION['mensagem_alerta'] = $mensagem_erro;
    header("Location: carrinho.php");
    exit();
}


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Finalizar Compra - Checkout</title>
    <link rel="stylesheet" href="styles/carrinho.css"> 
    <style>
        
        .checkout-container { max-width: 900px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background-color: #fff; }
        .checkout-header h1 { color: #333; border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; }
        .resumo-pedido { margin-bottom: 30px; padding: 15px; background-color: #f9f9f9; border-radius: 6px; }
        .resumo-pedido h2 { border-bottom: 1px dashed #ccc; padding-bottom: 10px; margin-bottom: 15px; font-size: 1.2em; }
        .resumo-item { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .resumo-total { font-size: 1.5em; font-weight: bold; color: #dc3545; border-top: 1px solid #ddd; padding-top: 10px; margin-top: 10px; }
        
        .pagamento-opcoes h2 { margin-bottom: 15px; font-size: 1.2em; }
        .opcoes-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .metodo-card { border: 2px solid #eee; border-radius: 8px; padding: 20px; cursor: pointer; transition: all 0.2s; }
        .metodo-card:hover, .metodo-card.selected { border-color: #f97316; background-color: #fff7ed; }
        .metodo-card h3 { margin-top: 0; display: flex; align-items: center; }
        .metodo-icone { font-size: 2em; margin-right: 10px; color: #f97316; }
        .btn-confirmar { display: block; width: 100%; padding: 15px; margin-top: 30px; background-color: #10b981; color: white; border: none; border-radius: 8px; font-size: 1.2em; cursor: pointer; transition: background-color 0.2s; }
        .btn-confirmar:hover { background-color: #059263; }

        .alerta { padding: 15px; margin-bottom: 20px; border-radius: 4px; font-weight: bold; }
        .alerta.erro { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
<div class="checkout-container">
    <div class="checkout-header">
        <h1>✅ Finalizar Compra</h1>
    </div>

    <?php if (isset($mensagem_erro)): ?>
        <div class="alerta erro"><?= htmlspecialchars($mensagem_erro) ?></div>
    <?php endif; ?>

    <div class="resumo-pedido">
        <h2>Resumo do Pedido</h2>
        <?php foreach ($carrinho_com_detalhes as $item): ?>
            <div class="resumo-item">
                <span><?= htmlspecialchars($item['nome']) ?> (<?= $item['quantidade'] ?>x)</span>
                <span>R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></span>
            </div>
        <?php endforeach; ?>
        
        <div class="resumo-total">
            <span>Total a Pagar:</span>
            <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
        </div>
    </div>

    <form action="processar_compra.php" method="POST">
        <input type="hidden" name="total_compra" value="<?= $total ?>">
        
        <div class="pagamento-opcoes">
            <h2>Escolha o Método de Pagamento</h2>
            <div class="opcoes-grid">
                
                <label class="metodo-card" data-metodo="pix">
                    <input type="radio" name="metodo_pagamento" value="pix" required style="display: none;">
                    <h3><span class="metodo-icone">⚡</span> PIX</h3>
                    <p>Pagamento instantâneo. A confirmação do pedido é imediata.</p>
                </label>

                <label class="metodo-card" data-metodo="credito">
                    <input type="radio" name="metodo_pagamento" value="credito" required style="display: none;">
                    <h3><span class="metodo-icone">💳</span> Cartão de Crédito</h3>
                    <p>Pagamento seguro via API (simulada).</p>
                </label>

                <label class="metodo-card" data-metodo="debito">
                    <input type="radio" name="metodo_pagamento" value="debito" required style="display: none;">
                    <h3><span class="metodo-icone">💰</span> Cartão de Débito</h3>
                    <p>Confirmação rápida com débito em conta.</p>
                </label>
                
            </div>
        </div>
        
        <button type="submit" class="btn-confirmar">CONFIRMAR E PAGAR R$ <?= number_format($total, 2, ',', '.') ?></button>
    </form>

</div>

<script>
    
    document.querySelectorAll('.metodo-card').forEach(card => {
        card.addEventListener('click', function() {
            
            document.querySelectorAll('.metodo-card').forEach(c => c.classList.remove('selected'));
            
            this.classList.add('selected');
            
            this.querySelector('input[type="radio"]').checked = true;
        });
    });
    
    
    const primeiroMetodo = document.querySelector('.metodo-card');
    if (primeiroMetodo) {
        primeiroMetodo.classList.add('selected');
        primeiroMetodo.querySelector('input[type="radio"]').checked = true;
    }
</script>

</body>
</html>