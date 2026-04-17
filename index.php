<?php
// Home page for Customer Support Portal
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Customer Support Portal</title>
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

    <main>
        <section class="hero py-5 position-relative">
            <div class="hero-illustration d-none d-lg-block">
              
            </div>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5">
                        <h1 class="display-5 fw-bold text-primary">Customer Support Portal</h1>
                        <p class="lead text-secondary">Track your orders, submit complaints and get fast support from our admin team.</p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="order-details.php" class="btn btn-primary btn-lg">Track Order</a>
                            <a href="complaint.php" class="btn btn-outline-primary btn-lg">Submit Complaint</a>
                        </div>
                    </div>
                    <div class="col-lg-5 offset-lg-1">
                        <div class="hero-card shadow rounded p-4 bg-white border border-1 border-light">
                            <h4 class="mb-3 text-primary">Quick Order Search</h4>
                            <form action="order-details.php" method="post">
                                <div class="mb-3">
                                    <label class="form-label small text-secondary">Order Number</label>
                                    <input type="text" name="order_number" class="form-control form-control-lg" placeholder="Enter order number" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-secondary">Email Address</label>
                                    <input type="email" name="customer_email" class="form-control form-control-lg" placeholder="Enter your email" required>
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg w-100">Search Order</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="features py-5 bg-white">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="feature-card p-4 rounded shadow-sm h-100">
                            <h5 class="text-primary">Order Tracking</h5>
                            <p class="text-secondary">Search your order status and delivery date using your email and order number.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="feature-card p-4 rounded shadow-sm h-100">
                            <h5 class="text-primary">Complaint Handling</h5>
                            <p class="text-secondary">Submit complaints quickly for delays, missing items, refunds, or wrong products.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="feature-card p-4 rounded shadow-sm h-100">
                            <h5 class="text-primary">Admin Control</h5>
                            <p class="text-secondary">Admins can review complaints, update statuses and manage support requests.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer py-4 bg-primary text-white">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="mb-2 mb-md-0">© 2026 Support Portal. All rights reserved.</p>
            <div>
                <a href="index.php" class="text-white text-decoration-none me-3">Home</a>
                <a href="complaint.php" class="text-white text-decoration-none">Contact Support</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
