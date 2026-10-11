<?php
/**
 * Controlador de Inscrições e Integração com Filiados
 * Padrão IDÊNTICO ao site principal (controllers/inscricaoController.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/filiadoModel.php";
include_once __DIR__ . "/../models/auditoriaModel.php";
include_once __DIR__ . "/alertHelper.php";

// Endpoint AJAX de busca de filiados para Select2
if (isset($_GET['acao']) && $_GET['acao'] === 'buscar_filiados') {
    header('Content-Type: application/json; charset=utf-8');
    $termo = trim($_GET['q'] ?? '');
    if (strlen($termo) < 2) {
        echo json_encode(['results' => []]);
        exit;
    }

    $filiadoModel = new Filiado();
    $filiados = $filiadoModel->buscarPorTermo($termo, 30);
    $results = [];

    foreach ($filiados as $f) {
        $codigo = !empty($f['codigo']) ? " [Cód: {$f['codigo']}]" : '';
        $dn = ($f['dataNascimento'] !== '0000-00-00') ? date('d/m/Y', strtotime($f['dataNascimento'])) : 'Data não informada';
        $results[] = [
            'id' => $f['id_filiado'],
            'text' => "{$f['nome']}{$codigo} - {$f['dojo']} ({$f['graduacao_nome']})",
            'nome' => $f['nome'],
            'dojo' => $f['dojo'],
            'dataNascimento' => $f['dataNascimento'],
            'dataNascimentoFormatada' => $dn,
            'graduacao_id' => $f['id_graduacao'],
            'graduacao_nome' => $f['graduacao_nome']
        ];
    }

    echo json_encode(['results' => $results]);
    exit;
}

if (!isset($_SESSION["id_usuario"])) {
    exibirMensagemEredirecionar("Acesso restrito.", "../views/login.php");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $inscricaoModel = new Inscricao();
    $categoriaModel = new Categoria();
    $campeonatoModel = new Campeonato();
    $filiadoModel = new Filiado();

    $tipo = $_POST["tipo"] ?? "";
    $idCampeonato = (int)($_POST["id_campeonato"] ?? 0);

    if ($tipo === "criar_inscricao") {
        $idCategoria = (int)($_POST["id_categoria"] ?? 0);
        $idFiliado = (int)($_POST["id_filiado"] ?? 0);
        $sexo = $_POST["sexo"] ?? "M";
        $pesoDeclarado = $_POST["peso_declarado"] !== "" ? (float)$_POST["peso_declarado"] : null;

        $campeonato = $campeonatoModel->buscarPorId($idCampeonato);
        $categoria = $categoriaModel->buscarPorId($idCategoria);
        $filiado = $filiadoModel->buscarPorId($idFiliado);

        if (!$campeonato || !$categoria || !$filiado) {
            exibirMensagemEredirecionar("Dados incompletos para a inscrição.", "../views/inscricoes.php?id=" . $idCampeonato);
        }

        // 1. Checa se já está inscrito nesta categoria
        if ($inscricaoModel->jaInscritoNaCategoria($idCategoria, $idFiliado)) {
            exibirMensagemEredirecionar("O atleta {$filiado['nome']} já está inscrito nesta categoria.", "../views/inscricoes.php?id=" . $idCampeonato);
        }

        // 2. Valida Data de Nascimento e Idade
        if (empty($filiado['dataNascimento']) || $filiado['dataNascimento'] === '0000-00-00') {
            exibirMensagemEredirecionar("O filiado {$filiado['nome']} está sem data de nascimento cadastrada.", "../views/criar_inscricao.php?id=" . $idCampeonato);
        }

        $nasc = new DateTime($filiado['dataNascimento']);
        $ref = new DateTime($campeonato['data_referencia_idade']);
        $idade = $ref->diff($nasc)->y;

        if ($idade < (int)$categoria['idade_min'] || $idade > (int)$categoria['idade_max']) {
            exibirMensagemEredirecionar("Idade do atleta ({$idade} anos) incompatível com a categoria {$categoria['nome']} ({$categoria['idade_min']} a {$categoria['idade_max']} anos).", "../views/criar_inscricao.php?id=" . $idCampeonato);
        }

        // 3. Valida Sexo
        if ($categoria['sexo'] !== 'misto' && $categoria['sexo'] !== $sexo) {
            exibirMensagemEredirecionar("Sexo incompatível com a categoria selecionada.", "../views/criar_inscricao.php?id=" . $idCampeonato);
        }

        // 4. Grava Inscrição com Snapshot Imutável
        $statusInicial = ($categoria['exige_pesagem'] == 1) ? 'pendente' : 'aprovada';

        $dadosSnapshot = [
            'id_campeonato' => $idCampeonato,
            'id_categoria' => $idCategoria,
            'id_filiado' => $idFiliado,
            'nome_atleta_snapshot' => $filiado['nome'],
            'dojo_snapshot' => $filiado['dojo'] ?: 'Independente',
            'graduacao_id_snapshot' => (int)$filiado['id_graduacao'],
            'graduacao_nome_snapshot' => $filiado['graduacao_nome'],
            'data_nascimento_snapshot' => $filiado['dataNascimento'],
            'idade_evento' => $idade,
            'sexo' => $sexo,
            'peso_declarado' => $pesoDeclarado,
            'status_elegibilidade' => $statusInicial
        ];

        $idInscricao = $inscricaoModel->criarComSnapshot($dadosSnapshot);
        if ($idInscricao > 0) {
            Auditoria::log("INSCREVER_ATLETA", "campeonato_inscricoes", $idInscricao, null, [
                'atleta' => $filiado['nome'],
                'categoria' => $categoria['nome']
            ], null, $idCampeonato);
            exibirMensagemEredirecionar("Atleta {$filiado['nome']} inscrito com sucesso na categoria {$categoria['nome']}!", "../views/inscricoes.php?id=" . $idCampeonato);
        } else {
            exibirMensagemEredirecionar("Erro ao registrar inscrição.", "../views/criar_inscricao.php?id=" . $idCampeonato);
        }

    } elseif ($tipo === "atualizar_status") {
        $idInscricao = (int)($_POST["id_inscricao"] ?? 0);
        $novoStatus = $_POST["status"] ?? "aprovada";
        $motivo = trim($_POST["motivo"] ?? "");

        if ($inscricaoModel->atualizarStatus($idInscricao, $novoStatus, $motivo ?: null)) {
            Auditoria::log("ALTERAR_STATUS_INSCRICAO", "campeonato_inscricoes", $idInscricao, null, ['status' => $novoStatus, 'motivo' => $motivo], null, $idCampeonato);
            exibirMensagemEredirecionar("Status da inscrição atualizado para " . strtoupper($novoStatus) . ".", "../views/inscricoes.php?id=" . $idCampeonato);
        } else {
            exibirMensagemEredirecionar("Erro ao atualizar status da inscrição.", "../views/inscricoes.php?id=" . $idCampeonato);
        }
    }
}
