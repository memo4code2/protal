<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Issue - Max Fashion</title>
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

<!-- Complaint Section -->
<section class="complaint">
    <a href="order-details.html" class="back-btn">← Back to Order</a>
    
    <h2>⚠️ Report an Issue</h2>

    <form class="complaint-form" id="complaintForm">
        <input type="text" id="orderId" placeholder="Order ID" readonly>
        
        <input type="email" id="email" placeholder="Your Email" required>
        
        <select id="issueType" required>
            <option value="">Select Issue Type</option>
            <option value="late">🚚 Late Delivery</option>
            <option value="missing">📦 Missing Item</option>
            <option value="refund">💰 Refund Request</option>
            <option value="damaged">🔨 Damaged Product</option>
            <option value="other">❓ Other</option>
        </select>
        
        <textarea id="message" placeholder="Describe your issue in detail..." rows="5" required></textarea>
        
        <button type="submit">📨 Submit Complaint</button>
    </form>
</section>

<script src="script.js"></script>

</body>
</html>