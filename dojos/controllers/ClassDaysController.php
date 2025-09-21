<?php
session_start();
if (isset($_SESSION["user_id"]) && $_SESSION['type'] == "admin") {
    require_once __DIR__ . '/../models/Modality.php';
    require_once __DIR__ . '/../config/db.php';

    // Instância do modelo Modality
    $modality = new Modality($conn);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        $action = isset($_GET['action']) ? strtolower($_GET['action']) : '';

        switch ($action) {
            case 'create':
                if ($modality->createModalityDays($id, $_POST["day_of_week"])) {
                    header("Location: ../index.php?page=modalities&action=update&id=$id");
                } else {
                    header("Location: ../index.php?page=modalities&action=update&id=$id");
                }
                break;

            case 'delete':
                if ($id === null) {
                    header("Location: ../index.php?page=modalities&action=update&id=$id");
                    exit;
                }
                if ($modality->deleteModalityDays($id, $_POST["day_of_week"])) {
                    var_dump($id, $_POST["day_of_week"]);
                    header("Location: ../index.php?page=modalities&action=update&id=$id");
                } else {
                    header("Location: ../index.php?page=modalities&action=update&id=$id");
                }
                break;

            default:
                header("Location: ../index.php?page=modalities&action=update&id=$id");
                break;
        }
    }
} else {
    echo "<center><strong><h1>Você não Tem permição para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = '../index.php?page=login'; }, 3000);";
    echo "</script>";
}
