<?php

class User
{
    private $db;
    private $table = 'user';

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function register($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        return $this->db->insert($this->table, $data);
    }

    public function login($login, $password)
    {
        $user = $this->db->selectOne("SELECT * FROM {$this->table} WHERE login = :login", ['login' => $login]);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function getById($id)
    {
        return $this->db->selectOne("SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function updateProfile($id, $data)
    {
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function updateAvatar($id, $filename)
    {
        return $this->db->update($this->table, ['image' => $filename], ['id' => $id]);
    }

    public function changeRole($id, $newRole)
    {
        return $this->db->update($this->table, ['role' => $newRole], ['id' => $id]);
    }

    public function emailExists($email)
    {
        $user = $this->db->selectOne("SELECT id FROM {$this->table} WHERE email = :email", ['email' => $email]);
        return !empty($user);
    }

    public function loginExists($login)
    {
        $user = $this->db->selectOne("SELECT id FROM {$this->table} WHERE login = :login", ['login' => $login]);
        return !empty($user);
    }

    public function createRoleRequest($userId, $text)
    {
        return $this->db->insert('request', [
            'user_id' => $userId,
            'text' => $text
        ]);
    }

    public function getRoleRequests()
    {
        $sql = "
            SELECT r.user_id, r.text, u.name, u.email
            FROM request r
            JOIN user u ON u.id = r.user_id
        ";
        return $this->db->select($sql);
    }

    public function updateRole($userId, $role)
    {
        return $this->db->update('user', ['role' => $role], ['id' => $userId]);
    }

    public function deleteRoleRequest($userId)
    {
        return $this->db->delete('request', ['user_id' => $userId]);
    }

    public function getUserRoleRequest($userId)
    {
        $sql = "SELECT * FROM request WHERE user_id = :user_id LIMIT 1";
        return $this->db->selectOne($sql, ['user_id' => $userId]);
    }

    public function getUserInitiatives($userId)
    {
        $sql = "
            SELECT i.id, i.title, i.start_time,
                CASE
                    WHEN i.organizer_id = :id THEN 'Організатор'
                    ELSE 'Волонтер'
                END AS role_label
            FROM initiative i
            LEFT JOIN initiative_staff s ON s.initiative_id = i.id AND s.user_id = :id
            WHERE i.organizer_id = :id OR s.user_id = :id
            GROUP BY i.id
            ORDER BY i.start_time DESC
        ";
        return $this->db->select($sql, ['id' => $userId]);
    }

}
