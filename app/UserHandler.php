<?php

namespace App;
use PDO;
use PDOException;

session_start();

class UserHandler {
    private $pdo;

    public function __construct($host = 'localhost', $dbname = 'project', $username = 'root', $password = 'admin') {
        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function handleMethod() {
        if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['auth_hidden'])) {
            $this->auth();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reg_hidden'])) {
            $this->checkFields();
        }
    }

    public function auth() {
        $auth_name = $_POST["auth_name"];
        $auth_pass = $_POST["auth_password"];

        $stmt = $this->pdo->prepare("SELECT id, password FROM auth_table WHERE name = :name");
        $stmt->bindParam(':name', $auth_name);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($auth_pass, $row["password"])) {
                $_SESSION["user_id"] = $row["id"];
                header("Location: ../public/main.php");
            } else {
                $_SESSION['auth_error'] = "Неверный пароль!";
                header("Location: ../public/main.php#auth");
            }
        } else {
            $_SESSION['user_error'] = "Пользователь не найден!";
            header("Location: ../public/main.php#auth");
        }

        exit();
    }

    public function checkFields() {
        $reg_name = $_POST['reg_name'];
        $reg_email = $_POST['reg_email'];
        $reg_phone = $_POST['reg_phone'];
        $reg_password = password_hash($_POST['reg_password'], PASSWORD_DEFAULT);

        if (empty($reg_name) || empty($reg_email) || empty($reg_phone) || empty($reg_password)) {
            echo "Все поля обязательны для заполнения.";
            exit();
        } else {
            $this->reg($reg_name, $reg_email, $reg_phone, $reg_password);
        }
    }

    public function redirectInReg() {
        header("Location: ../public/main.php#reg");
        exit();
    }

    public function checkFieldsInDB($count_name, $count_email, $count_phone) {
        if ($count_name > 0) {
            $_SESSION['reg_error'] = 'Имя пользователя занято!';
            $this->redirectInReg();
        } elseif ($count_email > 0) {
            $_SESSION['reg_error'] = 'Email пользователя занят!';
            $this->redirectInReg();
        } elseif ($count_phone > 0) {
            $_SESSION['reg_error'] = 'Номер телефона пользователя занят!';
            $this->redirectInReg();
        }
    }

    public function reg($reg_name, $reg_email, $reg_phone, $reg_password)
    {
        try {
            $stmt_name = $this->pdo->prepare("SELECT COUNT(*) FROM reg_table WHERE name = :name");
            $stmt_name->bindParam(':name', $reg_name);
            $stmt_name->execute();
            $count_name = $stmt_name->fetchColumn();

            $stmt_email = $this->pdo->prepare("SELECT COUNT(*) FROM reg_table WHERE email = :email");
            $stmt_email->execute(['email' => $reg_email]);
            $count_email = $stmt_email->fetchColumn();

            $stmt_phone = $this->pdo->prepare("SELECT COUNT(*) FROM reg_table WHERE phone = :phone");
            $stmt_phone->execute(['phone' => $reg_phone]);
            $count_phone = $stmt_phone->fetchColumn();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        $this->checkFieldsInDB($count_name, $count_email, $count_phone);

        $stmt = $this->pdo->prepare("INSERT INTO reg_table (`name`, email, phone, `password`) VALUES (:name, :email, :phone, :password)");
        $stmt->bindParam(':name', $reg_name);
        $stmt->bindParam(':email', $reg_email);
        $stmt->bindParam(':phone', $reg_phone);
        $stmt->bindParam(':password', $reg_password);
        $stmt->execute();

        header("Location: ../public/main.php#auth");
        exit();
    }
}

?>