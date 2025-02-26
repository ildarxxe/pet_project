<?php

session_start();
include("./addTask.html");

$dsn = "mysql:host=localhost;dbname=project";
$username = "root";
$password = "admin";
$user_id = $_SESSION['user_id'];

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "" . $e->getMessage() . "";
}

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = $user_id");
$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="tasks">
    <div class="add_tasks bg-dark text-white">
        <span class="plus">+</span>
    </div>
    <h2 class="tasks__title text-center">Ваши задачи</h2>
    <div class="tasks__inner d-flex flex-row flex-wrap gap-3">
        <?php
        for ($i = 0; $i < count($data); $i++) {
            $row = $data[$i];
            ?>
            <div class="tasks__card card">
                <div class="card-body">
                    <div class="tasks__card--head hstack row">
                        <h3 class="tasks__card--name card-title fs-4"><?= $row["task_name"] ?></h3>
                        <form class="delete__form" action="../../app/formHandler.php" method="post">
                            <input type="submit" value="X" class="tasks__card--close bg-dark text-white" name="delete">
                            <input type="hidden" name="id" value="<?= $row['id'] ?>">
                            <input type="hidden" name="delete_task">
                            <input type="hidden" name="method" value="DELETE">
                        </form>
                    </div>
                    <p class="tasks__card--desc card-text fs-5"><?= $row["task_desc"] ?></p>
                    <strong class="tasks__card--priority"><?= $row["priority"] ?></strong>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>