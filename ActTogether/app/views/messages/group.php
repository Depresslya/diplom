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
    <div class="private-chat-container">
        <a href="index.php?page=messages" class="back-button">← Назад до чатів</a>

        <h2 class="chat-title">Чат ініціативи: <?= htmlspecialchars($initiative['title']) ?></h2>

        <div id="chat-box" class="chat-box">
        </div>

        <form action="index.php?page=sendGroupMessage" method="post" class="chat-form">
            <input type="hidden" name="initiative_id" value="<?= $initiative['id'] ?>">
            <textarea name="message" rows="3" required></textarea>
            <button type="submit">Надіслати</button>
        </form>

    </div>
</div>


    <?php include("app/include/footer.php"); ?>

<script>
    const chatBox = document.querySelector('#chat-box');
    const initiativeId = <?= (int)$initiative['id'] ?>;
    const currentUserId = <?= (int)$_SESSION['user']['id'] ?>;

    function formatDateTime(isoString) {
        const date = new Date(isoString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${day}.${month}.${year} ${hours}:${minutes}`;
    }

    function fetchMessages() {
        fetch(`index.php?page=fetchGroupMessages&initiative_id=${initiativeId}`)
            .then(res => res.json())
            .then(data => {
                chatBox.innerHTML = '';

                data.forEach(msg => {
                    const div = document.createElement('div');
                    const isMe = msg.user_id == <?= (int)$_SESSION['user']['id'] ?>;
                    div.className = 'chat-message ' + (isMe ? 'message-right' : 'message-left');

                    div.innerHTML = `
                        <div class="message-text"><strong>${msg.name}:</strong><br>${msg.message}</div>
                        <div class="message-time">${formatDateTime(msg.time)}</div>
                    `;
                    chatBox.appendChild(div);
                });

                chatBox.scrollTop = chatBox.scrollHeight;
            });
    }

    setInterval(fetchMessages, 5000);
    fetchMessages();
</script>

</body>
</html>