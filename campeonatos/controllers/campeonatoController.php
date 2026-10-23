<?php
/**
 * Controlador de Campeonatos
 * Padrão IDÊNTICO ao site principal (controllers/campeonatoController.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/auditoriaModel.php";
include_once __DIR__ . "/alertHelper.php";

if (!isset($_SESSION["id_usuario"])) {
    exibirMensagemEredirecionar("Acesso restrito. Faça login para continuar.", "../views/login.php");
}

include_once __DIR__ . "/csrfHelper.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrfExigir('../views/inicio.php');
    exigirPerfil([]);
    $campeonatoModel = new Campeonato();
    $tipo = $_POST["tipo"] ?? "";

    if ($tipo === "criar_campeonato") {
        $dados = [
            'titulo' => trim($_POST['titulo'] ?? ''),
            'subtitulo' => trim($_POST['subtitulo'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'edicao' => trim($_POST['edicao'] ?? ''),
            'ano' => (int)($_POST['ano'] ?? date('Y')),
            'data_inicio' => $_POST['data_inicio'] ?? date('Y-m-d'),
            'data_fim' => $_POST['data_fim'] ?? date('Y-m-d'),
            'data_pesagem' => !empty($_POST['data_pesagem']) ? $_POST['data_pesagem'] : null,
            'data_referencia_idade' => !empty($_POST['data_referencia_idade']) ? $_POST['data_referencia_idade'] : ($_POST['data_inicio'] ?? date('Y-m-d')),
            'local_nome' => trim($_POST['local_nome'] ?? ''),
            'local_endereco' => trim($_POST['local_endereco'] ?? ''),
            'cidade' => trim($_POST['cidade'] ?? ''),
            'estado' => strtoupper(trim($_POST['estado'] ?? 'SP')),
            'status' => $_POST['status'] ?? 'rascunho',
            'limite_atletas' => !empty($_POST['limite_atletas']) ? (int)$_POST['limite_atletas'] : null
        ];

        if (empty($dados['titulo']) || empty($dados['local_nome'])) {
            exibirMensagemEredirecionar("Informe o título e o local do campeonato.", "../views/criar_campeonato.php");
        }

        $id = $campeonatoModel->criarCampeonato($dados);
        if ($id > 0) {
            Auditoria::log("CRIAR_CAMPEONATO", "campeonato_eventos", $id, null, $dados, null, $id);
            exibirMensagemEredirecionar("Campeonato criado com sucesso!", "../views/detalhes_campeonato.php?id=" . $id);
        } else {
            exibirMensagemEredirecionar("Erro ao criar campeonato. Tente novamente.", "../views/criar_campeonato.php");
        }

    } elseif ($tipo === "editar_campeonato") {
        $id = (int)($_POST['id_campeonato'] ?? 0);
        $dados = [
            'titulo' => trim($_POST['titulo'] ?? ''),
            'subtitulo' => trim($_POST['subtitulo'] ?? ''),
            'descricao' => trim($_POST['descricao'] ?? ''),
            'edicao' => trim($_POST['edicao'] ?? ''),
            'ano' => (int)($_POST['ano'] ?? date('Y')),
            'data_inicio' => $_POST['data_inicio'] ?? date('Y-m-d'),
            'data_fim' => $_POST['data_fim'] ?? date('Y-m-d'),
            'data_pesagem' => !empty($_POST['data_pesagem']) ? $_POST['data_pesagem'] : null,
            'data_referencia_idade' => !empty($_POST['data_referencia_idade']) ? $_POST['data_referencia_idade'] : ($_POST['data_inicio'] ?? date('Y-m-d')),
            'local_nome' => trim($_POST['local_nome'] ?? ''),
            'local_endereco' => trim($_POST['local_endereco'] ?? ''),
            'cidade' => trim($_POST['cidade'] ?? ''),
            'estado' => strtoupper(trim($_POST['estado'] ?? 'SP')),
            'status' => $_POST['status'] ?? 'rascunho',
            'limite_atletas' => !empty($_POST['limite_atletas']) ? (int)$_POST['limite_atletas'] : null
        ];

        if ($campeonatoModel->editarCampeonato($id, $dados)) {
            Auditoria::log("EDITAR_CAMPEONATO", "campeonato_eventos", $id, null, $dados, null, $id);
            exibirMensagemEredirecionar("Campeonato atualizado com sucesso!", "../views/detalhes_campeonato.php?id=" . $id);
        } else {
            exibirMensagemEredirecionar("Erro ao atualizar campeonato.", "../views/editar_campeonato.php?id=" . $id);
        }

    } elseif ($tipo === "excluir_campeonato") {
        $id = (int)($_POST['id_campeonato'] ?? 0);
        if ($campeonatoModel->excluirCampeonato($id)) {
            Auditoria::log("EXCLUIR_CAMPEONATO", "campeonato_eventos", $id, null, null, null, $id);
            exibirMensagemEredirecionar("Campeonato excluído com sucesso.", "../views/campeonatos.php");
        } else {
            exibirMensagemEredirecionar("Erro ao excluir campeonato.", "../views/detalhes_campeonato.php?id=" . $id);
        }

    } elseif ($tipo === "encerrar_campeonato") {
        $id = (int)($_POST['id_campeonato'] ?? 0);
        $campeonato = $campeonatoModel->buscarPorId($id);
        if (!$campeonato) {
            exibirMensagemEredirecionar("Campeonato não encontrado.", "../views/inicio.php");
        }
        if ($campeonato['status'] === 'cancelado') {
            exibirMensagemEredirecionar("Um campeonato cancelado não pode ser encerrado.", "../views/detalhes_campeonato.php?id=" . $id);
        }
        if ($campeonato['status'] === 'finalizado') {
            exibirMensagemEredirecionar("Este campeonato já está encerrado.", "../views/detalhes_campeonato.php?id=" . $id);
        }

        if ($campeonatoModel->atualizarStatus($id, 'finalizado')) {
            Auditoria::log(
                "ENCERRAR_CAMPEONATO",
                "campeonato_eventos",
                $id,
                ['status' => $campeonato['status']],
                ['status' => 'finalizado'],
                null,
                $id
            );
            exibirMensagemEredirecionar(
                "Campeonato encerrado. Os certificados já podem ser emitidos e consultados no perfil dos atletas.",
                "../views/detalhes_campeonato.php?id=" . $id
            );
        } else {
            exibirMensagemEredirecionar("Não foi possível encerrar o campeonato.", "../views/detalhes_campeonato.php?id=" . $id);
        }

    } elseif ($tipo === "mudar_status") {
        $id = (int)($_POST['id_campeonato'] ?? 0);
        $novoStatus = $_POST['status'] ?? 'rascunho';
        if ($campeonatoModel->atualizarStatus($id, $novoStatus)) {
            Auditoria::log("MUDAR_STATUS_CAMPEONATO", "campeonato_eventos", $id, null, ['status' => $novoStatus], null, $id);
            exibirMensagemEredirecionar("Status do campeonato atualizado para " . strtoupper($novoStatus) . ".", "../views/detalhes_campeonato.php?id=" . $id);
        } else {
            exibirMensagemEredirecionar("Erro ao alterar status.", "../views/detalhes_campeonato.php?id=" . $id);
        }
    }
}
