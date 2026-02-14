<?php
session_start();

include('config.php'); 

$mensagem_alerta = '';
$mensagem_sucesso = '';
$modo = $_GET['modo'] ?? 'login'; 


if ($modo === 'login' && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['acao']) && $_POST['acao'] === 'login') {
    
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        $mensagem_alerta = "Preencha todos os campos.";
    } else {
        try {
            
            $sql = "SELECT `id.usuario`, nome, email, senha, nivel_acesso FROM usuario WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario_db && password_verify($senha, $usuario_db['senha'])) {
                
                if ($usuario_db['nivel_acesso'] === 'administrador') {
                    
                    $_SESSION['logado'] = true;
                    $_SESSION['usuario'] = [
                        
                        'id' => $usuario_db['id.usuario'], 
                        'email' => $usuario_db['email'],
                        'nome' => $usuario_db['nome'],
                        'nivel_acesso' => $usuario_db['nivel_acesso']
                    ];
                    
                    header("Location: index.php");
                    exit();
                } else {
                    $mensagem_alerta = "Acesso negado. Este usuário não é um administrador.";
                }
            } else {
                $mensagem_alerta = "Email ou senha incorretos.";
            }

        } catch (PDOException $e) {
            error_log("Erro no login ADM: " . $e->getMessage());
            $mensagem_alerta = "Ocorreu um erro interno. Tente novamente.";
        }
    }
}



if ($modo === 'cadastro' && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['acao']) && $_POST['acao'] === 'cadastro') {
    
    $nome = $_POST['nome'] ?? ''; 
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    
    if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
        $mensagem_alerta = "Preencha todos os campos.";
    } elseif ($senha !== $confirmar_senha) {
        $mensagem_alerta = "As senhas não coincidem.";
    } else {
        try {
            
            $sql_check = "SELECT `id.usuario` FROM usuario WHERE email = ?";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute([$email]);
            
            if ($stmt_check->rowCount() > 0) {
                $mensagem_alerta = "Este email já está cadastrado.";
            } else {
                
                $senha_hashed = password_hash($senha, PASSWORD_DEFAULT);
                
                
                $sql_insert = "INSERT INTO usuario (nome, email, senha, nivel_acesso) VALUES (?, ?, ?, 'administrador')";
                $stmt_insert = $pdo->prepare($sql_insert);
                
                if ($stmt_insert->execute([$nome, $email, $senha_hashed])) {
                    $mensagem_sucesso = "Cadastro de administrador realizado com sucesso! Faça o login.";
                    $modo = 'login'; 
                } else {
                    $mensagem_alerta = "Erro ao cadastrar administrador. Tente novamente.";
                }
            }

        } catch (PDOException $e) {
            error_log("Erro no cadastro ADM: " . $e->getMessage());
            $mensagem_alerta = "Ocorreu um erro interno. Tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= ($modo === 'login' ? 'Login' : 'Cadastro') ?> ADM - CantinaEtec</title>
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
<div class="tela-login">
    <div class="login-box">
        <h2 class="login-titulo"><?= ($modo === 'login' ? 'Login de Administrador' : 'Cadastro de Administrador') ?></h2>

        <?php if (!empty($mensagem_alerta)): ?>
            <div class="mensagem alerta"><?= htmlspecialchars($mensagem_alerta) ?></div>
        <?php endif; ?>
        <?php if (!empty($mensagem_sucesso)): ?>
            <div class="mensagem sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
        <?php endif; ?>

        <?php if ($modo === 'login'): ?>
            <form method="POST" action="login.php?modo=login">
                <input type="hidden" name="acao" value="login">
                <input type="email" name="email" class="login-input" placeholder="Email do Administrador" required>
                <input type="password" name="senha" class="login-input" placeholder="Senha" required>
                <button class="btn login-btn" type="submit">Entrar</button>
            </form>
           

        <?php elseif ($modo === 'cadastro'): ?>
            <form method="POST" action="login.php?modo=cadastro">
                <input type="hidden" name="acao" value="cadastro">
                <input type="text" name="nome" class="login-input" placeholder="Seu Nome" required>
                <input type="email" name="email" class="login-input" placeholder="Email (Será seu login)" required>
                <input type="password" name="senha" class="login-input" placeholder="Nova Senha" required>
                <input type="password" name="confirmar_senha" class="login-input" placeholder="Confirme a Senha" required>
                <button class="btn login-btn" type="submit">Cadastrar</button>
            </form>
            <p class="mudar-modo">
                Já tem conta? <a href="login.php?modo=login">Fazer Login</a>
            </p>
        <?php endif; ?>

    </div>
</div>
</body>
</html>