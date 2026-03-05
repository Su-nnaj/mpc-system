<?php
class UserModel extends Model {
    protected string $table = 'users';

    public function findByEmail(string $email): ?array {
        return $this->db->fetchOne("SELECT * FROM users WHERE email = ?", [$email]);
    }

    public function findByUsername(string $username): ?array {
        return $this->db->fetchOne("SELECT * FROM users WHERE username = ?", [$username]);
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function register(array $data): int {
        $data['password'] = $this->hashPassword($data['password']);
        $data['role'] = 'customer';
        return $this->create($data);
    }

    public function getAll(int $page = 1, int $limit = 20): array {
        $offset = ($page - 1) * $limit;
        return $this->db->fetchAll(
            "SELECT id, username, email, full_name, role, is_active, created_at FROM users ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$limit, $offset]
        );
    }

    public function getUnreadNotifications(int $userId): array {
        return $this->db->fetchAll(
            "SELECT * FROM notifications WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC LIMIT 10",
            [$userId]
        );
    }

    public function markNotificationsRead(int $userId): void {
        $this->db->query("UPDATE notifications SET is_read = 1 WHERE user_id = ?", [$userId]);
    }
}
