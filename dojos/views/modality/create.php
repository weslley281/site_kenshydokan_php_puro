<div class="container mt-5">
    <h1>Criar Turma</h1> <!-- Título da página -->
    <form action="./controllers/ModalityController.php?action=create" method="post"> <!-- Ação do formulário -->
        <div class="form-group form-group"> <!-- Campo para o nome da classe -->
            <label for="name" class="form-label"><strong>Nome:</strong></label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="form-group"> <!-- Campo para a descrição da classe -->
            <label for="description" class="form-label"><strong>Descrição:</strong></label>
            <textarea name="description" id="description" class="form-control" cols="30" rows="5" required></textarea>
        </div>

        <div class="form-group"> <!-- Campo para a descrição da classe -->
            <label for="value" class="form-label"><strong>Valor:</strong></label>
            <input type="text" id="value" name="value" class="form-control" value="99.99" oninput="formatarNumero(this)" required>
            <small>Insira valores separados por pontos, exemplo <strong>"2.99"</strong></small>
        </div>

        <div class="form-group"> <!-- Campo para o ID da arte marcial associada -->
            <label for="id_martial_art" class="form-label"><strong>Arte Marcial:</strong></label>
            <select name="id_martial_art" class="form-control" id="id_martial_art">
                <?php
                $martial_arts = $martial_art->getAll();

                if (isset($martial_arts) && !empty($martial_arts)) {
                    foreach ($martial_arts as $item) {
                        $description = truncate($item['description'], 50);
                ?>
                        <option value="<?= htmlspecialchars($item['id']) ?>"><?= htmlspecialchars($item['name']) ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group"> <!-- Campo para o ID do instrutor associado -->
            <label for="id_instructor" class="form-label"><strong>Instrutor:</strong></label>
            <select class="form-control" name="id_instructor" id="id_instructor">
                <?php
                $users = $user->getAllInstructors();

                if (isset($users) && !empty($users)) {
                    foreach ($users as $item) {
                ?>
                        <option value="<?= htmlspecialchars($item['id']) ?>"><?= htmlspecialchars($item['name']) ?></option>
                <?php
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group"> <!-- Campo para a hora inicial da classe -->
            <label for="initial_hour" class="form-label"><strong>Hora Inicial:</strong></label>
            <input type="time" id="initial_hour" name="initial_hour" class="form-control" required>
        </div>

        <div class="form-group"> <!-- Campo para a hora final da classe -->
            <label for="final_hour" class="form-label"><strong>Hora Final:</strong></label>
            <input type="time" id="final_hour" name="final_hour" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="days" class="form-label"><strong>Quantidade de dias de duração do plano:</strong></label>
            <input type="number" id="days" name="days" class="form-control" required>
        </div>

        <div class="form-group row"> <!-- Botões para submeter ou cancelar -->
            <div class="col">
                <button type="submit" class="mb-3 btn btn-success float-left">Criar</button> <!-- Botão de criação -->
            </div>
            <div class="col">
                <a href="./index.php?page=modalities" class="btn btn-light float-right">Cancelar</a> <!-- Link para cancelar -->
            </div>
        </div>
    </form>
</div>