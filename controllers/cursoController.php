<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/cursoModel.php";
    include_once "../repositorios/CursoRepositorio.php";
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
                exibirMensagemEredirecionar("Formato de imagem inválido", '../views/admin/index.php?pagina=cursos');
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
                        $_POST["cargaHoraria"],
                        $_POST["situacao"],
                        $dataMudanca,
                        $_POST["percentual_conclusao_certificado"],
                        $_POST["temCertificado"]
                    );

                    if ($cursoRepositorio->criarCurso($cursoModel)) {
                        exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=cursos');
                    } else {
                        exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=cursos');
                    }
                } else {
                    exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=cursos');
                }
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=cursos');
            }
        } elseif ($_POST["tipo"] == "editar") {
            $id_curso = $_POST["id_curso"];

            $cursoModel = new CursoModel(
                $id_curso,
                $_POST["id_categoria"],
                $_POST["nome"],
                $_POST["descricao"],
                $_POST["professor"],
                null, // id_imagem is null for editing course details, handled separately
                $_POST["cargaHoraria"],
                $_POST["situacao"],
                $dataMudanca,
                $_POST["percentual_conclusao_certificado"],
                $_POST["temCertificado"]
            );

            $destino = "../views/admin/editar_curso.php?id=" . $id_curso;

            if ($cursoRepositorio->editarCurso($id_curso, $cursoModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, $destino);
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, $destino);
            }
        } elseif ($_POST["tipo"] == "editar_imagem") {

            $imagemRepositorio = new ImagemRepositorio;
            $cursoRepositorio = new CursoRepositorio;

            $diretorioUpload = "../img/";
            $nomeImagem = uniqid() . $_FILES["imagem"]["name"];
            $caminho = $diretorioUpload . $nomeImagem;

            $extensaoImagem = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));

            if (in_array($extensaoImagem, ["jpg", "jpeg", "gif", "png"])) {
                if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho)) {
                    $imagemModel = new Imagem($nomeImagem, $caminho, $dataMudanca);

                    if ($imagemRepositorio->registrar_imagem($imagemModel)) {
                        $id_imagem = $imagemRepositorio::procura_id_imagem($nomeImagem);

                        $dados_imagem_antiga = $imagemRepositorio::procura_imagem($_POST["id_imagem"]);

                        $caminho = $dados_imagem_antiga != null ? file_exists($dados_imagem_antiga["caminho"]) : "";

                        if (file_exists($caminho)) {
                            unlink($caminhoImagemAntiga);
                        }

                        $imagemRepositorio->deleta_imagem($_POST["id_imagem"]);
                        $destino = "../views/admin/editar_curso.php?id=" . $_POST["id_curso"];

                        if ($cursoRepositorio->editarImagemCurso($_POST["id_curso"], $id_imagem, $dataMudanca)) {
                            exibirMensagemEredirecionar(MSG_SUCESSO, $destino);
                        } else {
                            exibirMensagemEredirecionar("Erro ao editar a imagem do usuário", $destino);
                        }
                    } else {
                        exibirMensagemEredirecionar("Erro: Imagem não salva, tente novamente", $destino);
                    }
                } else {
                    exibirMensagemEredirecionar("Erro: Imagem não enviada, tente novamente", $destino);
                }
            } else {
                exibirMensagemEredirecionar("Formato de imagem inválido", $destino);
            }
        } elseif ($_POST["tipo"] == "excluir") {
            $id_curso = $_POST["id_curso"];

            if ($cursoRepositorio->excluirCurso($id_curso)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=cursos');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=cursos');
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/admin/index.php?pagina=cursos');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/admin/index.php?pagina=cursos');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
