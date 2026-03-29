<?php
require_once __DIR__ . '/../config/db.php';

class SalesPaymentItem
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
            $stmt = $this->conn->prepare('INSERT INTO sales_payment_item (sale_id, payment_method_id, amount_paid) VALUES (?, ?, ?)');

            $stmt->bind_param('iid', $data['sale_id'], $data['payment_method_id'], $data['amount_paid']);

            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function getById($id)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM sales_payment_item WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();

            return $stmt->get_result()->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }

    public function getTotalAmountPaidBySaleId($sale_id)
    {
        try {
            $stmt = $this->conn->prepare('SELECT SUM(amount_paid) as total FROM sales_payment_item WHERE sale_id = ?');
            $stmt->bind_param('i', $sale_id);
            $stmt->execute();

            $result = $stmt->get_result()->fetch_assoc();
            $total = $result['total'];

            if ($total === null || $total == 0) {
                return 0.0;
            }

            return (float)$total;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return 0.0;
        }
    }

    public function getBySaleId($sale_id)
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM sales_payment_item WHERE sale_id = ?');
            $stmt->bind_param('i', $sale_id);
            $stmt->execute();

            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return [];
        }
    }


    public function update(array $data, $id)
    {
        try {
            $stmt = $this->conn->prepare('UPDATE sales_payment_item SET sale_id = ?, payment_method_id = ?, amount_paid = ? WHERE id = ?');

            $stmt->bind_param('iidi', $data['sale_id'], $data['payment_method_id'], $data['amount_paid'], $id);

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
            $stmt = $this->conn->prepare('DELETE FROM sales_payment_item WHERE id = ?');
            $stmt->bind_param('i', $id);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function clean($sale_id)
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM sales_payment_item WHERE sale_id = ?');
            $stmt->bind_param('i', $sale_id);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function getAll()
    {
        try {
            $result = $this->conn->query('SELECT * FROM sales_payment_item');
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return [];
        }
    }

    public function countAll()
    {
        try {
            $result = $this->conn->query('SELECT COUNT(*) as total FROM sales_payment_item');
            $row = $result->fetch_assoc();
            return $row['total'];
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return 0;
        }
    }
}
