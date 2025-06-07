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
        <div class="profile-container">
            <?php if (!isset($_SESSION['user'])): ?>
                <p class="profile-login-request">Будь ласка, <a href="index.php?page=login">увійдіть</a> у систему.</p>
            <?php else: ?>

                <h2 class="profile-title">Ваш профіль</h2>

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

                <?php if ($_SESSION['user']['role'] == 0): ?>
                    <p class="profile-request"><a href="index.php?page=requestOrganizer">Подати запит на роль організатора</a></p>
                <?php endif; ?>

                <hr class="profile-separator">

                <div class="profile-section">
                    <h3>
                        <button type="button" class="profile-toggle-btn" onclick="toggleProfileForm()">Змінити дані профілю</button>
                    </h3>

                    <form id="profile-form" action="index.php?page=updateProfile" method="post" class="profile-form" style="display: none;">
                        <label for="name">Ім’я:</label><br>
                        <input type="text" name="name" id="name" required value="<?= htmlspecialchars($_SESSION['user']['name']) ?>">

                        <br><br><label for="email">Email:</label><br>
                        <input type="email" name="email" id="email" required value="<?= htmlspecialchars($_SESSION['user']['email']) ?>">

                        <br><br><button type="submit">Зберегти зміни</button>
                    </form>
                </div>

                <hr class="profile-separator">

                <div class="profile-section">
                    <h3>
                        <button type="button" class="avatar-toggle-btn" onclick="toggleAvatarForm()">Змінити аватар</button>
                    </h3>

                    <form id="avatar-form" action="index.php?page=updateAvatar" method="post" enctype="multipart/form-data" class="profile-form" style="display: none;">
                        <label for="avatar">Оберіть зображення:</label><br>
                        <input type="file" name="avatar" id="avatar" accept="image/*" required>

                        <button type="submit">Завантажити аватар</button>
                    </form>
                </div>

            <?php endif; ?>
            </div>
    </div>

    <?php include("app/include/footer.php"); ?>

    <script>
        function toggleProfileForm() {
            const form = document.getElementById('profile-form');
            const button = document.querySelector('.profile-toggle-btn');

            const isVisible = form.style.display === 'block';
            form.style.display = isVisible ? 'none' : 'block';
            button.textContent = isVisible ? 'Змінити дані профілю' : 'Сховати';
        }
    </script>
    <script>
        function toggleAvatarForm() {
            const form = document.getElementById('avatar-form');
            const button = document.querySelector('.avatar-toggle-btn');

            const isVisible = form.style.display === 'block';
            form.style.display = isVisible ? 'none' : 'block';
            button.textContent = isVisible ? 'Змінити аватар' : 'Сховати';
        }
    </script>

</body>
</html>