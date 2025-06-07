<?php

class Database
{
    private $host = 'localhost';
    private $dbName = 'acttogether';
    private $username = 'root';
    private $password = 'mysql';
    public $pdo;
    private $error;

    public function __construct()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("DB Connection failed: " . $this->error);
        }
    }

    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            die("Query error: " . $e->getMessage());
        }
    }

    public function insert($table, $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$table} ($columns) VALUES ($placeholders)";
        return $this->query($sql, $data);
    }

    public function update($table, $data, $where)
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }

        $setClause = implode(', ', $set);
        $whereClause = implode(' AND ', array_map(fn($key) => "$key = :$key", array_keys($where)));
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";

        $params = array_merge($data, $where);
        return $this->query($sql, $params);
    }

    public function delete($table, $where)
    {
        $whereClause = implode(' AND ', array_map(fn($key) => "$key = :$key", array_keys($where)));
        $sql = "DELETE FROM {$table} WHERE {$whereClause}";

        return $this->query($sql, $where);
    }

    public function select($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function selectOne($sql, $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
}
