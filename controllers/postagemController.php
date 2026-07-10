<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/publicacaoModel.php";
    include_once "../models/imagemModel.php";

    define("MSG_ERRO", "Erro: Ocorreu um erro. Tente novamente.");
    define("MSG_SUCESSO", "Operação realizada com sucesso.");

    // Verifica se o usuário tem permissão mínima para postar
    if (!isset($_SESSION["id_usuario"]) || ($_SESSION["nivel"] !== "admin" && $_SESSION["nivel"] !== "sensei")) {
        exibirMensagemEredirecionar("Você não tem permissão para realizar esta operação.", '../views/login.php');
        exit();
    }

    if (isset($_POST["tipo"])) {
        $dataMudanca = date("Y-m-d");
        $publicacaoModel = new Publicacao();

        if ($_POST["tipo"] == "inserir") {
            $titulo = $_POST["titulo"];
            $conteudo = $_POST["conteudo"];
            $id_usuario = $_SESSION["id_usuario"]; // Garante que usa o ID da sessão do autor logado

            // Processa Upload da Imagem de Capa
            $id_imagem = null;
            if (isset($_FILES['capa']) && $_FILES['capa']['error'] == UPLOAD_ERR_OK && $_FILES['capa']['size'] > 0) {
                $imagemModel = new Imagem();
                $diretorioUpload = "../img/";
                $extensaoImagem = strtolower(pathinfo($_FILES["capa"]["name"], PATHINFO_EXTENSION));

                if (in_array($extensaoImagem, ["jpg", "jpeg", "gif", "png", "webp"])) {
                    $nomeImagem = uniqid() . '.' . $extensaoImagem;
                    $caminho = $diretorioUpload . $nomeImagem;

                    if (move_uploaded_file($_FILES["capa"]["tmp_name"], $caminho)) {
                        $novaImagem = new Imagem($nomeImagem, $caminho, $dataMudanca);
                        if ($imagemModel->registrar_imagem($novaImagem)) {
                            $id_imagem = Imagem::procura_id_imagem($nomeImagem);
                        }
                    }
                }
            }

            // Regra: admin posta diretamente como aprovado; sensei aguarda aprovação
            $status = ($_SESSION["nivel"] === "admin") ? "aprovado" : "aguardando";

            $publicacao = new Publicacao($id_usuario, $id_imagem, $titulo, $conteudo, $dataMudanca, $status);

            if ($publicacaoModel->criarPublicacao($publicacao)) {
                exibirMensagemEredirecionar(MSG_SUCESSO, '../views/perfil/suas_postagens.php');
            } else {
                exibirMensagemEredirecionar(MSG_ERRO, '../views/perfil/criar_postagem.php');
            }

        } elseif ($_POST["tipo"] == "editar") {
            $id_publicacao = $_POST["id_publicacao"];
            $titulo = $_POST["titulo"];
            $conteudo = $_POST["conteudo"];

            // Busca postagem atual
            $postagem_atual = Publicacao::buscarPostagemPorId($id_publicacao);
            if (!$postagem_atual) {
                exibirMensagemEredirecionar("Postagem não encontrada.", '../views/perfil/suas_postagens.php');
                exit();
            }

            // Verifica propriedade: somente o autor ou admin pode editar
            if ($postagem_atual["id_usuario"] != $_SESSION["id_usuario"] && $_SESSION["nivel"] !== "admin") {
                exibirMensagemEredirecionar("Você não tem permissão para editar esta postagem.", '../views/perfil/suas_postagens.php');
                exit();
            }

            // Processa Upload da nova Imagem de Capa (se enviada)
            $id_imagem = $postagem_atual["id_imagem"];
            if (isset($_FILES['capa']) && $_FILES['capa']['error'] == UPLOAD_ERR_OK && $_FILES['capa']['size'] > 0) {
                $imagemModel = new Imagem();
                $diretorioUpload = "../img/";
                $extensaoImagem = strtolower(pathinfo($_FILES["capa"]["name"], PATHINFO_EXTENSION));

                if (in_array($extensaoImagem, ["jpg", "jpeg", "gif", "png", "webp"])) {
                    $nomeImagem = uniqid() . '.' . $extensaoImagem;
                    $caminho = $diretorioUpload . $nomeImagem;

                    if (move_uploaded_file($_FILES["capa"]["tmp_name"], $caminho)) {
                        $novaImagem = new Imagem($nomeImagem, $caminho, $dataMudanca);
                        if ($imagemModel->registrar_imagem($novaImagem)) {
                            // Deleta a imagem de capa antiga (física e registro)
                            if (!empty($postagem_atual["id_imagem"])) {
                                $dados_imagem_antiga = Imagem::procura_imagem($postagem_atual["id_imagem"]);
                                if ($dados_imagem_antiga) {
                                    $caminho_antigo = $dados_imagem_antiga["caminho"];
                                    $caminho_antigo_rel = str_starts_with($caminho_antigo, "../") ? $caminho_antigo : "../" . $caminho_antigo;
                                    if (file_exists($caminho_antigo_rel)) {
                                        unlink($caminho_antigo_rel);
                                    }
                                }
                                $imagemModel->deleta_imagem($postagem_atual["id_imagem"]);
                            }
                            $id_imagem = Imagem::procura_id_imagem($nomeImagem);
                        }
                    }
                }
            }

            // Regra: admin mantém status atual; sensei volta para 'aguardando'
            $status = ($_SESSION["nivel"] === "admin") ? $postagem_atual["status"] : "aguardando";

            // Mantém o autor original da postagem
            $publicacao = new Publicacao($postagem_atual["id_usuario"], $id_imagem, $titulo, $conteudo, $dataMudanca, $status);

            if ($publicacaoModel->editar_publicacao($id_publicacao, $publicacao)) {
                if ($_SESSION["nivel"] === "admin") {
                    exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=postagens');
                } else {
                    exibirMensagemEredirecionar(MSG_SUCESSO, '../views/perfil/suas_postagens.php');
                }
            } else {
                if ($_SESSION["nivel"] === "admin") {
                    exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/editar_postagem.php?id=' . $id_publicacao);
                } else {
                    exibirMensagemEredirecionar(MSG_ERRO, '../views/perfil/editar_postagem.php?id=' . $id_publicacao);
                }
            }

        } elseif ($_POST["tipo"] == "excluir") {
            $id_publicacao = $_POST["id_publicacao"];

            $postagem_atual = Publicacao::buscarPostagemPorId($id_publicacao);
            if (!$postagem_atual) {
                exibirMensagemEredirecionar("Postagem não encontrada.", '../views/perfil/suas_postagens.php');
                exit();
            }

            // Verifica propriedade: somente o autor ou admin pode excluir
            if ($postagem_atual["id_usuario"] != $_SESSION["id_usuario"] && $_SESSION["nivel"] !== "admin") {
                exibirMensagemEredirecionar("Você não tem permissão para excluir esta postagem.", '../views/perfil/suas_postagens.php');
                exit();
            }

            // Deleta imagem de capa associada antes de excluir o post
            if (!empty($postagem_atual["id_imagem"])) {
                $imagemModel = new Imagem();
                $dados_imagem = Imagem::procura_imagem($postagem_atual["id_imagem"]);
                if ($dados_imagem) {
                    $caminho_img = $dados_imagem["caminho"];
                    $caminho_img_rel = str_starts_with($caminho_img, "../") ? $caminho_img : "../" . $caminho_img;
                    if (file_exists($caminho_img_rel)) {
                        unlink($caminho_img_rel);
                    }
                }
                $imagemModel->deleta_imagem($postagem_atual["id_imagem"]);
            }

            if (Publicacao::excluir_publicacao($id_publicacao)) {
                if ($_SESSION['nivel'] === "admin") {
                    exibirMensagemEredirecionar(MSG_SUCESSO, '../views/admin/index.php?pagina=postagens');
                } else {
                    exibirMensagemEredirecionar(MSG_SUCESSO, '../views/perfil/suas_postagens.php');
                }
            } else {
                if ($_SESSION['nivel'] === "admin") {
                    exibirMensagemEredirecionar(MSG_ERRO, '../views/admin/index.php?pagina=postagens');
                } else {
                    exibirMensagemEredirecionar(MSG_ERRO, '../views/perfil/suas_postagens.php');
                }
            }
        } else {
            exibirMensagemEredirecionar("Tipo de operação inválido", '../views/perfil/suas_postagens.php');
        }
    } else {
        exibirMensagemEredirecionar("Preencha todos os dados", '../views/perfil/suas_postagens.php');
    }
} else {
    exibirMensagemEredirecionar("Não é uma requisição post", '../views/perfil/suas_postagens.php');
}

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script language='javascript'>window.alert('$mensagem'); </script>";
    echo "<script language='javascript'>window.location='$destino'; </script>";
    exit;
}
?>