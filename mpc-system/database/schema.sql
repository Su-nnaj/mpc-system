-- ============================================================
-- MPC Trading PC Shop - Budget-Aware E-Commerce System
-- Database Schema
-- ============================================================

CREATE DATABASE IF NOT EXISTS mpc_trading_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mpc_trading_db;

-- ============================================================
-- USERS TABLE
-- ============================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address TEXT,
    role ENUM('customer', 'sales_staff', 'inventory_manager', 'admin') DEFAULT 'customer',
    is_active TINYINT(1) DEFAULT 1,
    profile_image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================
-- CATEGORIES TABLE
-- ============================================================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    icon VARCHAR(50),
    component_type ENUM('cpu','motherboard','ram','gpu','storage','psu','case','cooling','monitor','keyboard','mouse','other') DEFAULT 'other',
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- SUPPLIERS TABLE
-- ============================================================
CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    contact_person VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- PRODUCTS TABLE
-- ============================================================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    supplier_id INT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    sku VARCHAR(100) UNIQUE,
    description TEXT,
    specifications JSON,
    price DECIMAL(12,2) NOT NULL,
    sale_price DECIMAL(12,2) DEFAULT NULL,
    condition_type ENUM('brand_new','used','refurbished') DEFAULT 'brand_new',
    stock_quantity INT DEFAULT 0,
    min_stock_alert INT DEFAULT 5,
    image_main VARCHAR(255),
    images JSON,
    brand VARCHAR(100),
    model VARCHAR(100),
    socket_type VARCHAR(50),
    form_factor VARCHAR(50),
    tdp_watts INT,
    power_required INT,
    is_featured TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    views_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL
);

-- ============================================================
-- COMPATIBILITY RULES TABLE
-- ============================================================
CREATE TABLE compatibility_rules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    component_type_a ENUM('cpu','motherboard','ram','gpu','storage','psu','case','cooling','monitor','keyboard','mouse','other') NOT NULL,
    component_type_b ENUM('cpu','motherboard','ram','gpu','storage','psu','case','cooling','monitor','keyboard','mouse','other') NOT NULL,
    rule_type ENUM('socket','form_factor','ddr_gen','pcie_gen','power','size') NOT NULL,
    attribute_a VARCHAR(100) NOT NULL,
    attribute_b VARCHAR(100) NOT NULL,
    is_compatible TINYINT(1) DEFAULT 1,
    description VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================================
-- PC BUILDS TABLE
-- ============================================================
CREATE TABLE pc_builds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(100) DEFAULT 'My PC Build',
    budget DECIMAL(12,2),
    usage_type ENUM('gaming','office','workstation','streaming','general') DEFAULT 'general',
    is_saved TINYINT(1) DEFAULT 0,
    is_public TINYINT(1) DEFAULT 0,
    total_price DECIMAL(12,2) DEFAULT 0,
    total_tdp INT DEFAULT 0,
    compatibility_status ENUM('compatible','warning','incompatible','unchecked') DEFAULT 'unchecked',
    compatibility_notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================================
-- PC BUILD ITEMS TABLE
-- ============================================================
CREATE TABLE pc_build_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    build_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    price_at_addition DECIMAL(12,2),
    FOREIGN KEY (build_id) REFERENCES pc_builds(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- ============================================================
-- CART TABLE
-- ============================================================
CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    session_id VARCHAR(100),
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- ============================================================
-- ORDERS TABLE
-- ============================================================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    order_number VARCHAR(50) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    delivery_address TEXT NOT NULL,
    barangay VARCHAR(100),
    city VARCHAR(100) DEFAULT 'Dasmariñas',
    province VARCHAR(100) DEFAULT 'Cavite',
    payment_method ENUM('cod','gcash','bank_transfer') DEFAULT 'cod',
    payment_status ENUM('pending','paid','failed','refunded') DEFAULT 'pending',
    order_status ENUM('pending','confirmed','processing','shipped','delivered','cancelled') DEFAULT 'pending',
    subtotal DECIMAL(12,2) NOT NULL,
    delivery_fee DECIMAL(12,2) DEFAULT 0,
    discount_amount DECIMAL(12,2) DEFAULT 0,
    total_amount DECIMAL(12,2) NOT NULL,
    notes TEXT,
    staff_notes TEXT,
    build_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (build_id) REFERENCES pc_builds(id) ON DELETE SET NULL
);

-- ============================================================
-- ORDER ITEMS TABLE
-- ============================================================
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    product_name VARCHAR(255) NOT NULL,
    product_sku VARCHAR(100),
    quantity INT NOT NULL,
    unit_price DECIMAL(12,2) NOT NULL,
    total_price DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

