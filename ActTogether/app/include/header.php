<nav class="main-header">
    <div class="header-inner">
        <div class="header-logo">
            <a href="index.php?page=home"><img src="./assets/img/logo.png" alt="" id="logo-link"></a>
        </div>

        <div class="menu-wrapper">
            <button id="menu-toggle" class="menu-btn">☰ Меню</button>
            <ul id="dropdown-menu" class="menu-dropdown">
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="index.php?page=profile">Профіль</a></li>
                    <li><a href="index.php?page=initiativeList">Усі івенти</a></li>

                    <?php if ($_SESSION['user']['role'] == 0): ?>
                        <li><a href="index.php?page=requestOrganizer">Запит на роль</a></li>
                    <?php elseif ($_SESSION['user']['role'] >= 1): ?>
                        <li><a href="index.php?page=myInitiatives">Мої івенти</a></li>
                    <?php endif; ?>

                    <li><a href="index.php?page=calendar">Календар <span id="calendar-count" class="menu-count"></span></a></li>
                    <li><a href="index.php?page=messages">Повідомлення <span id="unread-total-count" class="menu-count"></span></a></li>

                    <?php if ($_SESSION['user']['role'] > 1): ?>
                        <li><a href="index.php?page=adminRequests">Запити на роль</a></li>
                    <?php endif; ?>
                    <li><a href="index.php?page=logout">Вихід</a></li>
                <?php else: ?>
                    <p><a href="index.php?page=login">Логін</a></p>
                    <p><a href="index.php?page=register">Реєстрація</a></p>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="header-line-wrapper">
        <div class="header-line-diagonal"></div>
    </div>
</nav>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const toggleBtn = document.getElementById('menu-toggle');
  const dropdown = document.getElementById('dropdown-menu');
  const logo = document.getElementById('logo-link');

  toggleBtn.addEventListener('click', () => {
    dropdown.classList.toggle('show');

    if (dropdown.classList.contains('show')) {
      logo.classList.remove('logo-animate');
      void logo.offsetWidth;
      logo.classList.add('logo-animate');
    }
  });
});
</script>
<script>
    document.getElementById('menu-toggle').addEventListener('click', function () {
        const menu = document.getElementById('dropdown-menu');
        const logo = document.querySelector('.header-logo a');

        menu.classList.toggle('show');

        if (menu.classList.contains('show')) {
            logo.classList.remove('logo-animate');
            void logo.offsetWidth;
            logo.classList.add('logo-animate');
        }
    });
</script>
<script>
    document.getElementById('menu-toggle').addEventListener('click', function () {
        document.getElementById('dropdown-menu').classList.toggle('show');
    });

    document.addEventListener('click', function (e) {
        const toggle = document.getElementById('menu-toggle');
        const menu = document.getElementById('dropdown-menu');
        if (!toggle.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove('show');
        }
    });
</script>
<script>
    function checkUpcomingEvents() {
        fetch('index.php?page=upcomingCount')
            .then(res => res.json())
            .then(data => {
                const el = document.getElementById('calendar-count');
                el.textContent = data.count > 0 ? `(${data.count})` : '';
            });
    }

    setInterval(checkUpcomingEvents, 10000);
    checkUpcomingEvents();
</script>
<script>
function checkAllUnread() {
    let privateCount = 0;
    let groupCount = 0;

    fetch('index.php?page=unreadCount')
        .then(res => res.json())
        .then(data => {
            privateCount = parseInt(data.count) || 0;

            return fetch('index.php?page=unreadGroupCount');
        })
        .then(res => res.json())
        .then(data => {
            groupCount = data.group.reduce((sum, row) => sum + parseInt(row.unread || 0), 0);

            const total = privateCount + groupCount;
            const badge = document.getElementById('unread-total-count');
            badge.textContent = total > 0 ? `(${total})` : '';
        });
}

setInterval(checkAllUnread, 10000);
checkAllUnread();
</script>
