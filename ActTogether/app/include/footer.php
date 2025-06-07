<footer class="main-footer">
    <div class="footer-inner">

        <div class="footer-left">
            <p>&copy; <?= date('Y') ?> ActTogether. Усі права захищено.</p>
            <p>Розроблено з ❤️ для волонтерської спільноти.</p>
        </div>

        <div class="footer-links">
            <a href="index.php?page=about">Про нас</a>
            <a href="index.php?page=faq">FAQ</a>
            <a href="index.php?page=home">Головна</a>
        </div>

        <div class="footer-contact">
            <p>Зв'язатися з нами:</p>
            <form action="mailto:youremail@example.com" method="post" enctype="text/plain">
                <input type="text" name="message" placeholder="Ваше повідомлення..." required>
                <button type="submit">Надіслати</button>
            </form>

            <div class="footer-social">
                <a href="https://facebook.com" target="_blank">Facebook</a>
                <a href="https://instagram.com" target="_blank">Instagram</a>
                <a href="https://t.me" target="_blank">Telegram</a>
            </div>
        </div>

    </div>
</footer>
