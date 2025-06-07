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
        <div class="messages-dialogs-container">
            <h2 class="dialogs-title">Ваші діалоги</h2>

            <h3 class="dialogs-subtitle">👤 Приватні повідомлення</h3>
            <?php if (!empty($privateDialogs)): ?>
                <ul class="dialogs-list">
                    <?php foreach ($privateDialogs as $user): ?>
                        <li class="dialog-item">
                            <a href="index.php?page=privateChat&id=<?= $user['id'] ?>">
                                <?= htmlspecialchars($user['name']) ?>
                                <span id="unread-private-<?= $user['id'] ?>" class="unread-count"></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="no-dialogs">Немає особистих повідомлень.</p>
            <?php endif; ?>

            <h3 class="dialogs-subtitle">👥 Групові чати ініціатив</h3>
            <?php if (!empty($groupDialogs)): ?>
                <ul class="dialogs-list">
                    <?php foreach ($groupDialogs as $initiative): ?>
                        <li class="dialog-item">
                            <a href="index.php?page=groupChat&id=<?= $initiative['id'] ?>">
                                <?= htmlspecialchars($initiative['title']) ?>
                                <span id="unread-group-<?= $initiative['id'] ?>" class="unread-count"></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="no-dialogs">Ви не берете участь у жодній ініціативі.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include("app/include/footer.php"); ?>

    <script>
        function updateUnreadCounts() {
            fetch('index.php?page=unreadPrivateList')
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        const span = document.getElementById(`unread-private-${item.id}`);
                        if (span) {
                            span.textContent = item.count > 0 ? `(${item.count})` : '';
                        }
                    });
                });

            fetch('index.php?page=unreadGroupList')
                .then(res => res.json())
                .then(data => {
                    data.forEach(item => {
                        const span = document.getElementById(`unread-group-${item.id}`);
                        if (span) {
                            span.textContent = item.count > 0 ? `(${item.count})` : '';
                        }
                    });
                });
        }

        setInterval(updateUnreadCounts, 10000);
        updateUnreadCounts();
    </script>
</body>
</html>