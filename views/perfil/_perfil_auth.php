<?php
// _perfil_auth.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

include_once __DIR__ . "/../../db/conexao.php";
$c = new Conexao();
$conexao = $c->conectar();

$id_usuario = $_SESSION['id_usuario'];

$busca_usuario = "SELECT * FROM usuarios WHERE id_usuario = '$id_usuario'";
$resultado_usuario = mysqli_query($conexao, $busca_usuario);
$usuario = mysqli_fetch_array($resultado_usuario);

$id_imagem = $usuario["id_imagem"];
$busca_imagem = "SELECT * FROM imagens WHERE id_imagem = '$id_imagem'";
$resultado_imagem = mysqli_query($conexao, $busca_imagem);
$imagem = mysqli_fetch_array($resultado_imagem);

$estaFiliado = "Não filiado";
$filiado = ['dojo' => 'A definir', 'confirmacao' => 'nao', 'id_graduacao' => 0];
$graduacao = ['graduacao' => 'Sem registro'];

if (!empty($usuario["id_fil"])) {
    $id_filiado = $usuario["id_fil"];
    $busca_filiado = "SELECT * FROM filiados WHERE id_filiado = '$id_filiado'";
    $resultado_filiado = mysqli_query($conexao, $busca_filiado);
    $filiado_data = mysqli_fetch_array($resultado_filiado);

    if ($filiado_data) {
        $filiado = $filiado_data;
        $estaFiliado = ($filiado["confirmacao"] == "sim" && ($usuario["nivel"] !== "aluno")) ? "Você está filiado" : "Aguardando Confirmação de Filiação, Não Está Filiado Não";

        if ($filiado["confirmacao"] == "sim" && ($usuario["nivel"] === "aluno")) {
            $estaFiliado = "Você não está filiado, e seu nível é 'aluno'. Contate o administrador.";
            $filiado["dojo"] = "A definir";
           
        } elseif ($filiado["confirmacao"] == "sim" && ($usuario["nivel"] === "aluno") && ($filiado["id_graduacao"] != 0)) {
            $estaFiliado = "Você está filiado, mas seu nível é 'aluno'. Contate o administrador.";

            $id_graduacao = $filiado["id_graduacao"];
            $busca_graduacao = "SELECT * FROM graduacoes WHERE id_graduacao = '$id_graduacao'";
            $resultado_graduacao = mysqli_query($conexao, $busca_graduacao);
            $graduacao_data = mysqli_fetch_array($resultado_graduacao);
            if ($graduacao_data) {
                $graduacao = $graduacao_data;
            }
        }else{
            $id_graduacao = $filiado["id_graduacao"];
            $busca_graduacao = "SELECT * FROM graduacoes WHERE id_graduacao = '$id_graduacao'";
            $resultado_graduacao = mysqli_query($conexao, $busca_graduacao);
            $graduacao_data = mysqli_fetch_array($resultado_graduacao);
            if ($graduacao_data) {
                $graduacao = $graduacao_data;
            }
        }
        
    }
}
?>