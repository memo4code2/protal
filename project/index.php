<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Max Fashion - Support Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<header class="navbar">
    <div class="logo">🛍️ Max Fashion Support</div>
    <nav>
        <a href="index.html">Home</a>
        <a href="faq.html">FAQ</a>
        <a href="contact.html">Contact Us</a>
        <a href="login.html" class="login-btn">Login</a>
    </nav>
</header>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-text">
        <h1>Track Your Order</h1>
        <p>Enter your Order ID to check your order status, delivery date, or report an issue.</p>
        <div class="search-box">
            <input type="text" id="orderInput" placeholder="Enter Order ID (e.g., #ORD-12345)">
            <button onclick="trackOrder()">Track Order →</button>
        </div>
    </div>
    <div class="hero-image">
        <img src="photos/photo1.png" alt="Customer Support">
    </div>
</section>

<!-- Services Section -->
<section class="services">
    <div class="service" onclick="window.location.href='order-details.html'">
        <h3>📦 Check Delivery Status</h3>
        <p>Track your package in real-time</p>
    </div>
    <div class="service" onclick="window.location.href='complaint.html'">
        <h3>💰 Request a Refund</h3>
        <p>Get your money back</p>
    </div>
    <div class="service" onclick="window.location.href='complaint.html'">
        <h3>⚠️ Report an Issue</h3>
        <p>Missing item or late delivery</p>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    <div class="footer-section">
        <h4>Customer Support</h4>
        <p>Help Center</p>
        <p>Contact Us</p>
        <p>Live Chat</p>
    </div>
    <div class="footer-section">
        <h4>Quick Links</h4>
        <p>FAQ</p>
        <p>Shipping Info</p>
        <p>Return Policy</p>
    </div>
    <div class="footer-section">
        <h4>Follow Us</h4>
        <p>Facebook</p>
        <p>Twitter</p>
        <p>Instagram</p>
    </div>
</footer>

<script src="script.js"></script>

</body>
</html>