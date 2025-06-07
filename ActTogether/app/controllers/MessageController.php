<?php

class MessageController
{
    private $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function listDialogs()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $userId = $_SESSION['user']['id'];

        $privateDialogs = $this->model->getPrivateDialogs($userId);
        $groupDialogs = $this->model->getGroupChats($userId);

        include './app/views/messages/list.php';
    }

    public function privateChat()
    {
        if (!isset($_SESSION['user']) || !isset($_GET['id'])) {
            header("Location: index.php?page=messages");
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $partnerId = (int)$_GET['id'];

        $partner = $this->model->getUserById($partnerId);
        if (!$partner) {
            echo "<p>Користувача не знайдено.</p>";
            return;
        }

        $messages = $this->model->getPrivateMessages($userId, $partnerId);
        $this->model->markAsRead($userId, $partnerId);

        include './app/views/messages/private.php';
    }

    public function sendPrivateMessage()
    {
        $from = $_SESSION['user']['id'];
        $to = (int)$_POST['to'];
        $text = trim($_POST['message']);

        if ($text !== '') {
            $this->model->sendPrivateMessage($from, $to, $text);
        }

        header("Location: index.php?page=privateChat&id=" . $to);
        exit;
    }

    public function groupChat()
    {
        if (!isset($_SESSION['user']) || !isset($_GET['id'])) {
            header("Location: index.php?page=messages");
            exit;
        }

        $initiativeId = (int)$_GET['id'];
        $userId = $_SESSION['user']['id'];

        $initiative = $this->model->getInitiativeById($initiativeId);

        if (
            !$this->model->isUserInInitiative($userId, $initiativeId)
            && $initiative['organizer_id'] != $userId
        ) {
            echo "<p>У вас немає доступу до цього чату.</p>";
            return;
        }

        $initiative = $this->model->getInitiativeById($initiativeId);
        $messages = $this->model->getGroupMessages($initiativeId);
        $this->model->markGroupAsRead($userId, $initiativeId);

        include './app/views/messages/group.php';
    }

    public function sendGroupMessage()
    {
        $userId = $_SESSION['user']['id'];
        $initiativeId = (int)$_POST['initiative_id'];
        $text = trim($_POST['message']);

        if ($text !== '') {
            $this->model->sendGroupMessage($userId, $initiativeId, $text);
        }

        header("Location: index.php?page=groupChat&id=" . $initiativeId);
        exit;
    }

    public function fetchGroupMessages()
    {
        if (!isset($_GET['initiative_id']) || !isset($_SESSION['user'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad request']);
            return;
        }

        $initiativeId = (int)$_GET['initiative_id'];
        $messages = $this->model->getGroupMessages($initiativeId);
        echo json_encode($messages);
    }

    public function fetchPrivateMessages()
    {
        if (!isset($_SESSION['user']) || !isset($_GET['partner_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Bad request']);
            return;
        }

        $userId = $_SESSION['user']['id'];
        $partnerId = (int)$_GET['partner_id'];

        $messages = $this->model->getPrivateMessages($userId, $partnerId);
        echo json_encode($messages);
    }

    public function unreadCount()
    {
        if (!isset($_SESSION['user'])) {
            echo json_encode(['count' => 0]);
            return;
        }

        $userId = $_SESSION['user']['id'];
        $count = $this->model->getUnreadPrivateCount($userId);
        echo json_encode(['count' => $count]);
    }

    public function unreadGroupCount()
    {
        if (!isset($_SESSION['user'])) {
            echo json_encode(['group' => []]);
            return;
        }

        $userId = $_SESSION['user']['id'];
        $groupUnread = $this->model->getUnreadGroupCount($userId);
        echo json_encode(['group' => $groupUnread]);
    }

    public function unreadPrivateList()
    {
        $userId = $_SESSION['user']['id'];
        echo json_encode($this->model->getUnreadPrivateList($userId));
    }

    public function unreadGroupList()
    {
        $userId = $_SESSION['user']['id'];
        echo json_encode($this->model->getUnreadGroupList($userId));
    }

}
