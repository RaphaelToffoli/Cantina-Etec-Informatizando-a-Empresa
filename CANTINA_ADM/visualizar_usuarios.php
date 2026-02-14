<?php
session_start();
include('config.php'); 


if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['nivel_acesso'] !== 'administrador') {
    header("Location: login.php?modo=login");
    exit();
}

$usuarios = [];
$mensagem = '';

try {
    
    $sql = "SELECT `id.usuario`, nome, email, nivel_acesso FROM usuario ORDER BY nivel_acesso DESC, nome ASC";
    $stmt = $pdo->query($sql);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $mensagem = "Erro ao carregar usuários: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Visualizar Usuários</title>
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .tabela-usuarios { 
            width: 90%; 
            margin: 60px 
            auto;
            margin-bottom:650px;
            border-collapse: collapse; 
        }
        .tabela-usuarios th, .tabela-usuarios td { 
            border: 1px solid #ddd; 
            padding: 12px; text-align: left;
            background-color:white;
        }
        .tabela-usuarios th { 
            background-color: white; 
        }
    </style>
</head>
<body>
    <header class="cabecalho">
        <h1>Lista de Usuários Cadastrados</h1>
        <a href="index.php" class="btn sair-btn"> ← Voltar ao Painel</a>
    </header>

    <main>
        <?php if (!empty($mensagem)): ?>
            <div style="color: red; text-align: center; margin-bottom: 15px;"><?= htmlspecialchars($mensagem) ?></div>
        <?php elseif (empty($usuarios)): ?>
            <p style="text-align: center;">Nenhum usuário cadastrado.</p>
        <?php else: ?>
            <table class="tabela-usuarios">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Nível de Acesso</th>
                        </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id.usuario']) ?></td>
                        <td><?= htmlspecialchars($user['nome']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars(ucfirst($user['nivel_acesso'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
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