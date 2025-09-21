<?php
$get_martialart = $martialart->getById($class_item["id_martial_art"]);
$get_instructor = $user->getById($class_item["id_instructor"]);
?>
<div class="container mt-5">
    <h1>Editar Turma</h1>
    <form action="./controllers/ClassController.php?action=update" method="post"> <!-- Ação do formulário -->
        <input type="hidden" name="id" value="<?php echo $_GET["id"] ?>">
        <div class="form-group form-group"> <!-- Campo para o nome da classe -->
            <label for="name" class="form-label"><strong>Nome:</strong></label>
            <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($class_item['name']); ?>" required>
        </div>

        <div class="form-group"> <!-- Campo para a descrição da classe -->
            <label for="description" class="form-label"><strong>Descrição:</strong></label>
            <textarea name="description" id="description" class="form-control" cols="30" rows="5" required><?= htmlspecialchars($class_item['description']); ?></textarea>
        </div>

        <div class="form-group"> <!-- Campo para a descrição da classe -->
            <label for="value" class="form-label"><strong>Valor:</strong></label>
            <input type="text" id="value" name="value" value="<?= htmlspecialchars($class_item['value']); ?>" class="form-control" oninput="formatarNumero(this)" required>
            <small>Insira valores separados por pontos, exemplo <strong>"2.99"</strong></small>
        </div>

        <div class="form-group"> <!-- Campo para o ID da arte marcial associada -->
            <label for="id_martial_art" class="form-label"><strong>Arte Marcial:</strong></label>
            <select name="id_martial_art" class="form-control" id="id_martial_art">
                <option value="<?= htmlspecialchars($get_martialart["id"]); ?>"><?= htmlspecialchars($get_martialart["name"]); ?></option>
                <?php
                $martialarts = $martialart->getAll();

                if (isset($martialarts) && !empty($martialarts)) {
                    foreach ($martialarts as $martialart) {
                        if ($martialart['name'] != $get_martialart["name"]) {
                ?>
                            <option value="<?= htmlspecialchars($martialart['id']) ?>"><?= htmlspecialchars($martialart['name']) ?></option>
                <?php
                        }
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group"> <!-- Campo para o ID do instrutor associado -->
            <label for="id_instructor" class="form-label"><strong>Instrutor:</strong></label>
            <select class="form-control" name="id_instructor" id="id_instructor">
                <option value="<?= htmlspecialchars($get_instructor["id"]); ?>"><?= htmlspecialchars($get_instructor["name"]); ?></option>
                <?php
                $users = $user->getAllInstructors();

                if (isset($users) && !empty($users)) {
                    foreach ($users as $instructor) {
                        if ($instructor['name'] != $get_instructor["name"]) {
                ?>
                            <option value="<?= htmlspecialchars($instructor['id']) ?>"><?= htmlspecialchars($instructor['name']) ?></option>
                <?php
                        }
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group"> <!-- Campo para a hora inicial da classe -->
            <label for="initial_hour" class="form-label"><strong>Hora Inicial:</strong></label>
            <input type="time" id="initial_hour" name="initial_hour" class="form-control" value="<?= htmlspecialchars($class_item['initial_hour']); ?>" required>
        </div>

        <div class="form-group"> <!-- Campo para a hora final da classe -->
            <label for="final_hour" class="form-label"><strong>Hora Final:</strong></label>
            <input type="time" id="final_hour" name="final_hour" class="form-control" value="<?= htmlspecialchars($class_item['final_hour']); ?>" required>
        </div>

        <div class="form-group">
            <label for="days" class="form-label"><strong>Quantidade de dias de duração do plano::</strong></label>
            <input type="number" id="days" name="days" class="form-control" value="<?= htmlspecialchars($class_item['days']); ?>" required>
        </div>

        <div class="form-group row"> <!-- Botões para submeter ou cancelar -->
            <div class="col">
                <button type="submit" class="mb-3 btn btn-secondary float-left">
                    Salvar
                </button> <!-- Botão de criação -->
            </div>
            <div class="col">
                <a href="./index.php?page=modalities" class="mb-3 btn btn-light float-right"><i class="fa-solid fa-xmark"></i> Cancelar</a> <!-- Link para cancelar -->
            </div>
        </div>
    </form>
    <form action="./controllers/ClassDaysController.php?action=create" method="post">
        <input type="hidden" name="id" value="<?php echo $_GET["id"] ?>">
        <div class="form-group">
            <label for="day_of_week"><strong>Escolha os dias da Semana</strong></label>
            <div class="row">
                <div class="col-sm-11">
                    <select class="form-control" name="day_of_week" id="day_of_week">
                        <option value="Monday">Segunda-Feira</option>
                        <option value="Tuesday">Terça-Feira</option>
                        <option value="Wednesday">Quarta-Feira</option>
                        <option value="Thursday">Quinta-Feira</option>
                        <option value="Friday">Sexta-Feira</option>
                        <option value="Saturday">Sábado</option>
                        <option value="Sunday">Domingo</option>
                    </select>
                </div>
                <div class="col-sm-1">
                    <button class="btn btn-secondary" type="submit"><i class="fa-regular fa-floppy-disk"></i></button>
                </div>
            </div>

        </div>
    </form>
    <h3><strong>Dias de Aulas:</strong></h3>
    <ul class="list-group my-4">
        <?php
        $class_days = $class->getClassDays($_GET["id"]);
        if (isset($class_days) && !empty($class_days)) {
            foreach ($class_days as $day_of_week) {
                $days_item = [
                    "Monday" => "Segunda-Feira",
                    "Tuesday" => "Terça-Feira",
                    "Wednesday" => "Quarta-Feira",
                    "Thursday" => "Quinta-Feira",
                    "Friday" => "Sexta-Feira",
                    "Saturday" => "Sábado-Feira",
                    "Sunday" => "Domingo-Feira",
                ]
        ?>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-11"><?= $days_item[$day_of_week] ?></div>
                        <div class="col-1">
                            <form action="./controllers/ClassDaysController.php?action=delete" method="post">
                                <input type="hidden" name="id" value="<?= htmlspecialchars($_GET["id"]) ?>">
                                <input type="hidden" name="day_of_week" value="<?= htmlspecialchars($day_of_week) ?>">
                                <button class="btn btn-danger" type="submit"><i class="fa-regular fa-trash-can"></i></button>
                            </form>
                        </div>
                    </div>
                </li>
        <?php
            }
        } ?>
    </ul>
</div>