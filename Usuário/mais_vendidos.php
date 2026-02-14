<?php
session_start();
include("config.php"); 

$dados_ranking = [];
$mensagem_erro = '';


$sql_ranking = "
    SELECT
        p.nome AS nome_produto,  /* <-- AJUSTE AQUI: Mantenha 'nome' ou mude para 'descricao' */
        SUM(sd.quantidade) AS total_vendido
    FROM 
        `saida.detalhes` sd
    JOIN 
        `produto` p ON sd.`id.produto` = p.`id.produto`
    GROUP BY
        p.`id.produto`, p.nome /* Agrupa pelo ID e nome do produto */
    ORDER BY
        total_vendido DESC
    LIMIT 10;
";

try {
    $stmt = $pdo->query($sql_ranking);
    $dados_ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    error_log("Erro no relatório de mais vendidos: " . $e->getMessage());
    $mensagem_erro = "Erro ao gerar o relatório. Erro Técnico: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Top 10 Produtos Mais Vendidos</title>
    <link rel="stylesheet" href="styles/maisvendidos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<a href="index.php" class="botao-voltar" >
        <i data-lucide="chevron-left" style="width: 20px; height: 20px; margin-right: 5px;"></i>
           ← Voltar para a pagina inicial
    </a>
<div class="container">
    <h1>Produtos Mais Vendidos</h1>

    <?php if (!empty($mensagem_erro)): ?>
        <div class="erro"><?= htmlspecialchars($mensagem_erro) ?></div>
    <?php endif; ?>

    <?php if (empty($dados_ranking)): ?>
        <p>Nenhum dado de vendas encontrado ainda.</p>
    <?php else: ?>
        
        <table>
            <thead>
                <tr>
                    <th class="ranking">#</th>
                    <th>Produto</th>
                    <th class="vendido">Total Vendido</th>
                </tr>
            </thead>
            <tbody>
                <?php $rank = 1; ?>
                <?php foreach ($dados_ranking as $produto): ?>
                <tr>
                    <td class="ranking"><?= $rank++ ?>º</td>
                    <td><?= htmlspecialchars($produto['nome_produto']) ?></td>
                    <td class="vendido"><?= htmlspecialchars($produto['total_vendido']) ?> unidades</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>
    
</div>
<footer  class="rodape" >
        <section class="rodape__container" >
            <ul class="list ds" >
                <h3>Desenvolvido por</h3>
                    <li>Kaua Henrique Dos Santos De Oliveira</li>
                    <li>Luis Gustavo Vicente Da Silva</li>
                    <li>Raphael Toffoli Da Silva Nobrega</li>
            </ul>

            <ul class="list contato" >
                <h3>Contato</h3>
                    <li>(14) 99757-0325</li>
                    <li>(14) 99761-1163</li>
                    <li>(19) 98896-1031</li>
            </ul>

            <div class="bloco-redes-sociais">
    <h3>Redes Socias</h3>
    
    <div class="icones-linha">
        <a href="URL_INSTA_KAUA" target="_blank" title="Instagram do Kaua">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="URL_GITHUB_KAUA" target="_blank" title="GitHub do Kaua">
            <i class="fab fa-github"></i>
        </a>
        <a href="URL_LINKEDIN_KAUA" target="_blank" title="LinkedIn do Kaua">
            <i class="fab fa-linkedin-in"></i>
        </a>
    </div>
    
    <div class="icones-linha">
        <a href="URL_INSTA_LUIS" target="_blank" title="Instagram do Luis">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="URL_GITHUB_LUIS" target="_blank" title="GitHub do Luis">
            <i class="fab fa-github"></i>
        </a>
        <a href="URL_LINKEDIN_LUIS" target="_blank" title="LinkedIn do Luis">
            <i class="fab fa-linkedin-in"></i>
        </a>
    </div>
    
    <div class="icones-linha">
        <a href="URL_INSTA_RAPHAEL" target="_blank" title="Instagram do Raphael">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="URL_GITHUB_RAPHAEL" target="_blank" title="GitHub do Raphael">
            <i class="fab fa-github"></i>
        </a>
        <a href="URL_LINKEDIN_RAPHAEL" target="_blank" title="LinkedIn do Raphael">
            <i class="fab fa-linkedin-in"></i>
        </a>
    </div>
</div>
        </section>
        <section class="rodape__container2" >
            <p class="rodape__copy" >Copyright @2025 All reserved | This template is made by GRUPO TCC CANTINA ETEC:Otimizando a empresa</p>
        </section>
            
    </footer>

</body>
</html>