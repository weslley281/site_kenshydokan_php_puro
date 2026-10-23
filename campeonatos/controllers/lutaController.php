<?php
/**
 * Controlador de Lutas: distribuição nos tatames e resultado sem combate
 * Padrão IDÊNTICO ao site principal (controllers/*.php)
 *
 *  - alocar_categoria   (admin)   RF07.2: manda as lutas de uma categoria para um tatame
 *  - resultado_sem_luta (mesário) RF06.5: W.O., desistência ou desclassificação
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/authHelper.php";
include_once __DIR__ . "/../models/lutaModel.php";
include_once __DIR__ . "/../models/categoriaModel.php";
include_once __DIR__ . "/../models/tatameModel.php";
include_once __DIR__ . "/../models/auditoriaModel.php";
include_once __DIR__ . "/alertHelper.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../views/inicio.php");
    exit;
}

csrfExigir('../views/inicio.php');

$lutaModel = new Luta();
$tipo = $_POST["tipo"] ?? "";

if ($tipo === "alocar_categoria") {
    exigirPerfil([]); // somente administrador

    $idCampeonato = (int)($_POST["id_campeonato"] ?? 0);
    $idCategoria = (int)($_POST["id_categoria"] ?? 0);
    $idTatame = !empty($_POST["id_tatame"]) ? (int)$_POST["id_tatame"] : null;
    $destino = "../views/tatames.php?id=" . $idCampeonato;

    $categoria = (new Categoria())->buscarPorId($idCategoria);
    if (!$categoria || (int)$categoria['id_campeonato'] !== $idCampeonato) {
        exibirMensagemEredirecionar("Categoria não encontrada neste campeonato.", $destino);
    }

    $nomeTatame = 'sem tatame';
    if ($idTatame !== null) {
        $tatame = (new Tatame())->buscarPorId($idTatame);
        if (!$tatame || (int)$tatame['id_campeonato'] !== $idCampeonato) {
            exibirMensagemEredirecionar("Tatame não encontrado neste campeonato.", $destino);
        }
        $nomeTatame = $tatame['nome'];
    }

    $total = $lutaModel->alocarCategoriaAoTatame($idCategoria, $idTatame);
    Auditoria::log("ALOCAR_CATEGORIA_TATAME", "campeonato_lutas", $idCategoria, null,
        ['id_tatame' => $idTatame, 'lutas' => $total], null, $idCampeonato);

    exibirMensagemEredirecionar(
        $idTatame === null
            ? "As lutas de '{$categoria['nome']}' foram tiradas do tatame."
            : "As lutas de '{$categoria['nome']}' vão para o {$nomeTatame}.",
        $destino
    );

} elseif ($tipo === "resultado_sem_luta") {
    exigirPerfil(['mesario']);

    $idLuta = (int)($_POST["id_luta"] ?? 0);
    $lado = $_POST["vencedor_lado"] ?? "";
    $forma = $_POST["forma_vitoria"] ?? "";
    $motivo = trim($_POST["motivo"] ?? "");

    $luta = $lutaModel->buscarPorId($idLuta);
    if (!$luta) {
        exibirMensagemEredirecionar("Luta não encontrada.", "../views/inicio.php");
    }
    $destino = "../views/ver_chave.php?id=" . (int)$luta['id_categoria'];

    if ($motivo === "") {
        exibirMensagemEredirecionar("Escreva o motivo (ex.: atleta não compareceu à chamada).", $destino);
    }

    $idVencedor = $lutaModel->registrarResultadoSemLuta($idLuta, $lado, $forma);
    if ($idVencedor === 0) {
        exibirMensagemEredirecionar("Não foi possível registrar: a luta já terminou ou ainda não tem os dois atletas.", $destino);
    }

    if (!empty($luta['id_tatame'])) {
        (new Tatame())->atualizarStatus((int)$luta['id_tatame'], 'disponivel', null);
    }

    Auditoria::log("RESULTADO_SEM_LUTA", "campeonato_lutas", $idLuta, null, [
        'vencedor_id' => $idVencedor,
        'forma_vitoria' => $forma,
        'motivo' => $motivo
    ], $motivo, (int)$luta['id_campeonato']);

    $nomeVencedor = $lado === 'aka' ? ($luta['nome_aka'] ?? 'AKA') : ($luta['nome_shiro'] ?? 'SHIRO');
    exibirMensagemEredirecionar("Luta {$luta['numero_luta']} encerrada. {$nomeVencedor} passa para a próxima fase.", $destino);

} else {
    header("Location: ../views/inicio.php");
    exit;
}
