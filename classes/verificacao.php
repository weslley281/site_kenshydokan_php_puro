<?php 
 include_once("conexao.php");

 Class verificacao{
   private $conexao;

   public function __construct($conexao)
   {
      $c = new Conexao();
      $this->conexao = $c->conectar();
   }
   
    public static function verifica_nome_autor($id_usuario){
        $c = new conectar();
        $conexao = $c->conexao();

        $busca = "SELECT nome FROM usuarios where id_usuario = '$id_usuario'";
        //var_dump($busca);
        $resultado = mysqli_query($conexao, $busca);
        $nome = mysqli_fetch_array($resultado);
        return $nome["nome"];
    }
 }