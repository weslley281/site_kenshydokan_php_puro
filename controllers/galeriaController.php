<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/galeriaModel.php";
    include_once "../models/fotoModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $galeriaManager = new Galeria();
        $fotoManager = new Foto();

        if ($_POST["tipo"] == "criar_galeria") {
            $nome = $_POST["nome"];
            $galeriaModel = new Galeria(null, $nome);

            if ($galeriaManager->criarGaleria($galeriaModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=galerias');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=galerias');
            }
        } elseif ($_POST["tipo"] == "editar_galeria") {
            $id_galeria = $_POST["id_galeria"];
            $nome = $_POST["nome"];

            if ($galeriaManager->editarGaleria($id_galeria, $nome)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/editar_galeria.php?id=' . $id_galeria);
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/editar_galeria.php?id=' . $id_galeria);
            }
        } elseif ($_POST["tipo"] == "excluir_galeria") {
            $id_galeria = $_POST["id_galeria"];

            if ($galeriaManager->excluirGaleria($id_galeria)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=galerias');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=galerias');
            }
        } elseif ($_POST["tipo"] == "adicionar_foto") {
            $id_galeria = $_POST["id_galeria"];
            $nome = $_POST["nome"];
            $dataUpload = date("Y-m-d");

            $diretorioUpload = "../slides/";
            $nomeFoto = uniqid() . $_FILES["foto"]["name"];
            var_dump($nomeFoto);
            $caminho = $diretorioUpload . $nomeFoto;
            var_dump($caminho);
            $extensaoFoto = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));
            var_dump($extensaoFoto);

            if (!in_array($extensaoFoto, ["jpg", "jpeg", "gif", "png"])) {
                exibirMensagemEredirecionar("Formato de imagem inválido", '../views/admin/editar_galeria.php?id=' . $id_galeria);
                exit();
            }

            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $caminho)) {
                $fotoModel = new Foto(null, $id_galeria, $nome, $nomeFoto, $dataUpload);

                if ($fotoManager->adicionarFoto($fotoModel)) {
                    exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/editar_galeria.php?id=' . $id_galeria);
                } else {
                    echo "Erro ao salvar no banco de dados.<br>";
                    var_dump($fotoManager->adicionarFoto($fotoModel));
                    var_dump($fotoModel);
                    //exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/editar_galeria.php?id=' . $id_galeria);
                }
            } else {
                exibirMensagemEredirecionar(
                    "A imagem excede o limite de tamanho permitido (2MB). Por favor, envie uma imagem menor.",
                    '../views/admin/adicionar_foto.php?id=' . $id_galeria
                );
                exit();
                //exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/editar_galeria.php?id=' . $id_galeria);
            }
        } elseif ($_POST["tipo"] == "excluir_foto") {
            $id_foto = $_POST["id_foto"];
            $id_galeria = $_POST["id_galeria"];

            if ($fotoManager->excluirFoto($id_foto)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/editar_galeria.php?id=' . $id_galeria);
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/editar_galeria.php?id=' . $id_galeria);
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/admin/index.php?pagina=galerias');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/admin/index.php?pagina=galerias');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
