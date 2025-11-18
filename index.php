<?php
require 'conexao.php';
$categoria = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;

$where = [];
if ($categoria) $where[] = "j.id_categoria = $categoria";
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

  // Listagem joias e categorias//
$sql = "SELECT j.*, c.nome_categoria FROM joias j LEFT JOIN categoria c ON j.id_categoria = c.id_categoria $where_sql ORDER BY j.id_joia DESC";
$resultado = $conexao->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Magold - Catálogo 💍</title>

	<link rel="stylesheet" type="text/css" href="css/style.css">

  
</head>
<body>

  <!----- Topo Menu ----->
	<header class="top">
		<h1>Magold - Semijoias ✨</h1>
    <br>

		<nav>
      <a href="index.php">Catálogo </a> | 
      <a href="contato.php"> Contatos </a>| 
      <a  href="admin/login.php"> Painel</a> 
    </nav>

	</header>

  <!----- Campo de pesquisa ----->
	<main class="container">
  <aside class="filters">
    <form method="get">

      <select name="categoria" onchange="this.form.submit()">
        <option value="0">Todas categorias</option>
        <?php
        $cats = $conexao->query('SELECT * FROM categoria');
        while($c = $cats->fetch_assoc()){
            $sel = ($c['id_categoria']==$categoria)?'selected':'';
            echo "<option value='{$c['id_categoria']}' $sel>".htmlspecialchars($c['nome_categoria'])."</option>";
        }
        ?>
      </select>
      <button type="submit" class="btn2">Filtrar</button>
    </form>
  </aside>
<br>

  <!----- Produtos ----->
      <!----- preço, imagem e descrição ----->
  <section class="grid">
    <?php while($j = $resultado->fetch_assoc()): ?>

      <article class="card">
        <img src="<?php echo htmlspecialchars($j['imagem'] ?: 'imgs/placeholder.png'); ?>">

        <p class="cat"><?php echo htmlspecialchars($j['nome_categoria']); ?></p>

        <p class="price">R$ <?php echo number_format($j['preco'],2,',','.'); ?></p>

        <p>
          <a href="detalhes.php?id=<?php echo $j['id_joia']; ?>" class="btn">Ver detalhes</a>
        </p>
      </article>

    <?php endwhile; ?>


  </section>
</main>

  <!----- rodape ----->
<footer class="foot">Magold &copy; 2025 - Desenvolvido por Joelma - Revendedora </footer>


<!----- botão para cima ----->
<button id="btnTop">△</button>


<script src="js/script.js"></script>

</body>
</html>