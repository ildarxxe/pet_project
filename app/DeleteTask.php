<?php

namespace App;

use PDO;
use PDOException;

class DeleteTask {
    private $pdo;

    public function __construct($host = 'localhost', $dbname = 'project', $user = 'root', $password = 'admin') {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function handleMethod() {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $this->handleRequest();
        } else {
            echo 'Error: method is not post';
        }
    }

    public function handleRequest() {
        if (isset($_POST["method"]) && $_POST["method"] === "DELETE") {
            $this->sendRequest();
        } else {
            echo 'Error';
        }
    }

    public function sendRequest() {
        if (!isset($_POST["id"])) {
            echo 'Error: id not set';
        }
        $id = $_POST["id"];
        try {
            $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE `tasks`.`id` = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $this->redirect();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function redirect() {
        $redirect = isset($_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']:'../public/main.php';
        header("Location: $redirect#tasks");
        exit();
    }
}
?>