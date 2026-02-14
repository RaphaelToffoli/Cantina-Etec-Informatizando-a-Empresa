<?php
session_start();
include("config.php"); 

$carrinho = $_SESSION['carrinho'] ?? [];
$total = 0;
$detalhes_map = [];
$carrinho_com_detalhes = [];

if (!empty($carrinho)) {
    
    $ids = array_unique(array_column($carrinho, 'id'));

    if (!empty($ids)) {
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        
        $sql = "SELECT `id.produto` AS id, imagem, descricao FROM produto WHERE `id.produto` IN ({$placeholders})";
        
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($ids); 
            $detalhes_produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            
            foreach ($detalhes_produtos as $detalhe) {
                $detalhes_map[$detalhe['id']] = [
                    'imagem' => $detalhe['imagem'],
                    'descricao' => $detalhe['descricao']
                ];
            }
            
        } catch (PDOException $e) {
            error_log("Erro ao buscar detalhes do produto no carrinho: " . $e->getMessage());
        }
    }

    
    foreach ($carrinho as $item) {
        $id_produto = $item['id'];
        if (isset($detalhes_map[$id_produto])) {
            $item['imagem'] = $detalhes_map[$id_produto]['imagem'];
            $item['descricao'] = $detalhes_map[$id_produto]['descricao'];
        } else {
            
            $item['imagem'] = 'caminho/para/imagem_padrao.png'; 
            $item['descricao'] = 'Detalhes não disponíveis';
        }
        $carrinho_com_detalhes[] = $item;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Meu Carrinho</title>
    <link rel="stylesheet" href="styles/carrinho.css"> 
</head>
<?php

$mensagem_alerta = $_SESSION['mensagem_alerta'] ?? '';
$mensagem_sucesso = $_SESSION['mensagem_sucesso'] ?? '';


unset($_SESSION['mensagem_alerta']);
unset($_SESSION['mensagem_sucesso']); 

?>
<body class="carrinho-body">
<div class="carrinho-container">
    <div class="carrinho-header">
        <h1>Meu Carrinho</h1>
    </div>
    <?php if (!empty($mensagem_alerta)): ?>
        <div class="alerta erro" style="padding: 15px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 20px;">
            <?= htmlspecialchars($mensagem_alerta) ?>
        </div>
    <?php endif; ?>
    
    <?php if (!empty($mensagem_sucesso)): ?>
        <div class="alerta sucesso" style="padding: 15px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 20px;">
            <?= htmlspecialchars($mensagem_sucesso) ?>
        </div>
    <?php endif; ?>

<?php if (empty($carrinho_com_detalhes)): ?>
    <div class="carrinho-vazio">
        <p>Seu carrinho está vazio. <a href="index.php" class="link-acao">Clique aqui para continuar comprando</a>.</p>
    </div>
<?php else: ?>

    <div class="carrinho-tabela">
        <div class="carrinho-cabecalho-grid">
            <div class="c-col-img">Imagem</div>
            <div class="c-col-produto">Produto</div>
            <div class="c-col-unitario">Preço Unitário</div>
            <div class="c-col-quantidade">Quantidade</div>
            <div class="c-col-total">Total</div>
            <div class="c-col-acoes">Ações</div>
        </div>
        
        <div class="carrinho-itens-wrapper">
        <?php foreach ($carrinho_com_detalhes as $item): ?>
            <div class="carrinho-item-grid">
                <div class="c-col-img">
                    <img src="<?= htmlspecialchars($item['imagem']) ?>" alt="<?= htmlspecialchars($item['nome']) ?>">
                </div>
                
                <div class="c-col-produto produto-info">
                    <h3 class="nome-produto"><?= htmlspecialchars($item['nome']) ?></h3>
                    <p class="descricao-produto"><?= htmlspecialchars($item['descricao']) ?></p>
                </div>
                
                <div class="c-col-unitario">
                    <span class="label-mobile">Preço Unitário:</span>
                    <span class="valor-unitario">R$ <?= number_format($item['preco'], 2, ',', '.') ?></span>
                </div>
                
                <div class="c-col-quantidade">
                    <span class="label-mobile">Quantidade:</span>
                    <form action="atualizar_carrinho.php" method="POST" class="quantidade-ajuste">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <button type="submit" name="acao" value="diminuir" title="Diminuir Quantidade" class="btn-diminuir" disabled>
                            &minus;
                        </button>
                        <input type="number" 
                            name="quantidade" 
                            value="<?= $item['quantidade'] ?>" 
                            min="1" 
                            required 
                            readonly 
                            class="input-quantidade">
                        <button type="submit" name="acao" value="aumentar" title="Aumentar Quantidade" class="btn-aumentar">
                            &plus;
                        </button>
                        <button type="submit" name="acao" value="atualizar" style="display: none;"></button>
                    </form>
                </div>
                
                <div class="c-col-total">
                    <span class="label-mobile">Total:</span>
                    <span class="valor-total-item">R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></span>
                </div>
                
                <div class="c-col-acoes">
                    <form method="POST" action="remover_carrinho.php" class="form-remover">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <button type="submit" title="Remover Produto" class="btn-remover">
                            <span class="btn-remover-icone">🗑</span>
                            <span class="hidden-lg">Remover</span>
                        </button>
                    </form>
                </div>
            </div>
            <?php $total += $item['preco'] * $item['quantidade']; ?>
        <?php endforeach; ?>
        </div>

        <div class="carrinho-rodape">
            <div class="rodape-total-wrapper">
                <span class="rodape-total-label">TOTAL DO CARRINHO:</span>
                <span class="rodape-total-valor">R$ <?= number_format($total, 2, ',', '.') ?></span>
            </div>
        </div>
    </div>

    <div class="botoes-acao">
        <form method="POST" action="limpar_carrinho.php">
            <button type="submit" class="btn-limpar" onclick="return confirm('Tem certeza que deseja limpar o carrinho?');">
                Limpar Carrinho
            </button>
        </form>

        <div class="botoes-acao-principal">
            <a href="index.php" class="btn-continuar">← Continuar Comprando</a>
            <a href="checkout.php" class="btn-finalizar">Finalizar Compra →</a>
        </div>
    </div>

<?php endif; ?>
</div>

<script>

function enviarAtualizacao(formElement, produtoId, novaQtd) {
    
   
    const dados = new URLSearchParams();
    dados.append('id', produtoId);
    dados.append('quantidade', novaQtd); 

    fetch('atualizar_carrinho.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: dados
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'sucesso') {
            
            window.location.reload(); 
            
            
        } else {
           
            alert("Erro: " + data.erro);
            
            
            if (data.limite_estoque !== undefined) {
                 const input = formElement.querySelector('.input-quantidade');
                 input.value = data.limite_estoque;
            }
        }
    })
    .catch(error => {
        console.error('Erro de comunicação:', error);
        alert('Não foi possível se comunicar com o servidor.');
    });
}



document.querySelectorAll('.quantidade-ajuste').forEach(form => {
    const input = form.querySelector('.input-quantidade');
    const diminuirBtn = form.querySelector('.btn-diminuir');
    const aumentarBtn = form.querySelector('.btn-aumentar');
    
    
    const produtoId = form.querySelector('input[name="id"]').value;

    function updateButtons() {
        diminuirBtn.disabled = parseInt(input.value) <= 1;
    }

    
    form.addEventListener('submit', (e) => {
        e.preventDefault(); 
        
        
    });


    
    diminuirBtn.addEventListener('click', (e) => {
        e.preventDefault(); 
        let current = parseInt(input.value);
        if (current > 1) {
            let novaQtd = current - 1;
            input.value = novaQtd;
            
            
            enviarAtualizacao(form, produtoId, novaQtd); 
        }
        updateButtons();
    });

    
    aumentarBtn.addEventListener('click', (e) => {
        e.preventDefault(); 
        let current = parseInt(input.value);
        let novaQtd = current + 1;
        input.value = novaQtd;
        
        
        enviarAtualizacao(form, produtoId, novaQtd); 
        updateButtons();
    });

    
    updateButtons();
});
</script>

</body>
</html>