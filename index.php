<?php
include 'config.php';

$conn->query("UPDATE tasks
              SET status='Испорчен'
              WHERE expiry_date < CURDATE()");

$result = $conn->query("SELECT * FROM tasks ORDER BY created_at DESC");

$edit_id = isset($_GET['edit_status']) ? (int) $_GET['edit_status'] : null;
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Учёт продуктов в холодильнике</title>
    <link href="css/index.css" rel="stylesheet">
</head>

<body>
    <h1>Учёт продуктов в холодильнике</h1>
    <a href="add.php" class="button-add">Добавить продукт</a>

    <table class="table">
        <thead>
            <tr>
                <th>Название</th>
                <th>Описание</th>
                <th>Статус</th>
                <th>Дата добавления</th>
                <th>Срок годности</th>
                <th>Количество</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td class="status-cell">
                        <?php if (in_array($row['status'], ['В наличии', 'Закончился'])): ?>
                            <?php if ($edit_id === (int) $row['id']): ?>
                                <form method="post" action="update_status.php" class="status-form">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <select name="status">
                                        <option value="В наличии" <?= $row['status'] == 'В наличии' ? 'selected' : '' ?>>В наличии
                                        </option>
                                        <option value="Закончился" <?= $row['status'] == 'Закончился' ? 'selected' : '' ?>>Закончился
                                        </option>
                                    </select>
                                    <div>
                                        <button type="submit">Ок</button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <?= $row['status'] ?>
                                <a href="index.php?edit_status=<?= $row['id'] ?>" class="status-edit-btn">✎</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <?= $row['status'] ?>
                        <?php endif; ?>
                    </td>
                    <td><?= $row['created_at'] ?></td>
                    <td><?= $row['expiry_date'] ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td>
                        <div>
                            <a href="edit.php?id=<?= $row['id'] ?>" class="button button-edit">Редактировать</a>
                            <a href="delete.php?id=<?= $row['id'] ?>" class="button button-delete"
                                onclick="return confirm('Удалить продукт?')">Удалить</a>
                        </div>

                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>
