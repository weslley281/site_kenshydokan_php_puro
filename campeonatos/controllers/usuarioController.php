<?php
/**
 * Controlador de Gestão de Usuários do Sistema de Campeonatos (somente administrador)
 * Padrão IDÊNTICO ao site principal (controllers/usuarioController.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/authHelper.php";
include_once __DIR__ . "/../models/usuarioModel.php";
include_once __DIR__ . "/../models/auditoriaModel.php";
include_once __DIR__ . "/alertHelper.php";

$destino = "../views/usuarios.php";
$niveisValidos = ['admin', 'arbitro', 'mesario', 'leitor'];
$tamanhoMinimoSenha = 8;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . $destino);
    exit;
}

csrfExigir($destino);
exigirPerfil([]); // somente administrador

$usuarioModel = new Usuario();
$tipo = $_POST["tipo"] ?? "";
$idLogado = (int)($_SESSION["id_usuario"] ?? 0);

if ($tipo === "criar_usuario") {
    $nome = trim($_POST["nome"] ?? "");
    $email = strtolower(trim($_POST["email"] ?? ""));
    $senha = $_POST["senha"] ?? "";
    $nivel = $_POST["nivel"] ?? "mesario";

    if ($nome === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        exibirMensagemEredirecionar("Informe o nome e um e-mail válido.", $destino);
    }
    if (!in_array($nivel, $niveisValidos, true)) {
        exibirMensagemEredirecionar("Perfil de acesso inválido.", $destino);
    }
    if (strlen($senha) < $tamanhoMinimoSenha) {
        exibirMensagemEredirecionar("A senha precisa ter pelo menos {$tamanhoMinimoSenha} caracteres.", $destino);
    }
    if ($usuarioModel->emailEmUso($email)) {
        exibirMensagemEredirecionar("Já existe um usuário com este e-mail.", $destino);
    }

    $id = $usuarioModel->criarUsuario($nome, $email, $senha, $nivel);
    if ($id > 0) {
        Auditoria::log("CRIAR_USUARIO", "campeonato_usuarios", $id, null, ['nome' => $nome, 'email' => $email, 'nivel' => $nivel]);
        exibirMensagemEredirecionar("Usuário '{$nome}' criado com sucesso!", $destino);
    }
    exibirMensagemEredirecionar("Erro ao criar o usuário.", $destino);

} elseif ($tipo === "editar_usuario") {
    $id = (int)($_POST["id_usuario"] ?? 0);
    $nome = trim($_POST["nome"] ?? "");
    $email = strtolower(trim($_POST["email"] ?? ""));
    $nivel = $_POST["nivel"] ?? "";

    $atual = $usuarioModel->buscarPorId($id);
    if (!$atual) {
        exibirMensagemEredirecionar("Usuário não encontrado.", $destino);
    }
    if ($nome === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        exibirMensagemEredirecionar("Informe o nome e um e-mail válido.", $destino);
    }
    if (!in_array($nivel, $niveisValidos, true)) {
        exibirMensagemEredirecionar("Perfil de acesso inválido.", $destino);
    }
    if ($usuarioModel->emailEmUso($email, $id)) {
        exibirMensagemEredirecionar("Já existe outro usuário com este e-mail.", $destino);
    }

    // Protege contra ficar sem administrador (rebaixar a si mesmo ou o último admin)
    $rebaixandoAdmin = $atual['nivel'] === 'admin' && $nivel !== 'admin';
    if ($rebaixandoAdmin && !empty($atual['ativo']) && $usuarioModel->contarAdminsAtivos() <= 1) {
        exibirMensagemEredirecionar("Não é possível rebaixar o único administrador ativo do sistema.", $destino);
    }
    if ($rebaixandoAdmin && $id === $idLogado) {
        exibirMensagemEredirecionar("Você não pode rebaixar o seu próprio perfil. Peça a outro administrador.", $destino);
    }

    if ($usuarioModel->atualizarDados($id, $nome, $email, $nivel)) {
        Auditoria::log(
            "EDITAR_USUARIO", "campeonato_usuarios", $id,
            ['nome' => $atual['nome'], 'email' => $atual['email'], 'nivel' => $atual['nivel']],
            ['nome' => $nome, 'email' => $email, 'nivel' => $nivel]
        );
        if ($id === $idLogado) {
            $_SESSION["nome"] = $nome;
            $_SESSION["email"] = $email;
            $_SESSION["nivel"] = $nivel;
        }
        exibirMensagemEredirecionar("Usuário atualizado com sucesso!", $destino);
    }
    exibirMensagemEredirecionar("Erro ao atualizar o usuário.", $destino);

} elseif ($tipo === "alternar_ativo") {
    $id = (int)($_POST["id_usuario"] ?? 0);
    $ativar = ($_POST["ativar"] ?? "0") === "1";

    $atual = $usuarioModel->buscarPorId($id);
    if (!$atual) {
        exibirMensagemEredirecionar("Usuário não encontrado.", $destino);
    }
    if (!$ativar) {
        if ($id === $idLogado) {
            exibirMensagemEredirecionar("Você não pode desativar o seu próprio acesso.", $destino);
        }
        if ($atual['nivel'] === 'admin' && !empty($atual['ativo']) && $usuarioModel->contarAdminsAtivos() <= 1) {
            exibirMensagemEredirecionar("Não é possível desativar o único administrador ativo do sistema.", $destino);
        }
    }

    if ($usuarioModel->alterarAtivo($id, $ativar)) {
        Auditoria::log($ativar ? "ATIVAR_USUARIO" : "DESATIVAR_USUARIO", "campeonato_usuarios", $id, ['ativo' => (int)$atual['ativo']], ['ativo' => $ativar ? 1 : 0]);
        exibirMensagemEredirecionar($ativar ? "Usuário reativado." : "Usuário desativado. Ele não consegue mais entrar.", $destino);
    }
    exibirMensagemEredirecionar("Erro ao alterar o status do usuário.", $destino);

} elseif ($tipo === "redefinir_senha") {
    $id = (int)($_POST["id_usuario"] ?? 0);
    $senha = $_POST["senha"] ?? "";

    $atual = $usuarioModel->buscarPorId($id);
    if (!$atual) {
        exibirMensagemEredirecionar("Usuário não encontrado.", $destino);
    }
    if (strlen($senha) < $tamanhoMinimoSenha) {
        exibirMensagemEredirecionar("A nova senha precisa ter pelo menos {$tamanhoMinimoSenha} caracteres.", $destino);
    }

    if ($usuarioModel->redefinirSenha($id, $senha)) {
        // A senha nunca vai para a auditoria
        Auditoria::log("REDEFINIR_SENHA", "campeonato_usuarios", $id, null, ['email' => $atual['email']]);
        exibirMensagemEredirecionar("Senha de '{$atual['nome']}' redefinida com sucesso.", $destino);
    }
    exibirMensagemEredirecionar("Erro ao redefinir a senha.", $destino);

} else {
    header("Location: " . $destino);
    exit;
}
