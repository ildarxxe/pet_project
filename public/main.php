<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Проект</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../app/style.css">
</head>

<body>
    <header class="header bg-dark text-white">
        <ul class="header__nav nav">
            <?php
            if (isset($_SESSION["user_id"])) {
                echo '<li class="nav-item"><a href="../app/logout.php" class="nav-link header__link logout">Выйти</a></li><li class="nav-item"><a href="#tasks" class="nav-link header__link">Мои задачи</a></li>';
            } else {
                echo '<li class="nav-item"><a href="#auth" class="nav-link header__link">Войти</a></li>
            <li class="nav-item"><a href="#reg" class="nav-link header__link">Регистрация</a></li>';
            }
            ?>
            <?php
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                try {
                    $pdo = new PDO('mysql:host=localhost;dbname=project', 'root', 'admin');
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
                $sql = "SELECT name FROM auth_table WHERE id = :user_id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(['user_id' => $user_id]);
                $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $username = $user[0]['name'];

                echo '<p class="profile">Профиль: ' . $username . '</p>';
            }
            ?>
            <?php 
            if (isset($_SESSION['reg_error'])) {
                echo '<p style="color: red;">' . $_SESSION['reg_error'] . '</p>';
                unset($_SESSION['reg_error']);
            }
            ?>
            <?php 
            if (isset($_SESSION['auth_error'])) {
                echo '<p style="color: red;">' . $_SESSION['auth_error'] . '</p>';
                unset($_SESSION['auth_error']);
            }
            ?>
            <?php 
            if (isset($_SESSION['user_error'])) {
                echo '<p style="color: red;">' . $_SESSION['user_error'] . '</p>';
                unset($_SESSION['user_error']);
            }
            ?>
        </ul>
    </header>
    <div id="root">
    </div>
    <footer class="footer bg-dark text-white">
        <ul class="footer__nav nav justify-content-center"><li class="nav-item footer__content">2025 copyright</li></ul>
    </footer>
</body>
<script src="../app/script.js"></script>

</html>