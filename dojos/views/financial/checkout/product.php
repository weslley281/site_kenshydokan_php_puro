<div id="products" class="col-md-12 col-12">
    <div class="card mt-4">
        <div class="card-header">
            <h3>Produtos</h3>
        </div>
        <div class="card-body">
            <form action="./controllers/SalesItemController.php?action=create" method="post">
                <div class="form-group">
                    <label for="select_product">Selecionar Produto</label>
                    <div class="row">
                        <div class="col-11">
                            <input type="hidden" name="sale_id" value="<?= htmlspecialchars($sale_data["id"])  ?>">
                            <select class="form-control select_basic2" name="select_product" id="select_product">
                                <?php
                                $modalities = $modality->getAll(); // Obtém todas as modalities do modelo

                                if (isset($modalities) && !empty($modalities)) { // Verifica se há modalities para exibir
                                    foreach ($modalities as $item) {
                                        $get_user = $user->getById($item['id_instructor']);
                                        $valorFormatado = number_format((float) $item['value'], 2, ',', '.');
                                ?>
                                        <option value="<?= htmlspecialchars($item['id']) ?>">
                                            <?= htmlspecialchars($item['name']) . " R$ " . $valorFormatado ?>
                                        </option>
                                <?php }
                                } ?>
                            </select>
                        </div>
                        <div class="col-1">
                            <button class="btn btn-secondary" type="submit"><i class="fa-solid fa-cart-arrow-down"></i></button>
                        </div>
                    </div>

                </div>
            </form>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Turma</th>
                        <th>Professor</th>
                        <th>Preço Unitário</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $salesItems = $salesItem->getBySaleId($sale_data["id"]);

                    if (isset($salesItems) && !empty($salesItems)) {
                        foreach ($salesItems as $item) {
                            $item = $modality->getById($item["modality_id"]);
                            $sub_total += $item['value'];
                            $get_user = $user->getById($item['id_instructor']);
                            $valorFormatado = number_format((float) $item['value'], 2, ',', '.');
                    ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td><?= htmlspecialchars($get_user["name"]) ?></td>
                                <td>R$ <?= htmlspecialchars($valorFormatado) ?></td>
                                <td>
                                    <form action="controllers/SalesItemController.php?action=delete" method="post">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($item["id"]) ?>">
                                        <button type="submit" class="btn btn-danger"><i class="fa-regular fa-trash-can"></i></button>
                                    </form>
                                </td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>