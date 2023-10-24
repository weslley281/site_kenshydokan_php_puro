<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/usuarioModel.php";
    include_once "../models/imagemModel.php";
    include_once "../repositorios/usuarioRepositorio.php";
    include_once "../repositorios/imagemRepositorio.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $dataMudanca = date("Y-m-d");

        if ($_POST["tipo"] == "inserir") {

            $imagemRepositorio = new ImagemRepositorio;
            $usuarioRepositorio = new UsuarioRepositorio;
            if ($usuarioRepositorio::buscarUsuarioExistente($_POST["email"])) {

                exibirMensagemEredirecionar("Erro: Usuário já existe", '../views/login.php');
            } else {

                $senhaSegura = password_hash($_POST["senha"], PASSWORD_DEFAULT);

                $diretorioUpload = "../img/";
                $nomeImagem = uniqid() . $_FILES["imagem"]["name"];
                $caminho = $diretorioUpload . $nomeImagem;

                $extensaoImagem = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));

                if (in_array($extensaoImagem, ["jpg", "jpeg", "gif", "png"])) {
                    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho)) {
                        $imagemModel = new Imagem($nomeImagem, $caminho, $dataMudanca);

                        if ($imagemRepositorio->registrar_imagem($imagemModel)) {
                            $id_imagem = $imagemRepositorio::procura_id_imagem($nomeImagem);

                            $usuarioModel = new Usuario($_POST["nome"], $_POST["id_fil"], $id_imagem, $_POST["email"], $_POST["telefone"], $dataMudanca, $senhaSegura);

                            if ($usuarioRepositorio->criarUsuario($usuarioModel)) {
                                exibirMensagemEredirecionar("Usuário criado com sucesso", '../views/login.php');
                            } else {
                                exibirMensagemEredirecionar("Erro: Usuário não cadastrado, tente novamente", '../views/cadastrar.php');
                            }
                        } else {
                            exibirMensagemEredirecionar("Erro: Imagem não salva, tente novamente", '../views/cadastrar.php');
                        }
                    } else {
                        exibirMensagemEredirecionar("Erro: Imagem não salva, tente novamente", '../views/cadastrar.php');
                    }
                } else {
                    exibirMensagemEredirecionar("Erro: Imagem não salva, tente novamente", '../views/cadastrar.php');
                }
            }
        } elseif ($_POST["tipo"] == "edidar") {
            $nome = $_POST["nome"];
            $id_fil = $_POST["id_fil"];
            $id_imagem = $_POST["id_imagem"];
            $email = $_POST["email"];
            $telefone = $_POST["telefone"];

            $usuarioRepositorio = new UsuarioRepositorio;
            $usuarioModel = new Usuario($_POST["nome"], $_POST["id_fil"], $_POST["id_imagem"], $_POST["email"], $_POST["telefone"], $dataMudanca);

            $usuarioModel->setNivel($_POST["nivel"]);

            if ($usuarioRepositorio->editarUsuario($_POST["id_usuario"], $usuarioModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/editar_perfil.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/editar_perfil.php');
            }
        } elseif ($_POST["tipo"] == "edidar_admin") {
            $nome = $_POST["nome"];
            $id_fil = $_POST["id_fil"];
            $id_imagem = $_POST["id_imagem"];
            $nivel = $_POST["nivel"];
            $email = $_POST["email"];
            $telefone = $_POST["telefone"];

            $usuarioRepositorio = new UsuarioRepositorio;
            $usuarioModel = new Usuario($_POST["nome"], $_POST["id_fil"], $_POST["id_imagem"], $_POST["email"], $_POST["telefone"], $dataMudanca, null, $nivel);

            $usuarioModel->setNivel($_POST["nivel"]);

            if ($usuarioRepositorio->editarUsuario($_POST["id_usuario"], $usuarioModel)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/editar_perfil.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/editar_perfil.php');
            }
        } elseif ($_POST["tipo"] == "editar_nivel" && $_SESSION["nivel"] == "admin") {

            if ($usuarioRepositorio::editarNivelUsuario($_POST["id_usuario"], $_POST["nivel"], $dataMudanca)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/editar_perfil.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/editar_perfil.php');
            }
        } elseif ($_POST["tipo"] == "editar_imagem") {

            $imagemRepositorio = new ImagemRepositorio;
            $usuarioRepositorio = new UsuarioRepositorio;

            $diretorioUpload = "../img/";
            $nomeImagem = uniqid() . $_FILES["imagem"]["name"];
            $caminho = $diretorioUpload . $nomeImagem;

            $extensaoImagem = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));

            if (in_array($extensaoImagem, ["jpg", "jpeg", "gif", "png"])) {
                if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho)) {
                    $imagemModel = new Imagem($nomeImagem, $caminho, $dataMudanca);

                    if ($imagemRepositorio->registrar_imagem($imagemModel)) {
                        $id_imagem = $imagemRepositorio::procura_id_imagem($nomeImagem);
                        echo "id da nova imagem: $id_imagem";

                        $dados_imagem_antiga = $imagemRepositorio::procura_imagem($_POST["id_imagem"]);

                        $caminho = $dados_imagem_antiga != null ? file_exists($dados_imagem_antiga["caminho"]) : "";

                        if (file_exists($caminho)) {
                            unlink($caminhoImagemAntiga);
                        }

                        $imagemRepositorio->deleta_imagem($_POST["id_imagem"]);

                        if ($usuarioRepositorio->editarImagemUsuario($_POST["id_usuario"], $id_imagem, $dataMudanca)) {
                            exibirMensagemEredirecionar(MSG_SUCESSO, '../views/editar_perfil.php');
                        } else {
                            exibirMensagemEredirecionar("Erro ao editar a imagem do usuário", '../views/editar_perfil.php');
                        }
                    } else {
                        exibirMensagemEredirecionar("Erro: Imagem não salva, tente novamente", '../views/editar_perfil.php');
                    }
                } else {
                    exibirMensagemEredirecionar("Erro: Imagem não enviada, tente novamente", '../views/editar_perfil.php');
                }
            } else {
                exibirMensagemEredirecionar("Formato de imagem inválido", '../views/editar_perfil.php');
            }
        } elseif ($_POST["tipo"] == "editar_senha") {
            $usuarioRepositorio = new UsuarioRepositorio();

            if ($_POST["nova_senha"] != $_POST["senha2"]) {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/login.php');
                exit();
            }

            $id_usuario = $_POST["id_usuario"];
            $nova_senha = $_POST["nova_senha"];

            $senhaSegura = password_hash($nova_senha, PASSWORD_DEFAULT);

            if ($usuarioRepositorio->editarSenhaUsuario($id_usuario, $senhaSegura)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/editar_perfil.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/editar_perfil.php');
            }
        } elseif ($_POST["tipo"] == "deletar") {
            $id_usuario = $_POST["id_usuario"];

            if ($usuarioRepositorio->excluirUsuario($id_usuario)) {
                exibirMensagemEredirecionar("Usuário excluído com sucesso", '../views/login.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/editar_perfil.php');
            }
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/cadastrar.php');
    }
} else {
    exibirMensagemEredirecionar("A requisição não é POST", '../views/cadastrar.php');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
