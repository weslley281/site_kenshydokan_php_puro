<div id="order" class="col-md-8 col-12">
    <div class="card mt-4">
        <div class="card-header">
            <h3>Resumo do Pedido</h3>
        </div>
        <div id="payment-list-container" class="container mt-3">
            <h5>Pagamentos Efetuados</h5>
            <div id="payment-list">
                <!-- Payments will be dynamically inserted here -->
            </div>
        </div>
        <form class="container" action="controllers/SalesRecordController.php?action=discount" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($sale_data["id"]) ?>">
            <div class="form-group">
                <label for="discount">Desconto:</label>
                <input type="number" id="discount" name="discount" value="<?= htmlspecialchars($sale_data["discount"] ?? 0) ?>" class="form-control" step="0.01">
            </div>
            <div class="form-group">
                <button class="btn btn-secondary" type="submit">Efetuar Desconto</button>
            </div>

        </form>
        <?php if ($total_amount_paid) { ?>
            <form class="container" action="controllers/SalesPaymentItemController.php?action=clean" method="post">
                <input type="hidden" name="sale_id" value="<?= htmlspecialchars($sale_data["id"]) ?>">
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">Limpar Pagamentos</button>
                </div>
            </form>
        <?php } ?>
        <div class="card-body">
            <?php
            $total = $sub_total - $sale_data["discount"];
            $defit = $total_amount_paid - $total;
            ?>
            <p>Subtotal: R$ <span id="subtotal-display"><?= htmlspecialchars(number_format((float) $sub_total, 2, ',', '.')) ?></span></p>
            <p>Desconto: R$ <span id="discount-display"><?= htmlspecialchars(number_format((float) $sale_data["discount"], 2, ',', '.')) ?></span></p>
            <p>Total: R$ <span id="total-display"><?= htmlspecialchars(number_format((float) $total, 2, ',', '.')) ?></span></p>
            <p>Pago: R$ <span id="paid-display"><?= htmlspecialchars(number_format((float) $total_amount_paid, 2, ',', '.')) ?></span></p>

            <span id="subtotal-value" style="display: none;"><?= (float)$sub_total ?></span>
            <span id="paid-value" style="display: none;"><?= (float)$total_amount_paid ?></span>

            <p><span id="change-label"><?= $total > $total_amount_paid ? 'Falta' : 'Troco' ?></span>: R$ <span id="change-display"><?= htmlspecialchars(number_format((float) $defit, 2, ',', '.')) ?></span></p>


            <div id="finalize-buttons-container">
                <!-- This form will be shown by JS when the sale is ready to be finalized -->
                <form method="post" action="controllers/SalesRecordController.php?action=update" id="finalize-sale-form" style="display: none;">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($sale_data['id']) ?>">
                    <input type="hidden" name="cashier_id" value="<?= htmlspecialchars($sale_data['cashier_id']) ?>">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($sale_data['user_id']) ?>">
                    <input type="hidden" name="student_id" value="<?= htmlspecialchars($sale_data['student_id']) ?>">
                    <input type="hidden" id="hidden-amount-paid" name="amount_paid" value="<?= htmlspecialchars($total_amount_paid) ?>">
                    <input type="hidden" id="hidden-change-sale" name="change_sale" value="<?= htmlspecialchars($defit) ?>">
                    <input type="hidden" id="hidden-total" name="total" value="<?= htmlspecialchars($total) ?>">
                    <input type="hidden" name="status" value="processed">
                    <button type="submit" class="btn btn-success btn-lg btn-block">Finalizar Compra</button>
                </form>

                <!-- This button opens the payment modal -->
                <button type="button" id="add-payment-btn" class="btn btn-success btn-lg btn-block" data-toggle="modal" data-target="#finalize_sale">Adicionar Pagamento</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="finalize_sale" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Forma de Pagamento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="controllers/SalesPaymentItemController.php?action=create">
                    <input type="hidden" name="sale_id" value="<?= htmlspecialchars($sale_data['id']) ?>">

                    <div class="form-group">
                        <label for="payment_method_id">Método de Pagamento</label>
                        <select class="form-control" id="payment_method_id" name="payment_method_id">

                            <?php
                            $methodPayments = $methodPayment->getAll();
                            if (isset($methodPayments) && !empty($methodPayments)) {
                                foreach ($methodPayments as $item) {
                            ?>
                                    <option value="<?= htmlspecialchars($item["id"]) ?>"><?= htmlspecialchars($methods[$item["name"]]) ?></option>
                            <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount_paid">Quantidade</label>
                        <input type="number" class="form-control" id="amount_paid" name="amount_paid" value="<?= htmlspecialchars($total); ?>" step="0.01">
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class=" btn btn-primary">Salvar mudanças</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- ELEMENTS ---
    const discountInput = document.getElementById('discount');
    const discountForm = discountInput.closest('form');
    const discountButton = discountForm.querySelector('button[type="submit"]');
    
    const paymentModal = document.getElementById('finalize_sale');
    const paymentForm = paymentModal.querySelector('form');

    const subtotalValueEl = document.getElementById('subtotal-value');
    const paidValueEl = document.getElementById('paid-value');

    const discountDisplayEl = document.getElementById('discount-display');
    const totalDisplayEl = document.getElementById('total-display');
    const paidDisplayEl = document.getElementById('paid-display');
    const changeLabelEl = document.getElementById('change-label');
    const changeDisplayEl = document.getElementById('change-display');
    const paymentListEl = document.getElementById('payment-list');

    const finalizeSaleForm = document.getElementById('finalize-sale-form');
    const addPaymentBtn = document.getElementById('add-payment-btn');

    const hiddenTotalInput = document.getElementById('hidden-total');
    const hiddenChangeInput = document.getElementById('hidden-change-sale');
    const hiddenAmountPaidInput = document.getElementById('hidden-amount-paid');

    const studentId = "<?= $sale_data['student_id'] ?? '' ?>";

    // --- FUNCTIONS ---
    function formatCurrency(value) {
        return value.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function updateSummary() {
        const subtotal = parseFloat(subtotalValueEl.textContent) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        const paid = parseFloat(paidValueEl.textContent) || 0;

        const total = subtotal - discount;
        const change = paid - total;

        // Update displays
        discountDisplayEl.textContent = formatCurrency(discount);
        totalDisplayEl.textContent = formatCurrency(total);
        paidDisplayEl.textContent = formatCurrency(paid);
        
        if (change < 0) {
            changeLabelEl.textContent = 'Falta';
            changeDisplayEl.textContent = formatCurrency(Math.abs(change));
        } else {
            changeLabelEl.textContent = 'Troco';
            changeDisplayEl.textContent = formatCurrency(change);
        }

        // Update hidden form values
        hiddenTotalInput.value = total.toFixed(2);
        hiddenChangeInput.value = change.toFixed(2);
        hiddenAmountPaidInput.value = paid.toFixed(2);

        // Toggle finalize/add payment buttons
        if (paid >= total && studentId) {
            finalizeSaleForm.style.display = 'block';
            addPaymentBtn.style.display = 'none';
        } else {
            finalizeSaleForm.style.display = 'none';
            addPaymentBtn.style.display = 'block';
        }
    }

    function addPaymentToList(payment) {
        const paymentEl = document.createElement('p');
        paymentEl.className = 'mb-1';
        paymentEl.textContent = `- ${payment.methodName}: R$ ${formatCurrency(parseFloat(payment.amountPaid))}`;
        paymentListEl.appendChild(paymentEl);
    }

    // --- EVENT LISTENERS ---
    if (discountInput) {
        discountInput.addEventListener('input', updateSummary);
    }

    if(discountForm) {
        discountForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const originalButtonText = discountButton.textContent;
            discountButton.textContent = 'Salvando...';
            discountButton.disabled = true;

            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    discountButton.textContent = 'Salvo!';
                } else { 
                    discountButton.textContent = 'Erro!'; 
                }
            })
            .catch(error => {
                console.error('Error:', error);
                discountButton.textContent = 'Erro de conexão!';
            })
            .finally(() => {
                 setTimeout(() => {
                    discountButton.textContent = originalButtonText;
                    discountButton.disabled = false;
                }, 2000);
            });
        });
    }

    if (paymentForm) {
        paymentForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    paidValueEl.textContent = data.newTotalPaid;
                    addPaymentToList(data.payment);
                    updateSummary();
                    $('#finalize_sale').modal('hide');
                    paymentForm.reset();
                } else {
                    alert('Erro ao adicionar pagamento.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erro de conexão ao adicionar pagamento.');
            });
        });
    }
    
    // --- INITIALIZATION ---
    updateSummary();
});
</script>