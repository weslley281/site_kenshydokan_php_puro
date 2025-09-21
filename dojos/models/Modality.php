<?php
require_once __DIR__ . '/../config/db.php';

class Modality
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
            $stmt = $this->conn->prepare(
                'INSERT INTO modalities (id_martial_art, id_instructor, name, description, value, initial_hour, final_hour, days)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
            );

            $stmt->bind_param(
                'iisssssi',
                $data['id_martial_art'],
                $data['id_instructor'],
                $data['name'],
                $data['description'],
                $data['value'],
                $data['initial_hour'],
                $data['final_hour'],
                $data['days']
            );

            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function getAll(): array
    {
        try {
            $result = $this->conn->query('SELECT * FROM modalities');
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return [];
        }
    }

    public function getById(int $id): ?array
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM modalities WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();

            $result = $stmt->get_result()->fetch_assoc();
            return $result ?: null;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return null;
        }
    }

    public function update(array $data, int $id): bool
    {
        try {
            $stmt = $this->conn->prepare(
                'UPDATE modalities SET id_martial_art = ?, id_instructor = ?, name = ?, description = ?, value = ?, initial_hour = ?, final_hour = ?, days = ? WHERE id = ?'
            );

            $stmt->bind_param(
                'iisssssii',
                $data['id_martial_art'],
                $data['id_instructor'],
                $data['name'],
                $data['description'],
                $data['value'],
                $data['initial_hour'],
                $data['final_hour'],
                $data['days'],
                $id
            );

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
            $stmt = $this->conn->prepare('DELETE FROM modalities WHERE id = ?');
            $stmt->bind_param('i', $id);
            return $stmt->execute();
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function countAll(): int
    {
        try {
            $result = $this->conn->query('SELECT COUNT(*) as total FROM modalities');
            $row = $result->fetch_assoc();
            return $row['total'];
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return 0;
        }
    }

    public function createModalityDays(int $modality_id, string $daysOfWeek): bool
    {
        try {
            $stmt = $this->conn->prepare(
                'INSERT INTO modality_days (modality_id, day_of_week) VALUES (?, ?)'
            );
            $stmt->bind_param('is', $modality_id, $daysOfWeek);
            $stmt->execute();

            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }

    public function getModalityDays(int $modality_id): array
    {
        try {
            $stmt = $this->conn->prepare('SELECT day_of_week FROM modality_days WHERE modality_id = ?');
            $stmt->bind_param('i', $modality_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $daysOfWeek = [];
            while ($row = $result->fetch_assoc()) {
                $daysOfWeek[] = $row['day_of_week'];
            }
            return $daysOfWeek;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return [];
        }
    }

    public function deleteModalityDays(int $modality_id, string $day_of_week): bool
    {
        try {
            $stmt = $this->conn->prepare('DELETE FROM modality_days WHERE modality_id = ? AND day_of_week = ?');
            $stmt->bind_param('is', $modality_id, $day_of_week);
            $stmt->execute();
            return true;
        } catch (mysqli_sql_exception $e) {
            error_log($e->getMessage(), 3, __DIR__ . '/errors.log');
            return false;
        }
    }
}
