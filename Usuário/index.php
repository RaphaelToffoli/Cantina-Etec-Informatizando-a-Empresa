<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logado = isset($_SESSION['usuario']);
$nomeUsuario = $logado ? $_SESSION['usuario']['nome'] : '';

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}
?>

<!DOCTYPE html> 
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem vindo ao Site da Cantina</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <header class="cabecalho" >
        <nav class="cabecalho__menu" >
            <a class="logo"></a>
            <div class="menu_link" >

                <a class="cabecalho__menu__link" href="" >Home</a>
                <div class="dropdown" >
                    <a class="dropdown-toggle" data-toggle="dropdown" href="" >Cardapio</a>
                        <div class="dropdown-menu" >
                            <a class="dropdown-item" href="javascript:void(0)" onclick="carregarCategoria('salgados')">Salgados</a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="carregarCategoria('bebidas')">Bebidas</a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="carregarCategoria('salgadinhos')">Salgadinhos</a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="carregarCategoria('doces')">Doces</a>
                            <a class="dropdown-item" href="javascript:void(0)" onclick="carregarCategoria('especiais')">Especiais</a>
                        </div>
                </div>

                <a class="cabecalho__menu__link" href="mais_vendidos.php" >Mais Vendidos</a>
               <a class="cabecalho__menu__link" href="/CANTINA/CANTINA_ADM/login.php" >Parceiros</a>
            </div>
               <div class="menu_login" >

               <?php if ($logado): ?>
                    <div class="perfil-dropdown">
                        <div class="perfil-icone" onclick="toggleDropdown()">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="white"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="7" r="4"></circle>
                                <path d="M5.5 21a8.5 8.5 0 0 1 13 0"></path>
                            </svg>
                        </div>
                        <div id="perfilMenu" class="dropdown-conteudo">
                            <p><strong><?php echo htmlspecialchars($nomeUsuario); ?></strong></p>
                            <a href="carrinho.php">Carrinho</a>
                            <a href="excluir_conta.php">Configurações</a>
                            <a href="historico_pedido.php">Historico Pedidos</a>
                            <a href="sair.php">Sair</a>
                            <!-- <a href="excluir_conta.php" style="color:red;">Excluir Conta</a> -->
                        </div>
                    </div>
                <?php else: ?>
                    <a class="Login" href="login.PHP?form=cadastrar">Cadastrar</a>
                    <a class="LoginBtn"  href="login.PHP?form=login">Entrar</a>
                <?php endif; ?>
                </div>
        </nav>
    </header>
    <main id="conteudoPrincipal" class="principal" >

        <section class="principal__inicio" >
            <h1 class="principal__inicio__titulo">Bem-vindo à Cantina Online!</h1>
            <h3 class="principal__inicio__subtitulo" >Descubra nossos salgados, doces e bebidas fresquinhas. Faça sua reserva e garanta seu pedido sem sair da sala!</h3>
            <form id="formPesquisa" action="/search" method="get" class="barra" >
                <input type="search" id="campoPesquisa" name="search" placeholder="Pesquise aqui" >
                <button class="button" type="submit" >Buscar</button>
            </form>
        </section>
        <section class="principal__salgados">
            <div class="principal__salgados__selecionar">
                <h4 class="principal__titulo">Salgados</h4>
                <hr class="linhas" > 
                <div class="espaco" >
                    <?php
                        include("config.php");

                        $stmt = $pdo->query("SELECT * FROM produto WHERE categoria = 'salgados'");
                        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($produtos as $produto) {
                        echo "<a 
                                class='salgados image' 
                                style=\"background-image: url('" . $produto['imagem'] . "');\"
                                href='javascript:void(0)' 
                                onclick='abrirProduto(" . $produto['id.produto'] . ")'
                            ></a>";
                        }
                    ?>
                </div>
                    <button class="scroll-btn left">&#10094;</button>
                    <button class="scroll-btn right">&#10095;</button>
            </div>
        </section>
        <section class="principal__salgados">
            <div class="principal__salgados__selecionar" >
                <h4 class="principal__titulo" >Bebidas</h4>
                <hr class="linhas" >
                <div class="espaco" >
                    <?php
                        include("config.php");

                        $stmt = $pdo->query("SELECT * FROM produto WHERE categoria = 'bebidas'");
                        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($produtos as $produto) {
                        echo "<a 
                                class='salgados image' 
                                style=\"background-image: url('" . $produto['imagem'] . "');\"
                                href='javascript:void(0)' 
                                onclick='abrirProduto(" . $produto['id.produto'] . ")'
                            ></a>";
                        }
                    ?>
                </div>    
                    <button class="scroll-btn left">&#10094;</button>
                    <button class="scroll-btn right">&#10095;</button>
            </div>
        </section>
        <section class="principal__salgados">
            <div class="principal__salgados__selecionar" >
                <h4 class="principal__titulo" >Salgadinhos</h4>
                <hr class="linhas" >
                <div class="espaco">
                <?php
                        include("config.php");

                        $stmt = $pdo->query("SELECT * FROM produto WHERE categoria = 'salgadinhos'");
                        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($produtos as $produto) {
                        echo "<a 
                                class='salgados image' 
                                style=\"background-image: url('" . $produto['imagem'] . "');\"
                                href='javascript:void(0)' 
                                onclick='abrirProduto(" . $produto['id.produto'] . ")'
                            ></a>";
                        }
                    ?>
                </div>
                    <button class="scroll-btn left">&#10094;</button>
                    <button class="scroll-btn right">&#10095;</button>
            </div>
        </section>
        <section class="principal__salgados" >
            <div class="principal__salgados__selecionar" >
                <h4 class="principal__titulo" >Doces</h4>
                <hr class="linhas" >
                <div class="espaco" >
                <?php
                        include("config.php");

                        $stmt = $pdo->query("SELECT * FROM produto WHERE categoria = 'doces'");
                        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($produtos as $produto) {
                        echo "<a 
                                class='salgados image' 
                                style=\"background-image: url('" . $produto['imagem'] . "');\"
                                href='javascript:void(0)' 
                                onclick='abrirProduto(" . $produto['id.produto'] . ")'
                            ></a>";
                        }
                    ?>
                </div>
                    <button class="scroll-btn left">&#10094;</button>
                    <button class="scroll-btn right">&#10095;</button>
            </div>
        </section>
        <section class="principal__salgados">
            <div class="principal__salgados__selecionar" >
                <h4 class="principal__titulo">Especiais do dia</h4>
                <hr class="linhas">
                <div class="espaco">
                
                <?php
                        include("config.php");

                        $stmt = $pdo->query("SELECT * FROM produto WHERE categoria = 'especiais'");
                        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($produtos as $produto) {
                        echo "<a 
                                class='salgados image' 
                                style=\"background-image: url('" . $produto['imagem'] . "');\"
                                href='javascript:void(0)' 
                                onclick='abrirProduto(" . $produto['id.produto'] . ")'
                            ></a>";
                        }
                    ?>
                </div>
                    <button class="scroll-btn left">&#10094;</button>
                    <button class="scroll-btn right">&#10095;</button>
            </div>
        </section>
    </main>
    <main id="conteudoCategoria" style="display:none;">
        <h2 id="tituloCategoria"></h2>
        <div id="produtosContainer"></div>
        <button onclick="voltarPagina()" style="margin-top:20px;">← Voltar</button>
    </main>
    <footer class="rodape" >
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
    <div id="produtoModal" class="modal">
    <div class="modal-conteudo">
        <button class="fechar" onclick="fecharProdutoModal()">&times;</button>

        <div class="modal-corpo">
            
            <div class="modal-detalhes">
                <h2 id="modalTitulo" class="modal-titulo">Nome do Produto</h2>
                
                <div class="modal-imagem-wrapper">
                    <img id="modalImagem" src="" alt="Imagem do produto" class="modal-imagem">
                    <p class="modal-imagem-caption"></p> 
                </div>

                <p id="modalDescricao" class="modal-descricao">Descrição do produto...</p>
                
                <p class="modal-preco">
                    <span class="label">Preço: </span>
                    <span class="valor">R$ <span id="modalPreco">0.00</span></span>
                </p>
                <p class="modal-estoque">
                    <strong>Quantidade disponível:</strong> <span id="modalQtd">0</span>
                </p>
                
                <button type="button" class="btn-comprar-modal" onclick="comprarModal()">Comprar</button>
            </div>
            
            <div id="modalSugestoes" class="modal-sugestoes">
                <h4 class="sidebar-titulo">Outros produtos</h4>
                </div>
        </div>
    </div>
