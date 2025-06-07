<?php

class UserController
{
    private $model;

    public function __construct($userModel)
    {
        $this->model = $userModel;
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'login' => $_POST['login'],
                'email' => $_POST['email'],
                'name' => $_POST['name'],
                'password' => $_POST['password'],
                'role' => 0
            ];

            if ($this->model->emailExists($data['email'])) {
                $error = "Такий email вже зареєстрований.";
                include './app/views/auth/register.php';
                return;
            }

            if ($this->model->loginExists($data['login'])) {
                $error = "Такий логін вже використовується.";
                include './app/views/auth/register.php';
                return;
            }

            $this->model->register($data);
            header("Location: index.php?page=home");
            exit;
        }

        $data = ['login' => '', 'email' => '', 'name' => ''];
        include './app/views/auth/register.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $login = $_POST['login'];
            $password = $_POST['password'];

            $user = $this->model->login($login, $password);

            if ($user) {
                $_SESSION['user'] = $user;
                header("Location: index.php?page=home");
                exit;
            } else {
                $error = "Невірний логін або пароль.";
                $data = ['login' => $login];
                include './app/views/auth/login.php';
                return;
            }
        }

        $data = ['login' => ''];
        include './app/views/auth/login.php';
    }

    public function profile()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $_SESSION['user'] = $this->model->getById($userId);

        include './app/views/user/profile.php';
    }

    public function updateProfile()
    {
        if (isset($_SESSION['user']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['user']['id'];
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email']
            ];

            $this->model->updateProfile($id, $data);

            $_SESSION['user'] = $this->model->getById($id);

            header('Location: index.php?page=profile');
            exit;
        }
    }

    public function updateAvatar()
    {
        if (isset($_SESSION['user']) && isset($_FILES['avatar'])) {
            $id = $_SESSION['user']['id'];

            $uploadDir = 'assets/img/avatar/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $filename = uniqid('avatar_') . '_' . basename($_FILES['avatar']['name']);
            $targetPath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetPath)) {
                $this->model->updateAvatar($id, $targetPath);

                $_SESSION['user']['image'] = $targetPath;
            }

            header("Location: index.php?page=profile");
            exit;
        }
    }

    public function changeRole()
    {
        if (isset($_POST['user_id']) && isset($_POST['role'])) {
            $userId = $_POST['user_id'];
            $role = (int)$_POST['role'];
            $this->model->changeRole($userId, $role);
            echo "Роль змінено.";
        }
    }

    public function submitRoleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
            $userId = $_SESSION['user']['id'];
            $text = trim($_POST['text']);

            if ($text !== '') {
                $this->model->createRoleRequest($userId, $text);
            }

            header('Location: index.php?page=profile');
            exit;
        }
    }

    public function listRoleRequests()
    {
        if ($_SESSION['user']['role'] != 2) {
            header('Location: index.php?page=home');
            exit;
        }

        $requests = $this->model->getRoleRequests();
        include './app/views/admin/requests.php';
    }

    public function approveRoleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user']['role'] == 2) {
            $userId = $_POST['user_id'];
            $this->model->updateRole($userId, 1);
            $this->model->deleteRoleRequest($userId);
        }

        header("Location: index.php?page=adminRequests");
        exit;
    }

    public function requestOrganizer()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $existing = $this->model->getUserRoleRequest($userId);

        include './app/views/user/request.php';
    }

    public function deleteRoleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user']['role'] == 2) {
            $userId = $_POST['user_id'];
            $this->model->deleteRoleRequest($userId);
        }

        header("Location: index.php?page=adminRequests");
        exit;
    }

    public function publicProfile()
    {
        if (!isset($_GET['id'])) {
            echo "<p>Користувача не знайдено.</p>";
            return;
        }

        $user = $this->model->getById($_GET['id']);
        $initiatives = $this->model->getUserInitiatives($user['id']);
        if (!$user) {
            echo "<p>Користувача не знайдено.</p>";
            return;
        }

        include './app/views/user/public_profile.php';
    }

}
