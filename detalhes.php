<?php
require 'conexao.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $conexao->prepare('SELECT j.*, c.nome_categoria FROM joias j LEFT JOIN categoria c ON j.id_categoria = c.id_categoria WHERE j.id_joia = ?');
$stmt->bind_param('i',$id);
$stmt->execute();
$resultado = $stmt->get_result();
$j = $resultado->fetch_assoc();
if (!$j) { header('Location: index.php'); 
exit; 
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Magold</title>

	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<header class="top">

    <h1>Magold - Semijoias ✨</h1>
    <br>
    <nav>
      <a href="index.php">Catálogo</a> |
      <a href="contato.php">Contato</a>
    </nav>
  </header>

  <!----- Nome, preço, imagem e descrição ----->
<main class="container detail">
  <div class="detail-left">
    <img src="<?php echo htmlspecialchars($j['imagem'] ?: 'imgs/placeholder.png'); ?>" alt="">
  </div>

  <div class="detail-right">
      
    </p>
    <p class="price">R$ <?php echo number_format($j['preco'],2,',','.'); ?>
      
    </p>
    <p><?php echo nl2br(htmlspecialchars($j['descricao'])); ?>
      
    </p>
    <?php
$base_url = "http://localhost/revendedor_magold/";
$link_imagem = $base_url . $j['imagem'];

$mensagem = 
"Olá! Tenho interesse neste produto:%0A" 

. "*Categoria:* " 
. urlencode($j['nome_categoria']) . "%0A" 

. "*Preço:* R$ " 
. number_format($j['preco'], 2, ',', '.') . "%0A" 

. "*Descrição:* " 
. urlencode($j['descricao']) . "%0A%0A" 

. "Link da imagem:%0A"
. urlencode($link_imagem) . "%0A%0A"

. "Pode me enviar mais detalhes?";
?>
<a href="https://wa.me/5591986286027?text=<?=$mensagem?>" class="btn">Comprar pelo WhatsApp</a>

  </div>
</main>


  <!----- rodape ----->
<footer class="foot">Magold &copy; 2025 - Desenvolvido por Joelma - Revendedora </footer>

</body>
</html>