<?php 
 include_once("connection.php");

 Class Users{
 	private $connection;

    public function __construct()
    {
        $dbConnection = new Conection();
        $this->connection = $dbConnection->connect();
    }

    public function create_user($nome, $email, $telefone)
    {
    	// code...
    }

 	public function editar_usuario($id_usuario, $nome, $email, $telefone){
 		$insert = $this->connection->prepare("INSERT INTO posts (user_id, title, content, status, date) VALUES (?, ?, ?, ?, ?)");
        $insert->bind_param("issss", $user_id, $title, $content, $status, $date);
        $result = $insert->execute();
        $insert->close();
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