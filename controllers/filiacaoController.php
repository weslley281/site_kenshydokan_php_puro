<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../models/filiacaoModel.php";

define("MSG_ERRO", "Erro: Ocorreu um erro ao processar sua solicitação.");
define("MSG_SUCESSO", "Operação realizada com sucesso.");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["tipo"])) {
        $tipo = $_POST["tipo"];
        $filiacaoModel = new Filiacao();

        // 1. CRIAR FILIAÇÃO
        if ($tipo === "criar_filiacao") {
            if (!isset($_SESSION["id_usuario"]) || $_SESSION["nivel"] !== "admin") {
                exibirMensagemEredirecionar("Acesso negado.", "../views/admin/index.php");
            }

            $nome = $_POST["nome"] ?? "";
            $link = $_POST["link"] ?? null;
            $status = $_POST["status"] ?? "ativo";
            $logo = "";

            if (empty($nome)) {
                exibirMensagemEredirecionar("O nome da instituição é obrigatório.", "../views/admin/index.php?pagina=filiacoes");
            }

            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
                $nome_arquivo = uniqid() . '_' . basename($_FILES['logo']['name']);
                $caminho_arquivo = __DIR__ . '/../img/' . $nome_arquivo;
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $caminho_arquivo)) {
                    $logo = $nome_arquivo;
                } else {
                    exibirMensagemEredirecionar("Erro ao realizar upload da imagem da logo.", "../views/admin/index.php?pagina=filiacoes");
                }
            } else {
                exibirMensagemEredirecionar("A imagem da logo é obrigatória.", "../views/admin/index.php?pagina=filiacoes");
            }

            $novaFil = new Filiacao(null, $nome, $logo, $link, $status);

            if ($filiacaoModel->criarFiliacao($novaFil)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, "../views/admin/index.php?pagina=filiacoes");
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, "../views/admin/index.php?pagina=filiacoes");
            }
        }
        
        // 2. EDITAR FILIAÇÃO
        elseif ($tipo === "editar_filiacao") {
            if (!isset($_SESSION["id_usuario"]) || $_SESSION["nivel"] !== "admin") {
                exibirMensagemEredirecionar("Acesso negado.", "../views/admin/index.php");
            }

            $id_filiacao = $_POST["id_filiacao"] ?? null;
            $nome = $_POST["nome"] ?? "";
            $link = $_POST["link"] ?? null;
            $status = $_POST["status"] ?? "ativo";
            $logo_antiga = $_POST["logo_antiga"] ?? "";

            if (!$id_filiacao || empty($nome)) {
                exibirMensagemEredirecionar("Preencha todos os campos obrigatórios.", "../views/admin/index.php?pagina=filiacoes");
            }

            $logo = $logo_antiga;
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
                $nome_arquivo = uniqid() . '_' . basename($_FILES['logo']['name']);
                $caminho_arquivo = __DIR__ . '/../img/' . $nome_arquivo;
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $caminho_arquivo)) {
                    $logo = $nome_arquivo;
                    // Excluir a logo antiga se existir
                    if (!empty($logo_antiga)) {
                        @unlink(__DIR__ . '/../img/' . $logo_antiga);
                    }
                }
            }

            if ($filiacaoModel->editarFiliacao($id_filiacao, $nome, $logo, $link, $status)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, "../views/admin/index.php?pagina=filiacoes");
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, "../views/admin/index.php?pagina=filiacoes");
            }
        }

        // 3. EXCLUIR FILIAÇÃO
        elseif ($tipo === "excluir_filiacao") {
            if (!isset($_SESSION["id_usuario"]) || $_SESSION["nivel"] !== "admin") {
                exibirMensagemEredirecionar("Acesso negado.", "../views/admin/index.php");
            }

            $id_filiacao = $_POST["id_filiacao"] ?? null;

            if (!$id_filiacao) {
                exibirMensagemEredirecionar("ID inválido.", "../views/admin/index.php?pagina=filiacoes");
            }

            // Buscar filiação para apagar a imagem
            $fil = $filiacaoModel->buscarPorId($id_filiacao);
            if ($fil) {
                $logo = $fil->getLogo();
                if (!empty($logo)) {
                    @unlink(__DIR__ . '/../img/' . $logo);
                }
            }

            if ($filiacaoModel->excluirFiliacao($id_filiacao)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, "../views/admin/index.php?pagina=filiacoes");
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, "../views/admin/index.php?pagina=filiacoes");
            }
        }
    }
} else {
    exibirMensagemEredirecionar("Requisição inválida.", "../views/inicio.php");
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
