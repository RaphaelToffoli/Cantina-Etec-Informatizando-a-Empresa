
<?php
session_start();
include('config.php'); 

// Redirecionamento de segurança (ADM)
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['nivel_acesso'] !== 'administrador') {
    header("Location: login.php?modo=login"); 
    exit();
}


$mensagem = $_SESSION['mensagem'] ?? '';
$tipo_mensagem = $_SESSION['tipo_mensagem'] ?? ''; 
unset($_SESSION['mensagem']);
unset($_SESSION['tipo_mensagem']);

$produtos = [];

try {
    
    $sql = "SELECT `id.produto`, nome, categoria, quantidade_estoque FROM produto ORDER BY categoria, nome";
    $stmt = $pdo->query($sql);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    error_log("Erro ao carregar produtos no painel: " . $e->getMessage());
    $mensagem = "Erro interno ao carregar a lista de produtos.";
    $tipo_mensagem = 'erro';
}


$produtos_por_categoria = [];
foreach ($produtos as $produto) {
    $cat = $produto['categoria'];
    if (!isset($produtos_por_categoria[$cat])) {
        $produtos_por_categoria[$cat] = [];
    }
    $produtos_por_categoria[$cat][] = $produto;
}


$primeira_cat = key($produtos_por_categoria) ?? '';


