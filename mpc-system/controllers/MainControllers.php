<?php
// ============================================================
// Home Controller
// ============================================================
class HomeController extends Controller {
    public function index(): void {
        $productModel  = new ProductModel();
        $featured      = $productModel->getFeatured(8);
        $categories    = (new CategoryModel())->getActive();
        $this->view('home/index', [
            'title'      => 'MPC Trading — Budget-Aware PC Shop',
            'featured'   => $featured,
            'categories' => $categories,
            'flash'      => $this->getFlash(),
        ]);
    }
}

// ============================================================
// Category Model (simple)
// ============================================================
class CategoryModel extends Model {
    protected string $table = 'categories';
    public function getActive(): array {
        return $this->db->fetchAll("SELECT * FROM categories WHERE is_active = 1 ORDER BY name");
    }
    public function getBySlug(string $slug): ?array {
        return $this->db->fetchOne("SELECT * FROM categories WHERE slug = ?", [$slug]);
    }
}

// ============================================================
// Product Controller
// ============================================================
class ProductController extends Controller {
    private ProductModel $model;

    public function __construct() { $this->model = new ProductModel(); }

    public function index(): void {
        $query    = $this->get('q', '');
        $filters  = [
            'category'  => $this->get('category', ''),
            'condition' => $this->get('condition', ''),
            'min_price' => $this->get('min_price', 0),
            'max_price' => $this->get('max_price', 0),
            'sort'      => $this->get('sort', ''),
        ];
        $page     = max(1, (int)$this->get('page', 1));
        $products = $this->model->search($query, $filters, $page);
        $total    = $this->model->searchCount($query, $filters);
        $pages    = ceil($total / ITEMS_PER_PAGE);

        $this->view('products/index', [
            'title'      => 'Shop PC Components',
            'products'   => $products,
            'categories' => (new CategoryModel())->getActive(),
            'query'      => $query,
            'filters'    => $filters,
            'page'       => $page,
            'pages'      => $pages,
            'total'      => $total,
        ]);
    }

    public function show(string $slug): void {
        $product = $this->model->getBySlug($slug);
        if (!$product) { http_response_code(404); $this->view('home/404', ['title' => '404 Not Found']); return; }
        $this->model->incrementViews($product['id']);
        $related = $this->model->getByCategory($product['category_id'], 4);
        $this->view('products/show', [
            'title'   => $product['name'] . ' — MPC Trading',
            'product' => $product,
            'related' => $related,
            'specs'   => json_decode($product['specifications'] ?? '{}', true),
        ]);
    }
}

// ============================================================
// Cart Controller
// ============================================================
class CartController extends Controller {
    private CartModel $model;

    public function __construct() { $this->model = new CartModel(); }

    private function uid(): int { return (int)($_SESSION['user_id'] ?? 0); }
    private function sid(): string { return session_id(); }

    public function index(): void {
        $items = $this->model->getCart($this->uid(), $this->sid());
        $total = $this->model->getTotal($items);
        $deliveryFee = $total >= FREE_DELIVERY_THRESHOLD ? 0 : DELIVERY_FEE;
        $this->view('cart/index', [
            'title'       => 'Shopping Cart',
            'items'       => $items,
            'total'       => $total,
            'deliveryFee' => $deliveryFee,
            'grandTotal'  => $total + $deliveryFee,
        ]);
    }

    public function add(): void {
        $productId = (int)$this->post('product_id');
        $qty       = max(1, (int)$this->post('quantity', 1));
        if (!$productId) { $this->json(['error' => 'Invalid product'], 400); return; }
        $product = (new ProductModel())->find($productId);
        if (!$product || $product['stock_quantity'] < $qty) {
            $this->json(['error' => 'Product not available'], 400); return;
        }
        $this->model->addItem($productId, $qty, $this->uid(), $this->sid());
        $cartCount = count($this->model->getCart($this->uid(), $this->sid()));
        $this->json(['success' => true, 'cart_count' => $cartCount]);
    }

    public function update(): void {
        $cartId = (int)$this->post('cart_id');
        $qty    = max(1, (int)$this->post('quantity', 1));
        $this->model->updateItem($cartId, $qty);
        $this->redirect('/cart');
    }

    public function remove(): void {
        $cartId = (int)$this->post('cart_id');
        $this->model->removeItem($cartId);
        $this->redirect('/cart');
    }

    public function count(): void {
        $count = count($this->model->getCart($this->uid(), $this->sid()));
        $this->json(['count' => $count]);
    }
}

