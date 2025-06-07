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
        <div class="my-initiative-view">
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

            <form action="index.php?page=updateInitiativeStatus" method="post" style="margin-bottom: 20px;">
                <input type="hidden" name="initiative_id" value="<?= $initiative['id'] ?>">

                <label for="status">Змінити статус:</label>
                <select name="status" id="status">
                    <option value="0" <?= $initiative['status'] == 0 ? 'selected' : '' ?>>У підготовці</option>
                    <option value="1" <?= $initiative['status'] == 1 ? 'selected' : '' ?>>Активна</option>
                    <option value="2" <?= $initiative['status'] == 2 ? 'selected' : '' ?>>Завершена</option>
                </select>

                <button type="submit">Оновити</button>
            </form>

            <hr>

            <h3>Підтверджені учасники</h3>

            <?php if (!empty($approved)): ?>
                <ul>
                    <?php foreach ($approved as $v): ?>
                        <li style="margin-bottom: 10px;">
                            <a href="index.php?page=profile&id=<?= $v['id'] ?>">
                                <?= htmlspecialchars($v['name']) ?>
                            </a> (<?= htmlspecialchars($v['email']) ?>)

                            <?php if (!empty($v['staff_role'])): ?>
                                — <strong><?= htmlspecialchars($v['staff_role']) ?></strong>
                            <?php endif; ?>

                            <form action="index.php?page=assignStaffRole" method="post" style="display:inline; margin-left: 10px;">
                                <input type="hidden" name="initiative_id" value="<?= $initiative['id'] ?>">
                                <input type="hidden" name="user_id" value="<?= $v['id'] ?>">
                                <input type="text" name="role" placeholder="Нова роль" value="<?= htmlspecialchars($v['staff_role'] ?? '') ?>" required>
                                <button type="submit">Зберегти роль</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Ще немає підтверджених учасників.</p>
            <?php endif; ?>


            <h3>Запити на участь</h3>

            <?php if (!empty($requests)): ?>
                <ul>
                    <?php foreach ($requests as $user): ?>
                        <li>
                            <a href="index.php?page=profile&id=<?= $user['id'] ?>">
                                <?= htmlspecialchars($user['name']) ?>
                            </a>
                            (<?= htmlspecialchars($user['email']) ?>)

                            <form action="index.php?page=initiativeApprove" method="post" style="display:inline;">
                                <input type="hidden" name="initiative_id" value="<?= $initiative['id'] ?>">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit">Підтвердити</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Немає нових запитів.</p>
            <?php endif; ?>

            <hr>

            <p><a href="index.php?page=myInitiatives">&larr; Назад до моїх ініціатив</a></p>
        </div>
    </div>

    <?php include("app/include/footer.php"); ?>
</body>
</html>