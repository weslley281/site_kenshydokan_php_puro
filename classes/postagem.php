<?php
include_once("../classes/connection.php");


class Posting
{
    private $connection;

    public function __construct()
    {
        $dbConnection = new Conection();
        $this->connection = $dbConnection->connect();
    }

    public function create_posts(int $user_id, string $title, string $content, string $status, string $date): bool
    {
        $insert = $this->connection->prepare("INSERT INTO posts (user_id, title, content, status, date) VALUES (?, ?, ?, ?, ?)");
        $insert->bind_param("issss", $user_id, $title, $content, $status, $date);
        $result = $insert->execute();
        $insert->close();

        return $result;
    }

    public function edit_posts(int $post_id, string $title, string $content, string $status, string $date): bool
    {
        $update = $this->connection->prepare("UPDATE posts SET title = ?, content = ?, status = ?, date = ? WHERE post_id = ?");
        $update->bind_param("ssssi", $title, $content, $status, $date, $post_id);
        $result = $update->execute();
        $update->close();

        return $result;
    }

    public function edit_post_status(int $post_id, string $status): bool
    {
        $update = $this->connection->prepare("UPDATE posts SET status = ? WHERE post_id = ?");
        $update->bind_param("si", $status, $post_id);
        $result = $update->execute();
        $update->close();

        return $result;
    }

    public function delete_posts(int $post_id): bool
    {
        $delete = $this->connection->prepare("DELETE FROM posts WHERE post_id = ?");
        $delete->bind_param("i", $post_id);
        $result = $delete->execute();
        $delete->close();

        return $result;
    }

    public function register_post_images(string $name, string $path): bool
    {
        $insert = $this->connection->prepare("INSERT INTO images (name, path) VALUES (?, ?)");
        $insert->bind_param("ss", $name, $path);
        $result = $insert->execute();
        $insert->close();

        return $result;
    }
}
?>
