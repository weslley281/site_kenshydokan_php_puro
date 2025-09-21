<div class="container mt-5">
    <h1>Lista de Modalities</h1>
    <a href="index.php?page=modalities&action=create" class="btn btn-success mb-3">Criar nova Classe</a> <!-- Botão para criar nova classe -->
    <table id="minhaTabela" class="table table-striped"> <!-- Tabela para exibir as modalities -->
        <thead>
            <tr>
                <th>Nome</th>
                <th>Professor</th>
                <th>Valor</th>
                <th>Hora Inicial</th>
                <th>Hora Final</th>
                <th>Duração</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $modalities = $modality->getAll(); // Obtém todas as modalities do modelo

            if (isset($modalities) && !empty($modalities)) { // Verifica se há modalities para exibir
                foreach ($modalities as $item) {

                    $get_user = $user->getById($item['id_instructor']);
                    $valorFormatado = number_format((float) $item['value'], 2, ',', '.');
            ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name']) ?></td>
                        <td><?= htmlspecialchars($get_user["name"]) ?></td>
                        <td>R$ <?= htmlspecialchars($valorFormatado) ?></td>
                        <td><?= htmlspecialchars($item['initial_hour']) ?></td>
                        <td><?= htmlspecialchars($item['final_hour']) ?></td>
                        <td><?= htmlspecialchars($item['days']) . " dias" ?></td>
                        <td>
                            <a href="index.php?page=modalities&action=update&id=<?= $item['id'] ?>" class="btn btn-info"><i class="fa-regular fa-pen-to-square"></i></a>
                            <?php if (isset($_SESSION['type']) && $_SESSION['type'] === 'admin') { ?><a href="index.php?page=modalities&action=delete&id=<?= $item['id'] ?>" class="btn btn-danger"><i class="fa-regular fa-trash-can"></i></a><?php } ?>
                        </td>
                        <?php
                        if (isset($_GET["action"]) && $_GET["action"] == "update" && isset($_GET["id"]) && $_GET["id"] == $item['id']) {
                            include_once "update.php";
                        } elseif (isset($_SESSION['type']) && $_SESSION['type'] === 'admin' && isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["id"]) && $_GET["id"] == $item['id']) {
                            include_once "delete.php";
                        }
                        ?>
                    </tr>
            <?php
                } // Fim do loop para listar as modalities
            }
            ?>
        </tbody>
    </table>
</div>