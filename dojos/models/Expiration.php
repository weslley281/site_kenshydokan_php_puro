<?php
require_once __DIR__ . '/../config/db.php';

class Expiration
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
        //var_dump($data);
        try {
            $stmt = $this->conn->prepare('INSERT INTO expiration (student_id, modality_id, expirationDate) VALUES (?, ?, ?)');

            $stmt->bind_param('iis', $data['student_id'], $data['modality_id'], $data['expirationDate']);

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
            $stmt = $this->conn->prepare('SELECT * FROM expiration WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();

            return $stmt->get_result()->fetch_assoc();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }

    public function getBySaleAndUserId(int $modality_id, int $student_id)
    {
        //echo "SELECT * FROM expiration WHERE modality_id = $modality_id AND student_id = $student_id";
        // Validação básica de entrada
        if ($modality_id <= 0 || $student_id <= 0) {
            error_log("Invalid modality_id or student_id: modality_id=$modality_id, student_id=$student_id", 3, __DIR__ . '/errors.log');
            return null;
        }

        try {
            $stmt = $this->conn->prepare('SELECT * FROM expiration WHERE modality_id = ? AND student_id = ?');

            if ($stmt === false) {
                throw new mysqli_sql_exception("Failed to prepare statement: " . $this->conn->error);
            }

            $stmt->bind_param('ii', $modality_id, $student_id);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            return $result;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }

    public function getByUserId(int $student_id)
    {
        //echo "SELECT * FROM expiration WHERE student_id = $student_id";

        // Validação básica de entrada
        if ($student_id <= 0) {
            error_log("Invalid student_id=$student_id", 3, __DIR__ . '/errors.log');
            return null;
        }

        try {
            $stmt = $this->conn->prepare('SELECT * FROM expiration WHERE student_id = ?');

            if ($stmt === false) {
                throw new mysqli_sql_exception("Failed to prepare statement: " . $this->conn->error);
            }

            $stmt->bind_param('i', $student_id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Fetch all results as an associative array
            $data = $result->fetch_all(MYSQLI_ASSOC);

            $stmt->close();

            return $data;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }



    public function update(array $data, int $id)
    {
        //echo "A array enviada é: ";
        //var_dump($data);
        try {
            $stmt = $this->conn->prepare('UPDATE expiration SET student_id = ?, modality_id = ?, expirationDate = ? WHERE id = ?');

            $stmt->bind_param('iisi', $data['student_id'], $data['modality_id'], $data['expirationDate'], $id);

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
            $stmt = $this->conn->prepare('DELETE FROM expiration WHERE id = ?');
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
            $result = $this->conn->query('SELECT * FROM expiration');
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return [];
        }
    }



    public function countAll()
    {
        try {
            $result = $this->conn->query('SELECT COUNT(*) as total FROM expiration');
            $row = $result->fetch_assoc();
            return $row['total'];
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return 0;
        }
    }

    public function validateStudentEntry(int $student_id): bool
    {
        // Validação básica de entrada
        if ($student_id <= 0) {
            error_log("Invalid student_id", 3, __DIR__ . '/errors.log');
            return false;
        }

        try {
            // Consulta para verificar se há registros com expirationDate >= data atual
            $sql = "
                SELECT 1
                FROM expiration
                WHERE student_id = ? AND expirationDate >= CURDATE()
                LIMIT 1
            ";

            $stmt = $this->conn->prepare($sql);

            if ($stmt === false) {
                throw new mysqli_sql_exception("Failed to prepare statement: " . $this->conn->error);
            }

            $stmt->bind_param('i', $student_id);
            $stmt->execute();
            $stmt->store_result();

            $isValid = $stmt->num_rows > 0;
            $stmt->close();

            return $isValid;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }
}