</div>
    <script>
        // javascript do botão de rolagem lateral
       // javascript do botão de rolagem lateral
    const leftBtn = document.querySelectorAll('.scroll-btn.left');
    const rightBtn = document.querySelectorAll('.scroll-btn.right');
    const container = document.querySelectorAll('.espaco');

    leftBtn.forEach((leftBtn, index) => {
        leftBtn.addEventListener('click', () => {
            container[index].scrollBy({ left: -1000, behavior: 'smooth' });
        });
    });

    rightBtn.forEach((rightBtn, index) => {
        rightBtn.addEventListener('click', () => {
            container[index].scrollBy({ left: 1000, behavior: 'smooth' });
        });
    });

    // javascript do model
    function abrirProduto(id) {
        fetch(`get_produto.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.erro) {
                    alert(data.erro);
                } else {
                    // Preenche o modal com o produto principal
                    document.getElementById("modalTitulo").textContent = data.nome;
                    document.getElementById("modalImagem").src = data.imagem;
                    document.getElementById("modalDescricao").textContent = data.descricao;
                    document.getElementById("modalPreco").textContent = parseFloat(data.preco).toFixed(2);
                    document.getElementById("modalQtd").textContent = data.quantidade;

                    // Mostra o modal
                    document.getElementById("produtoModal").style.display = "flex";

                    definirProdutoModal(data);
                    // Carrega sugestões
                    carregarSugestoes(id);
                }
            })
            .catch(error => {
                console.error("Erro ao buscar o produto:", error);
            });
    }

    function carregarSugestoes(idAtual) {
        fetch('get_todos_produtos.php')
            .then(res => res.json())
            .then(produtos => {
                const container = document.getElementById("modalSugestoes");
                container.innerHTML = "<h4 class='sidebar-titulo'>Outros produtos</h4>"; 

                produtos.forEach(produto => {
                    
                    const produtoId = produto['id.produto'] || produto.id; 
                    if (produtoId == idAtual) return; 

                    const item = document.createElement("div");
                    item.style.display = "flex";
                    item.style.alignItems = "center";
                    item.style.cursor = "pointer";
                    item.style.marginBottom = "10px";
                    item.onclick = () => abrirProduto(produtoId); 

                    const img = document.createElement("img");
                    img.src = produto.imagem;
                    img.alt = produto.nome;
                    img.style.width = "40px";
                    img.style.height = "40px";
                    img.style.borderRadius = "5px";
                    img.style.marginRight = "10px";

                    const nome = document.createElement("span");
                    nome.textContent = produto.nome;

                    item.appendChild(img);
                    item.appendChild(nome);
                    container.appendChild(item);
                });
            });
    } 
    function fecharProdutoModal() {
        document.getElementById("produtoModal").style.display = "none";
    }   
    // javascript da categorias //
    function carregarCategoria(categoria) {
        
        document.getElementById("conteudoPrincipal").style.display = "none";
        
        const conteudoCat = document.getElementById("conteudoCategoria");
        conteudoCat.style.display = "block";

        
        document.getElementById("tituloCategoria").textContent = categoria.charAt(0).toUpperCase() + categoria.slice(1);

        
        fetch(`get_categoria.php?categoria=${categoria}`)
            .then(res => res.json())
            .then(produtos => {
                const container = document.getElementById("produtosContainer");
                container.innerHTML = "";

                if (produtos.erro) {
                    container.innerHTML = `<p style="color:red;">${produtos.erro}</p>`;
                    return;
                }

                if (produtos.length === 0) {
                    container.innerHTML = "<p>Nenhum produto encontrado.</p>";
                    return;
                }

                produtos.forEach(p => {
                    const card = document.createElement("div");
                    card.classList.add("produto-card");

                    const img = document.createElement("div");
                    img.classList.add("produto-imagem");
                    img.style.backgroundImage = `url(${p.imagem})`;
                    
                    
                    const produtoId = p['id.produto'] || p.id;
                    card.onclick = () => abrirProduto(produtoId);


                    const info = document.createElement("div");
                    info.classList.add("produto-info");

                    const nome = document.createElement("div");
                    nome.classList.add("produto-nome");
                    nome.textContent = p.nome;

                    const desc = document.createElement("div");
                    desc.classList.add("produto-descricao");
                    desc.textContent = p.descricao;

                    const preco = document.createElement("div");
                    preco.classList.add("produto-preco");
                    preco.textContent = `R$ ${parseFloat(p.preco).toFixed(2)}`;

                    // Botão de comprar
                    const botao = document.createElement("button");
                    botao.classList.add("produto-btn-comprar");
                    botao.textContent = "Comprar";
                    
                    botao.onclick = (event) => {
                        event.stopPropagation(); 
                        adicionarAoCarrinhoDireto(produtoId, p.nome, p.preco);
                    };

                    info.appendChild(nome);
                    info.appendChild(desc);
                    info.appendChild(preco);
                    info.appendChild(botao);

                    card.appendChild(img);
                    card.appendChild(info);

                    container.appendChild(card);
                });
            })
            .catch(err => {
                console.error("Erro ao carregar categoria:", err);
                document.getElementById("produtosContainer").innerHTML = "<p>Erro ao carregar produtos.</p>";
            });
    }

    function voltarPagina() {
        document.getElementById("conteudoCategoria").style.display = "none";
        document.getElementById("conteudoPrincipal").style.display = "flex";
    }

    // pesquisa

    document.getElementById("formPesquisa").addEventListener("submit", function(e) {
        e.preventDefault(); 

        const termo = document.getElementById("campoPesquisa").value.trim();

        if (termo === "") {
            alert("Digite algo para pesquisar!");
            return;
        }

        
        const principal = document.querySelector(".principal");
        if (principal) principal.style.display = "none";

        // Mostra o container da categoria (resultados)
        const conteudoCat = document.getElementById("conteudoCategoria");
        conteudoCat.style.display = "block";

        // Atualiza o título
        document.getElementById("tituloCategoria").textContent = `Resultados para "${termo}"`;

        // Busca produtos no PHP
        fetch(`get_pesquisa.php?q=${encodeURIComponent(termo)}`)
            .then(res => res.json())
            .then(produtos => {
                const container = document.getElementById("produtosContainer");
                container.innerHTML = "";

                if (produtos.erro) {
                    container.innerHTML = `<p style="color:red;">${produtos.erro}</p>`;
                    return;
                }

                if (produtos.length === 0) {
                    container.innerHTML = "<p>Nenhum produto encontrado.</p>";
                    return;
                }

                produtos.forEach(p => {
                    const produtoId = p['id.produto'] || p.id;
                    
                    const card = document.createElement("div");
                    card.classList.add("produto-card");
                    card.onclick = () => abrirProduto(produtoId); 
                    const img = document.createElement("div");
                    img.classList.add("produto-imagem");
                    img.style.backgroundImage = `url(${p.imagem})`;

                    const info = document.createElement("div");
                    info.classList.add("produto-info");

                    const nome = document.createElement("div");
                    nome.classList.add("produto-nome");
                    nome.textContent = p.nome;

                    const desc = document.createElement("div");
                    desc.classList.add("produto-descricao");
                    desc.textContent = p.descricao;

                    const preco = document.createElement("div");
                    preco.classList.add("produto-preco");
                    preco.textContent = `R$ ${parseFloat(p.preco).toFixed(2)}`;

                    const botao = document.createElement("button");
                    botao.classList.add("produto-btn-comprar");
                    botao.textContent = "Comprar";
                    
                    botao.onclick = (event) => {
                        event.stopPropagation(); 
                        adicionarAoCarrinhoDireto(produtoId, p.nome, p.preco);
                    };

                    info.appendChild(nome);
                    info.appendChild(desc);
                    info.appendChild(preco);
                    info.appendChild(botao);

                    card.appendChild(img);
                    card.appendChild(info);
                    container.appendChild(card);
                });
            })
            .catch(err => {
                console.error("Erro ao pesquisar:", err);
                document.getElementById("produtosContainer").innerHTML =
                    "<p>Erro ao carregar resultados.</p>";
            });
    });
    //menu do perfil
    function toggleDropdown() {
        const menu = document.getElementById('perfilMenu');
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    }

    
    window.addEventListener('click', function(e) {
        const icone = document.querySelector('.perfil-icone');
        const menu = document.getElementById('perfilMenu');

        
        if (!icone.contains(e.target) && !menu.contains(e.target)) {
            menu.style.display = 'none';
        }
    });

    window.produtoModalAtual = null;

    
    function definirProdutoModal(produto) {
        
        window.produtoModalAtual = produto;

        
        document.getElementById("modalTitulo").textContent = produto.nome;
        document.getElementById("modalImagem").src = produto.imagem;
        document.getElementById("modalDescricao").textContent = produto.descricao;
        document.getElementById("modalPreco").textContent = parseFloat(produto.preco || 0).toFixed(2);
        document.getElementById("modalQtd").textContent = produto.quantidade || 0;
        document.getElementById("produtoModal").style.display = "flex";
    }

    
    function comprarModal() {
        const p = window.produtoModalAtual;
        if (!p || (!p.id && !p['id.produto'] && !p['id_produto'])) {
            alert('Produto inválido.');
            return;
        }

        
        const id = p.id ?? p['id.produto'] ?? p['id_produto'];

        
        fetch('adicionar_carrinho.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                id: id,
                nome: p.nome,
                preco: p.preco ?? p.preco_formatado ?? 0,
                quantidade: 1
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.sucesso || data.status === 'sucesso') {
                alert(data.mensagem ?? 'Adicionado ao carrinho!');
                
                document.getElementById("produtoModal").style.display = "none";
            } else if (data.erro || data.status === 'erro') {
                const msg = data.erro ?? data.mensagem ?? 'Erro ao adicionar.';
                alert(msg);
                
                if (msg.toLowerCase().includes('logado') || msg.toLowerCase().includes('login')) {
                    window.location.href = 'login.php?form=login';
                }
            } else {
                console.log('Resposta inesperada:', data);
                alert('Resposta inesperada do servidor.');
            }
        })
        .catch(err => {
            console.error('Erro no fetch adicionar_carrinho:', err);
            alert('Erro de rede. Veja o console.');
        });
    }

    
    function adicionarAoCarrinhoDireto(id, nome, preco, quantidade = 1) {
        fetch('adicionar_carrinho.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                id: id,
                nome: nome,
                preco: preco,
                quantidade: quantidade
            })
        })
        .then(r => r.json())
        .then(data => {
            if (data.sucesso || data.status === 'sucesso') {
                alert(data.mensagem ?? 'Adicionado ao carrinho!');
            } else {
                const msg = data.erro ?? data.mensagem ?? 'Erro ao adicionar.';
                alert(msg);
                if (msg.toLowerCase().includes('logado') || msg.toLowerCase().includes('login')) {
                    window.location.href = 'login.php?form=login';
                }
            }
        })
        .catch(err => {
            console.error('Erro no fetch adicionar_carrinho:', err);
            alert('Erro de rede. Veja o console.');
        });
    }
        </script>
        

</body>
</html>