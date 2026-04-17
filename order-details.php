<?php
$message = '';
$order = null;

// Function to display status with user-friendly label and color
function getStatusDisplay($status) {
    $statusMap = [
        'Pending Review' => ['label' => 'Waiting', 'color' => 'warning'],
        'Verified' => ['label' => 'Verified', 'color' => 'info'],
        'Checked' => ['label' => 'Checked', 'color' => 'success'],
    ];
    
    if (isset($statusMap[$status])) {
        return $statusMap[$status];
    }
    
    return ['label' => $status, 'color' => 'secondary'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'config/db.php';
    $order_number = trim($_POST['order_number'] ?? '');
    $customer_email = trim($_POST['customer_email'] ?? '');

    if ($order_number && $customer_email) {
        $stmt = $conn->prepare('SELECT id, order_number, customer_email, status, delivery_date FROM orders WHERE order_number = ? LIMIT 1');
        $stmt->bind_param('s', $order_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();

        if (!$order) {
            $deliveryDate = date('Y-m-d', strtotime('+5 days'));
            $status = 'Pending Review';
            $insert = $conn->prepare('INSERT INTO orders (order_number, customer_email, status, delivery_date) VALUES (?, ?, ?, ?)');
            $insert->bind_param('ssss', $order_number, $customer_email, $status, $deliveryDate);
            $insert->execute();
            $orderId = $insert->insert_id;
            $insert->close();

            $stmt = $conn->prepare('SELECT id, order_number, customer_email, status, delivery_date FROM orders WHERE id = ? LIMIT 1');
            $stmt->bind_param('i', $orderId);
            $stmt->execute();
            $result = $stmt->get_result();
            $order = $result->fetch_assoc();
            $stmt->close();
            $message = '<div class="alert alert-success">Order not found. A new order has been created and displayed.</div>';
        }
    } else {
        $message = '<div class="alert alert-danger">Please provide both order number and email.</div>';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Track Order - Support Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="header bg-white shadow-sm">
        <div class="container d-flex align-items-center justify-content-between py-3">
            <a class="navbar-brand fw-bold text-primary" href="index.php">Support Portal</a>
            <nav>
                <a href="index.php" class="nav-link d-inline-block">Home</a>
                <a href="order-details.php" class="nav-link d-inline-block">Track Order</a>
                <a href="complaint.php" class="nav-link d-inline-block">Submit Complaint</a>
                <a href="login.php" class="nav-link d-inline-block btn btn-primary text-white ms-2">Admin Login</a>
            </nav>
        </div>
    </header>
    <main class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h1 class="fw-bold text-primary">Track Your Order</h1>
                <p class="text-secondary">Enter your order number and email to view the current status.</p>
            </div>
            <?php echo $message; ?>
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title">Order Search</h5>
                            <form method="post" action="order-details.php">
                                <div class="mb-3">
                                    <label class="form-label">Order Number</label>
                                    <input type="text" name="order_number" class="form-control" placeholder="1 or 100000" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="customer_email" class="form-control" placeholder="customer@example.com" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Search Order</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <?php if ($order): ?>
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Order Details</h5>
                                <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
                                <p><strong>Customer Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
                                <p>
                                    <strong>Status:</strong> 
                                    <?php 
                                    $statusDisplay = getStatusDisplay($order['status']);
                                    echo '<span class="badge bg-' . htmlspecialchars($statusDisplay['color']) . '">' . htmlspecialchars($statusDisplay['label']) . '</span>';
                                    ?>
                                </p>
                                <p><strong>Delivery Date:</strong> <?php echo htmlspecialchars($order['delivery_date'] ?: 'Pending'); ?></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card shadow-sm border-0 text-center py-5">
                            <div class="card-body">
                                <p class="text-secondary">Use the form to search for your order status.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <footer class="footer py-4 bg-primary text-white">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="mb-2 mb-md-0">© 2026 Support Portal.</p>
            <div>
                <a href="index.php" class="text-white text-decoration-none me-3">Home</a>
                <a href="complaint.php" class="text-white text-decoration-none">Submit Complaint</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
