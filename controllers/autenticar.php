<?php
include_once "../classes/conexao.php";
$c = new Conexao();
$conexao = $c->conectar();
session_start();

$_POST['usuario'] = strtolower($_POST['usuario']);
if (empty($_POST['usuario']) || empty($_POST['senha'])) {
    header('Location:../login.php');
    exit();

}

$email = mysqli_real_escape_string($conexao, $_POST['usuario']);
$senha = mysqli_real_escape_string($conexao, $_POST['senha']);

$consulta = "SELECT * FROM usuarios WHERE email = '$email'";
$resultado = mysqli_query($conexao, $consulta);
$dado = mysqli_fetch_array($resultado);
$linha = mysqli_num_rows($resultado);

if ($linha > 0) {
    $senha_banco = $dado["senha"];
    if (password_verify($senha, $senha_banco)) {
        $_SESSION['id_usuario'] = $dado['id_usuario'];
        $_SESSION['filiado'] = $email;
        $_SESSION['nome'] = $dado['nome'];
        $_SESSION['id_fil'] = $dado['id_fil'];
        $_SESSION['tipo'] = $dado['tipo'];
        header('Location:../filiado/perfil.php');
    } else {
        echo "<script language='javascript'>window.alert('login ou senha invalido'); </script>";
        echo "<script language='javascript'>window.location='../login.php'; </script>";
    }
} else {
    echo "<script language='javascript'>window.alert('login ou senha invalido'); </script>";
    echo "<script language='javascript'>window.location='../login.php'; </script>";
}
