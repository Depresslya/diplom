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
        <div class="initiative-list-container">
            <h2 class="initiative-list-title">Пошук ініціатив</h2>

            <form action="index.php" method="get" class="initiative-search-form">
                <input type="hidden" name="page" value="initiativeList">

                <input type="text" name="search" id="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">

                <select name="category" id="category">
                    <option value="">-- Всі категорії --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit" class="search-btn">Знайти</button>
            </form>

            <h2 class="initiative-list-subtitle">Ініціативи</h2>

            <?php foreach ($initiatives as $initiative): ?>
                <a href="index.php?page=initiativeView&id=<?= $initiative['id'] ?>" class="initiative-card-link">
                    <div class="initiative-card">
                        <?php if (!empty($initiative['image'])): ?>
                            <img src="<?= htmlspecialchars($initiative['image']) ?>" alt="Зображення ініціативи" class="initiative-image">
                        <?php endif; ?>

                        <div class="initiative-content">
                            <h3><?= htmlspecialchars($initiative['title']) ?></h3>
                            <p><strong>Категорія:</strong> <?= htmlspecialchars($initiative['category_title']) ?></p>
                            <p><strong>Локація:</strong> <?= htmlspecialchars($initiative['location']) ?></p>
                            <p><strong>Старт:</strong> <?= date('d.m.Y H:i', strtotime($initiative['start_time'])) ?></p>
                            <p><strong>Фініш:</strong> <?= date('d.m.Y H:i', strtotime($initiative['end_time'])) ?></p>
                            <p><?= nl2br(htmlspecialchars(mb_substr($initiative['description'], 0, 150))) ?>...</p>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include("app/include/footer.php"); ?>
</body>
</html>