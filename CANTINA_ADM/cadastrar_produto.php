<?php
session_start();
include('config.php'); 


if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['nivel_acesso'] !== 'administrador') {
    header("Location: login.php?modo=login");
    exit();
}

$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'] ?? '';
    $quantidade_estoque = intval($_POST['quantidade_estoque'] ?? 0);
    $preco = floatval($_POST['preco'] ?? 0.0);
    $descricao = $_POST['descricao'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $status_produto = 1; // 1 = Ativo
    $data_criacao = date('Y-m-d');
    
    $nome_imagem = '';
    
    
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $nome_imagem = $upload_dir . basename($_FILES['imagem']['name']);
        
        move_uploaded_file($_FILES['imagem']['tmp_name'], $nome_imagem);
    }

    if (empty($nome) || $preco <= 0 || empty($categoria)) {
        $mensagem = "Por favor, preencha o nome, preço e categoria do produto.";
    } else {
        try {
            $sql = "INSERT INTO produto (nome, quantidade_estoque, data_criacao, status_produto, preco, descricao, categoria, imagem) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute([$nome, $quantidade_estoque, $data_criacao, $status_produto, $preco, $descricao, $categoria, $nome_imagem])) {
                $_SESSION['mensagem'] = "Produto '{$nome}' cadastrado com sucesso!";
                header("Location: index.php");
                exit();
            } else {
                $mensagem = "Erro ao cadastrar o produto no banco de dados.";
            }

        } catch (PDOException $e) {
            $mensagem = "Erro: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        main{
            margin-bottom:410px;
        }
    </style>
</head>
<body>
    <header class="cabecalho">
        <h1>Cadastro de Novo Produto</h1>
        <a href="index.php" class="btn sair-btn"> ← Voltar ao Painel</a>
    </header>

    <main>
        <?php if (!empty($mensagem)): ?>
            <div style="color: red; text-align: center; margin-bottom: 15px;"><?= htmlspecialchars($mensagem) ?></div>
        <?php endif; ?>

        <form action="cadastrar_produto.php" method="POST" enctype="multipart/form-data" class="form-cadastro">
            <input type="text" name="nome" placeholder="Nome do Produto" required>
            <input type="number" name="quantidade_estoque" placeholder="Estoque Inicial" required min="0">
            <input type="number" name="preco" step="0.01" placeholder="Preço (ex: 5.00)" required min="0.01">
            
            <select name="categoria" required>
                <option value="">Selecione a Categoria</option>
                <option value="salgados">Salgados</option>
                <option value="bebidas">Bebidas</option>
                <option value="salgadinhos">Salgadinhos</option>
                <option value="doces">Doces</option>
                <option value="especiais">Especiais</option>
            </select>
            
            <textarea name="descricao" placeholder="Descrição do Produto"></textarea>
            
            <label for="imagem">Imagem do Produto:</label>
            <input type="file" name="imagem" id="imagem" accept="image/*">

            <button type="submit" class="btn-submit">Cadastrar Produto</button>
        </form>
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
</body>
</html>