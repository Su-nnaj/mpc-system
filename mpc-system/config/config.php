<?php
// ============================================================
// Database Configuration
// ============================================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');          // Change to your DB username
define('DB_PASS', '');              // Change to your DB password
define('DB_NAME', 'mpc_trading_db');
define('DB_CHARSET', 'utf8mb4');

// Application Settings
define('APP_NAME', 'MPC Trading PC Shop');
define('APP_URL', 'http://localhost/mpc-system');
define('APP_VERSION', '1.0.0');

// Session Settings
define('SESSION_LIFETIME', 3600 * 24); // 24 hours

// Upload Settings
define('UPLOAD_PATH', __DIR__ . '/../public/images/uploads/');
define('UPLOAD_URL', APP_URL . '/public/images/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

// Pagination
define('ITEMS_PER_PAGE', 12);

// Delivery
define('DELIVERY_FEE', 150);
define('FREE_DELIVERY_THRESHOLD', 5000);
