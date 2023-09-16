<?php
class Connection
{
    private $server = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "u696382984_kenshydokan";

    public function connect()
    {
        $connection = mysqli_connect($this->server, $this->username, $this->password, $this->db);

        if (mysqli_connect_errno()) {
            die("Failed to connect to the database: " . mysqli_connect_error());
        }

        mysqli_set_charset($connection, "utf8");

        return $connection;
    }
}

