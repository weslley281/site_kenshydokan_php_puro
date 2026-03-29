<?php
session_start();
if (isset($_SESSION["user_id"]) && $_SESSION['type'] == "admin") {
    require_once __DIR__ . '/../models/SalesPaymentItem.php';
    require_once __DIR__ . '/../models/MethodPayment.php'; // Include MethodPayment model
    require_once __DIR__ . '/../config/db.php';

    $salesPaymentItem = new SalesPaymentItem($conn);
    $methodPayment = new MethodPayment($conn); // Instantiate MethodPayment model

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
                "payment_method_id" => htmlspecialchars($post["payment_method_id"] ?? ''),
                "amount_paid" => htmlspecialchars($post["amount_paid"] ?? ''),
            ];
        }

        switch ($action) {
            case 'create':
                $data = getSalesItemData($_POST);
                if ($salesPaymentItem->create($data)) {
                    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        $newTotalPaid = $salesPaymentItem->getTotalAmountPaidBySaleId($data["sale_id"]);
                        $method = $methodPayment->getById($data["payment_method_id"]);
                        $methodName = $method ? $method['name'] : 'Desconhecido'; // Defensive check
                        
                        header('Content-Type: application/json');
                        echo json_encode([
                            'success' => true,
                            'newTotalPaid' => $newTotalPaid,
                            'payment' => [
                                'methodName' => $methodName,
                                'amountPaid' => $data['amount_paid']
                            ]
                        ]);
                        exit;
                    }
                    header("Location: ../index.php?page=financial&action=sell#order");
                } else {
                    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                        header('Content-Type: application/json');
                        http_response_code(500);
                        echo json_encode(['success' => false, 'message' => 'Erro ao adicionar pagamento.']);
                        exit;
                    }
                    header("Location: ../index.php?page=financial&action=sell&info=error");
                }
                break;

            case 'update':
                if ($id === null) {
                    header("Location: ../index.php?page=financial&action=sell&info=invalid");
                    exit;
                }
                $data = getSalesItemData($_POST);
                if ($salesPaymentItem->update($data, $id)) {
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
                if ($salesPaymentItem->delete($id)) {
                    header("Location: ../index.php?page=financial&action=sell&info=deleted");
                } else {
                    header("Location: ../index.php?page=financial&action=sell");
                }
                break;

            case 'clean':
                if ($salesPaymentItem->clean($_POST["sale_id"])) {
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
