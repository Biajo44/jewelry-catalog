<?php
session_start();
require '../conexao.php';
if (!isset($_SESSION['id_revendedor'])) {
    header('Location: login.php');
    exit();
} 

$id = isset($_GET['id'])?(int)$_GET['id']:0;
if ($id) {
    $stmt = $conexao->prepare('DELETE FROM joias WHERE id_joia = ?');
    $stmt->bind_param('i',$id);
    $stmt->execute();
}
header('Location: painel.php');
exit();
?>