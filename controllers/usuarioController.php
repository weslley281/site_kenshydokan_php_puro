<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/usuarioModel.php";
    include_once "../models/imagemModel.php";
    include_once "../repositorios/usuarioRepositorio.php";
    include_once "../repositorios/imagemRepositorio.php";

    if (isset($_POST["tipo"])) {
        $dataMudanca = date("Y-m-d");

        if ($_POST["tipo"] == "inserir") {

            $imagemRepositorio = new ImagemRepositorio;
            $usuarioRepositorio = new UsuarioRepositorio;
            if ($usuarioRepositorio::buscarUsuarioExistente($_POST["email"])) {
                echo "<script language='javascript'>window.alert('Usuário já existe'); </script>";
                echo "<script language='javascript'>window.location='../views/login.php'; </script>";

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
                                echo "<script language='javascript'>window.alert('Usuário criado com sucesso'); </script>";
                                echo "<script language='javascript'>window.location='../views/login.php'; </script>";
                            } else {
                                echo "<script language='javascript'>window.alert('Erro: Usuário não cadastrado, tente novamente'); </script>";
                                echo "<script language='javascript'>window.location='../views/cadastrar.php'; </script>";
                            }
                        } else {
                            echo "<script language='javascript'>window.alert('Erro: Imagem não salva, tente novamente'); </script>";
                            echo "<script language='javascript'>window.location='../views/cadastrar.php'; </script>";
                        }
                    } else {
                        echo "<script language='javascript'>window.alert('Erro: Imagem não enviada, tente novamente'); </script>";
                        echo "<script language='javascript'>window.location='../views/cadastrar.php'; </script>";
                    }
                } else {
                    echo "<script language='javascript'>window.alert('Formato de imagem inválido'); </script>";
                    echo "<script language='javascript'>window.location='../views/cadastrar.php'; </script>";
                }
            }
        } elseif ($_POST["tipo"] == "edidar") {
            $usuarioRepositorio = new UsuarioRepositorio;

            $usuarioModel = new Usuario($_POST["nome"], $_POST["id_fil"], $id_imagem, $_POST["email"], $_POST["telefone"], $dataMudanca);

            if ($usuarioRepositorio->editarUsuario($_POST["id_usuario"], $usuarioModel)) {
                echo "<script language='javascript'>window.alert('Usuário editado com sucesso'); </script>";
                echo "<script language='javascript'>window.location='../views/editar_perfil.php'; </script>";
            } else {
                echo "<script language='javascript'>window.alert('Erro: Usuário não cadastrado, tente novamente'); </script>";
                echo "<script language='javascript'>window.location='../views/editar_perfil.php'; </script>";
            }

        } elseif ($_POST["tipo"] == "editar_nivel" && $_SESSION["nivel"] == "admin") {

            if ($usuarioRepositorio::editarNivelUsuario($_POST["id_usuario"], $_POST["nivel"], $dataMudanca)) {
                echo "<script language='javascript'>window.alert('Usuário editado com sucesso'); </script>";
                echo "<script language='javascript'>window.location='../views/editar_perfil.php'; </script>";
            } else {
                echo "<script language='javascript'>window.alert('Erro: Usuário não cadastrado, tente novamente'); </script>";
                echo "<script language='javascript'>window.location='../views/editar_perfil.php'; </script>";
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

                        $dados_imagem_antiga = $imagemRepositorio::procura_imagem($_POST["id_imagem"]);
                        $caminhoImagemAntiga = $dados_imagem_antiga["caminho"];

                        // Exclua o arquivo de imagem antiga no sistema de arquivos
                        if (file_exists($caminhoImagemAntiga)) {
                            unlink($caminhoImagemAntiga);
                        }

                        if ($imagemRepositorio->deleta_imagem($_POST["id_imagem"])) {
                            if ($usuarioRepositorio->editarImagemUsuario($id_imagem, $dataMudanca, $_POST["id_usuario"])) {
                                // Redirecionar após o sucesso
                                //header("Location: ../views/editar_perfil.php.php");
                                //exit;
                            } else {
                                // Lida com falha na edição de imagem do usuário
                                echo "<script language='javascript'>window.alert('Erro ao editar a imagem do usuário'); </script>";
                                //header("Location: ../views/editar_perfil.php");
                                //exit;
                            }
                        } else {
                            // Lida com erro na exclusão da imagem do repositório
                            echo "<script language='javascript'>window.alert('Erro: Imagem não deletada do repositório, tente novamente'); </script>";
                            //header("Location: ../views/editar_perfil.php");
                            //exit;
                        }
                    } else {
                        // Lida com erro no registro da imagem
                        echo "<script language='javascript'>window.alert('Erro: Imagem não salva, tente novamente'); </script>";
                        //header("Location: ../views/editar_perfil.php");
                        //exit;
                    }
                } else {
                    // Lida com erro no envio da imagem
                    echo "<script language='javascript'>window.alert('Erro: Imagem não enviada, tente novamente'); </script>";
                    //header("Location: ../views/editar_perfil.php");
                    //exit;
                }
            } else {
                // Lida com formato de imagem inválido
                echo "<script language='javascript'>window.alert('Formato de imagem inválido'); </script>";
                //header("Location: ../views/editar_perfil.php");
                //exit;
            }

        } elseif ($_POST["tipo"] == "editar_senha") {

        } elseif ($_POST["tipo"] == "deletar") {

        }
    } else {
        echo "<script language='javascript'>window.alert('Preencha todos os dados'); </script>";
        echo "<script language='javascript'>window.location='../views/cadastrar.php'; </script>";
    }
} else {
    echo "<script language='javascript'>window.alert('A requisição não é POST'); </script>";
    echo "<script language='javascript'>window.location='../views/cadastrar.php'; </script>";
}
