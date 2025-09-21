<?php
require_once __DIR__ . '/../config/db.php';

class SalesRecord
{
    private $conn;

    public function __construct($conn)
    {
        if ($conn === null) {
            throw new Exception("Conexão com o banco de dados não fornecida.");
        }
        $this->conn = $conn;
    }

    public function create(array $data)
    {
        try {
            //echo "<br>também fui chamado";
            $stmt = $this->conn->prepare('INSERT INTO sales_records (cashier_id, user_id, student_id, amount_paid, change_sale, total, status) VALUES (?, ?, ?, ?, ?, ?, ?)');

            if ($stmt === false) {
                throw new mysqli_sql_exception('Erro ao preparar a instrução: ' . $this->conn->error);
            }

            $stmt->bind_param(
                'iiiddds',
                $data['cashier_id'],
                $data['user_id'],
                $data['student_id'],
                $data['amount_paid'],
                $data['change_sale'],
                $data['total'],
                $data['status']
            );

            $stmt->execute();
            $stmt->close();

            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }



    public function getById($id)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM sales_records WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();

            return $stmt->get_result()->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }

    public function getSaleInProcessByIdUser($user_id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM sales_records WHERE status = 'in_process' AND user_id = ?");
            $stmt->bind_param('i', $user_id);
            $stmt->execute();

            return $stmt->get_result()->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }

    public function update(array $data, $id)
    {
        //var_dump($data);
        try {
            $stmt = $this->conn->prepare('UPDATE sales_records SET cashier_id = ?, user_id = ?, student_id = ?, discount = ?, amount_paid = ?, change_sale = ?, total = ?, status = ? WHERE id = ?');

            $stmt->bind_param('iiiddddsi', $data['cashier_id'], $data['user_id'], $data['student_id'], $data['discount'], $data['amount_paid'], $data['change_sale'], $data['total'], $data['status'], $id);

            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function updateClient(int $client, int $id)
    {
        try {
            $stmt = $this->conn->prepare('UPDATE sales_records SET student_id = ? WHERE id = ?');

            $stmt->bind_param('ii', $client, $id);

            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function updateDiscount(int $discount, int $id)
    {
        try {
            $stmt = $this->conn->prepare('UPDATE sales_records SET discount = ? WHERE id = ?');

            $stmt->bind_param('di', $discount, $id);

            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM sales_records WHERE id = ?');
            $stmt->bind_param('i', $id);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function getAll()
    {
        try {
            $result = $this->conn->query('SELECT * FROM sales_records');
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return [];
        }
    }

    public function countAll()
    {
        try {
            $result = $this->conn->query('SELECT COUNT(*) as total FROM sales_records');
            $row = $result->fetch_assoc();
            return $row['total'];
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return 0;
        }
    }

    public function countUserSales($userId): int
    {
        try {
            $stmt = $this->conn->prepare('SELECT COUNT(*) as total FROM sales_records WHERE user_id = ?');
            $stmt->bind_param('i', $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['total'];
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return 0;
        }
    }

    public function countUserSalesByStatus($userId, $status): int
    {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM sales_records WHERE user_id = ? AND status = ?");
            $stmt->bind_param('is', $userId, $status);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['total'];
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return 0;
        }
    }

    public function getLastProcessedSale()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM sales_records WHERE status = 'processed' ORDER BY id DESC LIMIT 1");
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }
}
