<?php
include('config.php'); 
session_start();


if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['usuario']['id'];
$mensagem_erro = '';
$mensagem_sucesso = '';
$dados_usuario = []; 


try {
   
    $sql_load = "SELECT nome, email, senha FROM usuario WHERE `id.usuario` = ?";
    $stmt_load = $pdo->prepare($sql_load);
    $stmt_load->execute([$user_id]);
    $dados_usuario = $stmt_load->fetch(PDO::FETCH_ASSOC);

    if (!$dados_usuario) {
        $mensagem_erro = "Dados do usuário não encontrados.";
    }
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao carregar dados do usuário: " . $e->getMessage();
}


$nome_atual = $dados_usuario['nome'] ?? '';
$email_atual = $dados_usuario['email'] ?? '';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['atualizar_perfil'])) {
    
    
    $novo_nome = trim($_POST['nome'] ?? '');
    $novo_email = trim($_POST['email'] ?? '');
    
    
    $senha_atual_digitada = $_POST['senha_atual'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';
    $confirma_nova_senha = $_POST['confirma_nova_senha'] ?? '';

    
    if (empty($novo_nome) || empty($novo_email)) {
        $mensagem_erro = "Nome e E-mail são campos obrigatórios.";
    } elseif (!filter_var($novo_email, FILTER_VALIDATE_EMAIL)) {
        $mensagem_erro = "Formato de e-mail inválido.";
    }
    
    
    $alterar_senha = !empty($senha_atual_digitada) || !empty($nova_senha) || !empty($confirma_nova_senha);

    if (empty($mensagem_erro) && $alterar_senha) {
        if (empty($senha_atual_digitada) || empty($nova_senha) || empty($confirma_nova_senha)) {
            $mensagem_erro = "Para alterar a senha, você deve preencher a Senha Atual, a Nova Senha e a Confirmação da Nova Senha.";
        } elseif ($nova_senha !== $confirma_nova_senha) {
            $mensagem_erro = "A Nova Senha e a Confirmação da Nova Senha não coincidem.";
        } elseif (strlen($nova_senha) < 6) { 
            $mensagem_erro = "A nova senha deve ter pelo menos 6 caracteres.";
        } elseif (!password_verify($senha_atual_digitada, $dados_usuario['senha'])) {
            $mensagem_erro = "A Senha Atual informada está incorreta.";
        }
    }
    
    
    if (empty($mensagem_erro)) {
        $params = [];
        $sql_update = "UPDATE usuario SET nome = ?, email = ?";
        $params[] = $novo_nome;
        $params[] = $novo_email;
        
        
        if ($alterar_senha) {
            $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $sql_update .= ", senha = ?";
            $params[] = $nova_senha_hash;
        }
        
        $sql_update .= " WHERE `id.usuario` = ?";
        $params[] = $user_id;

        try {
            $stmt_update = $pdo->prepare($sql_update);
            
            if ($stmt_update->execute($params)) {
                $mensagem_sucesso = "Seu perfil e/ou senha foram atualizados com sucesso!";
                
                
                $nome_atual = $novo_nome;
                $email_atual = $novo_email;
                $_SESSION['usuario']['nome'] = $novo_nome; 
                
            } else {
                $mensagem_erro = "Nenhuma alteração foi feita ou ocorreu um erro interno.";
            }
        } catch (PDOException $e) {
            
            if ($e->getCode() == '23000') {
                $mensagem_erro = "O e-mail fornecido já está em uso por outra conta.";
            } else {
                $mensagem_erro = "Erro no banco de dados ao atualizar: " . $e->getMessage();
            }
        }
    }

    
    if (!empty($mensagem_erro)) {
        $nome_atual = $novo_nome;
        $email_atual = $novo_email;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="stylesheet" href="styles/editarperfil.css" >
</head>
<body>

<div class="conteudo-max-largura">

    <a href="excluir_conta.php" class="back-link">
        <i data-lucide="chevron-left" style="width: 20px; height: 20px; margin-right: 5px;"></i>
        Voltar para Configurações
    </a>

    <div class="header-secao">
        <div class="header-titulo-container">
            <i data-lucide="user" class="icone-settings"></i>
            <h1 class="titulo-principal">Editar Perfil e Senha</h1>
        </div>
        <p class="header-subtitulo">Atualize suas informações pessoais e credenciais de login.</p>
    </div>

    <?php if (!empty($mensagem_erro)): ?>
        <p class="mensagem-erro"><?php echo $mensagem_erro; ?></p>
    <?php endif; ?>

    <?php if (!empty($mensagem_sucesso)): ?>
        <p class="mensagem-sucesso"><?php echo $mensagem_sucesso; ?></p>
    <?php endif; ?>

    <div class="card-edicao">
        <form method="POST" action="editar_perfil.php">

            <div class="form-section">
                <h3>Informações Pessoais</h3>
                
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome_atual); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email_atual); ?>" required>
                </div>
            </div>
            
            <div class="form-section">
                <h3>Alterar Senha</h3>
                <p style="color: var(--cor-gray-600); font-size: 14px; margin-bottom: 15px;">Preencha os campos abaixo apenas se desejar mudar sua senha.</p>
                
                <div class="form-group">
                    <label for="senha_atual">Senha Atual</label>
                    <input type="password" id="senha_atual" name="senha_atual" placeholder="Necessário para confirmar a alteração de senha">
                </div>

                <div class="form-group">
                    <label for="nova_senha">Nova Senha</label>
                    <input type="password" id="nova_senha" name="nova_senha" placeholder="Mínimo 6 caracteres">
                </div>

                <div class="form-group">
                    <label for="confirma_nova_senha">Confirme Nova Senha</label>
                    <input type="password" id="confirma_nova_senha" name="confirma_nova_senha">
                </div>
            </div>

            <button type="submit" name="atualizar_perfil" class="botao-salvar">
                Salvar Alterações
            </button>
        </form>
    </div>

</div>

<script>
    lucide.createIcons();
</script>

</body>
</html>