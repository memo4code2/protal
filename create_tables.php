<?php
$host = 'localhost';
$db_name = 'support_portal';
$db_user = 'root';
$db_pass = '';

try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `$db_name`");

    $tables = [
        'orders' => "CREATE TABLE IF NOT EXISTS `orders` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `order_number` VARCHAR(100) NOT NULL UNIQUE,
            `customer_email` VARCHAR(255) NOT NULL,
            `status` VARCHAR(50) NOT NULL DEFAULT 'Pending Review',
            `delivery_date` DATE DEFAULT NULL,
            `checked_by_admin` VARCHAR(3) NOT NULL DEFAULT 'NO',
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        'complaints' => "CREATE TABLE IF NOT EXISTS `complaints` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `order_id` INT UNSIGNED NOT NULL,
            `customer_email` VARCHAR(255) NOT NULL,
            `type` VARCHAR(100) NOT NULL,
            `message` TEXT NOT NULL,
            `status` VARCHAR(50) NOT NULL DEFAULT 'open',
            `reply` TEXT DEFAULT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX (`order_id`),
            CONSTRAINT `fk_complaints_order`
                FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`)
                ON DELETE CASCADE
                ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        'admins' => "CREATE TABLE IF NOT EXISTS `admins` (
            `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(255) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
    ];

    foreach ($tables as $tableName => $sql) {
        $pdo->exec($sql);
        echo "Table '$tableName' created or already exists." . PHP_EOL;
    }

    $adminAccounts = [
        ['Admin User', 'admin@test.com', '123456'],
        ['mo', 'mo@test.com', '123'],
    ];

    foreach ($adminAccounts as $account) {
        [$name, $email, $password] = $account;
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('SELECT id FROM admins WHERE email = ?'); 
        $stmt->execute([$email]);
        if ($stmt->rowCount() === 0) {
            $insertAdmin = $pdo->prepare('INSERT INTO admins (name, email, password) VALUES (?, ?, ?)');
            $insertAdmin->execute([$name, $email, $hash]);
        }
    }

    echo "Database '$db_name' is ready." . PHP_EOL;
} catch (PDOException $e) {
    exit('Setup failed: ' . $e->getMessage());
}
