<?php
session_start();
require '../conexao.php';

// Evita cache pelo navegador //
header("Expires: 0");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (!isset($_SESSION['id_revendedor'])) {
 header('Location: login.php');
 exit();
}

  // busca das joias do banco e suas categorias //
$joias = $conexao->query('SELECT j.*, c.nome_categoria FROM joias j   LEFT JOIN categoria c ON j.id_categoria = c.id_categoria  ORDER BY j.id_joia DESC');
  // quantas tem //
$count = $joias->num_rows;
 
  // calcula o total //
$total = 0;
$resultado2 = $conexao->query('SELECT SUM(preco) AS soma FROM joias');
if ($resultado2) { 
  $resultado = $resultado2->fetch_assoc(); 
$total = $resultado['soma'] ?: 0; 
}


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
	<title>Painel - Magold</title>
	<link rel="stylesheet" href="../css/style.css">
</head>

<body>
	<header class="top">
		<h1>Magold - Painel</h1>
<br>
		<nav>
     <a href="cadastrar.php">Cadastrar Joia</a> | 
      <a href="categorias.php">Categorias</a> | 
      <a href="logout.php">Sair</a>
    </nav>
  </header>

<main class="container">
  <h2> Estojo de Joias</h2>

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

  <p>
    <strong>Joias cadastradas: <?php echo $count; ?>
    </strong> — Valor total: <strong> R$ <?php echo number_format($total,2,',','.'); ?></strong>
  </p>

  <section class="list">
    <?php while($j = $resultado->fetch_assoc()): ?>
      <div class="item">

<!----- preço, imagem e categoria ----->
 <img src="../<?php echo htmlspecialchars($j['imagem'] ?: 'imgs/placeholder.png'); ?>"alt=""
  style="width:80px;
     height:80px;
     object-fit:cover">

    <div>
 <br>
     R$ <?php echo number_format($j['preco'],2,',','.'); ?> - <?php echo htmlspecialchars($j['nome_categoria']); ?>
 </div>

     <!----- Botoes ----->
  <div class="actions">
     <a href="editar.php?id=<?php echo $j['id_joia']; ?>">Editar</a> |

   <a href="deletar.php?id=<?php echo $j['id_joia']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir
     </a>
   </div>
 </div>
    <?php endwhile; ?>
  </section>
</main>

</body>
</html>