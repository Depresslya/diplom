<?php

class InitiativeController
{
    private $model;

    public function __construct($initiativeModel)
    {
        $this->model = $initiativeModel;
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $location = trim($_POST['location']);
            $categoryId = (int)$_POST['category_id'];
            $startTime = $_POST['start_time'];
            $endTime = $_POST['end_time'];
            $organizerId = $_SESSION['user']['id'];

            $uploadDir = 'assets/img/initiative/';
            $imagePath = null;
            $placeImagePath = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('img_') . '.' . $ext;
                $imagePath = $uploadDir . $filename;
                move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
            }

            if (isset($_FILES['place_image']) && $_FILES['place_image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['place_image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('place_') . '.' . $ext;
                $placeImagePath = $uploadDir . $filename;
                move_uploaded_file($_FILES['place_image']['tmp_name'], $placeImagePath);
            }

            $data = [
                'title' => $title,
                'description' => $description,
                'location' => $location,
                'category_id' => $categoryId,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'organizer_id' => $organizerId,
                'status' => 0
            ];

            if ($imagePath) $data['image'] = $imagePath;
            if ($placeImagePath) $data['place_image'] = $placeImagePath;

            $this->model->createInitiative($data);
            header("Location: index.php?page=myInitiatives");
            exit;
        }

        $categories = $this->model->getCategories();
        include './app/views/initiatives/create.php';
    }

    public function edit()
    {
        if (!isset($_GET['id'])) {
            header("Location: index.php?page=myInitiatives");
            exit;
        }

        $id = (int)$_GET['id'];
        $initiative = $this->model->getInitiativeById($id);

        if ($_SESSION['user']['id'] != $initiative['organizer_id'] && $_SESSION['user']['role'] != 2) {
            header("Location: index.php?page=myInitiatives");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $location = trim($_POST['location']);
            $categoryId = (int)$_POST['category_id'];
            $startTime = $_POST['start_time'];
            $endTime = $_POST['end_time'];

            $uploadDir = 'assets/img/initiative/';
            $imagePath = $initiative['image'];
            $placeImagePath = $initiative['place_image'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('img_') . '.' . $ext;
                $imagePath = $uploadDir . $filename;
                move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
            }

            if (isset($_FILES['place_image']) && $_FILES['place_image']['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES['place_image']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('place_') . '.' . $ext;
                $placeImagePath = $uploadDir . $filename;
                move_uploaded_file($_FILES['place_image']['tmp_name'], $placeImagePath);
            }

            $data = [
                'title' => $title,
                'description' => $description,
                'location' => $location,
                'category_id' => $categoryId,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'image' => $imagePath,
                'place_image' => $placeImagePath
            ];

            $this->model->updateInitiative($id, $data);
            header("Location: index.php?page=myInitiatives");
            exit;
        }

        $categories = $this->model->getCategories();
        include './app/views/initiatives/edit.php';
    }

    public function delete()
    {
        $id = $_GET['id'];
        $this->model->deleteInitiative($id);
        header("Location: index.php?page=myInitiatives");
        exit;
    }

    public function request()
    {
        if (isset($_GET['id']) && isset($_SESSION['user'])) {
            $initiativeId = $_GET['id'];
            $userId = $_SESSION['user']['id'];

            if (!$this->model->checkIfRequested($initiativeId, $userId)) {
                $this->model->requestParticipation($initiativeId, $userId);
            }

            header("Location: index.php?page=initiativeView&id={$initiativeId}");
            exit;
        }
    }

    public function approve()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['initiative_id'], $_POST['user_id'])) {
            if ($_SESSION['user']['role'] >= 1) {
                $this->model->approveParticipation($_POST['initiative_id'], $_POST['user_id']);
            }
            header("Location: index.php?page=myView&id=" . $_POST['initiative_id']);
            exit;
        }
    }

    public function list()
    {
        $search = $_GET['search'] ?? '';
        $categoryId = $_GET['category'] ?? '';

        $initiatives = $this->model->searchInitiatives($search, $categoryId);
        $categories = $this->model->getCategories();

        include './app/views/initiatives/list.php';
    }

    public function view()
    {
        if (!isset($_GET['id'])) {
            header("Location: index.php?page=initiativeList");
            exit;
        }

        $id = (int)$_GET['id'];

        $initiative = $this->model->getInitiativeWithCategory($id);

        if (!$initiative) {
            echo "<p>Ініціативу не знайдено.</p>";
            return;
        }

        $volunteers = $this->model->getApprovedVolunteers($id);

        $comments = $this->model->getComments($id);

        $status = null;
        if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 0) {
            $row = $this->model->getParticipationStatus($id, $_SESSION['user']['id']);
            if ($row) {
                $status = (int)$row['status'];
            }
        }


        include './app/views/initiatives/view.php';
    }

    public function addComment()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'], $_GET['id'])) {
            $initiativeId = $_GET['id'];
            $userId = $_SESSION['user']['id'];
            $text = trim($_POST['text']);

            if ($text !== '') {
                $this->model->addComment($initiativeId, $userId, $text);
            }
        }

        header("Location: index.php?page=initiativeView&id=" . $_GET['id']);
        exit;
    }

    public function myInitiatives()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $userId = $_SESSION['user']['id'];

        $sql = "
            SELECT i.*, c.title AS category_title
            FROM initiative i
            JOIN category c ON i.category_id = c.id
            WHERE i.organizer_id = :user_id
            ORDER BY i.start_time DESC
        ";
        $initiatives = $this->model->getInitiativesByOrganizer($userId);

        include './app/views/user/myinitiatives.php';
    }

    public function myView()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $id = $_GET['id'];
        $initiative = $this->model->getInitiativeWithCategory($id);

        if ($_SESSION['user']['id'] != $initiative['organizer_id'] && $_SESSION['user']['role'] != 2) {
            header("Location: index.php?page=home");
            exit;
        }

        $requests = $this->model->getPendingRequests($id);
        $approved = $this->model->getApprovedVolunteers($id);

        include './app/views/initiatives/myview.php';
    }

    public function assignStaffRole()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['user']['role'] >= 1) {
            $initiativeId = $_POST['initiative_id'];
            $userId = $_POST['user_id'];
            $role = trim($_POST['role']);

            if ($role !== '') {
                $this->model->updateStaffRole($initiativeId, $userId, $role);
            }
        }

        header("Location: index.php?page=myView&id=" . $_POST['initiative_id']);
        exit;
    }

    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['initiative_id'], $_POST['status'])) {
            $id = (int)$_POST['initiative_id'];
            $status = (int)$_POST['status'];

            $initiative = $this->model->getInitiativeById($id);
            if ($_SESSION['user']['id'] == $initiative['organizer_id'] || $_SESSION['user']['role'] == 2) {
                $this->model->updateInitiative($id, ['status' => $status]);
            }
        }

        header("Location: index.php?page=myView&id=" . $_POST['initiative_id']);
        exit;
    }

    public function calendar()
    {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $events = $this->model->getUpcomingEventsForUser($userId);

        include './app/views/initiatives/calendar.php';
    }

    public function upcomingCount()
    {
        if (!isset($_SESSION['user'])) {
            echo json_encode(['count' => 0]);
            return;
        }

        $count = $this->model->getUpcomingEventCount($_SESSION['user']['id']);
        echo json_encode(['count' => $count]);
    }

    public function home()
    {
        $latestInitiatives = $this->model->getLatestInitiatives();

        include './app/views/home.php';
    }

}
