<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['id_usuario'])) {
    header('Location: ../views/login.php');
    exit;
}

require_once __DIR__ . '/../models/usuarioModel.php';
require_once __DIR__ . '/../models/campeonatoCertificadoModel.php';
require_once __DIR__ . '/../campeonatos/libs/CertificadoCampeonatoPdf.php';

$usuario = (new Usuario())->buscarUsuario((int)$_SESSION['id_usuario']);
$idFiliado = (int)($usuario['id_fil'] ?? 0);
$idCertificado = (int)($_GET['id'] ?? 0);

if ($idFiliado <= 0 || $idCertificado <= 0) {
    http_response_code(404);
    exit('Certificado não encontrado.');
}

$modelo = new CampeonatoCertificadoModel();
$certificado = $modelo->buscarCertificadoDoFiliado($idCertificado, $idFiliado);
if (!$certificado) {
    http_response_code(404);
    exit('Certificado indisponível ou não pertence a este perfil.');
}

$resultados = $modelo->buscarResultados(
    (int)$certificado['id_campeonato'],
    $idFiliado,
    $certificado['resultados_snapshot'] ?? null
);
CertificadoCampeonatoPdf::emitir($certificado, $resultados);
