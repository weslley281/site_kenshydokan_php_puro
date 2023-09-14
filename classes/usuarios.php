<?php 
 include_once("conexao.php");

 Class usuario{

 	public function editar_usuario($id_usuario, $nome, $email, $telefone){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$consulta = "UPDATE usuarios SET nome = '$nome', email = '$email', telefone = '$telefone' WHERE id_usuario = $id_usuario";
 		$resultado = mysqli_query($conexao, $consulta);
 		//var_dump($consulta);

 		return $resultado;
 	}

 	public function editar_senha($id_usuario, $senha){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$consulta = "UPDATE usuarios SET senha = '$senha' WHERE id_usuario = $id_usuario";
 		$resultado = mysqli_query($conexao, $consulta);
 		//var_dump($consulta);

 		return $resultado;
 	}

 	public function excluir_usuario($id_usuario){
 		$c = new conectar();
		$conexao=$c->conexao();

		$deletar = "DELETE FROM usuarios where id_usuario = '$id_usuario'";
		$resultado = mysqli_query($conexao, $deletar);

		return $resultado; 
 	}

 	public function registrar_imagem_usuario($nome, $caminho){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$inserir = "INSERT INTO imagens (nome, caminho) VALUES ('$nome', '$caminho')";
 		$resultado = mysqli_query($conexao, $inserir);

 		return $resultado;
 	}
 }
 ?>