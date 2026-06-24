<?php
// controllers/certificadoUploadController.php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Apenas admin
    if (!isset($_SESSION["id_usuario"]) || $_SESSION['nivel'] != "admin") {
        echo "<script>alert('Acesso negado.'); window.location.href = '../views/login.php';</script>";
        exit();
    }

    include_once "../models/certificadoUploadModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro ao processar sua solicitação.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");
    $retorno = '../views/admin/index.php?pagina=certificados_upload';

    if (isset($_POST["tipo"])) {
        $model = new CertificadoUploadModel();

        if ($_POST["tipo"] == "inserir") {
            $id_filiado = intval($_POST["id_filiado"]);
            $titulo = trim($_POST["titulo"]);
            $data_upload = date("Y-m-d");

            if ($id_filiado <= 0 || empty($titulo)) {
                exibirMensagemEredirecionar("Erro: Preencha todos os campos obrigatórios.", $retorno);
            }

            if (!isset($_FILES["imagem"]) || $_FILES["imagem"]["error"] !== UPLOAD_ERR_OK) {
                $erroMsg = "Erro no envio da imagem do certificado.";
                if (isset($_FILES["imagem"])) {
                    switch ($_FILES["imagem"]["error"]) {
                        case UPLOAD_ERR_INI_SIZE:
                        case UPLOAD_ERR_FORM_SIZE:
                            $erroMsg = "Erro: A imagem do certificado é muito grande.";
                            break;
                        case UPLOAD_ERR_NO_FILE:
                            $erroMsg = "Erro: Selecione uma imagem do certificado para enviar.";
                            break;
                    }
                }
                exibirMensagemEredirecionar($erroMsg, $retorno);
            }

            $diretorioUpload = "../img/certificados/";
            $extensaoImagem = strtolower(pathinfo($_FILES["imagem"]["name"], PATHINFO_EXTENSION));

            if (in_array($extensaoImagem, ["jpg", "jpeg", "png", "webp"])) {
                $nomeImagem = uniqid() . '.' . $extensaoImagem;
                $caminho = $diretorioUpload . $nomeImagem;

                // Garante que o diretório existe
                if (!file_exists($diretorioUpload)) {
                    mkdir($diretorioUpload, 0755, true);
                }

                if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho)) {
                    if ($model->criarCertificadoUpload($id_filiado, $titulo, $nomeImagem, $data_upload)) {
                        exibirMensagemEredirecionar(MSG_SUCESSO, $retorno);
                    } else {
                        // Deleta imagem se falhar o salvamento no banco de dados
                        if (file_exists($caminho)) {
                            unlink($caminho);
                        }
                        exibirMensagemEredirecionar("Erro ao salvar informações do certificado no banco de dados.", $retorno);
                    }
                } else {
                    exibirMensagemEredirecionar("Erro: Falha ao mover o arquivo de imagem para o servidor.", $retorno);
                }
            } else {
                exibirMensagemEredirecionar("Erro: Formato de imagem inválido. Apenas JPG, JPEG, PNG e WEBP são permitidos.", $retorno);
            }

        } elseif ($_POST["tipo"] == "excluir") {
            $id = intval($_POST["id"]);
            $dados = $model->buscarPorId($id);

            if ($dados) {
                $caminho_imagem = "../img/certificados/" . $dados['imagem'];
                if (file_exists($caminho_imagem)) {
                    unlink($caminho_imagem);
                }

                if ($model->excluirCertificadoUpload($id)) {
                    exibirMensagemEredirecionar(MSG_SUCESSO, $retorno);
                } else {
                    exibirMensagemEredirecionar(MSG_ERRO, $retorno);
                }
            } else {
                exibirMensagemEredirecionar("Erro: Certificado não encontrado.", $retorno);
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
?>
