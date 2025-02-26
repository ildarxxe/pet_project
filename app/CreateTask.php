<?php

namespace App;
use PDO;
use PDOException;

session_start();

class CreateTask {
    private $pdo;

    public function __construct($host = 'localhost', $dbname = 'project', $username = 'root', $password = 'admin') {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function redirect() {
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER']:'../public/main.php';
        header("Location: $redirect#tasks");
        exit();
    }

    function handleMethod() {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $this->checkFields();
        } else {
            echo 'Error: method is not post';
        }
    }

    public function checkFields() {
        $task_name = $_POST['task_name'];
        $task_desc = $_POST['task_desc'];
        $task_priority = $_POST['priority'];
        if (empty($task_name) || empty($task_desc) || empty($task_priority)) {
            echo "Все поля обязательны для заполнения.";
            exit();
        } else {
            $this->sendRequest($task_name, $task_desc, $task_priority);
        }
    }

    public function sendRequest($task_name, $task_desc, $task_priority) {
        $user_id = $_SESSION["user_id"];
        try {
            $stmt = $this->pdo->prepare("INSERT INTO tasks (task_name, task_desc, priority, user_id) VALUES (:task_name, :task_desc, :priority, :user_id)");
            $stmt->bindParam(':task_name', $task_name);
            $stmt->bindParam(':task_desc', $task_desc);
            $stmt->bindParam(':priority', $task_priority);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $this->redirect();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}








?>