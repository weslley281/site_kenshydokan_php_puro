<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/site_kenshydokan/repositorios/dojoRepositorio.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/site_kenshydokan/models/dojoModel.php';

// Define as mensagens e o destino do redirecionamento
define("MSG_ERRO", "Erro: Ocorreu um erro ao processar a operação. Tente novamente.");
define("MSG_SUCESSO", "Operação realizada com sucesso.");
define("DESTINO", "../views/admin/index.php?pagina=dojos");

function exibirMensagemEredirecionar($mensagem, $destino)
{
    echo "<script>alert('$mensagem'); window.location.href = '$destino';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['tipo'])) {
    exibirMensagemEredirecionar("Requisição inválida.", DESTINO);
}

$dojoRepositorio = new DojoRepositorio();
$tipo = $_POST['tipo'];

switch ($tipo) {
    case 'inserir':
        $dojo = new DojoModel();
        $dojo->setRazaoSocial($_POST['razao_social']);
        $dojo->setNomeFantasia($_POST['nome_fantasia']);
        $dojo->setCnpj($_POST['cnpj']);
        $dojo->setIdFiliadoResponsavel($_POST['id_filiado_responsavel']);
        $dojo->setTelefone($_POST['telefone']);
        $dojo->setCelular($_POST['celular']);
        $dojo->setEmail($_POST['email']);
        $dojo->setCep($_POST['cep']);
        $dojo->setEndereco($_POST['endereco']);
        $dojo->setCidade($_POST['cidade']);
        $dojo->setEstado($_POST['estado']);
        $dojo->setDataFiliacao($_POST['data_filiacao']);
        $dojo->setStatus('ativo');

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $nome_arquivo = uniqid() . '_' . basename($_FILES['imagem']['name']);
            $caminho_arquivo = $_SERVER['DOCUMENT_ROOT'] . '/site_kenshydokan/img/' . $nome_arquivo;
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_arquivo)) {
                $dojo->setImagem($nome_arquivo);
            } else {
                $dojo->setImagem('sem_imagem.png');
            }
        } else {
            $dojo->setImagem('sem_imagem.png');
        }

        if ($dojoRepositorio->criarDojo($dojo)) {
            exibirMensagemEredirecionar(MSG_SUCESSO, DESTINO);
        } else {
            exibirMensagemEredirecionar(MSG_ERRO, DESTINO);
        }
        break;

    case 'editar':
        $dojo = new DojoModel();
        $dojo->setId($_POST['id']);
        $dojo->setRazaoSocial($_POST['razao_social']);
        $dojo->setNomeFantasia($_POST['nome_fantasia']);
        $dojo->setCnpj($_POST['cnpj']);
        $dojo->setIdFiliadoResponsavel($_POST['id_filiado_responsavel']);
        $dojo->setTelefone($_POST['telefone']);
        $dojo->setCelular($_POST['celular']);
        $dojo->setEmail($_POST['email']);
        $dojo->setCep($_POST['cep']);
        $dojo->setEndereco($_POST['endereco']);
        $dojo->setCidade($_POST['cidade']);
        $dojo->setEstado($_POST['estado']);
        $dojo->setDataFiliacao($_POST['data_filiacao']);
        $dojo->setStatus($_POST['status']);

        $imagem = $_POST['imagem_antiga'];
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $nome_arquivo = uniqid() . '_' . basename($_FILES['imagem']['name']);
            $caminho_arquivo = $_SERVER['DOCUMENT_ROOT'] . '/site_kenshydokan/img/' . $nome_arquivo;
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho_arquivo)) {
                $imagem = $nome_arquivo;
                // Opcional: excluir a imagem antiga
                if ($_POST['imagem_antiga'] && $_POST['imagem_antiga'] != 'sem_imagem.png') {
                    @unlink($_SERVER['DOCUMENT_ROOT'] . '/site_kenshydokan/img/' . $_POST['imagem_antiga']);
                }
            }
        }
        $dojo->setImagem($imagem);

        if ($dojoRepositorio->editarDojo($dojo)) {
            exibirMensagemEredirecionar(MSG_SUCESSO, DESTINO);
        } else {
            exibirMensagemEredirecionar(MSG_ERRO, DESTINO);
        }
        break;

    case 'excluir':
        $id = $_POST['id'];
        
        $dojo = $dojoRepositorio->buscarDojoPorId($id);
        if($dojo && $dojo->getImagem() && $dojo->getImagem() != 'sem_imagem.png'){
            @unlink($_SERVER['DOCUMENT_ROOT'] . '/site_kenshydokan/img/' . $dojo->getImagem());
        }
        
        if ($dojoRepositorio->excluirDojo($id)) {
            exibirMensagemEredirecionar(MSG_SUCESSO, DESTINO);
        } else {
            exibirMensagemEredirecionar(MSG_ERRO, DESTINO);
        }
        break;

    default:
        exibirMensagemEredirecionar("Tipo de operação inválido.", DESTINO);
        break;
}
