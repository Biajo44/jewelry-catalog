<?php
session_start();
require '../conexao.php';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $stmt = $conexao->prepare('SELECT id_revendedor, nome, senha FROM revendedor WHERE email = ? LIMIT 1');
    $stmt->bind_param('s',$email);
    $stmt->execute();
    $resultado = $stmt->get_result()->fetch_assoc();
    if ($resultado && password_verify($senha, $resultado['senha'])) {
        $_SESSION['id_revendedor'] = $resultado['id_revendedor'];
        $_SESSION['revendedor_nome'] = $resultado['nome'];
        header('Location: painel.php');
        exit();
    } else {
        $msg = 'Credenciais inválidas.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Magold</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <main class="loginbox">
  <h2>Login Revendedor</h2>

  <?php if($msg): ?>
    <div class="msg error"><?php echo $msg; ?>
        
    </div><?php endif; ?>

  <form method="post">
    <label>Email:
        <input type="email" name="email" required>
    </label>

     <br>
   <br>

    <label>Senha:
        <input type="password" name="senha" required>
    </label>

     <br>
    <br>

    <button type="submit" class="btn2" >Entrar</button>
  </form>
</main>
</body>
</html>