<?php 
 include_once("conexao.php");

 Class graduacao{

 	public function registrar_graduacao($graduacao){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$inserir = "INSERT INTO graduacao (graduacao) VALUES ('$graduacao')";
 		$resultado = mysqli_query($conexao, $inserir);

 		return $resultado;
 	}

 	public function editar_graduacao($id_graduacao, $graduacao){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$consulta = "UPDATE graduacao SET graduacao = '$graduacao' WHERE id_graduacao = $id_graduacao";
 		$resultado = mysqli_query($conexao, $consulta);

 		return $resultado;
 	}

 	public function excluir_graduacao($id_graduacao){
 		$c = new conectar();
		$conexao=$c->conexao();

		$deletar = "DELETE FROM graduacao where id_graduacao = '$id_graduacao'";
		$resultado = mysqli_query($conexao, $deletar);

		return $resultado; 
 	}
 }
 ?>