<?php
require_once __DIR__ . '/db.php';

class CreateTables
{
    public static function createUsersTable($conn)
    {
        $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255),
        phone VARCHAR(100),
        email VARCHAR(255) UNIQUE,
        password VARCHAR(255),
        cpf VARCHAR(255) UNIQUE,
        type ENUM('admin', 'instructor', 'student'),
        address VARCHAR(255),
        complement VARCHAR(255),
        country VARCHAR(100),
        state VARCHAR(100),
        city VARCHAR(100),
        neighborhood VARCHAR(100),
        postal_code VARCHAR(10),
        marital_status ENUM('single', 'married', 'divorced', 'widower'),
        gender ENUM('masculine', 'feminine', 'non-binary', 'gender-fluid', 'transgender', 'agender', 'two-spirit', 'other', 'null'),
        birth_date DATE,
        editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    ";

        if ($conn->query($sql) === true) {
            //echo "Tabela 'users' criada com sucesso.";
        } else {
            echo "Erro ao criar tabela 'users': " . $conn->error;
        }
    }

    public static function createMartialArtsTable($conn)
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS martial_arts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) UNIQUE,
            description TEXT(500),
            editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        ";

        if ($conn->query($sql) === true) {
            //echo "Tabela de usuários criada com sucesso.";
        } else {
            echo "Erro ao criar tabela 'users': " . $conn->error;
        }
    }

    public static function createModalityTable($conn)
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS modalities (
            id INT AUTO_INCREMENT PRIMARY KEY,
            id_martial_art INT,
            id_instructor INT,
            name VARCHAR(255) UNIQUE,
            description TEXT(500),
            value DECIMAL(10, 2),
            initial_hour TIME,
            final_hour TIME,
            days INT,
            editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (id_martial_art) REFERENCES martial_arts(id),
            FOREIGN KEY (id_instructor) REFERENCES users(id)
        );
        ";

        if ($conn->query($sql) === true) {
            //echo "Tabela 'modalities' criada com sucesso.";
        } else {
            echo "Erro ao criar tabela 'modalities': " . $conn->error;
        }
    }

    public static function createModalityDaysTable($conn)
    {
        $sql = "
        CREATE TABLE IF NOT EXISTS modality_days (
            id INT AUTO_INCREMENT PRIMARY KEY,
            modality_id INT,
            day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
            editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (modality_id) REFERENCES modalities(id)
        );

        ";

        if ($conn->query($sql) === true) {
            //echo "Tabela 'modalities' criada com sucesso.";
        } else {
            echo "Erro ao criar tabela 'modalities': " . $conn->error;
        }
    }

    public static function createMethodPaymentTable($conn)
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS method_payment (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255),
                processing_fee DECIMAL(10, 2) DEFAULT 0,
                editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ";

        if ($conn->query($sql) === true) {
            //echo "Tabela 'method_payment' criada com sucesso.";
        } else {
            echo "Erro ao criar tabela 'method_payment': " . $conn->error;
        }
    }

    public static function createCashierTable($conn)
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS cashier (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                cash DECIMAL(10, 2) DEFAULT 0,
                credit DECIMAL(10, 2) DEFAULT 0,
                debit DECIMAL(10, 2) DEFAULT 0,
                deposit DECIMAL(10, 2) DEFAULT 0,
                openedBy INT,
                closedBy INT,
                status ENUM('open', 'close'),
                open TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                close TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (openedBy) REFERENCES users(id),
                FOREIGN KEY (closedBy) REFERENCES users(id)
            );
        ";

        if ($conn->query($sql) === true) {
            //echo "Tabela 'cashier' criada com sucesso.";
        } else {
            echo "Erro ao criar tabela 'cashier': " . $conn->error;
        }
    }

    public static function createSalesTable($conn)
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS sales_records (
                id INT AUTO_INCREMENT PRIMARY KEY,
                cashier_id INT NOT NULL,
                user_id INT NOT NULL,
                student_id INT NULL,
                discount DECIMAL(10, 2) NULL,
                amount_paid DECIMAL(10, 2) NULL,
                change_sale DECIMAL(10, 2) NULL,
                total DECIMAL(10, 2) NULL,
                status ENUM('in_process', 'processed', 'canceled'),
                saleDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (cashier_id) REFERENCES cashier(id),
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (student_id) REFERENCES users(id)
            );
        ";

        if ($conn->query($sql) === true) {
            //echo "Tabela 'sales_records' criada com sucesso.";
        } else {
            echo "Erro ao criar tabela 'sales_records': " . $conn->error;
        }
    }

    public static function createSalesItemTable($conn)
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS sales_item (
                id INT AUTO_INCREMENT PRIMARY KEY,
                sale_id INT NOT NULL,
                modality_id INT NOT NULL,
                editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (sale_id) REFERENCES sales_records(id),
                FOREIGN KEY (modality_id) REFERENCES modalities(id)
            );
        ";

        if ($conn->query($sql) === true) {
            //echo "Tabela 'sales_item' criada com sucesso.";
        } else {
            echo "Erro ao criar tabela 'sales_item': " . $conn->error;
        }
    }

    public static function createSalesPaymentItemTable($conn)
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS sales_payment_item (
                id INT AUTO_INCREMENT PRIMARY KEY,
                sale_id INT NOT NULL,
                payment_method_id INT NOT NULL,
                amount_paid DECIMAL(10, 2) NOT NULL,
                editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (sale_id) REFERENCES sales_records(id),
                FOREIGN KEY (payment_method_id) REFERENCES method_payment(id)
            );
        ";

        if ($conn->query($sql) === true) {
            //echo "Tabela 'sales_item' criada com sucesso.";
        } else {
            echo "Erro ao criar tabela 'sales_item': " . $conn->error;
        }
    }

    public static function createExpirationTable($conn)
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS expiration (
                id INT AUTO_INCREMENT PRIMARY KEY,
                student_id INT NOT NULL,
                modality_id INT NOT NULL,
                expirationDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                editDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                createDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (student_id) REFERENCES users(id),
                FOREIGN KEY (modality_id) REFERENCES modalities(id)
            );
        ";

        if ($conn->query($sql) === true) {
            //echo "Tabela 'sales_item' criada com sucesso.";
        } else {
            echo "Erro ao criar tabela 'sales_item': " . $conn->error;
        }
    }

    public static function insertPaymentMethods($conn)
    {
        $paymentMethods = ['cash', 'credit', 'debit', 'deposit'];

        foreach ($paymentMethods as $method) {
            // Verifica se o método de pagamento já existe
            $checkSql = "SELECT id FROM method_payment WHERE name = '$method'";
            $result = $conn->query($checkSql);

            if ($result->num_rows == 0) {
                // Insere o método de pagamento se não existir
                $insertSql = "INSERT INTO method_payment (name, processing_fee) VALUES ('$method', 0)";
                if ($conn->query($insertSql) === true) {
                    //echo "Método de pagamento '$method' inserido com sucesso.";
                }
            }
        }
    }
}
