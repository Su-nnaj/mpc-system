<?php
class CartModel extends Model {
    protected string $table = 'cart';

    public function getCart(int $userId = 0, string $sessionId = ''): array {
        if ($userId) {
            return $this->db->fetchAll(
                "SELECT c.*, p.name, p.slug, p.image_main, p.stock_quantity, p.condition_type,
                         COALESCE(p.sale_price, p.price) as effective_price
                 FROM cart c JOIN products p ON c.product_id = p.id
                 WHERE c.user_id = ? ORDER BY c.created_at DESC",
                [$userId]
            );
        }
        return $this->db->fetchAll(
            "SELECT c.*, p.name, p.slug, p.image_main, p.stock_quantity, p.condition_type,
                     COALESCE(p.sale_price, p.price) as effective_price
             FROM cart c JOIN products p ON c.product_id = p.id
             WHERE c.session_id = ? ORDER BY c.created_at DESC",
            [$sessionId]
        );
    }

    public function addItem(int $productId, int $qty, int $userId = 0, string $sessionId = ''): void {
        $condition = $userId ? "user_id = ?" : "session_id = ?";
        $condVal = $userId ?: $sessionId;
        $existing = $this->db->fetchOne(
            "SELECT id, quantity FROM cart WHERE product_id = ? AND $condition",
            [$productId, $condVal]
        );
        if ($existing) {
            $this->db->query("UPDATE cart SET quantity = quantity + ? WHERE id = ?", [$qty, $existing['id']]);
        } else {
            $data = ['product_id' => $productId, 'quantity' => $qty];
            if ($userId) $data['user_id'] = $userId;
            else $data['session_id'] = $sessionId;
            $this->create($data);
        }
    }

    public function updateItem(int $cartId, int $qty): void {
        $this->db->query("UPDATE cart SET quantity = ? WHERE id = ?", [$qty, $cartId]);
    }

    public function removeItem(int $cartId): void {
        $this->db->query("DELETE FROM cart WHERE id = ?", [$cartId]);
    }

    public function clearCart(int $userId = 0, string $sessionId = ''): void {
        if ($userId) $this->db->query("DELETE FROM cart WHERE user_id = ?", [$userId]);
        else $this->db->query("DELETE FROM cart WHERE session_id = ?", [$sessionId]);
    }

    public function getTotal(array $cartItems): float {
        return array_sum(array_map(fn($i) => $i['effective_price'] * $i['quantity'], $cartItems));
    }

    public function mergeSessionCart(string $sessionId, int $userId): void {
        $items = $this->db->fetchAll("SELECT * FROM cart WHERE session_id = ?", [$sessionId]);
        foreach ($items as $item) {
            $this->addItem($item['product_id'], $item['quantity'], $userId);
        }
        $this->db->query("DELETE FROM cart WHERE session_id = ?", [$sessionId]);
    }
}

class OrderModel extends Model {
    protected string $table = 'orders';

    public function generateOrderNumber(): string {
        return 'MPC-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function createOrder(array $orderData, array $items): int {
        $this->db->beginTransaction();
        try {
            $orderId = $this->create($orderData);
            foreach ($items as $item) {
                $item['order_id'] = $orderId;
                $this->db->query(
                    "INSERT INTO order_items (order_id, product_id, product_name, product_sku, quantity, unit_price, total_price)
                     VALUES (?,?,?,?,?,?,?)",
                    [$orderId, $item['product_id'], $item['product_name'], $item['product_sku'] ?? '',
                     $item['quantity'], $item['unit_price'], $item['total_price']]
                );
                // Decrease stock
                $this->db->query(
                    "UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?",
                    [$item['quantity'], $item['product_id']]
                );
            }
            $this->db->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getOrderWithItems(int $orderId): ?array {
        $order = $this->find($orderId);
        if (!$order) return null;
        $order['items'] = $this->db->fetchAll(
            "SELECT oi.*, p.image_main FROM order_items oi LEFT JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?",
            [$orderId]
        );
        return $order;
    }

    public function getUserOrders(int $userId, int $page = 1): array {
        $offset = ($page - 1) * ITEMS_PER_PAGE;
        return $this->db->fetchAll(
            "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [$userId, ITEMS_PER_PAGE, $offset]
        );
    }

    public function getByOrderNumber(string $orderNumber): ?array {
        $order = $this->db->fetchOne("SELECT * FROM orders WHERE order_number = ?", [$orderNumber]);
        if (!$order) return null;
        $order['items'] = $this->db->fetchAll(
            "SELECT * FROM order_items WHERE order_id = ?", [$order['id']]
        );
        return $order;
    }

    public function updateStatus(int $orderId, string $status, string $staffNotes = ''): void {
        $data = ['order_status' => $status];
        if ($staffNotes) $data['staff_notes'] = $staffNotes;
        $this->update($orderId, $data);
    }

    public function getAllAdmin(int $page = 1, string $status = ''): array {
        $limit = 20;
        $offset = ($page - 1) * $limit;
        $where = $status ? "WHERE o.order_status = ?" : "WHERE 1=1";
        $params = $status ? [$status, $limit, $offset] : [$limit, $offset];
        return $this->db->fetchAll(
            "SELECT o.*, u.full_name as customer_name FROM orders o
             LEFT JOIN users u ON o.user_id = u.id
             $where ORDER BY o.created_at DESC LIMIT ? OFFSET ?",
            $params
        );
    }

    public function getStats(): array {
        return [
            'total_orders'    => $this->db->fetchOne("SELECT COUNT(*) as c FROM orders")['c'],
            'pending_orders'  => $this->db->fetchOne("SELECT COUNT(*) as c FROM orders WHERE order_status='pending'")['c'],
            'today_revenue'   => $this->db->fetchOne("SELECT COALESCE(SUM(total_amount),0) as r FROM orders WHERE DATE(created_at)=CURDATE() AND order_status!='cancelled'")['r'],
            'month_revenue'   => $this->db->fetchOne("SELECT COALESCE(SUM(total_amount),0) as r FROM orders WHERE MONTH(created_at)=MONTH(CURDATE()) AND order_status!='cancelled'")['r'],
        ];
    }
}
