<?php
include 'config.php';

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = (int) $_POST['id'];
    $status = $_POST['status'];

    if ($status === "Закончился") {
        $stmt = $conn->prepare("UPDATE tasks SET status=?, quantity=0 WHERE id=?");
        $stmt->bind_param("si", $status, $id);
    }
    elseif ($status === "В наличии") {
        $stmt = $conn->prepare("UPDATE tasks SET status=?, quantity=IF(quantity=0, 1, quantity) WHERE id=?");
        $stmt->bind_param("si", $status, $id);
    }
    else {
        $stmt = $conn->prepare("UPDATE tasks SET status=? WHERE id=?");
        $stmt->bind_param("si", $status, $id);
    }

    $stmt->execute();
    header("Location: index.php");
    exit;
} else {
    header("Location: index.php");
    exit;
}
