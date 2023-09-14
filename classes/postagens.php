<?php 
 include_once("conexao.php");

 Class postagem{

 	public function criar_postagens($id_usuario, $titulo, $conteudo, $situacao, $data){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$inserir = "INSERT INTO postagens (id_usuario, titulo, conteudo, situacao, data) VALUES ('$id_usuario', '$titulo', '$conteudo', '$situacao', '$data')";
 		$resultado = mysqli_query($conexao, $inserir);
 		//var_dump($consulta);

 		return $resultado;
 	}

 	public function editar_postagens($id_postagem, $titulo, $conteudo, $situacao, $data){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$consulta = "UPDATE postagens SET titulo = '$titulo', conteudo = '$conteudo', situacao = '$situacao' WHERE id_postagem = $id_postagem";
 		$resultado = mysqli_query($conexao, $consulta);
 		//var_dump($consulta);

 		return $resultado;
 	}

 	public function editar_situacao_postagem($id_postagem, $situacao){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$consulta = "UPDATE postagens SET situacao = '$situacao' WHERE id_postagem = $id_postagem";
 		$resultado = mysqli_query($conexao, $consulta);
 		//var_dump($consulta);

 		return $resultado;
 	}

 	public function excluir_postagens($id_postagem){
 		$c = new conectar();
		$conexao=$c->conexao();

		$deletar = "DELETE FROM postagens where id_postagem = '$id_postagem'";
		$resultado = mysqli_query($conexao, $deletar);

		return $resultado; 
 	}

 	public function registrar_imagem_postagens($nome, $caminho){
 		$c = new conectar();
 		$conexao = $c->conexao();

 		$inserir = "INSERT INTO imagens (nome, caminho) VALUES ('$nome', '$caminho')";
 		$resultado = mysqli_query($conexao, $inserir);

 		return $resultado;
 	}
 }
 ?>