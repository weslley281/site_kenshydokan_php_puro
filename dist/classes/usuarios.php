<?php 
 include_once("conexao.php");

 Class usuario{

 	public function registrar_usuario($nome, $email, $tipo, $telefone, $id_filiado, $senha){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$inserir = "INSERT INTO usuarios (nome, email, tipo, telefone, id_fil, senha) VALUES ('$nome', '$email', '$tipo', '$telefone', '$id_filiado', '$senha')";
 		$resultado = mysqli_query($conexao, $inserir);
 		var_dump($inserir);
 		var_dump($resultado);

 		return $resultado;
 	}

 	public function editar_usuario($id_usuario, $nome, $email, $tipo, $telefone, $senha){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$consulta = "UPDATE usuarios SET nome = '$nome', email = '$email', tipo = '$tipo', telefone = '$telefone', senha = '$senha' WHERE id_usuario = $id_usuario";
 		$resultado = mysqli_query($conexao, $consulta);

 		return $resultado;
 	}

 	public function excluir_usuario($id_usuario){
 		$c = new conectar();
		$conexao=$c->conexao();

		$deletar = "DELETE FROM usuarios where id_usuario = '$id_usuario'";
		$resultado = mysqli_query($conexao, $deletar);

		return $resultado; 
 	}
 }
 ?>