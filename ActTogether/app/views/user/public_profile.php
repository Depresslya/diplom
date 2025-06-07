<!DOCTYPE html>
<html lang="uk">
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
        <div class="public-profile">
            <h2 class="public-profile-title">Профіль користувача</h2>

            <div class="public-profile-info">
                <div class="profile-flex">
                    <?php if (!empty($_SESSION['user']['image'])): ?>
                        <img class="profile-avatar" src="<?= htmlspecialchars($_SESSION['user']['image']) ?>" alt="Avatar">
                    <?php else: ?>
                        <img class="profile-avatar" src="./assets/img/avatar/avatar.png" alt="Avatar">
                    <?php endif; ?>

                    <div class="profile-info">
                        <p><strong>Ім’я:</strong> <?= htmlspecialchars($_SESSION['user']['name']) ?></p>
                        <p><strong>Логін:</strong> <?= htmlspecialchars($_SESSION['user']['login']) ?></p>
                        <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['user']['email']) ?></p>
                        <p><strong>Роль:</strong>
                            <?php
                            switch ((int)$_SESSION['user']['role']) {
                                case 0: echo 'Волонтер'; break;
                                case 1: echo 'Організатор'; break;
                                case 2: echo 'Адміністратор'; break;
                                default: echo 'Невідома роль';
                            }
                            ?>
                        </p>
                    </div>
                </div>

                <hr class="public-profile-separator">

                <?php if (isset($_SESSION['user']) && $_SESSION['user']['id'] !== $user['id']): ?>
                    <p>
                        <a href="index.php?page=privateChat&id=<?= $user['id'] ?>">
                            <button class="message-btn">Написати повідомлення</button>
                        </a>
                    </p>
                <?php endif; ?>
            </div>

            <hr class="public-profile-separator">

            <h3 class="public-profile-subtitle">Ініціативи користувача</h3>

            <?php if (!empty($initiatives)): ?>
                <ul class="initiative-list">
                    <?php foreach ($initiatives as $i): ?>
                        <li>
                            <a href="index.php?page=initiativeView&id=<?= $i['id'] ?>">
                                <?= htmlspecialchars($i['title']) ?>
                            </a>
                            — <em><?= htmlspecialchars($i['role_label']) ?></em>,
                            <?= date('d.m.Y', strtotime($i['start_time'])) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Користувач ще не брав участі в ініціативах.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include("app/include/footer.php"); ?>
</body>
</html>
