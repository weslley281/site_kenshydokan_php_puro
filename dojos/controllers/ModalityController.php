<?php
session_start();
if (isset($_SESSION["user_id"]) && isset($_SESSION['type']) && in_array($_SESSION['type'], ['admin', 'instructor'])) {
    require_once __DIR__ . '/../models/Modality.php';
    require_once __DIR__ . '/../config/db.php';

    // Instância do modelo Modality
    $modality = new Modality($conn);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        $action = isset($_GET['action']) ? strtolower($_GET['action']) : '';

        function getModalityData($post)
        {
            return [
                "id_martial_art" => intval($post["id_martial_art"] ?? 0),
                "id_instructor" => intval($post["id_instructor"] ?? 0),
                "name" => htmlspecialchars($post["name"] ?? ''),
                "description" => htmlspecialchars($post["description"] ?? ''),
                "value" => htmlspecialchars($post["value"] ?? ''),
                "initial_hour" => htmlspecialchars($post["initial_hour"] ?? ''),
                "final_hour" => htmlspecialchars($post["final_hour"] ?? ''),
                "days" => htmlspecialchars($post["days"] ?? 0),
            ];
        }

        switch ($action) {
            case 'create':
                $data = getModalityData($_POST);
                if ($modality->create($data)) {
                    header("Location: ../index.php?page=modalities&action=success");
                } else {
                    header("Location: ../index.php?page=modalities&action=fail");
                }
                break;

            case 'update':
                if ($id === null) {
                    header("Location: ../index.php?page=modalities&action=invalid");
                    exit;
                }
                $data = getModalityData($_POST);
                if ($modality->update($data, $id)) {
                    header("Location: ../index.php?page=modalities&action=saved");
                } else {
                    header("Location: ../index.php?page=modalities&action=fail");
                }
                break;

            case 'delete':
                if ($_SESSION['type'] !== 'admin') {
                    header("Location: ../index.php?page=modalities&action=permission_error");
                    exit;
                }
                if ($id === null) {
                    header("Location: ../index.php?page=modalities&action=invalid");
                    exit;
                }
                if ($modality->delete($id)) {
                    header("Location: ../index.php?page=modalities&action=deleted");
                } else {
                    header("Location: ../index.php?page=modalities&action=fail");
                }
                break;

            default:
                header("Location: ../index.php?page=modalities&action=unknown");
                break;
        }
    }
} else {
    echo "<center><strong><h1>Você não Tem permição para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = '../index.php?page=login'; }, 3000);";
    echo "</script>";
}
