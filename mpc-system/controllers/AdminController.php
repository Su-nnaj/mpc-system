<?php
// ============================================================
// Admin Controller
// ============================================================
class AdminController extends Controller {
    public function __construct() { $this->requireRole('admin'); }

    public function dashboard(): void {
        $orderStats  = (new OrderModel())->getStats();
        $productModel = new ProductModel();
        $userModel   = new UserModel();
        $this->view('admin/dashboard', [
            'title'      => 'Admin Dashboard',
            'stats'      => [
                'orders'    => $orderStats,
                'products'  => $productModel->count(),
                'users'     => $userModel->count(),
                'low_stock' => count($productModel->getLowStock()),
            ],
            'recentOrders' => (new OrderModel())->getAllAdmin(1),
            'lowStock'     => $productModel->getLowStock(),
        ]);
    }

    public function products(): void {
        $page     = max(1, (int)$this->get('page', 1));
        $search   = $this->get('q', '');
        $products = (new ProductModel())->getAllAdmin($page, $search);
        $categories = (new CategoryModel())->getActive();
        $suppliers  = $this->db_fetchAll("SELECT * FROM suppliers WHERE is_active = 1");
        $this->view('admin/products', [
            'title'      => 'Manage Products',
            'products'   => $products,
            'categories' => $categories,
            'suppliers'  => $suppliers,
            'search'     => $search,
            'page'       => $page,
            'flash'      => $this->getFlash(),
        ]);
    }

    private function db_fetchAll(string $sql): array {
        return Database::getInstance()->fetchAll($sql);
    }

    public function saveProduct(): void {
        $data = [
            'category_id'    => (int)$this->post('category_id'),
            'supplier_id'    => (int)$this->post('supplier_id') ?: null,
            'name'           => $this->sanitize($this->post('name')),
            'description'    => $this->sanitize($this->post('description', '')),
            'price'          => (float)$this->post('price'),
            'sale_price'     => $this->post('sale_price') ? (float)$this->post('sale_price') : null,
            'condition_type' => $this->post('condition_type', 'brand_new'),
            'stock_quantity' => (int)$this->post('stock_quantity', 0),
            'min_stock_alert'=> (int)$this->post('min_stock_alert', 5),
            'brand'          => $this->sanitize($this->post('brand', '')),
            'model'          => $this->sanitize($this->post('model', '')),
            'socket_type'    => $this->sanitize($this->post('socket_type', '')),
            'is_featured'    => (int)$this->post('is_featured', 0),
            'is_active'      => (int)$this->post('is_active', 1),
        ];

        $productModel = new ProductModel();
        $id = (int)$this->post('id');

        // Handle slug
        if (!$id) {
            $data['slug'] = $this->createSlug($data['name']);
            $data['sku']  = 'MPC-' . strtoupper(substr(uniqid(), -8));
            $productModel->create($data);
            $this->setFlash('success', 'Product created successfully!');
        } else {
            $productModel->update($id, $data);
            $this->setFlash('success', 'Product updated successfully!');
        }
        $this->redirect('/admin/products');
    }

    public function deleteProduct(): void {
        $id = (int)$this->post('id');
        (new ProductModel())->update($id, ['is_active' => 0]);
        $this->setFlash('success', 'Product deactivated.');
        $this->redirect('/admin/products');
    }

    public function orders(): void {
        $page   = max(1, (int)$this->get('page', 1));
        $status = $this->get('status', '');
        $orders = (new OrderModel())->getAllAdmin($page, $status);
        $this->view('admin/orders', [
            'title'   => 'Manage Orders',
            'orders'  => $orders,
            'status'  => $status,
            'page'    => $page,
            'flash'   => $this->getFlash(),
        ]);
    }

    public function updateOrder(): void {
        $id     = (int)$this->post('order_id');
        $status = $this->post('order_status');
        $notes  = $this->sanitize($this->post('staff_notes', ''));
        (new OrderModel())->updateStatus($id, $status, $notes);
        $this->setFlash('success', 'Order updated.');
        $this->redirect('/admin/orders');
    }