-- ============================================================
-- RECOMMENDATIONS LOG TABLE
-- ============================================================
CREATE TABLE recommendation_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    session_id VARCHAR(100),
    budget DECIMAL(12,2),
    usage_type VARCHAR(50),
    recommended_products JSON,
    was_accepted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================================
-- INVENTORY LOGS TABLE
-- ============================================================
CREATE TABLE inventory_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT,
    action ENUM('add','remove','adjust','sale','return') NOT NULL,
    quantity_change INT NOT NULL,
    quantity_before INT NOT NULL,
    quantity_after INT NOT NULL,
    notes VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================================
-- NOTIFICATIONS TABLE
-- ============================================================
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('order','inventory','price','system','promo') DEFAULT 'system',
    is_read TINYINT(1) DEFAULT 0,
    link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================
-- SEED DATA
-- ============================================================

-- Admin user (password: admin123)
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@mpctrading.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Admin', 'admin'),
('staff1', 'staff@mpctrading.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sales Staff', 'sales_staff'),
('inventory1', 'inventory@mpctrading.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Inventory Manager', 'inventory_manager'),
('customer1', 'customer@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan Dela Cruz', 'customer');

-- Categories
INSERT INTO categories (name, slug, description, icon, component_type) VALUES
('Processors (CPU)', 'cpu', 'Central Processing Units', 'microchip', 'cpu'),
('Motherboards', 'motherboard', 'System motherboards', 'circuit-board', 'motherboard'),
('Memory (RAM)', 'ram', 'Random Access Memory modules', 'memory', 'ram'),
('Graphics Cards (GPU)', 'gpu', 'Video/Graphics Processing Units', 'gpu-card', 'gpu'),
('Storage (SSD/HDD)', 'storage', 'SSDs, HDDs, NVMe drives', 'hdd', 'storage'),
('Power Supplies (PSU)', 'psu', 'Power Supply Units', 'plug', 'psu'),
('Cases', 'case', 'PC Chassis and Cases', 'box', 'case'),
('Cooling', 'cooling', 'CPU Coolers, Case Fans', 'wind', 'cooling'),
('Monitors', 'monitor', 'Display monitors', 'monitor', 'monitor'),
('Peripherals', 'peripherals', 'Keyboards, mice, headsets', 'keyboard', 'other');

-- Suppliers
INSERT INTO suppliers (name, contact_person, email, phone) VALUES
('PC Express Philippines', 'Jose Reyes', 'orders@pcx.com.ph', '02-8441-0888'),
('Villman Computers', 'Maria Santos', 'sales@villman.com', '02-7719-2311'),
('EasyPC', 'Pedro Cruz', 'info@easypc.com.ph', '02-8635-5800'),
('CDR-King', 'Ana Lim', 'support@cdrking.com', '1800-888-0088'),
('Local Supplier', 'Mikael Owner', 'mikael@mpctrading.com', '0917-123-4567');

