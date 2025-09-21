<?php
$cashier_open = $cashier->getCashierOpenByIdUser($_SESSION["user_id"]);

if (!$salesRecord->countUserSalesByStatus($_SESSION["user_id"], "in_process")) {
    echo "Eu executei";
    $data = [
        "cashier_id" => $cashier_open["id"],
        "user_id" => $_SESSION["user_id"],
        "student_id" => null,
        "amount_paid" => 0,
        "change_sale" => 0,
        "total" => 0,
        "status" => htmlspecialchars('in_process'),
    ];
    //var_dump($data);

    $salesRecord->create($data);
}

$sale_data = $salesRecord->getSaleInProcessByIdUser($_SESSION["user_id"]);
$user_data = $user->getById($sale_data["student_id"]);
$total_amount_paid = $salesPaymentItem->getTotalAmountPaidBySaleId($sale_data["id"]);

$sub_total = 0;

$methods = [
    "cash" => "Dinheiro",
    "credit" => "Crédito",
    "debit" => "Débito",
    "deposit" => "Depósito, Transferência ou PIX"
];
?>
<h1 class="text-center">Checkout</h1>

<div class="row mt-5">
    <?php
    include_once "checkout/product.php";
    include_once "checkout/order_summary.php";
    include_once "checkout/customer_information.php";
    ?>
</div>