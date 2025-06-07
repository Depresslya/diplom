<?php

class Initiative
{
    private $db;
    private $initiativeTable = 'initiative';
    private $categoryTable = 'category';
    private $staffTable = 'initiative_staff';

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getCategories()
    {
        return $this->db->select("SELECT * FROM {$this->categoryTable}");
    }

    public function createInitiative($data)
    {
        return $this->db->insert($this->initiativeTable, $data);
    }

    public function updateInitiative($id, $data)
    {
        return $this->db->update($this->initiativeTable, $data, ['id' => $id]);
    }

    public function deleteInitiative($id)
    {
        return $this->db->delete($this->initiativeTable, ['id' => $id]);
    }

    public function getInitiativeById($id)
    {
        return $this->db->selectOne("SELECT * FROM {$this->initiativeTable} WHERE id = :id", ['id' => $id]);
    }

    public function requestParticipation($initiativeId, $userId)
    {
        return $this->db->insert($this->staffTable, [
            'initiative_id' => $initiativeId,
            'user_id' => $userId,
            'status' => 0
        ]);
    }

    public function approveParticipation($initiativeId, $userId)
    {
        return $this->db->update($this->staffTable, ['status' => 1], [
            'initiative_id' => $initiativeId,
            'user_id' => $userId
        ]);
    }

    public function checkIfRequested($initiativeId, $userId)
    {
        $sql = "
            SELECT id FROM initiative_staff
            WHERE initiative_id = :initiative_id AND user_id = :user_id
        ";
        $result = $this->db->selectOne($sql, [
            'initiative_id' => $initiativeId,
            'user_id' => $userId
        ]);

        return !empty($result);
    }

    public function getAllWithCategory()
    {
        $sql = "
            SELECT i.*, c.title AS category_title
            FROM initiative i
            JOIN category c ON i.category_id = c.id
        ";
        return $this->db->select($sql);
    }

    public function getInitiativeWithCategory($id)
    {
        $sql = "
            SELECT i.*, c.title AS category_title, u.name AS organizer_name, u.id AS organizer_id
            FROM initiative i
            JOIN category c ON i.category_id = c.id
            JOIN user u ON i.organizer_id = u.id
            WHERE i.id = :id
        ";
        return $this->db->selectOne($sql, ['id' => $id]);
    }

    public function getApprovedVolunteers($initiativeId)
    {
        $sql = "
            SELECT u.id, u.name, u.login, u.email, u.image, s.role AS staff_role
            FROM initiative_staff s
            JOIN user u ON u.id = s.user_id
            WHERE s.initiative_id = :initiative_id AND s.status = 1
        ";
        return $this->db->select($sql, ['initiative_id' => $initiativeId]);
    }


    public function getComments($initiativeId)
    {
        $sql = "
            SELECT c.text, c.time, u.name, u.id AS user_id
            FROM initiative_comment c
            JOIN user u ON u.id = c.user_id
            WHERE c.initiative_id = :initiative_id
            ORDER BY c.id DESC
        ";
        return $this->db->select($sql, ['initiative_id' => $initiativeId]);
    }

    public function addComment($initiativeId, $userId, $text)
    {
        return $this->db->insert('initiative_comment', [
            'initiative_id' => $initiativeId,
            'user_id' => $userId,
            'text' => $text
        ]);
    }

    public function getInitiativesByOrganizer($userId)
    {
        $sql = "
            SELECT i.*, c.title AS category_title
            FROM initiative i
            JOIN category c ON i.category_id = c.id
            WHERE i.organizer_id = :user_id
            ORDER BY i.start_time DESC
        ";
        return $this->db->select($sql, ['user_id' => $userId]);
    }

    public function getPendingRequests($initiativeId)
    {
        $sql = "
            SELECT u.id, u.name, u.email
            FROM initiative_staff s
            JOIN user u ON u.id = s.user_id
            WHERE s.initiative_id = :initiative_id AND s.status = 0
        ";
        return $this->db->select($sql, ['initiative_id' => $initiativeId]);
    }

    public function getParticipationStatus($initiativeId, $userId)
    {
        $sql = "
            SELECT status
            FROM initiative_staff
            WHERE initiative_id = :initiative_id AND user_id = :user_id
            LIMIT 1
        ";

        return $this->db->selectOne($sql, [
            'initiative_id' => $initiativeId,
            'user_id' => $userId
        ]);
    }

    public function updateStaffRole($initiativeId, $userId, $role)
    {
        return $this->db->update('initiative_staff', ['role' => $role], [
            'initiative_id' => $initiativeId,
            'user_id' => $userId
        ]);
    }

    public function searchInitiatives($search, $categoryId)
    {
        $sql = "
            SELECT i.*, c.title AS category_title
            FROM initiative i
            JOIN category c ON i.category_id = c.id
            WHERE 1=1
        ";

        $params = [];

        if (!empty($search)) {
            $sql .= " AND i.title LIKE :search";
            $params['search'] = '%' . $search . '%';
        }

        if (!empty($categoryId)) {
            $sql .= " AND i.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }

        $sql .= " ORDER BY i.start_time DESC";

        return $this->db->select($sql, $params);
    }

    public function getUpcomingEventsForUser($userId)
    {
        $sql = "
            SELECT i.*, s.role AS staff_role,
                CASE 
                    WHEN i.organizer_id = :id THEN 'Організатор'
                    WHEN s.role IS NOT NULL THEN s.role
                    ELSE 'Учасник'
                END AS user_role
            FROM initiative i
            LEFT JOIN initiative_staff s ON s.initiative_id = i.id AND s.user_id = :id AND s.status = 1
            WHERE (i.organizer_id = :id OR s.user_id = :id)
            AND i.start_time >= NOW()
            ORDER BY i.start_time ASC
        ";
        return $this->db->select($sql, ['id' => $userId]);
    }

    public function getUpcomingEventCount($userId)
    {
        $sql = "
            SELECT COUNT(*) as count
            FROM initiative i
            LEFT JOIN initiative_staff s ON s.initiative_id = i.id AND s.user_id = :id AND s.status = 1
            WHERE (i.organizer_id = :id OR s.user_id = :id)
            AND i.start_time >= NOW()
            AND i.start_time <= DATE_ADD(NOW(), INTERVAL 7 DAY)
        ";
        return $this->db->selectOne($sql, ['id' => $userId])['count'] ?? 0;
    }

    public function getLatestInitiatives($limit = 5)
    {
        $sql = "
            SELECT i.*, c.title AS category_title
            FROM initiative i
            JOIN category c ON c.id = i.category_id
            WHERE i.start_time >= NOW()
            ORDER BY i.start_time ASC
            LIMIT :limit
        ";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