function get_icon_svg($name, $class = "w-6 h-6") {
    $icons = [
        'package' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '"><path d="m7.5 4.27 9 5.15"/><path d="m21 8-9 5-9-5"/><path d="M3 7.92v9.16c0 .45.23.86.6.1L12 22l8.4-4.92a1 1 0 0 0 .6-.91V7.92"/><path d="m3.6 7.92 8.4 4.91 8.4-4.91"/><path d="m7.5 14.82 9 5.15"/></svg>',
        'plus' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '"><path d="M12 5v14"/><path d="M5 12h14"/></svg>',
        'users' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        'trending-up' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>',
        'trending-down' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '"><polyline points="22 17 13.5 8.5 8.5 13.5 2 7"/><polyline points="16 17 22 17 22 11"/></svg>',
        'log-out' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="' . $class . '"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>'
    ];
    return $icons[$name] ?? '';
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Admin - CantinaEtec</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .saudacao-titulo .admin-name { 
            background-image: linear-gradient(to right, #f97316, #dc2626); 
            -webkit-background-clip: text; 
            color: transparent; 
}
    </style>
</head>
<body>
    <header class="cabecalho">
        <div class="logo-container">
            <span class="logo-text">Cantina Etec</span>
        </div>
        <a href="logout.php" class="btn-sair">
            <?= get_icon_svg('log-out', 'w-4 h-4') ?>
            Sair
        </a>
    </header>

    <main class="main-content">
        <div class="mb-8">
            <h1 class="saudacao-titulo">
                <span>Olá, </span>
                <span class="admin-name">
                    <?= htmlspecialchars($_SESSION['usuario']['nome'] ?? 'Administrador') ?>
                </span>
                <span>!</span>
            </h1>
            <p class="saudacao-sub">Bem-vindo ao painel de gerenciamento.</p>
        </div>

        <?php if (!empty($mensagem)): ?>
            <div class="mensagem <?= htmlspecialchars($tipo_mensagem) ?>">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <div class="cards-acao-grid">
            <a href="estoque.php" class="card-acao-btn">
                <div class="card-icon-bg">
                    <?= get_icon_svg('package', 'w-6 h-6') ?>
                </div>
                <div>
                    <div class="card-title">Visualizar Estoque</div>
                    <div class="card-subtitle">Gerencie seus produtos</div>
                </div>
            </a>
            <a href="cadastrar_produto.php" class="card-acao-btn">
                <div class="card-icon-bg">
                    <?= get_icon_svg('plus', 'w-6 h-6') ?>
                </div>
                <div>
                    <div class="card-title">Cadastrar Novo Produto</div>
                    <div class="card-subtitle">Adicione ao cardápio</div>
                </div>
            </a>
            <a href="visualizar_usuarios.php" class="card-acao-btn">
                <div class="card-icon-bg">
                    <?= get_icon_svg('users', 'w-6 h-6') ?>
                </div>
                <div>
                    <div class="card-title">Visualizar Usuários</div>
                    <div class="card-subtitle">Veja quem está cadastrado</div>
                </div>
            </a>
        </div>
        
        <div class="estoque-rapido-container">
            <h2 class="estoque-rapido-header">
                <div class="icon-bg">
                    <?= get_icon_svg('package', 'w-6 h-6 text-white') ?>
                </div>
                Gerenciamento Rápido de Estoque
            </h2>

            <div class="estoque-rapido-grid">
                <div class="estoque-card estoque-card-add">
                    <div class="estoque-info">
                        <div class="estoque-icon-bg estoque-icon-add">
                            <?= get_icon_svg('trending-up', 'w-8 h-8 text-white') ?>
                        </div>
                        <div>
                            <h3 class="estoque-title">Adicionar ao Estoque</h3>
                            <p class="estoque-sub">Registre novas entradas de produtos</p>
                        </div>
                    </div>
                    <button class="estoque-btn estoque-btn-add" onclick="abrirModal('inserir')">
                        Adicionar ao Estoque
                    </button>
                </div>
                
                <div class="estoque-card estoque-card-retirar">
                    <div class="estoque-info">
                        <div class="estoque-icon-bg estoque-icon-retirar">
                            <?= get_icon_svg('trending-down', 'w-8 h-8 text-white') ?>
                        </div>
                        <div>
                            <h3 class="estoque-title">Retirar do Estoque</h3>
                            <p class="estoque-sub">Registre saídas e vendas</p>
                        </div>
                    </div>
                    <button class="estoque-btn estoque-btn-retirar" onclick="abrirModal('retirar')">
                        Retirar do Estoque
                    </button>
                </div>
            </div>
        </div>
        
        </main>
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
    <div id="modal-estoque">
        <div class="modal-content">
            <h2 id="modal-titulo"></h2>

            <div class="categorias">
                <?php if (!empty($produtos_por_categoria)): ?>
                    <?php foreach (array_keys($produtos_por_categoria) as $cat): ?>
                        <button class="btn categoria-btn" type="button" 
                                onclick="mostrarProdutosPorCategoria('<?= htmlspecialchars($cat) ?>')"
                                style="background-color: <?= ($cat === $primeira_cat) ? 'var(--primary-blue)' : '#ccc' ?>;">
                            <?= htmlspecialchars(ucfirst($cat)) ?>
                        </button>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="text-align: center;">Nenhum produto cadastrado. <a href="cadastrar_produto.php">Cadastre um produto.</a></p>
                <?php endif; ?>
            </div>

            <?php if (!empty($produtos_por_categoria)): ?>
                <?php foreach ($produtos_por_categoria as $categoria_nome => $lista_produtos): ?>
                    <div id="produtos-<?= htmlspecialchars($categoria_nome) ?>" 
                         class="produtos <?= ($categoria_nome === $primeira_cat) ? 'active' : '' ?>">
                        
                        <h3><?= htmlspecialchars(ucfirst($categoria_nome)) ?></h3>

                        <form method="POST" action="processar_estoque.php" class="form-estoque form-inserir">
                            <input type="hidden" name="acao" value="inserir">
                            
                            <select name="id_produto" required>
                                <?php foreach ($lista_produtos as $produto): ?>
                                    <option value="<?= htmlspecialchars($produto['id.produto']) ?>">
                                        <?= htmlspecialchars($produto['nome']) ?> (Estoque: <?= $produto['quantidade_estoque'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <input type="number" name="quantidade" placeholder="Qtd." required min="1">
                            <button type="submit">Adicionar</button>
                        </form>

                        <form method="POST" action="processar_estoque.php" class="form-estoque form-retirar" style="display:none;">
                            <input type="hidden" name="acao" value="retirar">
                            
                            <select name="id_produto" required>
                                <?php foreach ($lista_produtos as $produto): ?>
                                    <option value="<?= htmlspecialchars($produto['id.produto']) ?>">
                                        <?= htmlspecialchars($produto['nome']) ?> (Estoque: <?= $produto['quantidade_estoque'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <input type="number" name="quantidade" placeholder="Qtd." required min="1">
                            <button type="submit">Retirar</button>
                        </form>

                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <button class="fechar-btn" type="button" onclick="fecharModal()">×</button>
        </div>
    </div>

    <script>
        let modoAtual = 'inserir'; 

        function fecharModal() {
            document.getElementById('modal-estoque').style.display = 'none';
        }

        function abrirModal(modo) {
            modoAtual = modo;
            document.getElementById('modal-estoque').style.display = 'flex';
            
            const titulo = document.getElementById('modal-titulo');
            titulo.textContent = (modo === 'inserir' ? 'Adicionar Estoque' : 'Retirar Estoque');

            
            document.querySelectorAll('.form-inserir').forEach(form => {
                form.style.display = (modo === 'inserir' ? 'flex' : 'none');
            });
            document.querySelectorAll('.form-retirar').forEach(form => {
                form.style.display = (modo === 'retirar' ? 'flex' : 'none');
            });
            
            
            const primeiroProduto = document.querySelector('.produtos');
            if (primeiroProduto) {
                const categoria = primeiroProduto.id.replace('produtos-', '');
                mostrarProdutosPorCategoria(categoria);
            }
        }

        function mostrarProdutosPorCategoria(categoria) {
            
            document.querySelectorAll('.produtos').forEach(div => {
                div.classList.remove('active');
            });
            
            const bloco = document.getElementById('produtos-' + categoria);
            if (bloco) {
                bloco.classList.add('active');
            }
            
            
            document.querySelectorAll('.categoria-btn').forEach(btn => {
                const cat_name_match = btn.onclick.toString().match(/'(.*?)'/);
                if (cat_name_match && cat_name_match[1] === categoria) {
                    btn.style.backgroundColor = 'var(--primary-blue)';
                } else {
                    btn.style.backgroundColor = '#ccc';
                }
            });
        }
        
        
        window.onload = function() {
            const primeiroProduto = document.querySelector('.produtos');
            if (primeiroProduto) {
                mostrarProdutosPorCategoria(primeiroProduto.id.replace('produtos-', ''));
            }
        };
    </script>
</body>
</html>