
<?php
session_start();

include("config.php");


if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['nivel_acesso'] !== 'administrador') {
    header("Location: login.php?modo=login");
    exit();
}

$produtos = [];
$mensagem_erro = '';

try {
    $sql = "
        SELECT 
            `id.produto`, nome, quantidade_estoque 
        FROM 
            produto 
        ORDER BY 
            nome ASC
    ";

    $stmt = $pdo->query($sql);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Erro ao carregar estoque: " . $e->getMessage());
    $mensagem_erro = "Erro ao carregar o estoque. Tente novamente.";
}


function getStatusCor($quantidade) {
    if ($quantidade == 0) return 'bg-red-100 text-red-700 border-red-300';
    if ($quantidade <= 2) return 'bg-yellow-100 text-yellow-700 border-yellow-300';
    return 'bg-green-100 text-green-700 border-green-300';
}

function getStatusTexto($quantidade) {
    if ($quantidade == 0) return 'Esgotado';
    if ($quantidade <= 2) return 'Estoque Baixo';
    return 'Em Estoque';
}


$total_produtos = count($produtos);
$total_itens = array_reduce($produtos, function($acc, $p) {
    return $acc + $p['quantidade_estoque'];
}, 0);
$estoque_baixo_count = count(array_filter($produtos, function($p) {
    return $p['quantidade_estoque'] <= 2 && $p['quantidade_estoque'] > 0;
}));

function get_icon_svg($name, $class = "w-6 h-6") {
    $icons = [
        'package' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '"><path d="m7.5 4.27 9 5.15"/><path d="m21 8-9 5-9-5"/><path d="M3 7.92v9.16c0 .45.23.86.6.1L12 22l8.4-4.92a1 1 0 0 0 .6-.91V7.92"/><path d="m3.6 7.92 8.4 4.91 8.4-4.91"/><path d="m7.5 14.82 9 5.15"/></svg>',
        'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>',
        'trash2' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>',
        'alert-circle' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>',
        'arrow-left' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>'
    ];
    return $icons[$name] ?? '';
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Estoque - CantinaEtec</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <div class="main-container">
        <div class="estoque-card-main" style="margin-top:60px;">
            <div class="estoque-header">
                <div class="estoque-header-titulo">
                    <div class="estoque-header-icon">
                        <?= get_icon_svg('package', 'w-8 h-8 text-white') ?>
                    </div>
                    <h1>Estoque Atual</h1>
                </div>
                <p>Visualize e gerencie seu inventário</p>
            </div>
            
            <?php if (!empty($mensagem_erro)): ?>
                <div class="p-4 bg-red-100 text-red-700 border-red-300 border-2 text-center font-bold">
                    <?= htmlspecialchars($mensagem_erro) ?>
                </div>
            <?php endif; ?>

            <div class="busca-container">
                <div class="busca-input-wrap">
                    <?= get_icon_svg('search', 'w-5 h-5') ?>
                    <input
                        type="text"
                        placeholder="Buscar produto..."
                        id="busca-produto"
                        onkeyup="filtrarTabela()"
                        class="busca-input"
                    />
                </div>
            </div>

            <div class="tabela-container">
                <table class="estoque-tabela" id="tabela-produtos">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-left">Produto</th>
                            <th class="px-6 py-4 text-center">Quantidade</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (empty($produtos)) : ?>
                            <tr class="estoque-vazio-row">
                                <td colspan="3" class="text-center">
                                    <?= get_icon_svg('package', 'w-16 h-16') ?>
                                    <p class="text-lg">Estoque vazio</p>
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($produtos as $produto) : ?>
                                <tr class="produto-linha">
                                    <td class="px-6 py-4">
                                        <span class="produto-nome"><?= htmlspecialchars($produto['nome']) ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="qtd-badge-wrap">
                                            <span class="qtd-badge"><?= (int)$produto['quantidade_estoque'] ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="status-badge-wrap">
                                            <span class="status-badge <?= getStatusCor((int)$produto['quantidade_estoque']) ?>">
                                                <?= getStatusTexto((int)$produto['quantidade_estoque']) ?>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="estoque-footer">
                <a href="index.php" class="footer-btn footer-btn-voltar">
                    <?= get_icon_svg('arrow-left', 'w-5 h-5') ?>
                    Voltar ao Painel
                </a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card stat-card-green">
                <p>Total de Produtos Cadastrados</p>
                <p><?= $total_produtos ?></p>
            </div>
            
            <div class="stat-card stat-card-blue">
                <p>Total de Itens em Estoque</p>
                <p><?= $total_itens ?></p>
            </div>
            
            <div class="stat-card stat-card-yellow">
                <p>Produtos com Estoque Baixo</p>
                <p><?= $estoque_baixo_count ?></p>
            </div>
        </div>
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

    <script>
        
        function filtrarTabela() {
            const input = document.getElementById('busca-produto');
            const filtro = input.value.toLowerCase();
            const tabela = document.getElementById('tabela-produtos');
            const linhas = tabela.getElementsByClassName('produto-linha');
            let encontrado = false;

            for (let i = 0; i < linhas.length; i++) {
                
                const nomeProduto = linhas[i].getElementsByTagName('td')[0].textContent; 
                
                if (nomeProduto.toLowerCase().indexOf(filtro) > -1) {
                    linhas[i].style.display = "";
                    encontrado = true;
                } else {
                    linhas[i].style.display = "none";
                }
            }
            
        }
        
        

        function fecharModal(id) {
            document.getElementById(id).style.display = 'none';
        }
        
        document.querySelectorAll('.modal').forEach(el => {
            el.style.cssText = document.getElementById('modal-zerar').style.cssText;
        });

    </script>
</body>
</html>
