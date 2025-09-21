<?php if (isset($_SESSION["user_id"]) && $_SESSION["type"] != "student") { ?>
    <div class="container mt-5">
        <h1>Criar Usuário</h1>
        <form action="./controllers/UserController.php?action=create" method="post" class="form-group" id="createUserForm" novalidate>
            <div class="mb-3 form-group">
                <label for="name" class="form-label"><strong>Nome:</strong></label>
                <input type="text" id="name" name="name" class="form-control" required>
                <div class="invalid-feedback">Por favor, insira um nome.</div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label"><strong>Email:</strong></label>
                <input type="email" id="email" name="email" class="form-control" required>
                <div class="invalid-feedback">Por favor, insira um e-mail válido.</div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label"><strong>Senha:</strong></label>
                <input type="password" id="password" name="password" class="form-control" required>
                <div class="invalid-feedback">Por favor, insira uma senha.</div>
            </div>

            <div class="mb-3">
                <label for="password2" class="form-label"><strong>Repita a Senha:</strong></label>
                <input type="password" id="password2" name="password2" class="form-control" required>
                <div class="invalid-feedback">As senhas não conferem.</div>
            </div>

            <div class="mb-3 form-group">
                <label for="phone" class="form-label"><strong>Telefone:</strong></label>
                <input type="number" id="phone" name="phone" class="form-control" required>
            </div>

            <div class="mb-3 form-group">
                <label for="cpf" class="form-label"><strong>CPF:</strong></label>
                <input type="text" id="cpf" name="cpf" class="form-control" required>
                <div class="invalid-feedback">Por favor, insira um CPF válido.</div>
            </div>

            <div class="mb-3 form-group">
                <label for="address" class="form-label"><strong>Endereço:</strong></label>
                <input type="text" id="address" name="address" class="form-control" required>
            </div>

            <div class="mb-3 form-group">
                <label for="complement" class="form-label"><strong>Complemento:</strong></label>
                <input type="text" id="complement" name="complement" class="form-control" required>
            </div>

            <div class="mb-3 form-group">
                <label for="country" class="form-label"><strong>País:</strong></label>
                <input type="text" id="country" name="country" class="form-control" value="Brasil" readonly required>
            </div>

            <div class="mb-3 form-group">
                <label for="state" class="form-label">Selecione o Estado</label>
                <select class="form-control" class="form-select" id="state" name="state">
                    <option value="AC">Acre</option>
                    <option value="AL">Alagoas</option>
                    <option value="AP">Amapá</option>
                    <option value="AM">Amazonas</option>
                    <option value="BA">Bahia</option>
                    <option value="CE">Ceará</option>
                    <option value="DF">Distrito Federal</option>
                    <option value="ES">Espírito Santo</option>
                    <option value="GO">Goiás</option>
                    <option value="MA">Maranhão</option>
                    <option value="MT">Mato Grosso</option>
                    <option value="MS">Mato Grosso do Sul</option>
                    <option value="MG">Minas Gerais</option>
                    <option value="PA">Pará</option>
                    <option value="PB">Paraíba</option>
                    <option value="PR">Paraná</option>
                    <option value="PE">Pernambuco</option>
                    <option value="PI">Piauí</option>
                    <option value="RJ">Rio de Janeiro</option>
                    <option value="RN">Rio Grande do Norte</option>
                    <option value="RS">Rio Grande do Sul</option>
                    <option value="RO">Rondônia</option>
                    <option value="RR">Roraima</option>
                    <option value="SC">Santa Catarina</option>
                    <option value="SP">São Paulo</option>
                    <option value="SE">Sergipe</option>
                    <option value="TO">Tocantins</option>
                </select>
            </div>

            <div class="mb-3 form-group">
                <label for="city" class="form-label"><strong>Cidade:</strong></label>
                <input type="text" id="city" name="city" class="form-control" required>
            </div>

            <div class="mb-3 form-group">
                <label for="neighborhood" class="form-label"><strong>Bairro:</strong></label>
                <input type="text" id="neighborhood" name="neighborhood" class="form-control" required>
            </div>

            <div class="mb-3 form-group">
                <label for="postal_code" class="form-label"><strong>CEP:</strong></label>
                <input type="text" id="postal_code" name="postal_code" class="form-control" required>
            </div>

            <div class="mb-3 form-group">
                <label for="marital_status" class="form-label"><strong>Estádo Civil:</strong></label>
                <select class="form-control" name="marital_status" id="marital_status" required>
                    <option value="single">Solteiro</option>
                    <option value="married">Casado</option>
                    <option value="divorced">Divorciado</option>
                    <option value="widower">Viúvo</option>
                </select>
            </div>

            <div class="mb-3 form-group">
                <label for="gender" class="form-label"><strong>Genêro:</strong></label>
                <select class="form-control" name="gender" id="gender">
                    <option value="masculine">Masculino</option>
                    <option value="feminine">Feminino</option>
                    <option value="non-binary">Não-Binário</option>
                    <option value="gender-fluid">Gênero-Fluido</option>
                    <option value="transgender">Transgênero</option>
                    <option value="agender">Agênero</option>
                    <option value="two-spirit">Dois Espíritos</option>
                    <option value="other">Outro</option>
                    <option value="null">Prefiro não dizer</option>
                </select>
            </div>

            <div class="mb-3 form-group">
                <label for="birth_date" class="form-label"><strong>Data de Nascimento:</strong></label>
                <input type="date" id="birth_date" name="birth_date" class="form-control" required>
            </div>

            <div class="row">
                <div class="col"><button type="submit" class="mb-3 btn btn-success float-left">Criar</button></div>
                <div class="col"><a href="./index.php?page=users" class="btn btn-light float-right">Cancelar</a></div>
            </div>
        </form>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createUserForm');
    const name = document.getElementById('name');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const password2 = document.getElementById('password2');
    const cpf = document.getElementById('cpf');

    // --- VALIDATION FUNCTIONS ---
    function validateName() {
        if (name.value.trim() === '') {
            name.classList.add('is-invalid');
            return false;
        } else {
            name.classList.remove('is-invalid');
            return true;
        }
    }

    function validateEmail() {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email.value)) {
            email.classList.add('is-invalid');
            return false;
        } else {
            email.classList.remove('is-invalid');
            return true;
        }
    }

    function validatePassword() {
        if (password.value.trim() === '') {
            password.classList.add('is-invalid');
            return false;
        } else {
            password.classList.remove('is-invalid');
            return true;
        }
    }

    function validatePasswordMatch() {
        if (password.value !== password2.value || password2.value.trim() === '') {
            password2.classList.add('is-invalid');
            return false;
        } else {
            password2.classList.remove('is-invalid');
            return true;
        }
    }

    function validateCPF() {
        const cpfValue = cpf.value.replace(/[.\-]/g, '');
        if (cpfValue.length !== 11 || /^(\d)\1{10}$/.test(cpfValue)) {
            cpf.classList.add('is-invalid');
            return false;
        }
        let sum = 0;
        let remainder;
        for (let i = 1; i <= 9; i++) {
            sum += parseInt(cpfValue.substring(i - 1, i)) * (11 - i);
        }
        remainder = (sum * 10) % 11;
        if ((remainder === 10) || (remainder === 11)) {
            remainder = 0;
        }
        if (remainder !== parseInt(cpfValue.substring(9, 10))) {
            cpf.classList.add('is-invalid');
            return false;
        }
        sum = 0;
        for (let i = 1; i <= 10; i++) {
            sum += parseInt(cpfValue.substring(i - 1, i)) * (12 - i);
        }
        remainder = (sum * 10) % 11;
        if ((remainder === 10) || (remainder === 11)) {
            remainder = 0;
        }
        if (remainder !== parseInt(cpfValue.substring(10, 11))) {
            cpf.classList.add('is-invalid');
            return false;
        }
        cpf.classList.remove('is-invalid');
        return true;
    }

    // --- EVENT LISTENERS for real-time validation ---
    name.addEventListener('blur', validateName);
    email.addEventListener('blur', validateEmail);
    password.addEventListener('blur', validatePassword);
    password2.addEventListener('input', validatePasswordMatch);
    cpf.addEventListener('blur', validateCPF);

    // --- FINAL SUBMIT VALIDATION ---
    form.addEventListener('submit', function(event) {
        // Run all validations
        const isNameValid = validateName();
        const isEmailValid = validateEmail();
        const isPasswordValid = validatePassword();
        const isPasswordMatchValid = validatePasswordMatch();
        const isCpfValid = validateCPF();

        // If any validation fails, prevent submission
        if (!isNameValid || !isEmailValid || !isPasswordValid || !isPasswordMatchValid || !isCpfValid) {
            event.preventDefault();
        }
    });
});
</script>

<?php } else {
    echo "<center><strong><h1>Você não Tem permição para isso</h1></strong></center>";
    echo "<script>";
    echo "setTimeout(function() { window.location.href = './index.php?page=dashboard'; }, 3000);";
    echo "</script>";
} ?>