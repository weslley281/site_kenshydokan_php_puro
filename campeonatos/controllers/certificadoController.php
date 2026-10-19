<?php
/**
 * Controlador de Certificados Oficiais
 * Padrão IDÊNTICO ao site principal (controllers/certificadoController.php)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/authHelper.php";
exigirLogin();

include_once __DIR__ . "/../models/certificadoModel.php";
include_once __DIR__ . "/../models/inscricaoModel.php";
include_once __DIR__ . "/../models/campeonatoModel.php";
include_once __DIR__ . "/../models/auditoriaModel.php";
include_once __DIR__ . "/alertHelper.php";
require_once __DIR__ . "/../libs/CertificadoCampeonatoPdf.php";

$certificadoModel = new Certificado();
$inscricaoModel = new Inscricao();
$campeonatoModel = new Campeonato();

// Emite ou reabre o certificado único do atleta neste campeonato.
if (isset($_GET['acao']) && $_GET['acao'] === 'gerar_pdf') {
    $idCertificado = (int)($_GET['id_certificado'] ?? 0);
    $certificado = $idCertificado > 0 ? $certificadoModel->buscarPorId($idCertificado) : null;
    if ($idCertificado > 0 && !$certificado) {
        exibirMensagemEredirecionar("Certificado não encontrado.", "../views/campeonatos.php");
    }

    if (!$certificado) {
        $idInscricao = (int)($_GET['id_inscricao'] ?? 0);
        $inscricao = $inscricaoModel->buscarPorId($idInscricao);
        if (!$inscricao || $inscricao['status_elegibilidade'] !== 'aprovada') {
            exibirMensagemEredirecionar("Inscrição aprovada não encontrada.", "../views/certificados.php");
        }

        $campeonato = $campeonatoModel->buscarPorId((int)$inscricao['id_campeonato']);
        if (!$campeonato || $campeonato['status'] !== 'finalizado') {
            exibirMensagemEredirecionar("Os certificados ficam disponíveis após finalizar o campeonato.", "../views/certificados.php?id=" . (int)$inscricao['id_campeonato']);
        }

        $certificado = $certificadoModel->buscarPorAtleta(
            (int)$inscricao['id_campeonato'],
            (int)$inscricao['id_filiado']
        );
        if (!$certificado) {
            $codigoHash = strtoupper(bin2hex(random_bytes(12)));
            $resultados = $certificadoModel->buscarResultadosAtleta(
                (int)$inscricao['id_campeonato'],
                (int)$inscricao['id_filiado']
            );
            $certificadoModel->criarCertificado([
                'id_campeonato' => (int)$inscricao['id_campeonato'],
                'id_inscricao' => (int)$inscricao['id_inscricao'],
                'nome_titular' => $inscricao['nome_atleta_snapshot'],
                'tipo_certificado' => 'participacao',
                'codigo_autenticidade' => $codigoHash,
                'arquivo_pdf_nome' => 'cert_campeonato_' . (int)$inscricao['id_campeonato'] . '_' . (int)$inscricao['id_filiado'] . '.pdf',
                'resultados_snapshot' => $resultados
            ]);
            $certificado = $certificadoModel->buscarPorAtleta(
                (int)$inscricao['id_campeonato'],
                (int)$inscricao['id_filiado']
            );
            Auditoria::log("EMITIR_CERTIFICADO_CAMPEONATO", "campeonato_certificados", (int)$inscricao['id_inscricao'], null, [
                'atleta' => $inscricao['nome_atleta_snapshot'],
                'codigo' => $codigoHash
            ], null, (int)$inscricao['id_campeonato']);
        }

        if (!$certificado) {
            throw new RuntimeException('O certificado foi salvo, mas não foi possível recuperá-lo.');
        }
        $certificado = $certificadoModel->buscarPorId((int)$certificado['id_certificado']);
    }

    if (!$certificado || $certificado['campeonato_status'] !== 'finalizado') {
        http_response_code(403);
        exit('Este certificado só pode ser consultado após o campeonato ser finalizado.');
    }

    $resultados = !empty($certificado['resultados_snapshot'])
        ? json_decode($certificado['resultados_snapshot'], true, 512, JSON_THROW_ON_ERROR)
        : $certificadoModel->buscarResultadosAtleta(
            (int)$certificado['id_campeonato'],
            (int)$certificado['id_filiado']
        );
    if (!is_array($resultados)) {
        throw new UnexpectedValueException('O retrato dos resultados do certificado é inválido.');
    }
    CertificadoCampeonatoPdf::emitir($certificado, $resultados);
    exit;
}

// Emissão em lote via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $tipo = $_POST["tipo"] ?? "";
    $idCampeonato = (int)($_POST["id_campeonato"] ?? 0);

    if ($tipo === "gerar_lote") {
        $campeonato = $campeonatoModel->buscarPorId($idCampeonato);
        if (!$campeonato || $campeonato['status'] !== 'finalizado') {
            exibirMensagemEredirecionar("Finalize o campeonato antes de emitir os certificados.", "../views/certificados.php?id=" . $idCampeonato);
        }

        $atletas = $certificadoModel->buscarAtletasPorCampeonato($idCampeonato);
        $emitidos = 0;

        foreach ($atletas as $atleta) {
            if (empty($atleta['id_certificado'])) {
                $codigoHash = strtoupper(bin2hex(random_bytes(12)));
                $resultados = $certificadoModel->buscarResultadosAtleta(
                    $idCampeonato,
                    (int)$atleta['id_filiado']
                );
                $certificadoModel->criarCertificado([
                    'id_campeonato' => $idCampeonato,
                    'id_inscricao' => (int)$atleta['id_inscricao'],
                    'nome_titular' => $atleta['nome_titular'],
                    'tipo_certificado' => 'participacao',
                    'codigo_autenticidade' => $codigoHash,
                    'arquivo_pdf_nome' => 'cert_campeonato_' . $idCampeonato . '_' . (int)$atleta['id_filiado'] . '.pdf',
                    'resultados_snapshot' => $resultados
                ]);
                $emitidos++;
            }
        }

        Auditoria::log("EMITIR_LOTE_CERTIFICADOS", "campeonato_certificados", null, null, ['total' => $emitidos], null, $idCampeonato);
        exibirMensagemEredirecionar("Certificados prontos: {$emitidos} novos emitidos para " . count($atletas) . " atletas.", "../views/certificados.php?id=" . $idCampeonato);
    }
}
