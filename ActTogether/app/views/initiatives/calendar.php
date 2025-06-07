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
        <div class="upcoming-events-container">
            <h2 class="upcoming-events-title">Майбутні події</h2>
            <?php if (!empty($events)): ?>
                <ul class="event-list">
                    <?php foreach ($events as $event): ?>
                        <li class="event-item">
                            <strong class="event-title"><?= htmlspecialchars($event['title']) ?></strong><br>
                            📍 <?= htmlspecialchars($event['location']) ?><br>
                            🕒 З <?= date('d.m.Y H:i', strtotime($event['start_time'])) ?>
                            до <?= date('d.m.Y H:i', strtotime($event['end_time'])) ?><br>
                            🧾 <em>Ваша роль: <?= htmlspecialchars($event['user_role']) ?></em><br>
                            🔗 <a href="index.php?page=initiativeView&id=<?= $event['id'] ?>">Переглянути ініціативу</a> |
                            💬 <a href="index.php?page=groupChat&id=<?= $event['id'] ?>">Перейти до чату</a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="no-events-message">Немає запланованих подій.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include("app/include/footer.php"); ?>    
</body>
</html>