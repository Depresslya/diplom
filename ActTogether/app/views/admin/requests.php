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
        <div class="admin-requests-container">
            <h2 class="admin-requests-title">Запити на роль організатора</h2>
            <?php if (!empty($requests)): ?>
                <ul class="admin-request-list">
                    <?php foreach ($requests as $req): ?>
                        <li class="admin-request-item">
                            <strong><?= htmlspecialchars($req['name']) ?> (<?= htmlspecialchars($req['email']) ?>)</strong><br>
                            <em>Запит:</em><br>
                            <div class="admin-request-text">
                                <?= nl2br(htmlspecialchars($req['text'])) ?>
                            </div>

                            <div class="admin-request-actions">
                                <form action="index.php?page=approveRoleRequest" method="post">
                                    <input type="hidden" name="user_id" value="<?= $req['user_id'] ?>">
                                    <button type="submit" class="approve-btn">Підвищити до організатора</button>
                                </form>

                                <form action="index.php?page=deleteRoleRequest" method="post" onsubmit="return confirm('Ви впевнені, що хочете видалити цей запит?');">
                                    <input type="hidden" name="user_id" value="<?= $req['user_id'] ?>">
                                    <button type="submit" class="delete-btn">Видалити</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="no-requests-message">Немає активних запитів.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include("app/include/footer.php"); ?>
</body>
</html>