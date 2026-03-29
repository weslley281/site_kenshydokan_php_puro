<?php
require_once __DIR__ . '/../config/db.php';

class Cashier
{
    private $conn;

    public function __construct($conn)
    {
        if ($conn === null) {
            throw new Exception("Conexão com o banco de dados não fornecida.");
        }
        $this->conn = $conn;
    }

    public function create(array $data): bool
    {
        try {
            $stmt = $this->conn->prepare('INSERT INTO cashier (user_id, cash, credit, debit, deposit, openedBy, status) VALUES (?, ?, ?, ?, ?, ?, ?)');

            $stmt->bind_param('iddddis', $data['user_id'], $data['cash'], $data['credit'], $data['debit'], $data['deposit'], $data['openedBy'], $data['status']);

            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function getById(int $id): array
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM cashier WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();

            return $stmt->get_result()->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return [];
        }
    }

    public function getCashierOpenByIdUser(int $user_id): array
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM cashier WHERE status = 'open' AND user_id = ?");
            $stmt->bind_param('i', $user_id);
            $stmt->execute();

            return $stmt->get_result()->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return [];
        }
    }

    public function update(array $data, int $id): bool
    {
        try {
            var_dump($data);
            var_dump($id);
            $stmt = $this->conn->prepare('UPDATE cashier SET user_id = ?, cash = ?, credit = ?, debit = ?, deposit = ?, closedBy = ?, status = ? WHERE id = ?');

            $stmt->bind_param('iddddisi', $data['user_id'], $data['cash'], $data['credit'], $data['debit'], $data['deposit'], $data['closedBy'], $data['status'], $id);

            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function delete(int $id): bool
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM cashier WHERE id = ?');
            $stmt->bind_param('i', $id);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    // Método para listar todos os registros no caixa
    public function getAll(): array
    {
        try {
            $result = $this->conn->query('SELECT * FROM cashier');
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return [];
        }
    }

    // Método para contar o número total de registros no caixa
    public function countAll(): int
    {
        try {
            $result = $this->conn->query('SELECT COUNT(*) as total FROM cashier');
            $row = $result->fetch_assoc();
            return $row['total'];
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return 0;
        }
    }

    // Método para verificar se já existe um caixa com o status "open"
    public function isOpen(): bool
    {
        try {
            $stmt = $this->conn->prepare('SELECT COUNT(*) as openCount FROM cashier WHERE status = "open"');
            if ($stmt === false) {
                throw new Exception("Falha na preparação da consulta SQL: " . $this->conn->error);
            }
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            return $result['openCount'] > 0;
        } catch (Exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function cashDrop(string $method, int $user_id, float $amount): bool
    {
        try {
            $stmt = $this->conn->prepare('UPDATE cashier SET ? = ? - ? WHERE user_id = ? AND status = "open"');
            $stmt->bind_param('ssdi', $method, $method, $amount, $user_id);
            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function cashSupply(int $user_id, float $amount): bool
    {
        try {
            $stmt = $this->conn->prepare('UPDATE cashier SET cash = cash + ? WHERE user_id = ? AND status = "open"');
            $stmt->bind_param('di', $amount, $user_id);
            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }
}
