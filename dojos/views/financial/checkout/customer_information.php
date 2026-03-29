<div id="client" class="col-md-4 col-12">
    <div class="card mt-4">
        <div class="card-header">
            <h3>Informações do Cliente</h3>
        </div>
        <?php if (!$sale_data["student_id"]) { ?>
            <div class="card-body">
                <form action="controllers/SalesRecordController.php?action=update_client" method="post">
                    <div class="form-group">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($sale_data["id"]) ?>">
                        <label for="client">Cliente</label>
                        <select class="form-control select_basic2" name="client" id="client">
                            <?php
                            $users = $user->getAll();

                            if (isset($users) && !empty($users)) {
                                foreach ($users as $item) {
                                    if ($item["id"] != 1) {
                            ?>
                                        <option value="<?= htmlspecialchars($item['id']) ?>">
                                            <?= htmlspecialchars($item['id']) . ": " . $item['name'] ?>
                                        </option>
                            <?php }
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Selecionar o Cliente</button>
                    </div>
                </form>
                <div class="form-group">
                    <label for="nomeCliente">Nome</label>
                    <input type="text" class="form-control" id="nomeCliente" placeholder="Nome do Cliente" readonly>
                </div>
                <div class="form-group">
                    <label for="emailCliente">Email</label>
                    <input type="email" class="form-control" id="emailCliente" placeholder="Email do Cliente" readonly>
                </div>
                <div class="form-group">
                    <label for="telefoneCliente">Telefone</label>
                    <input type="tel" class="form-control" id="telefoneCliente" placeholder="Telefone do Cliente" readonly>
                </div>
                </form>
            </div>
        <?php } else { ?>
            <div class="card-body">
                <form action="controllers/SalesRecordController.php?action=update_client" method="post">
                    <div class="form-group">

                        <input type="hidden" name="id" value="<?= htmlspecialchars($sale_data["id"]) ?>">
                        <label for="client">Cliente</label>
                        <select class="form-control select_basic2" name="client" id="client">
                            <option value=""><?= htmlspecialchars($sale_data["student_id"]) . ": " . $user_data["name"] ?></option>
                            <?php
                            $users = $user->getAll();

                            if (isset($users) && !empty($users)) {
                                foreach ($users as $item) {
                                    if ($item["id"] != $sale_data["student_id"]) {
                            ?>
                                        <option value="<?= htmlspecialchars($item['id']) ?>">
                                            <?= htmlspecialchars($item['id']) . ": " . $item['name'] ?>
                                        </option>
                            <?php }
                                }
                            } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Selecionar o Cliente</button>
                    </div>
                </form>
                <div class="form-group">
                    <label for="nomeCliente">Nome</label>
                    <input type="text" class="form-control" id="nomeCliente" value="<?= htmlspecialchars($user_data['name']) ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="emailCliente">Email</label>
                    <input type="email" class="form-control" id="emailCliente" value="<?= htmlspecialchars($user_data['email']) ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="telefoneCliente">Telefone</label>
                    <input type="tel" class="form-control" id="telefoneCliente" value="<?= htmlspecialchars($user_data['phone']) ?>" readonly>
                </div>
                </form>
            </div>
        <?php } ?>
    </div>
</div>