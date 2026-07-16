<?php
session_start();


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/usuarioModel.php";
    include_once "../models/imagemModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    if (isset($_POST["tipo"])) {
        $dataMudanca = date("Y-m-d");

        $usuarioModel = new Usuario();
        $imagemModel = new Imagem();

        if ($_POST["tipo"] == "inserir") {

            if (Usuario::buscarUsuarioExistente($_POST["email"])) {

                exibirMensagemEredirecionar("Erro: Usuário já existe", '../views/login.php');
            } else {

                $senhaSegura = password_hash($_POST["senha"], PASSWORD_DEFAULT);
                $id_imagem = null;

                if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK && $_FILES['imagem']['size'] > 0) {
                    $diretorioUpload = "../img/";
                    $nomeImagem = uniqid() . $_FILES["imagem"]["name"];
                    $caminho = $diretorioUpload . $nomeImagem;

                    $extensaoImagem = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));

                    if (in_array($extensaoImagem, ["jpg", "jpeg", "gif", "png"])) {
                        if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho)) {
                            $novaImagem = new Imagem($nomeImagem, $caminho, $dataMudanca);

                            if ($imagemModel->registrar_imagem($novaImagem)) {
                                $id_imagem = Imagem::procura_id_imagem($nomeImagem);
                            } else {
                                exibirMensagemEredirecionar("Erro: Imagem não salva, tente novamente 1", '../views/cadastrar.php');
                            }
                        } else {
                            exibirMensagemEredirecionar("Erro: Imagem não salva, tente novamente 2", '../views/cadastrar.php');
                        }
                    } else {
                        exibirMensagemEredirecionar("Erro: Imagem não salva, tente novamente 3", '../views/cadastrar.php');
                    }
                }

                $novoUsuario = new Usuario($_POST["nome"], $_POST["id_fil"], $id_imagem, $_POST["email"], $_POST["telefone"], $dataMudanca, $senhaSegura);

                if ($usuarioModel->criarUsuario($novoUsuario)) {
                    exibirMensagemEredirecionar("Usuário criado com sucesso", '../views/login.php');
                } else {
                    exibirMensagemEredirecionar("Erro: Usuário não cadastrado, tente novamente", '../views/cadastrar.php');
                }
            }
        } elseif ($_POST["tipo"] == "edidar") {
            $nome = $_POST["nome"];
            $id_fil = $_POST["id_fil"];
            $id_imagem = $_POST["id_imagem"];
            $email = $_POST["email"];
            $telefone = $_POST["telefone"];

            $usuarioEditado = new Usuario($_POST["nome"], $_POST["id_fil"], $_POST["id_imagem"], $_POST["email"], $_POST["telefone"], $dataMudanca);

            $usuarioEditado->setNivel($_POST["nivel"]);

            if ($usuarioModel->editarUsuario($_POST["id_usuario"], $usuarioEditado)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/perfil/editar_perfil.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/perfil/editar_perfil.php');
            }
        } elseif ($_POST["tipo"] == "edidar_admin") {
            $nome = $_POST["nome"];
            $id_fil = $_POST["id_fil"];
            $id_imagem = $_POST["id_imagem"];
            $nivel = $_POST["nivel"];
            $email = $_POST["email"];
            $telefone = $_POST["telefone"];

            $usuarioEditado = new Usuario($_POST["nome"], $_POST["id_fil"], $_POST["id_imagem"], $_POST["email"], $_POST["telefone"], $dataMudanca, null, $nivel);

            $usuarioEditado->setNivel($_POST["nivel"]);

            if ($usuarioModel->editarUsuario($_POST["id_usuario"], $usuarioEditado)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/perfil/editar_perfil.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/perfil/editar_perfil.php');
            }
        } elseif ($_POST["tipo"] == "editar_nivel" && $_SESSION["nivel"] == "admin") {

            if (Usuario::editarNivelUsuario($_POST["id_usuario"], $_POST["nivel"], $dataMudanca)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/perfil/editar_perfil.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/perfil/editar_perfil.php');
            }
        } elseif ($_POST["tipo"] == "editar_imagem") {

            $diretorioUpload = "../img/";
            $nomeImagem = uniqid() . $_FILES["imagem"]["name"];
            var_dump($nomeImagem);
            $caminho = $diretorioUpload . $nomeImagem;

            $extensaoImagem = strtolower(pathinfo($caminho, PATHINFO_EXTENSION));
            var_dump($extensaoImagem);

            if (in_array($extensaoImagem, ["jpg", "jpeg", "gif", "png"])) {
                var_dump($_FILES["imagem"]["tmp_name"], $caminho);
                if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho)) {
                    $novaImagem = new Imagem($nomeImagem, $caminho, $dataMudanca);

                    if ($imagemModel->registrar_imagem($novaImagem)) {
                        $id_imagem = Imagem::procura_id_imagem($nomeImagem);

                        $dados_imagem_antiga = Imagem::procura_imagem($_POST["id_imagem"]);

                        $caminho = $dados_imagem_antiga != null ? file_exists($dados_imagem_antiga["caminho"]) : "";

                        if (file_exists($caminho)) {
                            unlink($caminho);
                        }

                        $imagemModel->deleta_imagem($_POST["id_imagem"]);

                        if (Usuario::editarImagemUsuario($_POST["id_usuario"], $id_imagem, $dataMudanca)) {
                            exibirMensagemEredirecionar(MSG_SUCESSO, '../views/perfil/editar_perfil.php');
                        } else {
                            exibirMensagemEredirecionar("Erro ao editar a imagem do usuário", '../views/perfil/editar_perfil.php');
                        }
                    } else {
                        exibirMensagemEredirecionar("Erro: Imagem não salva, tente novamente", '../views/perfil/editar_perfil.php');
                    }
                } else {
                    var_dump(error_get_last());
                    exibirMensagemEredirecionar("Erro: Imagem não enviada, tente novamente", '../views/perfil/editar_perfil.php');
                }
            } else {
                exibirMensagemEredirecionar("Formato de imagem inválido", '../views/perfil/editar_perfil.php');
            }
        } elseif ($_POST["tipo"] == "editar_senha") {

            if ($_POST["nova_senha"] != $_POST["senha2"]) {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/login.php');
                exit();
            }

            $id_usuario = $_POST["id_usuario"];
            $nova_senha = $_POST["nova_senha"];

            $senhaSegura = password_hash($nova_senha, PASSWORD_DEFAULT);

            if ($usuarioModel->editarSenhaUsuario($id_usuario, $senhaSegura)) {
                $usuarioModel->editarTokenUsuario($id_usuario, "");
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/perfil/editar_perfil.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/perfil/editar_perfil.php');
            }
        } elseif ($_POST["tipo"] == "deletar") {
            $id_usuario = $_POST["id_usuario"];

            if (Usuario::excluirUsuario($id_usuario)) {
                exibirMensagemEredirecionar("Usuário excluído com sucesso", '../views/admin/index.php?pagina=usuarios');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=usuarios');
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
