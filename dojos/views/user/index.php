<?php
$type_user = [
    "admin" => "Administrador",
    "student" => "Aluno",
    "instructor" => "Professor"
]
?>
<div class="container mt-5">
    <h1>Lista de Usuário</h1>
    <a href="index.php?page=users&action=create" class="btn btn-success mb-3">Criar novo Usuário</a>
    <table id="minhaTabela" class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $users = $user->getAll();

            if (isset($users) && !empty($users)) {
                foreach ($users as $user) { ?>
                    <tr>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td>
                            <div class="row">
                                <div class="col-sm-3">
                                    <a href="index.php?page=users&action=update&id=<?= $user['id'] ?>" class="btn btn-info"><i class="fa-regular fa-pen-to-square"></i></a>
                                </div>
                                <?php if (isset($_SESSION['type']) && $_SESSION['type'] === 'admin') { ?>
                                <div class="col-sm-3">
                                    <a href="index.php?page=users&action=delete&id=<?= $user['id'] ?>" class="btn btn-danger"><i class="fa-regular fa-trash-can"></i></a>
                                </div>
                                <?php } ?>
                                <div class="col-sm-6">
                                    <?php
                                    if (isset($_SESSION["type"]) && $_SESSION["type"] == "admin") {
                                    ?>
                                        <form action="./controllers/UserController.php?action=updateType" method="post">

                                            <div class="row">
                                                <div class="col-sm-9">
                                                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">
                                                    <select name="type" class="form-control" id="type">
                                                        <option value="<?= htmlspecialchars($user['type']) ?>"><?= htmlspecialchars($type_user[$user["type"]]) ?></option>
                                                        <?php
                                                        switch ($user["type"]) {
                                                            case 'instructor':
                                                        ?>
                                                                <option value="student">Aluno</option>
                                                            <?php
                                                                break;
                                                            case 'student':
                                                            ?>
                                                                <option value="instructor">Professor</option>
                                                            <?php
                                                                break;
                                                            default:
                                                            ?>
                                                                <option value="student">Aluno</option>
                                                                <option value="instructor">Professor</option>
                                                        <?php
                                                                break;
                                                        }
                                                        ?>
                                                    </select>

                                                </div>
                                                <div class="col-sm-3">
                                                    <button class="btn btn-secondary" type="submit"><i class="fa-regular fa-floppy-disk"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </td>
                        <?php
                        if (isset($_GET["action"]) && $_GET["action"] == "update" && isset($_GET["id"]) && $_GET["id"] == $user['id']) {
                            include_once "update.php";
                        } elseif (isset($_SESSION['type']) && $_SESSION['type'] === 'admin' && isset($_GET["action"]) && $_GET["action"] == "delete" && isset($_GET["id"]) && $_GET["id"] == $user['id']) {
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