<?php
// _perfil_auth.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

include_once __DIR__ . "/../../models/usuarioModel.php";
include_once __DIR__ . "/../../models/imagemModel.php";
include_once __DIR__ . "/../../models/filiadoModel.php";
include_once __DIR__ . "/../../models/graduacaoModel.php";

$id_usuario = $_SESSION['id_usuario'];

$usuarioModelRepo = new Usuario();
$usuario = $usuarioModelRepo->buscarUsuario($id_usuario);

$imagemModelRepo = new Imagem();
$imagem = Imagem::procura_imagem($usuario["id_imagem"]);

$estaFiliado = "Não filiado";
$filiado = ['dojo' => 'A definir', 'confirmacao' => 'nao', 'id_graduacao' => 0];
$graduacao = ['graduacao' => 'Sem registro'];
$filiadoGraduacoes = [];

if (!empty($usuario["id_fil"])) {
    $id_filiado = $usuario["id_fil"];
    
    $filiadoModelRepo = new FiliadoModel();
    $filiado_data_obj = $filiadoModelRepo->buscarFiliadoPorId($id_filiado);
    
    if ($filiado_data_obj) {
        $filiadoGraduacoes = $filiadoModelRepo->buscarGraduacoesFiliado($id_filiado);
        $filiado = [
            'id_filiado' => $filiado_data_obj->getIdFiliado(),
            'codigo' => $filiado_data_obj->getCodigo(),
            'id_graduacao' => $filiado_data_obj->getIdGraduacao(),
            'nome' => $filiado_data_obj->getNome(),
            'dojo' => $filiado_data_obj->getDojo(),
            'telefone' => $filiado_data_obj->getTelefone(),
            'dataNascimento' => $filiado_data_obj->getDataNascimento(),
            'email' => $filiado_data_obj->getEmail(),
            'endereco' => $filiado_data_obj->getEndereco(),
            'cidade' => $filiado_data_obj->getCidade(),
            'id_estado' => $filiado_data_obj->getIdEstado(),
            'confirmacao' => $filiado_data_obj->getConfirmacao()
        ];
        $estaFiliado = ($filiado["confirmacao"] == "sim" && ($usuario["nivel"] !== "aluno")) ? "Você está filiado" : "Aguardando Confirmação de Filiação, Não Está Filiado Não";

        if ($filiado["confirmacao"] == "sim" && ($usuario["nivel"] === "aluno")) {
            $estaFiliado = "Você não está filiado, e seu nível é 'aluno'. Contate o administrador.";
            $filiado["dojo"] = "A definir";
           
        } elseif ($filiado["confirmacao"] == "sim" && ($usuario["nivel"] === "aluno") && ($filiado["id_graduacao"] != 0)) {
            $estaFiliado = "Você está filiado, mas seu nível é 'aluno'. Contate o administrador.";

            $id_graduacao = $filiado["id_graduacao"];
            $graduacao_data = Graduacao::buscarGraduacao($id_graduacao);
            if ($graduacao_data) {
                $graduacao = $graduacao_data;
            }
        }else{
            $id_graduacao = $filiado["id_graduacao"];
            $graduacao_data = Graduacao::buscarGraduacao($id_graduacao);
            if ($graduacao_data) {
                $graduacao = $graduacao_data;
            }
        }
    }
}
?>