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
        <div class="initiative-view">
            <h2><?= htmlspecialchars($initiative['title']) ?></h2>

            <?php if (!empty($initiative['image'])): ?>
                <img src="<?= htmlspecialchars($initiative['image']) ?>" alt="Ініціатива" width="300">
            <?php endif; ?>

            <?php if (!empty($initiative['place_image'])): ?>
                <p><strong>Зображення локації:</strong></p>
                <img src="<?= htmlspecialchars($initiative['place_image']) ?>" alt="Локація" width="300">
            <?php endif; ?>

            <p><strong>Категорія:</strong> <?= htmlspecialchars($initiative['category_title']) ?></p>
            <p><strong>Опис:</strong><br><?= nl2br(htmlspecialchars($initiative['description'])) ?></p>
            <p><strong>Локація:</strong> <?= htmlspecialchars($initiative['location']) ?></p>
            <p><strong>Період:</strong> з <?= date('d.m.Y H:i', strtotime($initiative['start_time'])) ?> до <?= date('d.m.Y H:i', strtotime($initiative['end_time'])) ?></p>
            <p><strong>Організатор:</strong>
                <a href="index.php?page=profile&id=<?= $initiative['organizer_id'] ?>">
                    <?= htmlspecialchars($initiative['organizer_name']) ?>
                </a>
            </p>

            <p><strong>Статус ініціативи:</strong>
                <?php
                switch ((int)$initiative['status']) {
                    case 0: echo 'У підготовці'; break;
                    case 1: echo 'Активна'; break;
                    case 2: echo 'Завершена'; break;
                    default: echo 'Невідомо';
                }
                ?>
            </p>

            <hr>

            <h3>Учасники ініціативи:</h3>

            <?php if (!empty($volunteers)): ?>
                <ul>
                    <?php foreach ($volunteers as $v): ?>
                        <li style="margin-bottom: 10px;">
                            <?php if (!empty($v['image'])): ?>
                                <img src="<?= htmlspecialchars($v['image']) ?>" alt="Avatar" width="40" style="vertical-align: middle; border-radius: 50%; margin-right: 10px;">
                            <?php endif; ?>

                            <a href="index.php?page=profile&id=<?= $v['id'] ?>">
                                <?= htmlspecialchars($v['name']) ?> (<?= htmlspecialchars($v['login']) ?>)
                            </a>

                            <?php if (!empty($v['staff_role'])): ?>
                                — <em><?= htmlspecialchars($v['staff_role']) ?></em>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Ще немає підтверджених учасників.</p>
            <?php endif; ?>

            <hr>

            <h3>Коментарі</h3>

            <?php if (!empty($comments)): ?>
                <?php foreach ($comments as $comment): ?>
                    <div style="border-bottom: 1px solid #ccc; margin-bottom: 10px;">
                        <strong>
                            <a href="index.php?page=profile&id=<?= $comment['user_id'] ?>">
                                <?= htmlspecialchars($comment['name']) ?>
                            </a>
                        </strong>
                        <em style="color: #666; font-size: 0.9em;">(<?= date('d.m.Y H:i', strtotime($comment['time'])) ?>)</em><br>
                        <?= nl2br(htmlspecialchars($comment['text'])) ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Ще немає коментарів.</p>
            <?php endif; ?>


            <?php if (isset($_SESSION['user'])): ?>
                <form action="index.php?page=initiativeComment&id=<?= $initiative['id'] ?>" method="post">
                    <label for="text">Ваш коментар:</label><br>
                    <textarea name="text" id="text" rows="4" required></textarea><br>
                    <button type="submit">Надіслати</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php include("app/include/footer.php"); ?>
</body>
</html>