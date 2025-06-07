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
        <div class="organizer-request-container">
            <h2 class="organizer-request-title">Запит на роль організатора</h2>

            <?php if (!empty($existing)): ?>
                <p class="organizer-request-warning">
                    <strong>Ви вже подали запит на роль організатора.</strong>
                </p>
                <p>Ваш запит:</p>
                <div class="organizer-request-box">
                    <?= nl2br(htmlspecialchars($existing['text'])) ?>
                </div>
            <?php else: ?>
                <p>Будь ласка, опишіть, чому ви хочете отримати роль організатора:</p>

                <form action="index.php?page=submitRoleRequest" method="post" class="organizer-request-form">
                    <textarea name="text" rows="5" required></textarea>
                    <button type="submit">Надіслати запит</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <?php include("app/include/footer.php"); ?>
</body>
</html>