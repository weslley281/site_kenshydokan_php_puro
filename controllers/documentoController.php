<?php
// controllers/documentoController.php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Apenas administrador tem permissão
    if (!isset($_SESSION["id_usuario"]) || $_SESSION['nivel'] != "admin") {
        echo "<script>alert('Acesso negado.'); window.location.href = '../views/login.php';</script>";
        exit();
    }

    include_once "../models/documentoModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro ao processar sua solicitação.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");
    $retorno = '../views/admin/index.php?pagina=documentos';

    if (isset($_POST["tipo"])) {
        $model = new DocumentoModel();

        if ($_POST["tipo"] == "inserir") {
            $titulo = trim($_POST["titulo"]);
            $descricao = isset($_POST["descricao"]) ? trim($_POST["descricao"]) : "";
            $dataMudanca = date("Y-m-d");

            if (empty($titulo)) {
                exibirMensagemEredirecionar("Erro: O título é obrigatório.", $retorno);
            }

            if (!isset($_FILES["documento"]) || $_FILES["documento"]["error"] !== UPLOAD_ERR_OK) {
                $erroMsg = "Erro no envio do arquivo.";
                if (isset($_FILES["documento"])) {
                    switch ($_FILES["documento"]["error"]) {
                        case UPLOAD_ERR_INI_SIZE:
                        case UPLOAD_ERR_FORM_SIZE:
                            $erroMsg = "Erro: O arquivo é muito grande.";
                            break;
                        case UPLOAD_ERR_NO_FILE:
                            $erroMsg = "Erro: Selecione um arquivo PDF para enviar.";
                            break;
                    }
                }
                exibirMensagemEredirecionar($erroMsg, $retorno);
            }

            $diretorioUpload = "../arquivos/";
            $extensao = strtolower(pathinfo($_FILES["documento"]["name"], PATHINFO_EXTENSION));

            if ($extensao === "pdf") {
                // Remove caracteres indesejados e garante nome amigável + id único para evitar colisão
                $nomeLimpo = preg_replace("/[^a-zA-Z0-9_-]/", "_", pathinfo($_FILES["documento"]["name"], PATHINFO_FILENAME));
                $nomeArquivo = $nomeLimpo . "_" . uniqid() . '.pdf';
                $caminho = $diretorioUpload . $nomeArquivo;

                // Garante que o diretório existe
                if (!file_exists($diretorioUpload)) {
                    mkdir($diretorioUpload, 0755, true);
                }

                if (move_uploaded_file($_FILES["documento"]["tmp_name"], $caminho)) {
                    $novoDoc = new DocumentoModel(
                        null,
                        $titulo,
                        $descricao,
                        $nomeArquivo,
                        $dataMudanca
                    );

                    if ($model->criarDocumento($novoDoc)) {
                        exibirMensagemEredirecionar(MSG_SUCESSO, $retorno);
                    } else {
                        // Exclui o arquivo se falhar a persistência no banco
                        if (file_exists($caminho)) {
                            unlink($caminho);
                        }
                        exibirMensagemEredirecionar("Erro ao salvar as informações no banco de dados.", $retorno);
                    }
                } else {
                    exibirMensagemEredirecionar("Erro: Falha ao mover o arquivo para o servidor.", $retorno);
                }
            } else {
                exibirMensagemEredirecionar("Erro: Formato de arquivo inválido. Apenas PDFs são permitidos.", $retorno);
            }

        } elseif ($_POST["tipo"] == "excluir") {
            $id_documento = intval($_POST["id_documento"]);
            $dados = $model->buscarPorId($id_documento);

            if ($dados) {
                $caminho_arquivo = "../arquivos/" . $dados['arquivo'];
                if (file_exists($caminho_arquivo)) {
                    unlink($caminho_arquivo);
                }

                if ($model->excluirDocumento($id_documento)) {
                    exibirMensagemEredirecionar(MSG_SUCESSO, $retorno);
                } else {
                    exibirMensagemEredirecionar(MSG_ERRO, $retorno);
                }
            } else {
                exibirMensagemEredirecionar("Erro: Documento não encontrado.", $retorno);
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", $retorno);
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/admin/index.php');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
