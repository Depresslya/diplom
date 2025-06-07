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

    <div class="container faq-container">
        <h2>Поширені запитання (FAQ)</h2>

        <div class="faq-item">
            <button class="faq-question">🔸 Як стати волонтером?</button>
            <div class="faq-answer">
                <p>Зареєструйтесь на платформі як користувач, виберіть ініціативу та подайте заявку на участь.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">🔸 Як створити ініціативу?</button>
            <div class="faq-answer">
                <p>Отримайте роль організатора, після чого зможете додавати нові ініціативи через панель керування.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">🔸 Я бачу чат, але не можу писати?</button>
            <div class="faq-answer">
                <p>Доступ до групового чату мають лише підтверджені учасники ініціативи.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">🔸 Як дізнатися про нові події?</button>
            <div class="faq-answer">
                <p>На головній сторінці або в календарі будуть відображатися всі події, а також надходитимуть сповіщення.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">🔸 Як змінити свою роль?</button>
            <div class="faq-answer">
                <p>Подайте запит на зміну ролі на сторінці профілю. Адміністратор розгляне вашу заявку.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">🔸 Чи можна скасувати участь в ініціативі?</button>
            <div class="faq-answer">
                <p>Так, у своєму профілі або на сторінці ініціативи ви можете скасувати участь до початку події.</p>
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">🔸 Як працює система повідомлень?</button>
            <div class="faq-answer">
                <p>Ви можете надсилати особисті повідомлення іншим учасникам або писати у групові чати ініціатив, до яких приєднані.</p>
            </div>
        </div>
    </div>

    <?php include("app/include/footer.php"); ?>

    <script>
        const questions = document.querySelectorAll('.faq-question');

        questions.forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                const isVisible = answer.style.display === 'block';

                document.querySelectorAll('.faq-answer').forEach(a => a.style.display = 'none');
                if (!isVisible) {
                    answer.style.display = 'block';
                }
            });
        });
    </script>
</body>
</html>