-- Sample Products
INSERT INTO products (category_id, supplier_id, name, slug, sku, description, specifications, price, condition_type, stock_quantity, brand, model, socket_type, tdp_watts, power_required, is_featured) VALUES
(1, 1, 'Intel Core i3-12100F', 'intel-core-i3-12100f', 'CPU-I3-12100F', '4-core, 8-thread LGA1700 processor, great for budget gaming builds', '{"cores":4,"threads":8,"base_clock":"3.3GHz","boost_clock":"4.3GHz","socket":"LGA1700","tdp":58,"cache":"12MB"}', 4950.00, 'brand_new', 15, 'Intel', 'Core i3-12100F', 'LGA1700', 58, 65, 1),
(1, 1, 'Intel Core i5-12400F', 'intel-core-i5-12400f', 'CPU-I5-12400F', '6-core, 12-thread mid-range powerhouse for gaming and productivity', '{"cores":6,"threads":12,"base_clock":"2.5GHz","boost_clock":"4.4GHz","socket":"LGA1700","tdp":65,"cache":"18MB"}', 8500.00, 'brand_new', 10, 'Intel', 'Core i5-12400F', 'LGA1700', 65, 75, 1),
(1, 1, 'AMD Ryzen 5 5600X', 'amd-ryzen-5-5600x', 'CPU-R5-5600X', '6-core, 12-thread AM4 processor with excellent gaming performance', '{"cores":6,"threads":12,"base_clock":"3.7GHz","boost_clock":"4.6GHz","socket":"AM4","tdp":65,"cache":"35MB"}', 9200.00, 'brand_new', 8, 'AMD', 'Ryzen 5 5600X', 'AM4', 65, 75, 1),
(1, 5, 'Intel Core i3-10100F', 'intel-core-i3-10100f-used', 'CPU-I3-10100F-U', 'Used 4-core processor, good condition for budget office builds', '{"cores":4,"threads":8,"base_clock":"3.6GHz","boost_clock":"4.3GHz","socket":"LGA1200","tdp":65}', 2200.00, 'used', 5, 'Intel', 'Core i3-10100F', 'LGA1200', 65, 65, 0),
(2, 1, 'MSI PRO B660M-A DDR4', 'msi-pro-b660m-a-ddr4', 'MB-B660M-MSI', 'Micro-ATX B660 motherboard for LGA1700, supports DDR4', '{"socket":"LGA1700","form_factor":"mATX","chipset":"B660","ddr_support":"DDR4","max_ram":"64GB","pcie":"PCIe 4.0","m2_slots":2}', 4800.00, 'brand_new', 12, 'MSI', 'PRO B660M-A DDR4', 'LGA1700', 0, 0, 1),
(2, 2, 'ASUS Prime B550M-A', 'asus-prime-b550m-a', 'MB-B550M-ASUS', 'Micro-ATX B550 motherboard for AM4, great value', '{"socket":"AM4","form_factor":"mATX","chipset":"B550","ddr_support":"DDR4","max_ram":"128GB","pcie":"PCIe 4.0","m2_slots":2}', 4200.00, 'brand_new', 10, 'ASUS', 'Prime B550M-A', 'AM4', 0, 0, 1),
(3, 1, 'Kingston Fury Beast 8GB DDR4 3200MHz', 'kingston-fury-8gb-ddr4', 'RAM-KF-8G-3200', 'Single 8GB DDR4 3200MHz module with heat spreader', '{"capacity":"8GB","type":"DDR4","speed":"3200MHz","cas_latency":"CL16","voltage":"1.35V","form_factor":"DIMM"}', 1300.00, 'brand_new', 25, 'Kingston', 'Fury Beast', NULL, 0, 0, 0),
(3, 1, 'Kingston Fury Beast 16GB DDR4 3200MHz (2x8GB)', 'kingston-fury-16gb-ddr4-kit', 'RAM-KF-16G-3200-KIT', 'Dual channel 16GB kit DDR4 3200MHz, ideal for gaming', '{"capacity":"16GB","type":"DDR4","speed":"3200MHz","cas_latency":"CL16","voltage":"1.35V","form_factor":"DIMM","modules":2}', 2500.00, 'brand_new', 20, 'Kingston', 'Fury Beast Kit', NULL, 0, 0, 1),
(4, 1, 'ASUS Dual RX 6600 8GB', 'asus-dual-rx6600-8gb', 'GPU-RX6600-ASUS', '1080p gaming powerhouse, AMD RDNA 2 architecture', '{"vram":"8GB","type":"GDDR6","core_clock":"2044MHz","boost_clock":"2491MHz","tdp":132,"pcie":"PCIe 4.0 x8","ports":"3x DP 1.4, 1x HDMI 2.1"}', 14500.00, 'brand_new', 6, 'ASUS', 'Dual RX 6600', NULL, 132, 160, 1),
(4, 2, 'MSI GeForce RTX 3060 12GB VENTUS', 'msi-rtx3060-12gb', 'GPU-RTX3060-MSI', 'NVIDIA Ampere, 12GB GDDR6, excellent for 1080p/1440p gaming', '{"vram":"12GB","type":"GDDR6","core_clock":"1320MHz","boost_clock":"1777MHz","tdp":170,"pcie":"PCIe 4.0 x16","ports":"3x DP 1.4a, 1x HDMI 2.1"}', 17800.00, 'brand_new', 4, 'MSI', 'GeForce RTX 3060 VENTUS', NULL, 170, 200, 1),
(4, 5, 'GTX 1660 Super 6GB (Used)', 'gtx-1660-super-used', 'GPU-1660S-U', 'Used GTX 1660 Super, good for 1080p gaming on a budget', '{"vram":"6GB","type":"GDDR6","tdp":125,"pcie":"PCIe 3.0 x16"}', 6500.00, 'used', 3, 'Various', 'GTX 1660 Super', NULL, 125, 150, 0),
(5, 1, 'Crucial P3 500GB NVMe SSD', 'crucial-p3-500gb-nvme', 'SSD-P3-500-CRU', 'PCIe Gen3 NVMe SSD, fast boot and load times', '{"capacity":"500GB","type":"NVMe","interface":"PCIe 3.0 x4","read_speed":"3500MB/s","write_speed":"1900MB/s","form_factor":"M.2 2280"}', 1800.00, 'brand_new', 20, 'Crucial', 'P3 500GB', NULL, 0, 0, 1),
(5, 1, 'Seagate Barracuda 1TB HDD', 'seagate-barracuda-1tb', 'HDD-SG-1TB', '7200RPM 3.5" SATA hard drive for mass storage', '{"capacity":"1TB","type":"HDD","rpm":7200,"interface":"SATA 6Gb/s","form_factor":"3.5 inch"}', 1650.00, 'brand_new', 18, 'Seagate', 'Barracuda 1TB', NULL, 0, 0, 0),
(6, 1, 'Seasonic FOCUS GX 650W 80+ Gold', 'seasonic-focus-gx-650w', 'PSU-SN-650G', 'Fully modular 650W 80+ Gold PSU, 10-year warranty', '{"wattage":650,"efficiency":"80+ Gold","modular":"Fully Modular","protection":"OVP,UVP,SCP,OCP,OTP"}', 4500.00, 'brand_new', 8, 'Seasonic', 'FOCUS GX 650W', NULL, 0, 0, 1),
(6, 2, 'Corsair CV550 550W 80+ Bronze', 'corsair-cv550-550w', 'PSU-CR-550B', 'Non-modular 550W 80+ Bronze for budget builds', '{"wattage":550,"efficiency":"80+ Bronze","modular":"Non-modular"}', 2200.00, 'brand_new', 15, 'Corsair', 'CV550 550W', NULL, 0, 0, 0),
(7, 3, 'DeepCool MATREXX 40 mATX Case', 'deepcool-matrexx40', 'CASE-DP-M40', 'Compact mATX case with tempered glass side panel', '{"form_factor":"mATX","color":"Black","tempered_glass":"Side","drive_bays":"2x 3.5, 2x 2.5","fan_support":"2x 120mm front, 1x 120mm rear"}', 1800.00, 'brand_new', 12, 'DeepCool', 'MATREXX 40', NULL, 0, 0, 0),
(7, 1, 'Lian Li LANCOOL 205 ATX Case', 'lian-li-lancool205', 'CASE-LL-205', 'ATX mid-tower with excellent airflow and tempered glass', '{"form_factor":"ATX","color":"Black","tempered_glass":"Both sides","fan_support":"2x 140mm front, 1x 120mm rear"}', 3200.00, 'brand_new', 7, 'Lian Li', 'LANCOOL 205', NULL, 0, 0, 1),
(8, 1, 'DeepCool AK400 CPU Cooler', 'deepcool-ak400', 'COOL-DP-AK400', 'High-performance tower cooler, 250W TDP support', '{"type":"Tower","fan_size":"120mm","tdp_support":260,"height":"155mm","socket_support":"LGA1700,LGA1200,AM4,AM5"}', 1600.00, 'brand_new', 14, 'DeepCool', 'AK400', NULL, 0, 0, 1),
(9, 2, 'LG 24MR400-B 24" FHD IPS Monitor', 'lg-24mr400-24fhd', 'MON-LG-24MR400', '24 inch 100Hz IPS panel, great for office and gaming', '{"size":"24 inch","resolution":"1920x1080","panel":"IPS","refresh_rate":"100Hz","response_time":"1ms","ports":"HDMI, DP"}', 7500.00, 'brand_new', 9, 'LG', '24MR400-B', NULL, 0, 0, 1),
(10, 4, 'Tecware Phantom L Keyboard + Mouse Combo', 'tecware-phantom-combo', 'PERI-TW-COMBO', 'Mechanical keyboard with RGB and gaming mouse bundle', '{"keyboard_type":"Mechanical","switches":"Outemu Red","mouse_dpi":"3200 DPI","rgb":"Yes"}', 1500.00, 'brand_new', 20, 'Tecware', 'Phantom L Combo', NULL, 0, 0, 0);

