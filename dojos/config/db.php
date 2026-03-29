<?php
$host = 'academias.mysql.uhserver.com';
$user = 'academias';
$password = 'Wesv@g28';
$database = 'academias';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die('Falha na conexão com o Banco de dados: ' . $conn->connect_error);
}
