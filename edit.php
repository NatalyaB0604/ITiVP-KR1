<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $task = $stmt->get_result()->fetch_assoc();
    if (!$task) {
        die("Продукт не найден!");
    }
} else {
    die("ID продукта не указан!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $expiry_date = $_POST['expiry_date'];
    $quantity = (int) $_POST['quantity'];
    $status = $_POST['status'];

    if (trim($description) === "") {
        $description = $title;
    }

    if ($status === "Закончился") {
        $quantity = 0;
    }

    if ($status === "В наличии" && $quantity <= 0) {
        $quantity = 1;
    }

    $stmt = $conn->prepare("UPDATE tasks SET title=?, description=?, expiry_date=?, quantity=?, status=? WHERE id=?");
    $stmt->bind_param("sssisi", $title, $description, $expiry_date, $quantity, $status, $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}


?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Редактирование продукта</title>
  <link href="css/edit.css" rel="stylesheet">
</head>
<body>
  <h1>Редактирование продукта</h1>
  <form method="post">
    <div>
      <label class="form-label">Название</label>
      <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($task['title']) ?>" required>
    </div>
    <div>
      <label class="form-label">Описание</label>
      <textarea name="description" class="form-control"><?= htmlspecialchars($task['description']) ?></textarea>
    </div>
    <div>
      <label class="form-label">Срок годности</label>
      <input type="date" name="expiry_date" class="form-control" value="<?= $task['expiry_date'] ?>" required>
    </div>
    <div>
      <label class="form-label">Количество</label>
      <input type="number" name="quantity" class="form-control" value="<?= $task['quantity'] ?>" min="0">
    </div>
    <div>
      <label class="form-label">Статус</label>
      <select name="status" class="form-control">
        <option value="В наличии" <?= $task['status'] == 'В наличии' ? 'selected' : '' ?>>В наличии</option>
        <option value="Закончился" <?= $task['status'] == 'Закончился' ? 'selected' : '' ?>>Закончился</option>
      </select>
    </div>
    <div class="buttons">
      <button type="submit" class="button button-add">Сохранить</button>
      <a href="index.php" class="button button-back">Назад</a>
    </div>
  </form>
</body>
</html>
