<div class="container mt-5">
    <h1>Lista de Artes Marciais</h1>
    <a href="index.php?page=martial_arts&action=create" class="btn btn-success mb-3">Criar nova Arte Marcial</a>
    <table id="minhaTabela" class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $martial_arts = $martial_art->getAll();

            if (isset($martial_arts) && !empty($martial_arts)) {
                foreach ($martial_arts as $martial_art) {
                    $description = truncate($martial_art['description'], 50);
            ?>
                    <tr>
                        <td><?= htmlspecialchars($martial_art['name']) ?></td>
                        <td><?= htmlspecialchars($description) ?></td>
                        <td>
                            <a href="index.php?page=martial_arts&action=update&id=<?= $martial_art['id'] ?>" class="btn btn-info"><i class="fa-regular fa-pen-to-square"></i></a>
                            <?php if (isset($_SESSION['type']) && $_SESSION['type'] === 'admin') { ?><a href="index.php?page=martial_arts&action=delete&id=<?= $martial_art['id'] ?>" class="btn btn-danger"><i class="fa-regular fa-trash-can"></i></a><?php } ?>
                        </td>
                        <?php
                        if (isset($_GET["action"]) && $_GET["action"] == "update" && isset($_GET["id"]) && $_GET["id"] == $martial_art['id']) {
                            include_once "update.php";
                        } elseif (isset($_SESSION['type']) && $_SESSION['type'] === 'admin' && isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["id"]) && $_GET["id"] == $martial_art['id']) {
                            include_once "delete.php";
                        }
                        ?>
                    </tr>
            <?php }
            }
            ?>
        </tbody>
    </table>
</div>