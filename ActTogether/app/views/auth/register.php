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
        <div class="register-container">
            <h2 class="register-title">Реєстрація користувача</h2>

            <form action="index.php?page=register" method="post" class="register-form">

                <?php if (!empty($error)): ?>
                    <div class="register-error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <label for="login">Логін:</label>
                <input type="text" name="login" id="login" required value="<?= htmlspecialchars($data['login'] ?? '') ?>">

                <label for="email">Електронна пошта:</label>
                <input type="email" name="email" id="email" required value="<?= htmlspecialchars($data['email'] ?? '') ?>">

                <label for="name">Ім’я та прізвище:</label>
                <input type="text" name="name" id="name" required value="<?= htmlspecialchars($data['name'] ?? '') ?>">

                <label for="password">Пароль:</label>
                <input type="password" name="password" id="password" required>

                <button type="submit" class="register-button">Зареєструватися</button>
            </form>
        </div>
    </div>

    <?php include("app/include/footer.php"); ?>
</body>
</html>