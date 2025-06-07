<?php
session_start();

define('ROOT', __DIR__);

require_once './app/core/Database.php';
require_once './app/models/User.php';
require_once './app/controllers/UserController.php';
require_once './app/models/Initiative.php';
require_once './app/controllers/InitiativeController.php';
require_once './app/controllers/MessageController.php';
require_once './app/models/Message.php';

$db = new Database();
$userModel = new User($db);
$userController = new UserController($userModel);
$initiativeModel = new Initiative($db);
$initiativeController = new InitiativeController($initiativeModel);
$messageModel = new Message($db);
$messageController = new MessageController($messageModel);

$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'register':
        $userController->register();
        break;

    case 'login':
        $userController->login();
        break;

    case 'profile':
        if (isset($_GET['id'])) {
            $userController->publicProfile();
        } else {
            $userController->profile();
        }
        break;

    case 'updateProfile':
        $userController->updateProfile();
        header('Location: index.php?page=profile');
        break;

    case 'updateAvatar':
        $userController->updateAvatar();
        header('Location: index.php?page=profile');
        break;

    case 'changeRole':
        $userController->changeRole();
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php');
        break;

    case 'initiativeCreate':
        $initiativeController->create();
        break;

    case 'initiativeEdit':
        $initiativeController->edit();
        break;

    case 'initiativeDelete':
        $initiativeController->delete();
        break;

    case 'initiativeRequest':
        $initiativeController->request();
        break;

    case 'initiativeList':
        $initiativeController->list();
        break;

    case 'initiativeView':
        $initiativeController->view();
        break;

    case 'initiativeComment':
        $initiativeController->addComment();
        break;

    case 'myInitiatives':
        $initiativeController->myInitiatives();
        break;

    case 'myView':
        $initiativeController->myView();
        break;

    case 'initiativeApprove':
        $initiativeController->approve();
        break;

    case 'requestOrganizer':
        $userController->requestOrganizer();
        break;

    case 'submitRoleRequest':
        $userController->submitRoleRequest();
        break;

    case 'adminRequests':
        $userController->listRoleRequests();
        break;

    case 'approveRoleRequest':
        $userController->approveRoleRequest();
        break;

    case 'deleteRoleRequest':
        $userController->deleteRoleRequest();
        break;

    case 'assignStaffRole':
        $initiativeController->assignStaffRole();
        break;

    case 'updateInitiativeStatus':
        $initiativeController->updateStatus();
        break;

    case 'messages':
        $messageController->listDialogs();
        break;

    case 'privateChat':
        $messageController->privateChat();
        break;

    case 'sendPrivateMessage':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messageController->sendPrivateMessage();
        }
        break;

    case 'groupChat':
        $messageController->groupChat();
        break;

    case 'sendGroupMessage':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $messageController->sendGroupMessage();
        }
        break;

    case 'fetchGroupMessages':
        header('Content-Type: application/json');
        $messageController->fetchGroupMessages();
        break;

    case 'fetchPrivateMessages':
        header('Content-Type: application/json');
        $messageController->fetchPrivateMessages();
        break;

    case 'unreadCount':
        header('Content-Type: application/json');
        $messageController->unreadCount();
        break;

    case 'unreadGroupCount':
        header('Content-Type: application/json');
        $messageController->unreadGroupCount();
        break;

    case 'unreadPrivateList':
        header('Content-Type: application/json');
        $messageController->unreadPrivateList();
        break;

    case 'unreadGroupList':
        header('Content-Type: application/json');
        $messageController->unreadGroupList();
        break;

    case 'calendar':
        $initiativeController->calendar();
        break;

    case 'upcomingCount':
        header('Content-Type: application/json');
        $initiativeController->upcomingCount();
        break;

    case 'about':
        include './app/views/about.php';
        break;

    case 'faq':
        include './app/views/faq.php';
        break;

    case 'home':
    default:
        $initiativeController->home();
        break;

}