-- Compatibility Rules
INSERT INTO compatibility_rules (component_type_a, component_type_b, rule_type, attribute_a, attribute_b, is_compatible, description) VALUES
('cpu', 'motherboard', 'socket', 'LGA1700', 'LGA1700', 1, 'Intel 12th/13th Gen CPUs with LGA1700 motherboards'),
('cpu', 'motherboard', 'socket', 'LGA1200', 'LGA1200', 1, 'Intel 10th/11th Gen CPUs with LGA1200 motherboards'),
('cpu', 'motherboard', 'socket', 'AM4', 'AM4', 1, 'AMD Ryzen 1000-5000 series with AM4 motherboards'),
('cpu', 'motherboard', 'socket', 'LGA1700', 'AM4', 0, 'Intel LGA1700 CPU incompatible with AM4 motherboard'),
('cpu', 'motherboard', 'socket', 'AM4', 'LGA1700', 0, 'AMD AM4 CPU incompatible with LGA1700 motherboard'),
('cpu', 'motherboard', 'socket', 'LGA1200', 'LGA1700', 0, 'Intel LGA1200 CPU incompatible with LGA1700 motherboard');

-- Indexes for performance
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_active ON products(is_active);
CREATE INDEX idx_products_condition ON products(condition_type);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(order_status);
CREATE INDEX idx_cart_user ON cart(user_id);
CREATE INDEX idx_cart_session ON cart(session_id);
