<?php
/**
 * Controlador de Categorias
 * Padrão IDÊNTICO ao site principal (controllers/categoriaController.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/auditoriaModel.php";
include_once __DIR__ . "/alertHelper.php";

if (!isset($_SESSION["id_usuario"])) {
    exibirMensagemEredirecionar("Acesso restrito.", "../views/login.php");
}

include_once __DIR__ . "/csrfHelper.php";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    csrfExigir('../views/inicio.php');
    exigirPerfil([]);
    $categoriaModel = new Categoria();
    $tipo = $_POST["tipo"] ?? "";
    $idCampeonato = (int)($_POST["id_campeonato"] ?? 0);

    if ($tipo === "criar_categoria") {
        $dados = [
            'id_campeonato' => $idCampeonato,
            'nome' => trim($_POST['nome'] ?? ''),
            'modalidade' => $_POST['modalidade'] ?? 'kumite',
            'sexo' => $_POST['sexo'] ?? 'misto',
            'idade_min' => (int)($_POST['idade_min'] ?? 0),
            'idade_max' => (int)($_POST['idade_max'] ?? 99),
            'peso_min' => $_POST['peso_min'] !== '' ? (float)$_POST['peso_min'] : null,
            'peso_max' => $_POST['peso_max'] !== '' ? (float)$_POST['peso_max'] : null,
            'tolerancia_peso_kg' => (float)($_POST['tolerancia_peso_kg'] ?? 0.0),
            'duracao_luta_segundos' => (int)($_POST['duracao_luta_segundos'] ?? 180),
            'tipo_chaveamento' => $_POST['tipo_chaveamento'] ?? 'eliminatoria_simples',
            'exige_pesagem' => isset($_POST['exige_pesagem']) ? (int)$_POST['exige_pesagem'] : 1
        ];

        if (empty($dados['nome'])) {
            exibirMensagemEredirecionar("Informe o nome da categoria.", "../views/categorias.php?id=" . $idCampeonato);
        }

        $id = $categoriaModel->criarCategoria($dados);
        if ($id > 0) {
            Auditoria::log("CRIAR_CATEGORIA", "campeonato_categorias", $id, null, $dados, null, $idCampeonato);
            exibirMensagemEredirecionar("Categoria criada com sucesso!", "../views/categorias.php?id=" . $idCampeonato);
        } else {
            exibirMensagemEredirecionar("Erro ao criar categoria.", "../views/categorias.php?id=" . $idCampeonato);
        }

    } elseif ($tipo === "editar_categoria") {
        $idCategoria = (int)($_POST['id_categoria'] ?? 0);
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'modalidade' => $_POST['modalidade'] ?? 'kumite',
            'sexo' => $_POST['sexo'] ?? 'misto',
            'idade_min' => (int)($_POST['idade_min'] ?? 0),
            'idade_max' => (int)($_POST['idade_max'] ?? 99),
            'peso_min' => $_POST['peso_min'] !== '' ? (float)$_POST['peso_min'] : null,
            'peso_max' => $_POST['peso_max'] !== '' ? (float)$_POST['peso_max'] : null,
            'tolerancia_peso_kg' => (float)($_POST['tolerancia_peso_kg'] ?? 0.0),
            'duracao_luta_segundos' => (int)($_POST['duracao_luta_segundos'] ?? 180),
            'tipo_chaveamento' => $_POST['tipo_chaveamento'] ?? 'eliminatoria_simples',
            'exige_pesagem' => isset($_POST['exige_pesagem']) ? (int)$_POST['exige_pesagem'] : 1
        ];

        if ($categoriaModel->editarCategoria($idCategoria, $dados)) {
            Auditoria::log("EDITAR_CATEGORIA", "campeonato_categorias", $idCategoria, null, $dados, null, $idCampeonato);
            exibirMensagemEredirecionar("Categoria atualizada com sucesso!", "../views/categorias.php?id=" . $idCampeonato);
        } else {
            exibirMensagemEredirecionar("Erro ao editar categoria.", "../views/categorias.php?id=" . $idCampeonato);
        }

    } elseif ($tipo === "excluir_categoria") {
        $idCategoria = (int)($_POST['id_categoria'] ?? 0);
        if ($categoriaModel->excluirCategoria($idCategoria)) {
            Auditoria::log("EXCLUIR_CATEGORIA", "campeonato_categorias", $idCategoria, null, null, null, $idCampeonato);
            exibirMensagemEredirecionar("Categoria excluída com sucesso.", "../views/categorias.php?id=" . $idCampeonato);
        } else {
            exibirMensagemEredirecionar("Erro ao excluir categoria.", "../views/categorias.php?id=" . $idCampeonato);
        }
    }
}