// ============================================================
// Order Controller
// ============================================================
class OrderController extends Controller {
    private OrderModel $orderModel;
    private CartModel  $cartModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->cartModel  = new CartModel();
    }

    public function checkout(): void {
        $this->requireLogin();
        $uid   = (int)$_SESSION['user_id'];
        $items = $this->cartModel->getCart($uid);
        if (empty($items)) { $this->redirect('/cart'); return; }
        $total       = $this->cartModel->getTotal($items);
        $deliveryFee = $total >= FREE_DELIVERY_THRESHOLD ? 0 : DELIVERY_FEE;
        $user        = (new UserModel())->find($uid);
        $this->view('orders/checkout', [
            'title'       => 'Checkout',
            'items'       => $items,
            'total'       => $total,
            'deliveryFee' => $deliveryFee,
            'grandTotal'  => $total + $deliveryFee,
            'user'        => $user,
        ]);
    }

    public function placeOrder(): void {
        $this->requireLogin();
        $uid   = (int)$_SESSION['user_id'];
        $items = $this->cartModel->getCart($uid);
        if (empty($items)) { $this->redirect('/cart'); return; }

        $subtotal    = $this->cartModel->getTotal($items);
        $deliveryFee = $subtotal >= FREE_DELIVERY_THRESHOLD ? 0 : DELIVERY_FEE;
        $grandTotal  = $subtotal + $deliveryFee;

        $orderData = [
            'user_id'          => $uid,
            'order_number'     => $this->orderModel->generateOrderNumber(),
            'full_name'        => $this->sanitize($this->post('full_name')),
            'email'            => $this->sanitize($this->post('email')),
            'phone'            => $this->sanitize($this->post('phone')),
            'delivery_address' => $this->sanitize($this->post('address')),
            'barangay'         => $this->sanitize($this->post('barangay')),
            'city'             => $this->sanitize($this->post('city', 'Dasmariñas')),
            'province'         => 'Cavite',
            'payment_method'   => 'cod',
            'subtotal'         => $subtotal,
            'delivery_fee'     => $deliveryFee,
            'total_amount'     => $grandTotal,
            'notes'            => $this->sanitize($this->post('notes', '')),
        ];

        $orderItems = array_map(fn($i) => [
            'product_id'   => $i['product_id'],
            'product_name' => $i['name'],
            'product_sku'  => '',
            'quantity'     => $i['quantity'],
            'unit_price'   => $i['effective_price'],
            'total_price'  => $i['effective_price'] * $i['quantity'],
        ], $items);

        $orderId = $this->orderModel->createOrder($orderData, $orderItems);
        $this->cartModel->clearCart($uid);

        $this->redirect('/orders/success/' . $orderData['order_number']);
    }

    public function success(string $orderNumber): void {
        $order = $this->orderModel->getByOrderNumber($orderNumber);
        $this->view('orders/success', ['title' => 'Order Placed!', 'order' => $order]);
    }

    public function myOrders(): void {
        $this->requireLogin();
        $uid    = (int)$_SESSION['user_id'];
        $orders = $this->orderModel->getUserOrders($uid);
        $this->view('orders/my-orders', ['title' => 'My Orders', 'orders' => $orders]);
    }

    public function detail(int $id): void {
        $this->requireLogin();
        $order = $this->orderModel->getOrderWithItems($id);
        $this->view('orders/detail', ['title' => 'Order #' . $order['order_number'], 'order' => $order]);
    }
}

// ============================================================
// Recommendation Controller
// ============================================================
class RecommendationController extends Controller {
    private RecommendationModel $model;

    public function __construct() { $this->model = new RecommendationModel(); }

    public function index(): void {
        $this->view('recommendation/index', ['title' => 'PC Build Recommender']);
    }

    public function generate(): void {
        $budget    = (float)$this->post('budget', 0);
        $usage     = $this->post('usage_type', 'general');
        $useUsed   = (bool)$this->post('include_used', false);

        if ($budget < 5000) { $this->json(['error' => 'Minimum budget is ₱5,000'], 400); return; }

        $result = $this->model->generateRecommendation($budget, $usage, $useUsed);
        $this->model->logRecommendation(array_merge($result, [
            'user_id'    => $_SESSION['user_id'] ?? null,
            'session_id' => session_id(),
        ]));
        $this->json($result);
    }

    public function addBuildToCart(): void {
        $this->requireLogin();
        $productIds = $_POST['product_ids'] ?? [];
        if (empty($productIds)) { $this->json(['error' => 'No products'], 400); return; }
        $cartModel = new CartModel();
        $uid = (int)$_SESSION['user_id'];
        foreach ($productIds as $pid) {
            $cartModel->addItem((int)$pid, 1, $uid);
        }
        $this->json(['success' => true, 'redirect' => APP_URL . '/cart']);
    }
}
?>