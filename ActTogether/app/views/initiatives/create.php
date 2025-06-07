<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Головна | ActTogether</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/styles.css">
</head>
<body>
    <?php include("app/include/header.php"); ?>

    <div class="container">
        <div class="initiative-create-container">
            <h2 class="initiative-create-title">Створення ініціативи</h2>

            <form action="index.php?page=initiativeCreate" method="post" enctype="multipart/form-data" class="initiative-create-form">

                <label for="title">Назва ініціативи:</label>
                <input type="text" name="title" id="title" required>

                <label for="description">Опис:</label>
                <textarea name="description" id="description" rows="5" required></textarea>

                <label for="location">Локація (місто або "онлайн"):</label>
                <input type="text" name="location" id="location" required>

                <label for="category_id">Категорія:</label>
                <select name="category_id" id="category_id" required>
                    <option value="">-- Виберіть категорію --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['title']) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="start_time">Дата та час початку:</label>
                <input type="datetime-local" name="start_time" id="start_time" required>

                <label for="end_time">Дата та час завершення:</label>
                <input type="datetime-local" name="end_time" id="end_time" required>

                <label for="image">Головне зображення (jpg, png):</label>
                <input type="file" name="image" id="image" accept="image/*">

                <label for="place_image">Зображення локації (jpg, png):</label>
                <input type="file" name="place_image" id="place_image" accept="image/*">

                <button type="submit">Створити ініціативу</button>
            </form>
        </div>
    </div>

    <?php include("app/include/footer.php"); ?>
</body>
</html>