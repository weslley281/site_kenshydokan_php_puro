<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Recibo</title>
    <link rel="stylesheet" href="../../libs/bootstrap/bootstrap.css">
    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() {
                window.location.href = '../../index.php?page=financial&action=sales_report#report';
            }, 10000);
        };
    </script>
</head>

<?php
require_once '../../models/User.php';
require_once '../../models/SalesRecords.php';
require_once '../../models/SalesItem.php';
require_once '../../models/MethodPayment.php';
require_once '../../models/SalesPaymentItem.php';
require_once '../../models/Expiration.php';
require_once '../../models/Modality.php';

$user = new User($conn);
$salesRecord = new SalesRecord($conn);

$salesData = $salesRecord->getAll();
//var_dump($salesData);
?>

<body>
    <div class="container">


        <table id="minhaTabela" class="table table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Usuário</th>
                    <th>Estudante</th>
                    <th>Desconto</th>
                    <th>Pago</th>
                    <th>Troco</th>
                    <th>Total</th>
                    <th>Estatus</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                <?php


                if (isset($salesData) && !empty($salesData)) {
                    foreach ($salesData as $item) {
                        if ($item['status'] != "in_process") {

                            $userData = $user->getById($item['user_id']);
                            $userName = explode(" ", $userData["name"]);

                            $studentData = $user->getById($item['student_id']);
                            $studentName = explode(" ", $studentData["name"]);

                            $objDate = new DateTime($item["saleDate"]);
                            $formattedDate = $objDate->format("d/m/Y");
                            $formattedTotal = number_format((float) $item["total"], 2, ',', '.');
                            $formattedAmountPaid = number_format((float) $item["amount_paid"], 2, ',', '.');
                            $formattedDiscount = number_format((float) $item["discount"], 2, ',', '.');
                            $formattedChangeSale = number_format((float) $item["change_sale"], 2, ',', '.');
                ?>
                            <tr>
                                <td><?= htmlspecialchars($item['cashier_id']) ?></td>
                                <td><?= htmlspecialchars($userName[0]) ?></td>
                                <td><?= htmlspecialchars($studentName[0]) ?></td>
                                <td><?= htmlspecialchars($formattedDiscount) ?></td>
                                <td><?= htmlspecialchars($formattedAmountPaid) ?></td>
                                <td><?= htmlspecialchars($formattedChangeSale) ?></td>
                                <td><?= htmlspecialchars($formattedTotal) ?></td>
                                <td><?= htmlspecialchars($item['status']) ?></td>
                                <td><?= htmlspecialchars($formattedDate) ?></td>
                            </tr>
                <?php }
                    }
                } ?>
            </tbody>
        </table>

    </div>
</body>

</html>