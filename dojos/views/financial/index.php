<div class="container mt-5">
    <h1>Vendas</h1>

    <?php
    switch ($action) {
        case 'success':
            echo renderAlert('success', 'Sucesso!', 'Caixa aberto com sucesso com sucesso.');
            break;

        case 'fail':
            echo renderAlert('danger', 'Erro!', 'Erro ao abrir caixa.');
            break;

        case 'closed':
            echo renderAlert('info', 'Sucesso!', 'Caixa fechado com sucesso.');
            break;

        case 'is_opened':
            echo renderAlert('warning', 'Erro!', 'Já existe um caixa aberto.');
            break;

        case 'fail2':
            echo renderAlert('danger', 'Erro!', 'Erro ao fechar caixa.');
            break;

        case 'closed':
            echo renderAlert('info', 'Sucesso!', 'Caixa fechado com sucesso.');
            break;
    }
    ?>

    <?php if (!$cashier->isOpen()) { ?>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#abrir_caixa">
            <i class="fa-regular fa-face-grin-beam"></i> Abrir Caixa
        </button>
    <?php } else { ?>
        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#sangria">
            <i class="fa-solid fa-face-sad-cry"></i> Sangria
        </button>

        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#suprimento">
            <i class="fa-solid fa-face-laugh-squint"></i> Suprimento
        </button>

        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#fechar_caixa">
            <i class="fa-regular fa-face-sad-tear"></i> Fechar Caixa
        </button>
    <?php } ?>

    <div class="container">
        <?php if ($action != "sell") { ?>
            <div class="row mt-5">
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 my-2">
                    <div class="card" style="width: 18rem;">
                        <img class="card-img-top" src="./images/venda.jpg" alt="Imagem de capa do card">
                        <div class="card-body">
                            <h5 class="card-title">Vender</h5>
                            <p class="card-text"><strong>Venda combo de planos</strong>.</p>
                            <?php if ($cashier->isOpen()) { ?>
                                <a href="index.php?page=financial&action=sell" class="btn btn-primary"><i class="fa-solid fa-eye"></i> Visitar</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-4 my-2">
                    <div class="card" style="width: 18rem;">
                        <img class="card-img-top" src="./images/gasto.jpg" alt="Imagem de capa do card">
                        <div class="card-body">
                            <h5 class="card-title">Despesas</h5>
                            <p class="card-text"><strong>Registre suas dispesas</strong>.</p>
                            <a href="index.php?page=financial&action=sell" class="btn btn-primary"><i class="fa-solid fa-eye"></i> Visitar</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-4 my-2">
                    <div class="card" style="width: 18rem;">
                        <img class="card-img-top" src="./images/relatorio.jpg" alt="Imagem de capa do card">
                        <div class="card-body">
                            <h5 class="card-title">Relatórios</h5>
                            <p class="card-text"><strong>Veja seus relatórios de vendas</strong>.</p>
                            <a href="index.php?page=financial&action=sales_report#report" class="btn btn-primary"><i class="fa-solid fa-eye"></i> Visitar</a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-4 my-2">
                    <div class="card" style="width: 18rem;">
                        <img class="card-img-top" src="./images/relatorio.jpg" alt="Imagem de capa do card">
                        <div class="card-body">
                            <h5 class="card-title">Adimplências</h5>
                            <p class="card-text"><strong>Veja quais alunos estão em dias com suas mensalidades</strong>.</p>
                            <a href="index.php?page=financial&action=compliance#compliance" class="btn btn-primary"><i class="fa-solid fa-eye"></i> Visitar</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php
            if ($action == "sales_report") include_once "sales_report.php";
            if ($action == "compliance") include_once "compliance.php";
        } elseif ($action == "sell") {
            include_once "checkout.php";
        } ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="abrir_caixa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Você tem certeza que deseja abrir o Caixa?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="./controllers/CashierController.php?action=create" method="post">
                        <input type="hidden" name="status" value="open">
                        <div class="form-group">
                            <label for="cash">Saldo inicial Dinheiro</label>
                            <input type="text" id="cash" name="cash" class="form-control" oninput="formatarNumero(this)" value="0" required>
                            <small>Insira valores separados por pontos, exemplo <strong>"2.99"</strong></small>
                        </div>
                        <div class="form-group">
                            <label for="credit ">Saldo inicial Crédito</label>
                            <input type="text" id="credit" name="credit" class="form-control" oninput="formatarNumero(this)" value="0" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="debit">Saldo inicial Débito</label>
                            <input type="text" id="debit" name="debit" class="form-control" oninput="formatarNumero(this)" value="0" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="deposit">Saldo inicial Depósiti</label>
                            <input type="text" id="deposit" name="deposit" class="form-control" oninput="formatarNumero(this)" value="0" required readonly>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar mudanças</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sangria" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Você tem certeza que deseja abrir o Caixa?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="./controllers/CashierController.php?action=create" method="post">
                        <input type="hidden" name="method" value="cash_drop">
                        <div class="form-group">
                            <label for="cash">Valor</label>
                            <input type="text" id="cash" name="cash" class="form-control" oninput="formatarNumero(this)" value="0" required>
                            <small>Insira valores separados por pontos, exemplo <strong>"2.99"</strong></small>
                        </div>
                        <div class="form-group">
                            <label for="credit ">Saldo inicial Crédito</label>
                            <input type="text" id="credit" name="credit" class="form-control" oninput="formatarNumero(this)" value="0" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="debit">Saldo inicial Débito</label>
                            <input type="text" id="debit" name="debit" class="form-control" oninput="formatarNumero(this)" value="0" required readonly>
                        </div>
                        <div class="form-group">
                            <label for="deposit">Saldo inicial Depósiti</label>
                            <input type="text" id="deposit" name="deposit" class="form-control" oninput="formatarNumero(this)" value="0" required readonly>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary">Salvar mudanças</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fechar_caixa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Você tem certeza que deseja fechar o Caixa?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if ($cashier->isOpen()) { ?>
                        <form action="./controllers/CashierController.php?action=update" method="post">
                            <?php
                            $cashier_open = $cashier->getCashierOpenByIdUser($_SESSION["user_id"]);
                            ?>
                            <input type="hidden" name="id" value="<?= htmlspecialchars($cashier_open['id']) ?>">
                            <input type="hidden" name="status" value="close">
                            <div class="form-group">
                                <label for="cash">Saldo Dinheiro</label>
                                <input type="text" id="cash" name="cash" class="form-control" value="<?= htmlspecialchars($cashier_open['cash']) ?>" readonly required>
                            </div>
                            <div class="form-group">
                                <label for="credit ">Saldo Crédito</label>
                                <input type="text" id="credit" name="credit" class="form-control" value="<?= htmlspecialchars($cashier_open['credit']) ?>" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="debit">Saldo Débito</label>
                                <input type="text" id="debit" name="debit" class="form-control" value="<?= htmlspecialchars($cashier_open['debit']) ?>" required readonly>
                            </div>
                            <div class="form-group">
                                <label for="deposit">Saldo Depósiti</label>
                                <input type="text" id="deposit" name="deposit" class="form-control" value="<?= htmlspecialchars($cashier_open['deposit']) ?>" required readonly>
                            </div>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-danger">Salvar mudanças</button>
                        </form>
                    <?php } else { ?>
                        <p class="text-cente"><strong>Não há caixa aberto para ser fechado</strong></p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>