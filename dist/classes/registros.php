<?php 
 include_once("conexao.php");

 Class adm{

 	public function registrar_adm($nome, $sobrenome, $email, $senha){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$inserir = "INSERT INTO adms (nome, sobrenome, email, senha) VALUES ('$nome', '$sobrenome', '$email', '$senha')";
 		$resultado = mysqli_query($conexao, $inserir);

 		return $resultado;
 	}

 	public function editar_adm($id_adm, $nome, $sobrenome, $email, $senha){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$consulta = "UPDATE adms SET nome = '$nome', sobrenome = '$sobrenome', email = '$email', senha = '$senha' WHERE id_adm = $id_adm";
 		$resultado = mysqli_query($conexao, $consulta);

 		return $resultado;
 	}

 	public function excluir_adm($id_adm){
 		$c = new conectar();
		$conexao=$c->conexao();

		$deletar = "DELETE FROM adms where id_adm = '$id_adm'";
		$resultado = mysqli_query($conexao, $deletar);

		return $resultado; 
 	}
 }
 ?>