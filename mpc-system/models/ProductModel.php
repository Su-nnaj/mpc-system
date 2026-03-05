<?php
class ProductModel extends Model {
    protected string $table = 'products';

    public function getWithCategory(int $id): ?array {
        return $this->db->fetchOne(
            "SELECT p.*, c.name as category_name, c.component_type, c.slug as category_slug
             FROM products p JOIN categories c ON p.category_id = c.id
             WHERE p.id = ? AND p.is_active = 1",
            [$id]
        );
    }

    public function getBySlug(string $slug): ?array {
        return $this->db->fetchOne(
            "SELECT p.*, c.name as category_name, c.component_type, c.slug as category_slug
             FROM products p JOIN categories c ON p.category_id = c.id
             WHERE p.slug = ? AND p.is_active = 1",
            [$slug]
        );
    }

    public function search(string $query, array $filters = [], int $page = 1): array {
        $limit = ITEMS_PER_PAGE;
        $offset = ($page - 1) * $limit;
        $params = [];
        $where = ["p.is_active = 1"];

        if ($query) {
            $where[] = "(p.name LIKE ? OR p.brand LIKE ? OR p.description LIKE ?)";
            $like = "%$query%";
            $params = array_merge($params, [$like, $like, $like]);
        }
        if (!empty($filters['category'])) {
            $where[] = "c.slug = ?";
            $params[] = $filters['category'];
        }
        if (!empty($filters['condition'])) {
            $where[] = "p.condition_type = ?";
            $params[] = $filters['condition'];
        }
        if (!empty($filters['min_price'])) {
            $where[] = "COALESCE(p.sale_price, p.price) >= ?";
            $params[] = $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[] = "COALESCE(p.sale_price, p.price) <= ?";
            $params[] = $filters['max_price'];
        }

        $whereStr = implode(' AND ', $where);
        $orderBy = match($filters['sort'] ?? '') {
            'price_asc'  => "COALESCE(p.sale_price, p.price) ASC",
            'price_desc' => "COALESCE(p.sale_price, p.price) DESC",
            'newest'     => "p.created_at DESC",
            'popular'    => "p.views_count DESC",
            default      => "p.is_featured DESC, p.created_at DESC"
        };

        $sql = "SELECT p.*, c.name as category_name, c.component_type
                FROM products p JOIN categories c ON p.category_id = c.id
                WHERE $whereStr ORDER BY $orderBy LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        return $this->db->fetchAll($sql, $params);
    }

    public function searchCount(string $query, array $filters = []): int {
        $params = [];
        $where = ["p.is_active = 1"];
        if ($query) {
            $where[] = "(p.name LIKE ? OR p.brand LIKE ? OR p.description LIKE ?)";
            $like = "%$query%";
            $params = array_merge($params, [$like, $like, $like]);
        }
        if (!empty($filters['category'])) { $where[] = "c.slug = ?"; $params[] = $filters['category']; }
        if (!empty($filters['condition'])) { $where[] = "p.condition_type = ?"; $params[] = $filters['condition']; }
        if (!empty($filters['min_price'])) { $where[] = "COALESCE(p.sale_price, p.price) >= ?"; $params[] = $filters['min_price']; }
        if (!empty($filters['max_price'])) { $where[] = "COALESCE(p.sale_price, p.price) <= ?"; $params[] = $filters['max_price']; }
        $whereStr = implode(' AND ', $where);
        $result = $this->db->fetchOne(
            "SELECT COUNT(*) as cnt FROM products p JOIN categories c ON p.category_id = c.id WHERE $whereStr",
            $params
        );
        return (int)($result['cnt'] ?? 0);
    }

    public function getFeatured(int $limit = 8): array {
        return $this->db->fetchAll(
            "SELECT p.*, c.name as category_name, c.component_type
             FROM products p JOIN categories c ON p.category_id = c.id
             WHERE p.is_featured = 1 AND p.is_active = 1 AND p.stock_quantity > 0
             ORDER BY p.created_at DESC LIMIT ?",
            [$limit]
        );
    }

    public function getByCategory(int $categoryId, int $limit = 20): array {
        return $this->db->fetchAll(
            "SELECT p.*, c.name as category_name FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.category_id = ? AND p.is_active = 1 ORDER BY p.price ASC LIMIT ?",
            [$categoryId, $limit]
        );
    }

    public function getByCategoryType(string $type, float $maxPrice = 0): array {
        $sql = "SELECT p.*, c.component_type FROM products p
                JOIN categories c ON p.category_id = c.id
                WHERE c.component_type = ? AND p.is_active = 1 AND p.stock_quantity > 0";
        $params = [$type];
        if ($maxPrice > 0) { $sql .= " AND COALESCE(p.sale_price, p.price) <= ?"; $params[] = $maxPrice; }
        $sql .= " ORDER BY COALESCE(p.sale_price, p.price) ASC";
        return $this->db->fetchAll($sql, $params);
    }

    public function getLowStock(int $threshold = 5): array {
        return $this->db->fetchAll(
            "SELECT p.*, c.name as category_name FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE p.stock_quantity <= p.min_stock_alert AND p.is_active = 1
             ORDER BY p.stock_quantity ASC"
        );
    }

    public function updateStock(int $id, int $newQuantity, int $userId, string $action, string $notes = ''): void {
        $product = $this->find($id);
        if (!$product) return;
        $change = $newQuantity - $product['stock_quantity'];
        $this->update($id, ['stock_quantity' => $newQuantity]);
        $this->db->query(
            "INSERT INTO inventory_logs (product_id, user_id, action, quantity_change, quantity_before, quantity_after, notes)
             VALUES (?,?,?,?,?,?,?)",
            [$id, $userId, $action, $change, $product['stock_quantity'], $newQuantity, $notes]
        );
    }

    public function incrementViews(int $id): void {
        $this->db->query("UPDATE products SET views_count = views_count + 1 WHERE id = ?", [$id]);
    }

    public function getEffectivePrice(array $product): float {
        return (float)($product['sale_price'] ?? $product['price']);
    }

    public function getAllAdmin(int $page = 1, string $search = ''): array {
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $params = [];
        $where = "1=1";
        if ($search) {
            $where = "(p.name LIKE ? OR p.brand LIKE ? OR p.sku LIKE ?)";
            $like = "%$search%";
            $params = [$like, $like, $like];
        }
        $params[] = $limit;
        $params[] = $offset;
        return $this->db->fetchAll(
            "SELECT p.*, c.name as category_name FROM products p
             JOIN categories c ON p.category_id = c.id
             WHERE $where ORDER BY p.created_at DESC LIMIT ? OFFSET ?",
            $params
        );
    }
}
