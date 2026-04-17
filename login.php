<?php
require_once 'config/error-handler.php';
require_once 'config/session.php';
require_once 'config/db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['_csrf_token']) || !verify_csrf_token($_POST['_csrf_token'])) {
        $message = '<div class="alert alert-danger">Session expired. Please try again.</div>';
    } else {
        $login_input = trim($_POST['login_input'] ?? '');
        $password = trim($_POST['password'] ?? '');
        if ($login_input && $password) {
            $stmt = $conn->prepare('SELECT id, name, email, password FROM admins WHERE email = ? OR name = ? LIMIT 1');
            $stmt->bind_param('ss', $login_input, $login_input);
            $stmt->execute();
            $stmt->bind_result($adminId, $adminName, $adminEmail, $hash);
            if ($stmt->fetch() && password_verify($password, $hash)) {
                session_regenerate_id(true);
                $_SESSION['admin_id'] = $adminId;
                $_SESSION['admin_email'] = $adminEmail;
                $_SESSION['admin_name'] = $adminName;
                header('Location: admin/dashboard.php');
                exit;
            }
            $stmt->close();
            $message = '<div class="alert alert-danger">Invalid login or password.</div>';
        } else {
            $message = '<div class="alert alert-warning">Please enter both username/email and password.</div>';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Login - Support Portal</title>
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
            </nav>
        </div>
    </header>
    <main class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h3 class="card-title text-primary mb-4">Admin Login</h3>
                            <?php echo $message; ?>
                            <form method="post" action="login.php">
                                <?php echo get_csrf_field(); ?>
                                <div class="mb-3">
                                    <label class="form-label">Email or Username</label>
                                    <input type="text" name="login_input" class="form-control" placeholder="admin@test.com or mo" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Login</button>
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
