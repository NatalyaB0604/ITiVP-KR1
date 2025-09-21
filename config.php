<?php
$conn = new mysqli("localhost", "root", "68168489", "task_manager");
if($conn->connect_error){
    die("Ошибка: " . $conn->connect_error);
}
?>
