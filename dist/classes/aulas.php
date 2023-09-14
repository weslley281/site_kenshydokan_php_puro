<?php

include_once("conexao.php");

 Class aula{

 	public function adcionar_aula($id_curso, $titulo, $link){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$inserir = "INSERT INTO aulas (id_curso, titulo, link) VALUES ('$id_curso', '$titulo', '$link')";
 		$resultado = mysqli_query($conexao, $inserir);

 		return $resultado;
 	}

 	public function excluir_aula($id_aula){
 		$c = new conectar();
		$conexao=$c->conexao();

		$deletar = "DELETE FROM aulas where id_aula = '$id_aula'";
		$resultado = mysqli_query($conexao, $deletar);

		return $resultado; 
 	}
 }
 ?>