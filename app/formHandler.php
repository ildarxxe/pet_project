<?php

require_once('../vendor/autoload.php');

if (isset($_POST['create_task'])) {
    $createTask = new \App\CreateTask();
    $createTask->handleMethod();
}
if (isset($_POST['auth_hidden']) || isset($_POST['reg_hidden'])) {
    $userhandler = new \App\UserHandler();
    $userhandler->handleMethod();
}
if (isset($_POST['delete_task'])) {
    $deleteTask = new \App\DeleteTask();
    $deleteTask->handleMethod();
}
?>
