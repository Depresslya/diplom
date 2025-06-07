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
    
    <div class="container home-container">
        <div class="home-header">
            <?php if (!isset($_SESSION['user'])): ?>
                <h2 class="home-title">Ласкаво просимо до <span class="highlight">ActTogether</span>!</h2>
                <p class="home-description">Об'єднуймося для створення добрих справ. Зареєструйтесь або увійдіть, щоб приєднатися до волонтерських ініціатив.</p>
                <div class="home-actions">
                    <a href="index.php?page=login"><button class="home-btn">Увійти</button></a>
                    <a href="index.php?page=register"><button class="home-btn">Реєстрація</button></a>
                </div>
            <?php else: ?>
                <h2 class="home-title">Привіт, <?= htmlspecialchars($_SESSION['user']['name']) ?>!</h2>
                <p class="home-description">Вас очікують нові події, ініціативи та спілкування з іншими волонтерами.</p>
                <a href="index.php?page=initiativeList"><button class="home-btn">Перейти до ініціатив</button></a>
            <?php endif; ?>
        </div>

        <section class="home-section quote-section">
            <blockquote class="volunteer-quote" id="quote-block">"Допомагаючи іншим, ми допомагаємо собі."</blockquote>
        </section>

        <div class="home-image">
            <img src="./assets/img/index1.png" alt="">
        </div>

        <section class="home-section">
            <h3 class="home-section-title">Із останніх новин:</h3>
            <p class="home-text">Нещодавно ініціатива ActTogether провела яскраву акцію в партнерстві з McDonald's — у комплекті з кожним Happy Meal діти отримували колекційні іграшки волонтерів. Метою цієї ініціативи було познайомити молодше покоління з важливою темою волонтерства та допомоги іншим.</p>
            <p class="home-text">Кожна іграшка представляла волонтера з коробкою "Гуманітарна допомога" та символікою ActTogether. Такі фігурки стали не лише веселими подарунками, а й чудовим способом розповісти дітям про те, як важливо бути добрими, небайдужими й підтримувати тих, хто цього потребує.</p>
            <p class="home-text">Це була не просто розвага — це був крок до формування культури взаємопідтримки ще з дитинства.</p>
        </section>

        <section class="home-section">
            <h3 class="home-section-title">Найближчі ініціативи</h3>
            <?php if (!empty($latestInitiatives)): ?>
                <ul class="home-initiative-list">
                    <?php foreach ($latestInitiatives as $i): ?>
                        <li class="home-initiative-item">
                            <a href="index.php?page=initiativeView&id=<?= $i['id'] ?>" class="home-initiative-title">
                                <?= htmlspecialchars($i['title']) ?>
                            </a><br>
                            <span class="home-initiative-info">📍 <?= htmlspecialchars($i['location']) ?></span><br>
                            <span class="home-initiative-info">🗓 <?= date('d.m.Y H:i', strtotime($i['start_time'])) ?></span><br>
                            <span class="home-initiative-info">🏷 <?= htmlspecialchars($i['category_title']) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p class="home-text">Наразі немає майбутніх ініціатив.</p>
            <?php endif; ?>
        </section>

        <div class="home-image">
            <img src="./assets/img/index2.jpg" alt="">
        </div>

        <section class="home-section">
            <h3 class="home-section-title">Чому варто приєднатися?</h3>
            <ul class="home-benefits">
                <li>✔️ Проста та зручна система організації ініціатив</li>
                <li>✔️ Приватні та групові чати</li>
                <li>✔️ Сповіщення про нові події</li>
                <li>✔️ Особистий календар</li>
            </ul>
        </section>

        <section class="home-section success-stories">
            <h3 class="home-section-title">Історії успіху наших волонтерів</h3>
            <div class="stories-grid">
                <div class="story-card">
                    <p class="story-text">"Я ніколи не думала, що зможу змінити щось у своєму місті. Завдяки ActTogether ми зібрали команду та провели прибирання парку. Тепер це наше улюблене місце."</p>
                    <p class="story-author">– Олена, Київ</p>
                </div>
                <div class="story-card">
                    <p class="story-text">"Після початку війни я відчував себе безсилим. Через платформу я приєднався до ініціативи з гуманітарної допомоги, і це дало мені відчуття важливості."</p>
                    <p class="story-author">– Сергій, Харків</p>
                </div>
                <div class="story-card">
                    <p class="story-text">"Завдяки ActTogether ми організували благодійний концерт, зібрали кошти для шпиталю та познайомились з дивовижними людьми."</p>
                    <p class="story-author">– Марина, Львів</p>
                </div>
            </div>
        </section>
    </div>

    <?php include("app/include/footer.php"); ?>

    <script>
        const quotes = [
            '"Допомагаючи іншим, ми допомагаємо собі."',
            '"Ніхто не став біднішим, допомагаючи іншим." – Анна Франк',
            '"Волонтерство – це не робота без оплати, це робота без ціни."',
            '"Кожен може допомогти. Потрібно лише бажання."',
            '"Малі дії багатьох людей можуть змінити світ."'
        ];

        const quoteBlock = document.getElementById('quote-block');
        let currentIndex = 0;

        setInterval(() => {
            currentIndex = (currentIndex + 1) % quotes.length;
            quoteBlock.style.opacity = 0;

            setTimeout(() => {
                quoteBlock.textContent = quotes[currentIndex];
                quoteBlock.style.opacity = 1;
            }, 300);
        }, 6000);
    </script>
</body>
</html>
