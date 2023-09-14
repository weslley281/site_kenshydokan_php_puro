<?php

include_once("conexao.php");

 Class filiado{

 	public function registrar_filiado($id_graduacao, $nome, $dojo, $telefone, $rg, $email, $endereco, $cidade, $id_estado, $confirmacao){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$inserir = "INSERT INTO filiados (id_graduacao, nome, dojo, telefone, rg, email, endereco, cidade, id_estado, confirmacao) VALUES ('$id_graduacao', '$nome', '$dojo', '$telefone', '$rg', '$email', '$endereco', '$cidade', '$id_estado', '$confirmacao')";

 		var_dump($inserir);
 		$resultado = mysqli_query($conexao, $inserir);

 		return $resultado;
 	}

 	public function editar_filiado($id_filiado, $id_graduacao, $nome, $dojo, $telefone, $rg, $email, $endereco, $cidade, $id_estado, $confirmacao){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$consulta = "UPDATE filiados SET id_graduacao = '$id_graduacao', nome = '$nome', dojo = '$dojo', telefone = '$telefone', rg = '$rg', email = '$email', endereco = '$endereco', cidade = '$cidade', id_estado = '$id_estado', confirmacao = '$confirmacao' WHERE id_filiado = $id_filiado";

 		$resultado = mysqli_query($conexao, $consulta);

 		return $resultado;
 	}

 	public function excluir_filiado($id_filiado){
 		$c = new conectar();
		$conexao=$c->conexao();

		$deletar = "DELETE FROM filiados where id_filiado = '$id_filiado'";
		$resultado = mysqli_query($conexao, $deletar);

		return $resultado; 
 	}

 }
 ?>