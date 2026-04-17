<?php
$host = 'localhost';
$db_name = 'support_portal';
$db_user = 'root';
$db_pass = '';

$conn = new mysqli($host, $db_user, $db_pass);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');
$conn->query("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db($db_name);

$createOrders = "CREATE TABLE IF NOT EXISTS `orders` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_number` VARCHAR(100) NOT NULL UNIQUE,
    `customer_email` VARCHAR(255) NOT NULL,
    `status` VARCHAR(50) NOT NULL DEFAULT 'Pending Review',
    `delivery_date` DATE DEFAULT NULL,
    `checked_by_admin` BOOLEAN NOT NULL DEFAULT FALSE,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_customer_email (customer_email),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$createComplaints = "CREATE TABLE IF NOT EXISTS `complaints` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT UNSIGNED NOT NULL,
    `customer_email` VARCHAR(255) NOT NULL,
    `type` VARCHAR(100) NOT NULL,
    `message` LONGTEXT NOT NULL,
    `status` VARCHAR(50) NOT NULL DEFAULT 'Open',
    `reply` LONGTEXT DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_order_id (order_id),
    INDEX idx_customer_email (customer_email),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at),
    CONSTRAINT `fk_complaints_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$createAdmins = "CREATE TABLE IF NOT EXISTS `admins` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$conn->query($createOrders);
$conn->query($createComplaints);
$conn->query($createAdmins);
$conn->query("ALTER TABLE `admins` ADD COLUMN IF NOT EXISTS `name` VARCHAR(100) NOT NULL DEFAULT 'Admin'");
$conn->query("ALTER TABLE `admins` ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
$conn->query("ALTER TABLE `complaints` ADD COLUMN IF NOT EXISTS `customer_email` VARCHAR(255) NOT NULL AFTER `order_id`");
$conn->query("ALTER TABLE `orders` MODIFY `checked_by_admin` BOOLEAN NOT NULL DEFAULT FALSE");

$adminAccounts = [
    ['Admin User', 'admin@test.com', '123456'],
    ['mo', 'mo@test.com', '123'],
];

foreach ($adminAccounts as $account) {
    [$adminName, $adminEmail, $adminPassword] = $account;
    $adminHash = password_hash($adminPassword, PASSWORD_DEFAULT);
    $adminStmt = $conn->prepare('SELECT id FROM admins WHERE email = ?');
    $adminStmt->bind_param('s', $adminEmail);
    $adminStmt->execute();
    $adminStmt->store_result();
    if ($adminStmt->num_rows === 0) {
        $insertAdmin = $conn->prepare('INSERT INTO admins (name, email, password) VALUES (?, ?, ?)');
        $insertAdmin->bind_param('sss', $adminName, $adminEmail, $adminHash);
        $insertAdmin->execute();
        $insertAdmin->close();
    }
    $adminStmt->close();
}

$orderCount = 0;
$result = $conn->query('SELECT COUNT(*) AS total FROM orders');
if ($result) {
    $row = $result->fetch_assoc();
    $orderCount = (int) $row['total'];
    $result->free();
}

if ($orderCount === 0) {
    $sampleOrders = [
        ['ORD-1001', 'customer1@test.com', 'Verified', '2026-05-02'],
        ['ORD-1002', 'customer2@test.com', 'Pending Review', '2026-05-05'],
        ['ORD-1003', 'customer3@test.com', 'Checked', '2026-04-20'],
    ];

    $insertOrder = $conn->prepare('INSERT INTO orders (order_number, customer_email, status, delivery_date) VALUES (?, ?, ?, ?)');
    foreach ($sampleOrders as $order) {
        $insertOrder->bind_param('ssss', $order[0], $order[1], $order[2], $order[3]);
        $insertOrder->execute();
    }
    $insertOrder->close();
}
