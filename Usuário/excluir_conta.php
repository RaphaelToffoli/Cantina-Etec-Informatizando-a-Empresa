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


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmar_exclusao'])) {
    
    
    $senha_confirmacao = trim ($_POST['senha_confirmacao'] ?? '');

    
    $sql_check = "SELECT senha FROM usuario WHERE `id.usuario` = ?"; 
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$user_id]);
    $usuario = $stmt_check->fetch(PDO::FETCH_ASSOC);

    
    if (!$usuario || !password_verify($senha_confirmacao, $usuario['senha'])) {
        $mensagem_erro = "Senha incorreta. A exclusão da conta foi cancelada.";
    } else {
        
        try {
            
            $sql_delete = "DELETE FROM usuario WHERE `id.usuario` = ?";
            $stmt_delete = $pdo->prepare($sql_delete);
            
            if ($stmt_delete->execute([$user_id])) {
                
                session_unset();
                session_destroy();
                
                header("Location: login.php?status=deleted");
                exit();
            } else {
                $mensagem_erro = "Erro interno ao excluir a conta. Tente novamente.";
            }
        } catch (PDOException $e) {
            $mensagem_erro = "Erro no banco de dados. Detalhe: " . $e->getMessage();
        }
    }
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Configurações da Conta</title>
    <link rel="stylesheet" href="styles/configuracoes.css" >
    </head>
<body>
   <script src="https://unpkg.com/lucide@latest"></script>

<div class="conteudo-max-largura">
    <a href="index.php" class="botao-voltar" >
        <i data-lucide="chevron-left" style="width: 20px; height: 20px; margin-right: 5px;"></i>
            Voltar para a pagina inicial
    </a>
    <div class="header-secao">
        <div class="header-titulo-container">
            <i data-lucide="settings" class="icone-settings"></i>
            <h1 class="titulo-principal">Configurações da Conta</h1>
        </div>
        <p class="header-subtitulo">Gerencie suas preferências e configurações de privacidade</p>
    </div>

    <?php if (!empty($mensagem_erro)): ?>
        <p style="color: red; padding: 10px 15px; background-color: #fef2f2; border-left: 4px solid #ef4444; border-radius: 4px; margin-bottom: 20px;"><?php echo $mensagem_erro; ?></p>
    <?php endif; ?>

    <div class="cards-container">
        <a href="editar_perfil.php" class="card-item">
            <div class="card-conteudo">
                <div class="card-icone-container">
                    <i data-lucide="user" style="width: 24px; height: 24px; color: var(--cor-laranja-600);"></i>
                </div>
                <div>
                    <h3 class="card-titulo">Informações Pessoais</h3>
                    <p class="card-descricao">Edite seu nome, email e senha</p>
                </div>
            </div>
            <i data-lucide="chevron-right" style="width: 24px; height: 24px; color: #9ca3af;"></i>
        </a>
    </div>

    <!-- <div class="secao-exclusao">
        <div class="exclusao-header-container">
            <div class="exclusao-icone-bg">
                <i data-lucide="trash-2" style="width: 28px; height: 28px; color: var(--cor-vermelho-600);"></i>
            </div>
            <div>
                <h2 class="exclusao-titulo">Excluir Conta</h2>
                <p class="exclusao-descricao">
                    Esta ação é <span class="exclusao-irreversivel">irreversível</span> e excluirá todos os seus dados. Por favor, prossiga com cautela.
                </p>
            </div>
        </div>

        <div class="alerta-exclusao">
            <div class="alerta-conteudo">
                <i data-lucide="alert-triangle" class="alerta-icone"></i>
                <div>
                    <p class="alerta-titulo">Atenção!</p>
                    <p class="alerta-texto-principal">Ao excluir sua conta, você perderá permanentemente:</p>
                    <ul class="alerta-lista">
                        <li>Histórico de pedidos</li>
                        <li>Dados pessoais e preferências</li>
                        <li>Pontos de fidelidade acumulados</li>
                    </ul>
                </div>
            </div>
        </div>

        <button
            id="abrirModalExclusao"
            class="botao-exclusao"
        >
            Excluir Minha Conta Permanentemente
        </button> -->
    </div>
</div>
<div id="modalExclusao" class="modal-overlay">
    <div class="modal-container">
        <span onclick="fecharModalExclusao()" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer; line-height: 1;">&times;</span>
        
        <div class="modal-header">
            <div class="modal-icone-bg">
                <i data-lucide="alert-triangle" style="width: 32px; height: 32px; color: var(--cor-vermelho-600);"></i>
            </div>
            <h3 class="modal-titulo">Confirmação de Exclusão de Conta</h3>
            <p class="modal-descricao">Tem certeza de que deseja excluir sua conta? **Esta ação não pode ser desfeita.**</p>
        </div>
        
        <form method="POST" action="excluir_conta.php" class="modal-form">
            <label for="senha_confirmacao">Digite sua senha para confirmar:</label>
            <input type="password" id="senha_confirmacao" name="senha_confirmacao" required>
            
            <div class="modal-botoes">
                <button type="button" onclick="fecharModalExclusao()" class="botao-cancelar">
                    Cancelar
                </button>
                <button type="submit" name="confirmar_exclusao" class="botao-confirmar-exclusao-modal">
                    SIM, EXCLUIR MINHA CONTA
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    
    lucide.createIcons();
    
    
    /*
    const modal = document.getElementById('modalExclusao');
    const btn = document.getElementById('abrirModalExclusao');
    
    function fecharModalExclusao() {
        modal.style.display = "none";
    }

    btn.onclick = function() {
        modal.style.display = "flex"; // Modifiquei de "block" para "flex" para centralizar
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            fecharModalExclusao();
        }
    }
    
    
    */
</script>
<script>
    const modal = document.getElementById('modalExclusao');
    const btn = document.getElementById('abrirModalExclusao');
    const span = document.getElementsByClassName("close")[0];

    
    btn.onclick = function() {
      modal.style.display = "block";
    }

    
    function fecharModalExclusao() {
      modal.style.display = "none";
    }

    
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
</script>
    </body>
</html>