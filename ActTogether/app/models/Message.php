<?php

class Message
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getPrivateDialogs($userId)
    {
        $sql = "
            SELECT DISTINCT u.id, u.name
            FROM private_message pm
            JOIN user u ON (u.id = pm.sender_id OR u.id = pm.receiver_id)
            WHERE (pm.sender_id = :id OR pm.receiver_id = :id)
              AND u.id != :id
        ";

        return $this->db->select($sql, ['id' => $userId]);
    }

    public function getGroupChats($userId)
    {
        $sql = "
            SELECT DISTINCT i.id, i.title
            FROM initiative i
            LEFT JOIN initiative_staff s ON s.initiative_id = i.id AND s.user_id = :id AND s.status = 1
            WHERE i.organizer_id = :id OR s.user_id = :id
        ";

        return $this->db->select($sql, ['id' => $userId]);
    }



    public function getUserById($id)
    {
        return $this->db->selectOne("SELECT id, name FROM user WHERE id = :id", ['id' => $id]);
    }

    public function getPrivateMessages($user1, $user2)
    {
        $sql = "
            SELECT pm.*, u.name
            FROM private_message pm
            JOIN user u ON u.id = pm.sender_id
            WHERE (pm.sender_id = :u1 AND pm.receiver_id = :u2)
            OR (pm.sender_id = :u2 AND pm.receiver_id = :u1)
            ORDER BY pm.time ASC
        ";

        return $this->db->select($sql, ['u1' => $user1, 'u2' => $user2]);
    }

    public function sendPrivateMessage($from, $to, $text)
    {
        return $this->db->insert('private_message', [
            'sender_id' => $from,
            'receiver_id' => $to,
            'message' => $text
        ]);
    }

    public function isUserInInitiative($userId, $initiativeId)
    {
        $sql = "SELECT id FROM initiative_staff WHERE user_id = :u AND initiative_id = :i AND status = 1";
        return $this->db->selectOne($sql, ['u' => $userId, 'i' => $initiativeId]);
    }

    public function getInitiativeById($id)
    {
        return $this->db->selectOne("SELECT * FROM initiative WHERE id = :id", ['id' => $id]);
    }

    public function getGroupMessages($initiativeId)
    {
        $sql = "
            SELECT ic.*, u.name
            FROM initiative_chat ic
            JOIN user u ON u.id = ic.user_id
            WHERE ic.initiative_id = :id
            ORDER BY ic.time ASC
        ";
        return $this->db->select($sql, ['id' => $initiativeId]);
    }

    public function sendGroupMessage($userId, $initiativeId, $text)
    {
        return $this->db->insert('initiative_chat', [
            'user_id' => $userId,
            'initiative_id' => $initiativeId,
            'message' => $text
        ]);
    }

    public function getUnreadPrivateCount($userId)
    {
        $sql = "
            SELECT COUNT(*) as count
            FROM private_message
            WHERE receiver_id = :id AND is_read = 0
        ";

        return $this->db->selectOne($sql, ['id' => $userId])['count'] ?? 0;
    }

    public function markAsRead($userId, $partnerId)
    {
        $sql = "
            UPDATE private_message
            SET is_read = 1
            WHERE receiver_id = :user AND sender_id = :partner AND is_read = 0
        ";

        $stmt = $this->db->pdo->prepare($sql);
        $stmt->execute([
            'user' => $userId,
            'partner' => $partnerId
        ]);
    }

    public function getUnreadGroupCount($userId)
    {
        $sql = "
            SELECT i.id, i.title, COUNT(ic.id) as unread
            FROM initiative i
            JOIN initiative_chat ic ON ic.initiative_id = i.id
            LEFT JOIN initiative_chat_read r ON r.user_id = :uid AND r.initiative_id = i.id
            WHERE (i.organizer_id = :uid OR EXISTS (
                SELECT 1 FROM initiative_staff s
                WHERE s.initiative_id = i.id AND s.user_id = :uid AND s.status = 1
            ))
            AND (r.last_read_message_id IS NULL OR ic.id > r.last_read_message_id)
            GROUP BY i.id
        ";

        return $this->db->select($sql, ['uid' => $userId]);
    }

    public function markGroupAsRead($userId, $initiativeId)
    {
        $sql = "SELECT MAX(id) as max_id FROM initiative_chat WHERE initiative_id = :id";
        $maxId = $this->db->selectOne($sql, ['id' => $initiativeId])['max_id'] ?? 0;

        if ($maxId > 0) {
            $sql = "
                INSERT INTO initiative_chat_read (user_id, initiative_id, last_read_message_id)
                VALUES (:user, :initiative, :msg)
                ON DUPLICATE KEY UPDATE last_read_message_id = :msg
            ";
            $this->db->pdo->prepare($sql)->execute([
                'user' => $userId,
                'initiative' => $initiativeId,
                'msg' => $maxId
            ]);
        }
    }

    public function getUnreadPrivateList($userId)
    {
        $sql = "
            SELECT sender_id as id, COUNT(*) as count
            FROM private_message
            WHERE receiver_id = :uid AND is_read = 0
            GROUP BY sender_id
        ";
        return $this->db->select($sql, ['uid' => $userId]);
    }

    public function getUnreadGroupList($userId)
    {
        $sql = "
            SELECT i.id, COUNT(ic.id) as count
            FROM initiative i
            JOIN initiative_chat ic ON ic.initiative_id = i.id
            LEFT JOIN initiative_chat_read r ON r.user_id = :uid AND r.initiative_id = i.id
            WHERE (i.organizer_id = :uid OR EXISTS (
                SELECT 1 FROM initiative_staff s
                WHERE s.initiative_id = i.id AND s.user_id = :uid AND s.status = 1
            ))
            AND (r.last_read_message_id IS NULL OR ic.id > r.last_read_message_id)
            GROUP BY i.id
        ";
        return $this->db->select($sql, ['uid' => $userId]);
    }


}
