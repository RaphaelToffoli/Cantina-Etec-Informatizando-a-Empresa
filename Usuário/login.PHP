<?php
include('config.php'); 

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    if (isset($_POST['cadastrar'])) {
        $nome = trim ($_POST['nome']);
        $email = trim ($_POST['email']);
        $senha_digitada = trim ($_POST['senha']);
        $senha = password_hash($senha_digitada, PASSWORD_DEFAULT);

        
        $sql_email = "SELECT * FROM usuario WHERE email = ?";
        $stmt = $pdo->prepare($sql_email);
        $stmt->execute([$email]);
        $result_email = $stmt->fetch();

        if ($result_email) {
            $mensagem_erro = "Já existe um usuário cadastrado com este email";
        } else {
            
            $sql = "INSERT INTO usuario (nome, email, senha) VALUES (?, ?, ?)";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([$nome, $email, $senha])) {
                 $id = $pdo->lastInsertId();

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['usuario'] = [
                    'id' => $id,
                    'nome' => $nome,
                    'email' => $email
                ];

                header("Location: index.php");
                exit();
            } else {
                $mensagem_erro = "Erro ao cadastrar o usuário.";
            }
        }
    }
    // Login
   elseif (isset($_POST['login'])) {
        $email = trim ($_POST['email']);
        $senha = trim ($_POST['senha']);

        $sql = "SELECT * FROM usuario WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            if (password_verify($senha, $row['senha'])) {
                $_SESSION['usuario'] = [
                    'id' => $row['id.usuario'] ?? $row['id'] ?? null,
                    'nome' => $row['nome'],
                    'email' => $row['email']
                ];
                header("Location: index.php");
                exit();
            } else {
                $mensagem_erro = "SENHA INCORRETA.";
            }
        } else {
            $mensagem_erro = "USUÁRIO NÃO ENCONTRADO.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar/Cadastrar</title>
    <link rel="stylesheet" href="styles/login.css">
</head>
<body>
    <header class="cabecalho" >
          <a class="logo"></a>
    </header>
    <main class="conteudo" >
        <div class="container">
            <div class="tabs">
            <button id="loginTab" class="active" onclick="showForm('login')">Login</button>
            <button id="registerTab" onclick="showForm('register')">Cadastro</button>
        </div>

        <div class="form-container">
            <form id="loginForm" class="active" method="POST" action="">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <button type="submit" name="login" >Entrar</button>
            </form>

            <form id="registerForm" method="POST" action="">
                <input type="text" name="nome" placeholder="Nome" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <button type="submit" name="cadastrar" >Cadastrar</button>
            </form>
        </div>
    </div>

    <div id="mensagemModal" class="modal">
      <div class="modal-conteudo">
        <span class="fechar" onclick="fecharModal()">&times;</span>
        <p id="mensagemTexto"></p>
      </div> 
    </div>
    <script>
  window.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const formType = urlParams.get('form');
    if (formType === 'cadastrar') {
      showForm('register');
    } else {
      showForm('login');
    }
  });
</script>
<script>
  function showForm(formType) {
    const loginTab = document.getElementById('loginTab');
    const registerTab = document.getElementById('registerTab');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    if (formType === 'login') {
      loginTab.classList.add('active');
      registerTab.classList.remove('active');
      loginForm.classList.add('active');
      registerForm.classList.remove('active');
    } else {
      loginTab.classList.remove('active');
      registerTab.classList.add('active');
      loginForm.classList.remove('active');
      registerForm.classList.add('active');
    }
  }
  function mostrarMensagem(texto) {
    document.getElementById('mensagemTexto').textContent = texto;
    document.getElementById('mensagemModal').style.display = "block";
  }

  function fecharModal() {
    document.getElementById('mensagemModal').style.display = "none";
  }
</script>
<?php if (!empty($mensagem_erro)) : ?>
  <script>
    mostrarMensagem("<?php echo $mensagem_erro; ?>");
  </script>
<?php endif; ?>
    </main>
</body>
</html>