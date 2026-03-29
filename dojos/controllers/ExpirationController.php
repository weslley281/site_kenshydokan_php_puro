<?php
session_start();
if (isset($_SESSION["user_id"]) && $_SESSION['type'] == "admin") {
    require_once __DIR__ . '/../models/Expiration.php';
    require_once __DIR__ . '/../config/db.php';

    $expiration = new Expiration($conn);

    // Verifica o método HTTP
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        // Verifica a ação a ser executada
        $action = isset($_GET['action']) ? strtolower($_GET['action']) : '';

        // Função para criar o array de dados de usuário
        function getExpirationData($post)
        {
            return [
                "student_id" => htmlspecialchars($post["sale_id"] ?? ''),
                "modality_id" => htmlspecialchars($post["select_product"] ?? ''),
                "expirationDate" => htmlspecialchars($post["expirationDate"] ?? '')
            ];
        }

        switch ($action) {
            case 'create':
                $data = getExpirationData($_POST);
                if ($expiration->create($data)) {
                    header("Location: ../index.php?page=financial&action=sell");
                } else {
                    header("Location: ../index.php?page=financial&action=sell&info=error");
                }
                break;

            case 'update':
                if ($id === null) {
                    header("Location: ../index.php?page=financial&action=sell&info=invalid");
                    exit;
                }
                $data = getExpirationData($_POST);
                if ($expiration->update($data, $id)) {
                    header("Location: ../index.php?page=financial&action=sell&info=saved");
                } else {
                    header("Location: ../index.php?page=financial&action=sell");
                }
                break;

            case 'delete':
                if ($id === null) {
                    header("Location: ../index.php?page=financial&action=sell&info=invalid");
                    exit;
                }
                if ($expiration->delete($id)) {
                    header("Location: ../index.php?page=financial&action=sell&info=deleted");
                } else {
                    header("Location: ../index.php?page=financial&action=sell");
                }
                break;

            case 'validate':
                if ($query == null || $query == "" || $query == 0) {
                    header("Location: ../index.php?page=validate");
                    exit;
                }
                if ($expiration->delete($id)) {
                    header("Location: ../index.php?page=financial&action=sell&info=deleted");
                } else {
                    header("Location: ../index.php?page=financial&action=sell");
                }
                break;

            default:
                header("Location: ../index.php?page=financial&action=sell&info==unknown");
                break;
        }
    }
} else {
    echo "<center><strong><h1>Você não Tem permição para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = '../index.php?page=login'; }, 3000);";
    echo "</script>";
}
