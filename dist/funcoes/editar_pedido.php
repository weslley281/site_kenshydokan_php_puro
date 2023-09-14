<?php

include_once("../../classes/pedidos.php");
$registro = new pedido();

$id_pedido = $_POST["id_pedido"];
$id_servico = $_POST["id_servico"];
$nome = $_POST["nome"];
$telefone = $_POST["telefone"];
$descricao = $_POST["descricao"];
$prazo = $_POST["prazo"];
$valor = $_POST["valor"];
$situacao = $_POST["situacao"];

$tentativa = $registro->editar_pedido($id_pedido, $id_servico, $nome, $telefone, $descricao, $prazo, $valor, $situacao);
if ($tentativa > 0) {
    echo "<script language='javascript'>window.alert('Pedido Editado com sucesso'); </script>";
    echo "<script language='javascript'>window.location='../inicio.php'; </script>";
}else{
    echo "<script language='javascript'>window.alert('Erro ao Editar'); </script>";
    echo "<script language='javascript'>window.location='../editar_pedido.php?id=$id_pedido'; </script>";
}
