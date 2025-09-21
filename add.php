<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $title = htmlspecialchars($_POST['title']);
  $description = trim($_POST['description']);
  $expiry_date = $_POST['expiry_date'];
  $quantity = (int) $_POST['quantity'];

  if ($description === "") {
      $description = $title;
  }

  $stmt = $conn->prepare("INSERT INTO tasks (title, description, expiry_date, quantity) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("sssi", $title, $description, $expiry_date, $quantity);
  $stmt->execute();
  header("Location: index.php");
  exit;
}

?>
<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <title>Добавление продукта</title>
  <link href="css/add.css" rel="stylesheet">
</head>

<body>
  <h1>Добавление продукта</h1>
  <form method="post">
    <div>
      <label class="form-label">Название</label>
      <input type="text" name="title" class="form-control" required>
    </div>
    <div>
      <label class="form-label">Описание</label>
      <textarea name="description" class="form-control"></textarea>
    </div>
    <div>
      <label class="form-label">Срок годности</label>
      <input type="date" name="expiry_date" class="form-control" required>
    </div>
    <div>
      <label class="form-label">Количество</label>
      <input type="number" name="quantity" class="form-control" value="1" min="0">
    </div>
    <div class="buttons">
      <button type="submit" class="button button-add">Добавить</button>
      <a href="index.php" class="button button-back">Назад</a>
    </div>
  </form>
</body>

</html>
