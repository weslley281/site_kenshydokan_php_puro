<div id="compliance" class="mt-5">
    <a href="views/financial/print_report.php" class="btn btn-primary"><i class="fa-solid fa-print"></i> Imprimir Relatório</a>
    <table id="minhaTabela" class="table table-striped">
        <thead>
            <tr>
                <th>Id</th>
                <th>Estudante</th>
                <th>CPF</th>
                <th>Email</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $usersDatas = $user->getAllStudents();
            $counter = 0;

            if (isset($usersDatas) && !empty($usersDatas)) {
                foreach ($usersDatas as $item) {
                    $counter += 1;

                    $studentName = explode(" ", $item["name"]);
                    $cpf = decrypt($item["cpf"], "gotosao");
            ?>
                    <tr>
                        <td><?= htmlspecialchars($item['id']) ?></td>
                        <td><?= htmlspecialchars($studentName[0]) ?></td>
                        <td><?= htmlspecialchars($cpf) ?></td>
                        <td><?= htmlspecialchars($item['email']) ?></td>
                        <td><?= htmlspecialchars($item['phone']) ?></td>
                        <td>
                            Modalidades Matriculadas <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?= $counter ?>"><i class="fa-solid fa-eye"></i></button>
                        </td>
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="modal<?= $counter ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Modalidades</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <ol>
                                        <?php
                                        $expirationDatas = $expiration->getByUserId($item["id"]);
                                        //var_dump($expirationDatas);
                                        if (isset($expirationDatas) && !empty($expirationDatas)) {
                                            foreach ($expirationDatas as $item2) {
                                                //var_dump($item2);
                                                if (isset($item2["modality_id"])) {
                                                    $modalityData = $modality->getById($item2["modality_id"]);

                                                    $expirationDateObj = new DateTime($item2["expirationDate"]);
                                                    $today = new DateTime();
                                                    if ($expirationDateObj < $today) {
                                                        $expired = "Expirado em";
                                                    } else {
                                                        $expired = "Expira em";
                                                    }
                                                    $expirationDateFormatted = $expirationDateObj->format("d/m/Y")

                                        ?>
                                                    <li><?= htmlspecialchars($modalityData["name"]) . " " . $expired . " " . htmlspecialchars($expirationDateFormatted) ?></li>
                                            <?php
                                                }
                                            }
                                        } else {
                                            ?>
                                            <p>O aluno não tem matriculas ativas</p>
                                        <?php } ?>
                                    </ol>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>

                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>