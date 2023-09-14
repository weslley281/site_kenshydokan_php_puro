<?php 
 include_once("conexao.php");

 Class verificacao{
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