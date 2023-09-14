<?php

class conectar
{
	private $servidor;
	private $usuario;
	private $senha;
	private $bd;

	public function __construct()
	{
		if (file_exists('../env')) {
			$env = file('../env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			foreach ($env as $line) {
				if (strpos($line, '=') !== false) {
					list($key, $value) = explode('=', $line, 2);
					$_ENV[trim($key)] = trim($value);
				}
			}
		}

		$this->servidor = $_ENV['DB_HOST'];
		$this->usuario = $_ENV['DB_USER'];
		$this->senha = $_ENV['DB_PASS'];
		$this->bd = $_ENV['DB_NAME'];
	}

	public function conexao()
	{
		$conexao = mysqli_connect($this->servidor, $this->usuario, $this->senha, $this->bd);

		return $conexao;
	}
}
