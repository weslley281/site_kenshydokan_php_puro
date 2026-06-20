<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/dojoModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $dataFiliacao = isset($_POST["data_filiacao"]) ? $_POST["data_filiacao"] : date("Y-m-d");

        $dojoModel = new DojoModel();

        if ($_POST["tipo"] == "inserir") {
            $imagem = 'sem_imagem.png';
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
                $nome_arquivo = uniqid() . '_' . basename($_FILES['imagem']['name']);
                $caminho_arquivo = __DIR__ . '/../img/' . $nome_arquivo;
                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_arquivo)) {
                    $imagem = $nome_arquivo;
                }
            }

            $novoDojo = new DojoModel(
                null,
                $_POST["razao_social"],
                $_POST["nome_fantasia"],
                $_POST["cnpj"],
                $_POST["id_filiado_responsavel"],
                $_POST["telefone"],
                $_POST["celular"],
                $_POST["email"],
                $_POST["cep"],
                $_POST["endereco"],
                $_POST["cidade"],
                $_POST["estado"],
                $dataFiliacao,
                "ativo",
                $imagem
            );

            if ($dojoModel->criarDojo($novoDojo)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=dojos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=dojos');
            }
        } elseif ($_POST["tipo"] == "editar") {
            $id_dojo = $_POST["id_dojo"];
            
            $imagem = isset($_POST['imagem_antiga']) ? $_POST['imagem_antiga'] : 'sem_imagem.png';
            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
                $nome_arquivo = uniqid() . '_' . basename($_FILES['imagem']['name']);
                $caminho_arquivo = __DIR__ . '/../img/' . $nome_arquivo;
                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_arquivo)) {
                    $imagem = $nome_arquivo;
                    // Excluir a imagem antiga se não for sem_imagem.png
                    if (!empty($_POST['imagem_antiga']) && $_POST['imagem_antiga'] !== 'sem_imagem.png') {
                        @unlink(__DIR__ . '/../img/' . $_POST['imagem_antiga']);
                    }
                }
            }

            $status = isset($_POST["status"]) ? $_POST["status"] : "ativo";

            $novoDojo = new DojoModel(
                $id_dojo,
                $_POST["razao_social"],
                $_POST["nome_fantasia"],
                $_POST["cnpj"],
                $_POST["id_filiado_responsavel"],
                $_POST["telefone"],
                $_POST["celular"],
                $_POST["email"],
                $_POST["cep"],
                $_POST["endereco"],
                $_POST["cidade"],
                $_POST["estado"],
                $dataFiliacao,
                $status,
                $imagem
            );

            if ($dojoModel->editarDojo($novoDojo)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=dojos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=dojos');
            }
        } elseif ($_POST["tipo"] == "excluir") {
            $id_dojo = $_POST["id_dojo"];

            // Buscar dojo para saber a imagem antiga e poder excluí-la
            $dojo = DojoModel::getDojoById($id_dojo);
            if ($dojo) {
                $imagem = $dojo->getImagem();
                if (!empty($imagem) && $imagem !== 'sem_imagem.png') {
                    @unlink(__DIR__ . '/../img/' . $imagem);
                }
            }

            if ($dojoModel->excluirDojo($id_dojo)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=dojos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=dojos');
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/admin/index.php?pagina=dojos');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/admin/index.php?pagina=dojos');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
}
?>