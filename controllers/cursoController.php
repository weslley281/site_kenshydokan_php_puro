<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/cursoModel.php";
    include_once "../repositorios/cursoRepositorio.php";
    include_once "../repositorios/imagemRepositorio.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $dataMudanca = date("Y-m-d");

        $cursoRepositorio = new CursoRepositorio();
        $imagemRepositorio = new ImagemRepositorio;

        if ($_POST["tipo"] == "inserir") {

            $diretorioUpload = "../img/";
            $nomeImagem = uniqid() . $_FILES["imagem"]["name"];
            $caminho = $diretorioUpload . $nomeImagem;
            $extensaoImagem = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));

            if (!in_array($extensaoImagem, ["jpg", "jpeg", "gif", "png"])) {
                exibirMensagemEredirecionar("Formato de imagem inválido", '../views/admin.php?pagina=cursos');
                exit();
            }

            if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho)) {
                $imagemModel = new Imagem($nomeImagem, $caminho, $dataMudanca);

                if ($imagemRepositorio->registrar_imagem($imagemModel)) {
                    $id_imagem = $imagemRepositorio::procura_id_imagem($nomeImagem);

                    $cursoModel = new CursoModel(
                        null,
                        $_POST["id_categoria"],
                        $_POST["nome"],
                        $_POST["descricao"],
                        $_POST["professor"],
                        $id_imagem,
                        "aguardando",
                        $dataMudanca
                    );

                    if ($cursoRepositorio->criarCurso($cursoModel)) {
                        exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin.php?pagina=cursos');
                    } else {
                        exibirMensagemEredirecionar(MSG_ERRO, '../views/admin.php?pagina=cursos');
                    }
                } else {
                    exibirMensagemEredirecionar(MSG_ERRO, '../views/admin.php?pagina=cursos');
                }
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin.php?pagina=cursos');
            }
        } elseif ($_POST["tipo"] == "editar") {
            $id_curso = $_POST["id_curso"];

            $cursoModel = new CursoModel(
                $id_curso,
                $_POST["id_categoria"],
                $_POST["nome"],
                $_POST["descricao"],
                $_POST["professor"],
                $_POST["id_imagem"],
                $_POST["situacao"],
                $dataMudanca
            );

            if ($cursoRepositorio->editarCurso($id_curso, $cursoModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin.php?pagina=cursos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin.php?pagina=cursos');
            }
        } elseif ($_POST["tipo"] == "excluir") {
            $id_curso = $_POST["id_curso"];

            if ($cursoRepositorio->excluirCurso($id_curso)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin.php?pagina=cursos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin.php?pagina=cursos');
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/admin.php?pagina=cursos');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/admin.php?pagina=cursos');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
