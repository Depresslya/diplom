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
        <div class="initiative-edit-container">
            <h2 class="initiative-edit-title">Редагування ініціативи</h2>

            <form action="index.php?page=initiativeEdit&id=<?= $initiative['id'] ?>" method="post" enctype="multipart/form-data" class="initiative-edit-form">

                <label for="title">Назва ініціативи:</label>
                <input type="text" name="title" id="title" required value="<?= htmlspecialchars($initiative['title']) ?>">

                <label for="description">Опис:</label>
                <textarea name="description" id="description" rows="5" required><?= htmlspecialchars($initiative['description']) ?></textarea>

                <label for="location">Локація:</label>
                <input type="text" name="location" id="location" required value="<?= htmlspecialchars($initiative['location']) ?>">

                <label for="category_id">Категорія:</label>
                <select name="category_id" id="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= $category['id'] == $initiative['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="start_time">Дата та час початку:</label>
                <input type="datetime-local" name="start_time" id="start_time"
                    value="<?= date('Y-m-d\TH:i', strtotime($initiative['start_time'])) ?>" required>

                <label for="end_time">Дата та час завершення:</label>
                <input type="datetime-local" name="end_time" id="end_time"
                    value="<?= date('Y-m-d\TH:i', strtotime($initiative['end_time'])) ?>" required>

                <?php if (!empty($initiative['image'])): ?>
                    <div class="image-preview">
                        <p>Поточне головне зображення:</p>
                        <img src="<?= htmlspecialchars($initiative['image']) ?>" alt="Image" width="150">
                    </div>
                <?php endif; ?>

                <label for="image">Нове головне зображення:</label>
                <input type="file" name="image" id="image" accept="image/*">

                <?php if (!empty($initiative['place_image'])): ?>
                    <div class="image-preview">
                        <p>Поточне зображення локації:</p>
                        <img src="<?= htmlspecialchars($initiative['place_image']) ?>" alt="Place" width="150">
                    </div>
                <?php endif; ?>

                <label for="place_image">Нове зображення локації:</label>
                <input type="file" name="place_image" id="place_image" accept="image/*">

                <button type="submit">Зберегти зміни</button>
            </form>
        </div>
    </div>

    <?php include("app/include/footer.php"); ?>
</body>
</html>