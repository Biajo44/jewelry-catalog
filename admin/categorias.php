<?php
session_start();
require '../conexao.php';
if (!isset($_SESSION['id_revendedor'])) {
  header('Location: login.php');
  exit();
}

$msg='';

  // Codigo de adicionar categorias //
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nome'])) {
 $nome = $conexao->real_escape_string($_POST['nome']);
 $conexao->query("INSERT INTO categoria (nome_categoria) VALUES ('".$nome."')");
 $msg = 'Categoria adicionada.';
}

  // Codigo da remoção de categoria e produto //
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_categoria']) && !isset($_POST['nome'])) {
  foreach ($_POST['id_categoria'] as $id) {
  $id=(int)$id;

 if ($id == 0) continue;

 $conexao->query("DELETE FROM joias WHERE id_categoria = $id"); // remoção do produtos primeiro //

 $conexao->query("DELETE FROM categoria WHERE id_categoria = $id"); // remoção da categoria //
  } 
    
    $msg='Categoria e produtos excluídos com sucesso!';
}

$cats = $conexao->query('SELECT * FROM categoria ORDER BY nome_categoria');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Categorias - Magold</title>
	<link rel="stylesheet" href="../css/style.css">
</head>

<body>

	<header class="top">
		<h1>Categorias</h1>
    <br>
		<nav>
			<a href="painel.php">Voltar</a>
		</nav>
	</header>

<main class="container">
  <?php if($msg): ?>
    <div class="msg success"><?php echo $msg; ?>
    </div><?php endif; ?>
  <form method="post" class="filters">

    <label>Nova categoria:
      <input type="text" name="nome" required>
    </label>

    <button type="submit" class="btn2">Adicionar</button>
  </form>

  <form method="post">
    <ul>
      <?php while ($c=$cats->fetch_assoc()):?> 
    <li>
     <label style="cursor: pointer;">
      <input type="checkbox" name="id_categoria[]" value="<?php echo $c['id_categoria'];?>">
      <?php echo htmlspecialchars($c['nome_categoria']);?>
  </label>
 </li>
    <?php endwhile;?>
    </ul>

  <button type="submit" class="btn2" onclick="return confirm ('Tem certeza que deseja excluir esta categoria?')">Excluir</button>
   </form>
   
</main>

</body>
</html>