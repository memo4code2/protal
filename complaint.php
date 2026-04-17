<?php
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'config/db.php';
    $order_number = trim($_POST['order_id'] ?? '');
    $customer_email = trim($_POST['customer_email'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $messageText = trim($_POST['message'] ?? '');

    if ($order_number && $customer_email && $type && $messageText) {
        $stmt = $conn->prepare('SELECT id FROM orders WHERE order_number = ? LIMIT 1');
        $stmt->bind_param('s', $order_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
        $stmt->close();

        if (!$order) {
            $deliveryDate = date('Y-m-d', strtotime('+5 days'));
            $status = 'Pending Review';
            $insertOrder = $conn->prepare('INSERT INTO orders (order_number, customer_email, status, delivery_date) VALUES (?, ?, ?, ?)');
            $insertOrder->bind_param('ssss', $order_number, $customer_email, $status, $deliveryDate);
            $insertOrder->execute();
            $orderId = $insertOrder->insert_id;
            $insertOrder->close();
        } else {
            $orderId = $order['id'];
        }

        $complaintStmt = $conn->prepare('INSERT INTO complaints (order_id, customer_email, type, message) VALUES (?, ?, ?, ?)');
        $complaintStmt->bind_param('isss', $orderId, $customer_email, $type, $messageText);
        $complaintStmt->execute();
        $complaintStmt->close();

        $message = '<div class="alert alert-success">Complaint submitted successfully.</div>';
    } else {
        $message = '<div class="alert alert-danger">Please complete all fields before submitting.</div>';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Submit Complaint - Support Portal</title>
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
                <h1 class="fw-bold text-primary">Submit a Complaint</h1>
                <p class="text-secondary">Tell us about your issue and our support team will review it.</p>
            </div>
            <?php echo $message; ?>
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <form method="post" action="complaint.php">
                                <div class="mb-3">
                                    <label class="form-label">Order ID</label>
                                    <input type="text" name="order_id" class="form-control" placeholder="1 or 100000" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="customer_email" class="form-control" placeholder="customer@example.com" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Complaint Type</label>
                                    <select name="type" class="form-select" required>
                                        <option value="">Select complaint type</option>
                                        <option value="Delayed Order">Delayed Order</option>
                                        <option value="Missing Item">Missing Item</option>
                                        <option value="Refund Issue">Refund Issue</option>
                                        <option value="Wrong Product">Wrong Product</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Message</label>
                                    <textarea name="message" rows="5" class="form-control" placeholder="Describe your complaint" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Submit Complaint</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="footer py-4 bg-primary text-white">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="mb-2 mb-md-0">© 2026 Support Portal.</p>
            <div>
                <a href="index.php" class="text-white text-decoration-none me-3">Home</a>
                <a href="order-details.php" class="text-white text-decoration-none">Track Order</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