    public function users(): void {
        $users = (new UserModel())->getAll();
        $this->view('admin/users', ['title' => 'Manage Users', 'users' => $users]);
    }

    public function toggleUser(): void {
        $id   = (int)$this->post('user_id');
        $user = (new UserModel())->find($id);
        if ($user) (new UserModel())->update($id, ['is_active' => $user['is_active'] ? 0 : 1]);
        $this->redirect('/admin/users');
    }

    public function reports(): void {
        $db = Database::getInstance();
        $salesByMonth = $db->fetchAll(
            "SELECT DATE_FORMAT(created_at,'%Y-%m') as month, SUM(total_amount) as revenue, COUNT(*) as orders
             FROM orders WHERE order_status != 'cancelled' GROUP BY month ORDER BY month DESC LIMIT 12"
        );
        $topProducts = $db->fetchAll(
            "SELECT p.name, SUM(oi.quantity) as sold, SUM(oi.total_price) as revenue
             FROM order_items oi JOIN products p ON oi.product_id = p.id
             GROUP BY oi.product_id ORDER BY sold DESC LIMIT 10"
        );
        $this->view('admin/reports', [
            'title'        => 'Sales Reports',
            'salesByMonth' => $salesByMonth,
            'topProducts'  => $topProducts,
        ]);
    }

    private function createSlug(string $name): string {
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
        $slug = trim($slug, '-');
        // Check uniqueness
        $db = Database::getInstance();
        $base = $slug;
        $i = 1;
        while ($db->fetchOne("SELECT id FROM products WHERE slug = ?", [$slug])) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}

// ============================================================
// Staff Controller
// ============================================================
class StaffController extends Controller {
    public function __construct() { $this->requireRole('admin', 'sales_staff'); }

    public function dashboard(): void {
        $orderModel = new OrderModel();
        $orders     = $orderModel->getAllAdmin(1, 'pending');
        $this->view('staff/dashboard', [
            'title'   => 'Staff Dashboard',
            'orders'  => $orders,
            'stats'   => $orderModel->getStats(),
            'flash'   => $this->getFlash(),
        ]);
    }

    public function orders(): void {
        $status = $this->get('status', '');
        $orders = (new OrderModel())->getAllAdmin(1, $status);
        $this->view('staff/orders', [
            'title'  => 'Manage Orders',
            'orders' => $orders,
            'status' => $status,
            'flash'  => $this->getFlash(),
        ]);
    }

    public function updateOrder(): void {
        $id     = (int)$this->post('order_id');
        $status = $this->post('order_status');
        $notes  = $this->sanitize($this->post('staff_notes', ''));
        (new OrderModel())->updateStatus($id, $status, $notes);
        $this->setFlash('success', 'Order status updated.');
        $this->redirect('/staff/orders');
    }
}

// ============================================================
// Inventory Controller
// ============================================================
class InventoryController extends Controller {
    public function __construct() { $this->requireRole('admin', 'inventory_manager'); }

    public function dashboard(): void {
        $productModel = new ProductModel();
        $this->view('inventory/dashboard', [
            'title'     => 'Inventory Dashboard',
            'lowStock'  => $productModel->getLowStock(),
            'flash'     => $this->getFlash(),
        ]);
    }

    public function products(): void {
        $page     = max(1, (int)$this->get('page', 1));
        $products = (new ProductModel())->getAllAdmin($page, $this->get('q', ''));
        $this->view('inventory/products', [
            'title'    => 'Inventory Management',
            'products' => $products,
            'page'     => $page,
            'flash'    => $this->getFlash(),
        ]);
    }

    public function updateStock(): void {
        $productId  = (int)$this->post('product_id');
        $newQty     = (int)$this->post('quantity');
        $action     = $this->post('action', 'adjust');
        $notes      = $this->sanitize($this->post('notes', ''));
        $userId     = (int)$_SESSION['user_id'];
        (new ProductModel())->updateStock($productId, $newQty, $userId, $action, $notes);
        $this->setFlash('success', 'Stock updated successfully.');
        $this->redirect('/inventory/products');
    }
}
