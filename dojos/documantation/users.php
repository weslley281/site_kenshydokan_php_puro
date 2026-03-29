<?php
// Vamos supor que o modelo de "users" já esteja configurado.
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Documentação do Modelo "Users"</title>
    <link rel="stylesheet" href="../libs/bootstrap/bootstrap.css">

    <script src="../libs/bootstrap/jquery.js"></script>
    <script src="../libs/bootstrap/popper.js"></script>
    <script src="../libs/bootstrap/bootstrap.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Documentação do Modelo "Users"</h1>

        <h2>Descrição Geral</h2>
        <p>
            O modelo "Users" representa os usuários do sistema. Ele armazena informações pessoais e detalhes necessários para autenticação e operações relacionadas aos usuários.
        </p>

        <h2>Estrutura do Banco de Dados</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Tipo</th>
                    <th>Descrição</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>id</td>
                    <td>INT (AUTO_INCREMENT)</td>
                    <td>Identificador único do usuário.</td>
                </tr>
                <tr>
                    <td>name</td>
                    <td>VARCHAR(255)</td>
                    <td>Nome completo do usuário.</td>
                </tr>
                <tr>
                    <td>email</td>
                    <td>VARCHAR(255)</td>
                    <td>E-mail do usuário (único).</td>
                </tr>
                <tr>
                    <td>phone</td>
                    <td>VARCHAR(30)</td>
                    <td>Telefone do usuário.</td>
                </tr>
                <tr>
                    <td>address</td>
                    <td>VARCHAR(255)</td>
                    <td>Endereço do usuário.</td>
                </tr>
                <tr>
                    <td>password</td>
                    <td>VARCHAR(255)</td>
                    <td>Senha do usuário (criptografada).</td>
                </tr>
                <tr>
                    <td>cpf</td>
                    <td>VARCHAR(11)</td>
                    <td>CPF do usuário (único, opcional).</td>
                </tr>
                <tr>
                    <td>birthDate</td>
                    <td>DATE</td>
                    <td>Data de nascimento do usuário.</td>
                </tr>
                <tr>
                    <td>createDate</td>
                    <td>DATETIME</td>
                    <td>Data de criação do registro.</td>
                </tr>
                <tr>
                    <td>editDate</td>
                    <td>DATETIME</td>
                    <td>Data da última edição do registro.</td>
                </tr>
            </tbody>
        </table>

        <h2>Métodos Relacionados ao Modelo "Users"</h2>
        <ul>
            <li><strong>create($data):</strong> Cria um novo usuário com base nos dados fornecidos.</li>
            <li><strong>getAll():</strong> Retorna uma lista de todos os usuários.</li>
            <li><strong>getById($id):</strong> Retorna um usuário com base no ID fornecido.</li>
            <li><strong>update($data, $id):</strong> Atualiza os dados de um usuário com base no ID fornecido.</li>
            <li><strong>delete($id):</strong> Deleta um usuário com base no ID fornecido.</li>
        </ul>

        <h2>Considerações Importantes</h2>
        <ul>
            <li>Certifique-se de criptografar a senha antes de armazená-la no banco de dados.</li>
            <li>Valide e sanitize todos os dados recebidos do usuário para evitar injeção de scripts e outros problemas de segurança.</li>
            <li>O CPF deve ser único, então implemente verificações para garantir que não haja duplicatas.</li>
            <li>Utilize consultas preparadas para prevenir SQL Injection ao interagir com o banco de dados.</li>
        </ul>

    </div>
</body>
</html>
