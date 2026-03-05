<?php
// ============================================================
// Base Model
// ============================================================
abstract class Model {
    protected Database $db;
    protected string $table = '';
    protected string $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function find(int $id): ?array {
        return $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }

    public function findAll(array $conditions = [], string $orderBy = '', int $limit = 0): array {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        if (!empty($conditions)) {
            $where = implode(' AND ', array_map(fn($k) => "$k = ?", array_keys($conditions)));
            $sql .= " WHERE $where";
            $params = array_values($conditions);
        }
        if ($orderBy) $sql .= " ORDER BY $orderBy";
        if ($limit) $sql .= " LIMIT $limit";
        return $this->db->fetchAll($sql, $params);
    }

    public function create(array $data): int {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $this->db->query(
            "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)",
            array_values($data)
        );
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $set = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
        $params = array_values($data);
        $params[] = $id;
        $this->db->query(
            "UPDATE {$this->table} SET $set WHERE {$this->primaryKey} = ?",
            $params
        );
        return true;
    }

    public function delete(int $id): bool {
        $this->db->query("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?", [$id]);
        return true;
    }

    public function count(array $conditions = []): int {
        $sql = "SELECT COUNT(*) as cnt FROM {$this->table}";
        $params = [];
        if (!empty($conditions)) {
            $where = implode(' AND ', array_map(fn($k) => "$k = ?", array_keys($conditions)));
            $sql .= " WHERE $where";
            $params = array_values($conditions);
        }
        $result = $this->db->fetchOne($sql, $params);
        return (int)($result['cnt'] ?? 0);
    }
}
