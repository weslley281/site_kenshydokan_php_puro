<?php

include_once("conexao.php");

 Class curso{

 	public function registrar_curso($id_categoria, $curso, $descricao, $professor, $id_imagem, $data, $situacao){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$inserir = "INSERT INTO curso (id_categoria, nome, descricao, professor, id_imagem, data, situacao) VALUES ('$id_categoria', '$curso', '$descricao', '$professor', '$id_imagem', '$data', '$situacao')";
 		$resultado = mysqli_query($conexao, $inserir);

 		return $resultado;
 	}

 	public function registrar_imagem_curso($nome, $caminho){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$inserir = "INSERT INTO imagens (nome, caminho) VALUES ('$nome', '$caminho')";
 		$resultado = mysqli_query($conexao, $inserir);

 		return $resultado;
 	}

 	public function editar_curso($id_curso, $id_categoria, $curso, $descricao, $professor, $situacao){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$consulta = "UPDATE curso SET id_categoria = '$id_categoria', descricao = '$descricao', nome = '$curso', professor = '$professor', situacao = '$situacao' WHERE id_curso = $id_curso";
 		$resultado = mysqli_query($conexao, $consulta);
 		var_dump($consulta);
 		return $resultado;
 	}

 	public function excluir_curso($id_curso){
 		$c = new conectar();
		$conexao=$c->conexao();

		$deletar = "DELETE FROM curso where id_curso = '$id_curso'";
		$resultado = mysqli_query($conexao, $deletar);

		return $resultado; 
 	}

 	public function excluir_imagem_curso($id_imagem){
 		$c = new conectar();
		$conexao=$c->conexao();

		$deletar = "DELETE FROM imagens where id_imagem = '$id_imagem'";
		$resultado = mysqli_query($conexao, $deletar);

		return $resultado; 
 	}
 }
 ?>