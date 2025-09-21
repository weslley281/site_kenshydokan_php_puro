<?php
?>
<div class="container mt-5">
    <h1>Editar Usuário</h1>
    <form action="./controllers/UserController.php?action=update" method="post" class="form-group">
        <input type="hidden" name="id" value="<?php echo $_GET["id"] ?>">
        <div class="mb-3 form-group">
            <label for="name" class="form-label"><strong>Nome:</strong></label>
            <input type="text" id="name" name="name" class="form-control" value="<?php echo $user["name"] ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label"><strong>Email:</strong></label>
            <input type="email" id="email" name="email" class="form-control" value="<?php echo $user["email"] ?>" required>
        </div>

        <div class="mb-3 form-group">
            <label for="phone" class="form-label"><strong>Telefone:</strong></label>
            <input type="number" id="phone" name="phone" class="form-control" value="<?php echo $user["phone"] ?>" required>
        </div>

        <div class="mb-3 form-group">
            <label for="cpf" class="form-label"><strong>CPF:</strong></label>
            <input type="text" id="cpf" name="cpf" class="form-control" value="<?php echo decrypt($user["cpf"], "gotosao")  ?>" required>
        </div>

        <div class="mb-3 form-group">
            <label for="address" class="form-label"><strong>Endereço:</strong></label>
            <input type="text" id="address" name="address" class="form-control" value="<?php echo $user["address"] ?>" required>
        </div>

        <div class="mb-3 form-group">
            <label for="complement" class="form-label"><strong>Complemento:</strong></label>
            <input type="text" id="complement" name="complement" class="form-control" value="<?php echo $user["complement"] ?>" required>
        </div>

        <div class="mb-3 form-group">
            <label for="country" class="form-label"><strong>País:</strong></label>
            <input type="text" id="country" name="country" class="form-control" value="Brasil" readonly required>
        </div>

        <div class="mb-3 form-group">
            <label for="state" class="form-label">Selecione o Estado</label>
            <select class="form-control" class="form-select" id="state" name="state" required>
                <option value="">Selecione um estado</option>
                <?php
                $states = array(
                    'AC' => 'Acre',
                    'AL' => 'Alagoas',
                    'AP' => 'Amapá',
                    'AM' => 'Amazonas',
                    'BA' => 'Bahia',
                    'CE' => 'Ceará',
                    'DF' => 'Distrito Federal',
                    'ES' => 'Espírito Santo',
                    'GO' => 'Goiás',
                    'MA' => 'Maranhão',
                    'MT' => 'Mato Grosso',
                    'MS' => 'Mato Grosso do Sul',
                    'MG' => 'Minas Gerais',
                    'PA' => 'Pará',
                    'PB' => 'Paraíba',
                    'PR' => 'Paraná',
                    'PE' => 'Pernambuco',
                    'PI' => 'Piauí',
                    'RJ' => 'Rio de Janeiro',
                    'RN' => 'Rio Grande do Norte',
                    'RS' => 'Rio Grande do Sul',
                    'RO' => 'Rondônia',
                    'RR' => 'Roraima',
                    'SC' => 'Santa Catarina',
                    'SP' => 'São Paulo',
                    'SE' => 'Sergipe',
                    'TO' => 'Tocantins',
                );

                foreach ($states as $code => $name) {
                    $selected = ($user['state'] === $code) ? 'selected' : '';
                    echo "<option value=\"$code\" $selected>$name</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3 form-group">
            <label for="city" class="form-label"><strong>Cidade:</strong></label>
            <input type="text" id="city" name="city" class="form-control" value="<?php echo $user["city"] ?>" required>
        </div>

        <div class="mb-3 form-group">
            <label for="neighborhood" class="form-label"><strong>Bairro:</strong></label>
            <input type="text" id="neighborhood" name="neighborhood" class="form-control" value="<?php echo $user["neighborhood"] ?>" required>
        </div>

        <div class="mb-3 form-group">
            <label for="postal_code" class="form-label"><strong>CEP:</strong></label>
            <input type="text" id="postal_code" name="postal_code" class="form-control" value="<?php echo $user["postal_code"] ?>" required>
        </div>

        <div class="mb-3 form-group">
            <label for="marital_status" class="form-label"><strong>Estado Civil:</strong></label>
            <select class="form-control" name="marital_status" id="marital_status" required>
                <?php
                $marital_statuses = [
                    'single' => 'Solteiro',
                    'married' => 'Casado',
                    'divorced' => 'Divorciado',
                    'widower' => 'Viúvo',
                ];

                foreach ($marital_statuses as $status => $label) {
                    $selected = ($user['marital_status'] === $status) ? 'selected' : '';
                    echo "<option value=\"$status\" $selected>$label</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3 form-group">
            <label for="gender" class="form-label"><strong>Gênero:</strong></label>
            <select class="form-control" name="gender" id="gender" required>
                <?php
                $genders = [
                    'masculine' => 'Masculino',
                    'feminine' => 'Feminino',
                    'non-binary' => 'Não-Binário',
                    'gender-fluid' => 'Gênero-Fluido',
                    'transgender' => 'Transgênero',
                    'agender' => 'Agênero',
                    'two-spirit' => 'Dois Espíritos',
                    'other' => 'Outro',
                    'null' => 'Prefiro não dizer',
                ];

                foreach ($genders as $gender => $label) {
                    $selected = ($user['gender'] === $gender) ? 'selected' : '';
                    echo "<option value=\"$gender\" $selected>$label</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3 form-group">
            <label for="birth_date" class="form-label"><strong>Data de Nascimento:</strong></label>
            <input type="date" id="birth_date" name="birth_date" class="form-control" value="<?php echo $user["birth_date"] ?>" required>
        </div>

        <div class="row">
            <div class="col"><button type="submit" class="mb-3 btn btn-secondary float-left">Salvar</button></div>
            <div class="col"><a href="./index.php?page=users" class="btn btn-light float-right">Cancelar</a></div>
        </div>


    </form>
</div>