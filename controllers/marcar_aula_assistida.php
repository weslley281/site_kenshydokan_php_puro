<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include_once "../models/aulaModel.php";

    $response = ["success" => false, "message" => ""];

    if (!isset($_SESSION["id_usuario"])) {
        $response["message"] = "Usuário não autenticado.";
        echo json_encode($response);
        exit;
    }

    if (!isset($_POST["id_aula"])) {
        $response["message"] = "ID da aula não fornecido.";
        echo json_encode($response);
        exit;
    }

    $id_usuario = $_SESSION["id_usuario"];
    $id_aula = $_POST["id_aula"];

    $aulaModel = new AulaModel();

    if ($aulaModel->marcarAulaAssistida($id_usuario, $id_aula)) {
        $response["success"] = true;
        $response["message"] = "Aula marcada como assistida com sucesso!";

        try {
            $aula = $aulaModel->buscarAula($id_aula);
            if ($aula && isset($aula['id_curso'])) {
                include_once "../models/certificadoModel.php";
                CertificadoModel::verificarEGerarCertificadoAuto($id_usuario, (int)$aula['id_curso']);
            }
        } catch (Exception $e) {
            error_log("Erro ao processar certificado automatico ao assistir aula: " . $e->getMessage());
        }
    } else {
        $response["message"] = "Erro ao marcar aula como assistida.";
    }

    echo json_encode($response);

} else {
    $response = ["success" => false, "message" => "Requisição inválida."];
    echo json_encode($response);
}
?>