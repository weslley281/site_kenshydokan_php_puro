<?php
session_start();
if (isset($_SESSION["user_id"]) && $_SESSION['type'] == "admin") {
    require_once __DIR__ . '/../models/SalesItem.php';
    require_once __DIR__ . '/../config/db.php';

    $salesItem = new SalesItem($conn);

    // Verifica o método HTTP
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        // Verifica a ação a ser executada
        $action = isset($_GET['action']) ? strtolower($_GET['action']) : '';

        // Função para criar o array de dados de usuário
        function getSalesItemData($post)
        {
            return [
                "sale_id" => htmlspecialchars($post["sale_id"] ?? ''),
                "modality_id" => htmlspecialchars($post["select_product"] ?? ''),
            ];
        }

        switch ($action) {
            case 'create':
                $data = getSalesItemData($_POST);
                if ($salesItem->create($data)) {
                    header("Location: ../index.php?page=financial&action=sell#products");
                } else {
                    header("Location: ../index.php?page=financial&action=sell&info=error");
                }
                break;

            case 'update':
                if ($id === null) {
                    header("Location: ../index.php?page=financial&action=sell&info=invalid");
                    exit;
                }
                $data = getSalesItemData($_POST);
                if ($salesItem->update($data, $id)) {
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
                if ($salesItem->delete($id)) {
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
