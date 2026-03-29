<?php
session_start();
if (isset($_SESSION["user_id"]) && $_SESSION['type'] == "admin") {
    require_once __DIR__ . '/../models/Cashier.php';
    require_once __DIR__ . '/../config/db.php';

    $cashier = new Cashier($conn);

    // Verifica o método HTTP
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Validação e Sanitização dos Dados
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        var_dump($id);

        // Verifica a ação a ser executada
        $action = isset($_GET['action']) ? strtolower($_GET['action']) : '';

        // Função para criar o array de dados de usuário
        function getCashierData($post)
        {
            return [
                "user_id" => $_SESSION["user_id"],
                "cash" => htmlspecialchars($post["cash"] ?? ''),
                "credit" => htmlspecialchars($post["credit"] ?? ''),
                "debit" => htmlspecialchars($post["debit"] ?? ''),
                "deposit" => htmlspecialchars($post["deposit"] ?? ''),
                "openedBy" => $_SESSION["user_id"],
                "closedBy" => $_SESSION["user_id"],
                "status" => htmlspecialchars($post["status"] ?? ''),
            ];
        }

        switch ($action) {
            case 'create':
                $data = getCashierData($_POST);
                var_dump($data);

                if (!$cashier->isOpen()) {
                    if ($cashier->create($data)) {
                        header("Location: ../index.php?page=financial&action=success");
                    } else {
                        header("Location: ../index.php?page=financial&action=fail");
                    }
                } else {
                    header("Location: ../index.php?page=financial&action=is_opened");
                }

                break;

            case 'update':
                if ($id === null) {
                    header("Location: ../index.php?page=financial&action=invalid2");
                    exit;
                }
                $data = getCashierData($_POST);
                if ($cashier->update($data, $id)) {
                    header("Location: ../index.php?page=financial&action=closed");
                } else {
                    header("Location: ../index.php?page=financial&action=fail2");
                }
                break;

            case 'delete':
                if ($id === null) {
                    header("Location: ../index.php?page=financial&action=invalid");
                    exit;
                }
                if ($cashier->delete($id)) {
                    header("Location: ../index.php?page=financial&action=deleted");
                } else {
                    header("Location: ../index.php?page=financial&action=fail");
                }
                break;

            case 'cash_drop':
                if ($id === null) {
                    header("Location: ../index.php?page=financial&action=invalid2");
                    exit;
                }

                if ($cashier->cashDrop($_POST["method"], $_SESSION["user_id"], $_POST["amount"])) {
                    header("Location: ../index.php?page=financial&action=closed");
                } else {
                    header("Location: ../index.php?page=financial&action=fail2");
                }
                break;

            case 'cash_supply':
                if ($id === null) {
                    header("Location: ../index.php?page=financial&action=invalid2");
                    exit;
                }
                $data = getCashierData($_POST);
                if ($cashier->update($data, $id)) {
                    header("Location: ../index.php?page=financial&action=closed");
                } else {
                    header("Location: ../index.php?page=financial&action=fail2");
                }
                break;

            default:
                header("Location: ../index.php?page=financial&action=unknown");
                break;
        }
    }
} else {
    echo "<center><strong><h1>Você não Tem permição para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = '../index.php?page=login'; }, 3000);";
    echo "</script>";
}
