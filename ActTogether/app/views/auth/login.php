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
        <div class="login-container">
            <h2 class="login-title">Вхід до системи</h2>

            <form action="index.php?page=login" method="post" class="login-form">

                <?php if (!empty($error)): ?>
                    <div class="login-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <label for="login">Логін:</label>
                <input type="text" name="login" id="login" required value="<?= htmlspecialchars($data['login'] ?? '') ?>">

                <label for="password">Пароль:</label>
                <input type="password" name="password" id="password" required>

                <button type="submit" class="login-button">Увійти</button>
            </form>
        </div>
    </div>

    <?php include("app/include/footer.php"); ?>
</body>
</html>