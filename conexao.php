<?php
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'magoldjoias';

$conexao = new mysqli($host, $usuario, $senha, $banco);
if ($conexao->connect_error) {
	die("erro na conexão: " . $conexao->connect_error);
}

?>