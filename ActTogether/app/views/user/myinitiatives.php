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
        <div class="my-initiatives-container">
            <h2 class="my-initiatives-title">Мої ініціативи</h2>

            <p class="add-initiative-link">
                <a href="index.php?page=initiativeCreate">+ Додати ініціативу</a>
            </p>

            <?php if (!empty($initiatives)): ?>
                <ul class="initiative-list">
                    <?php foreach ($initiatives as $i): ?>
                        <li class="initiative-item">
                            <strong class="initiative-title"><?= htmlspecialchars($i['title']) ?></strong><br>
                            <em class="initiative-category">Категорія: <?= htmlspecialchars($i['category_title']) ?></em><br>
                            <div class="initiative-actions">
                                <a href="index.php?page=myView&id=<?= $i['id'] ?>">Переглянути</a>
                                |
                                <a href="index.php?page=initiativeEdit&id=<?= $i['id'] ?>">Редагувати</a>
                                |
                                <a href="index.php?page=initiativeDelete&id=<?= $i['id'] ?>" onclick="return confirm('Ви впевнені, що хочете видалити цю ініціативу?')">Видалити</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="no-initiatives-message">У вас ще немає жодної ініціативи.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include("app/include/footer.php"); ?>
</body>
</html>