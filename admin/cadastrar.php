<?php
session_start();
require '../conexao.php';
if (!isset($_SESSION['id_revendedor'])) {
  header('Location: login.php');
  exit();
}

$msg='';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $conexao->real_escape_string($_POST['descricao']);
    $preco = (float)$_POST['preco'];
    $id_categoria = (int)$_POST['id_categoria'];
    
    $target = '../imgs/';
    $imagem = 'imgs/placeholder.png';
    if (!empty($_FILES['imagem']['tmp_name'])) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $fn = 'imgs/joia_'.time().'.'. $ext;
     if (move_uploaded_file($_FILES['imagem']['tmp_name'], '../'.$fn)) $imagem = $fn;
    }
    $stmt = $conexao->prepare('INSERT INTO joias (descricao, preco, imagem, id_categoria) VALUES (?,?,?,?)');
    $stmt->bind_param('sdsi',$descricao,$preco,$imagem,$id_categoria);
    if ($stmt->execute()) $msg = 'Joia cadastrada com sucesso.';
}
$cats = $conexao->query('SELECT * FROM categoria');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Cadastrar Joia — Magold</title>
	<link rel="stylesheet" href="../css/style.css">
</head>

<body>
	<header class="top">
		<h1>Cadastrar Joia</h1>
<br>

		<nav>
      <a href="painel.php">Voltar</a>
		</nav>
	</header>

<main class="container">
  <?php if($msg): ?><div class="msg success"><?php echo $msg; ?></div><?php endif; ?>

  <form class="filters" method="post" enctype="multipart/form-data" class="form-cad" >

    <br>

    <label>Descrição:
      <textarea name="descricao"></textarea>
    </label>
    <br>

    <label>Preço:
      <input type="number" step="0.01" name="preco" required>
    </label>
    <br>

    <label>Categoria:
      <select name="id_categoria" required>
        <?php while($c = $cats->fetch_assoc()): ?>
          <option value="<?php echo $c['id_categoria']; ?>"><?php echo htmlspecialchars($c['nome_categoria']); ?></option>
        <?php endwhile; ?>
      </select>
    </label>
    <br>

    <label>Imagem:
      <input type="file" name="imagem" accept="image/*">
    </label>
    <br>

    <button type="submit" class="btn2">Salvar</button>
  </form>
</main>

</body>
</html>