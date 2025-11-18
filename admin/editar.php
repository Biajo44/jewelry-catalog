<?php

session_start();
require '../conexao.php';

if (!isset($_SESSION['id_revendedor'])) header('Location:login.php');

$id = isset($_GET['id'])?(int)$_GET['id']:0;
$msg='';

$stmt = $conexao->prepare('SELECT * FROM joias WHERE id_joia = ?');
$stmt->bind_param('i',$id); 
$stmt->execute(); 
$j = $stmt->get_result()->fetch_assoc();

if (!$j) { header('Location: login.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $conexao->real_escape_string($_POST['descricao']);
    $preco = (float)$_POST['preco'];
    $id_categoria = (int)$_POST['id_categoria'];
    $imagem = $j['imagem'];

    if (!empty($_FILES['imagem']['tmp_name'])) {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $fn = 'imgs/joia_'.time().'.'. $ext;
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], '../'.$fn)) $imagem = $fn;
    }

    $stmt2 = $conexao->prepare('UPDATE joias SET descricao=?, preco=?, imagem=?, id_categoria=? WHERE id_joia=?');
    $stmt2->bind_param('sdsii',$descricao,$preco,$imagem,$id_categoria,$id);

    if ($stmt2->execute()) { $msg='Atualizado com sucesso.'; header('Refresh:1'); }
}
$cats = $conexao->query('SELECT * FROM categoria');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Joia - Magold</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <header class="top">
        <h1>Editar Joia</h1>
        <nav>
            <br>
            <a href="painel.php">Voltar</a>
        </nav>
    </header>

    <main class="container">
  <?php if($msg): ?>
    <div class="msg success"><?php echo $msg; ?>
    </div><?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="filters">
    <br>

    <label>Descrição:
        <textarea name="descricao"><?php echo htmlspecialchars($j['descricao']); ?>
        </textarea>
    </label>
    <br>

    <label>Preço:
        <input type="number" step="0.01" name="preco" required value="<?php echo htmlspecialchars($j['preco']); ?>">
    </label>
    <br>

    <label>Categoria:
      <select name="id_categoria" required>
        <?php while($c = $cats->fetch_assoc()): $sel = ($c['id_categoria']==$j['id_categoria'])?'selected':''; ?>
    <br>
      <option value="<?php echo $c['id_categoria']; ?>" <?php echo $sel; ?>><?php echo htmlspecialchars($c['nome_categoria']); ?>
              
    </option>
    <?php endwhile; ?>
      </select>
    </label>

    <p>Imagem atual:
        <br>
   <img src="../<?php echo htmlspecialchars($j['imagem'] ?: 'imgs/placeholder.png'); ?>" style="width:120px;
        height:120px;
        object-fit:cover">
    </p>

    <label>Substituir imagem
        <input type="file" name="imagem" accept="imgs/*">
    </label>

    <button type="submit" class="btn2">Atualizar</button>
  </form>
</main>

</body>
</html>