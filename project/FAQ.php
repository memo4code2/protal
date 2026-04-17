<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Max Fashion Support</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<header class="navbar">
    <div class="logo">🛍️ Max Fashion Support</div>
    <nav>
        <a href="index.html">Home</a>
        <a href="faq.html" style="color:#2c5aa0; font-weight:bold;">FAQ</a>
        <a href="contact.html">Contact Us</a>
        <a href="login.html" class="login-btn">Login</a>
    </nav>
</header>

<!-- FAQ Hero -->
<section class="faq-hero">
    <h1>❓ Frequently Asked Questions</h1>
    <p>Find answers to common questions about orders, shipping, returns, and more.</p>
    <div class="faq-search">
        <input type="text" id="faqSearchInput" placeholder="🔍 Search for answers...">
        <button onclick="searchFAQ()">Search</button>
    </div>
</section>

<!-- FAQ Categories -->
<section class="faq-categories">
    <button class="category-btn active" onclick="filterCategory('all')">All Questions</button>
    <button class="category-btn" onclick="filterCategory('orders')">📦 Orders</button>
    <button class="category-btn" onclick="filterCategory('shipping')">🚚 Shipping</button>
    <button class="category-btn" onclick="filterCategory('returns')">🔄 Returns & Refunds</button>
    <button class="category-btn" onclick="filterCategory('account')">👤 Account</button>
    <button class="category-btn" onclick="filterCategory('complaints')">⚠️ Complaints</button>
</section>

<!-- FAQ Content -->
<section class="faq-content">
    <div class="faq-container">
        
        <!-- Orders Section -->
        <div class="faq-section" data-category="orders">
            <h2>📦 Orders</h2>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> How do I track my order?
                </div>
                <div class="faq-answer">
                    You can track your order by entering your Order ID on the homepage. You'll receive real-time updates about your order status and estimated delivery date.
                </div>
            </div>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> Can I cancel my order?
                </div>
                <div class="faq-answer">
                    Yes, you can cancel your order within 2 hours of placing it. Go to Order Details and click "Cancel Order". For orders older than 2 hours, please contact customer support.
                </div>
            </div>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> How do I change my order details?
                </div>
                <div class="faq-answer">
                    To change shipping address or product size/color, contact us within 1 hour of placing the order. After that, changes may not be possible.
                </div>
            </div>
        </div>
        
        <!-- Shipping Section -->
        <div class="faq-section" data-category="shipping">
            <h2>🚚 Shipping</h2>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> How long does delivery take?
                </div>
                <div class="faq-answer">
                    Standard delivery takes 3-5 business days. Express delivery takes 1-2 business days. Delivery times may vary during holidays or sales events.
                </div>
            </div>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> What are the shipping costs?
                </div>
                <div class="faq-answer">
                    Shipping is free for orders over 500 EGP. For orders under 500 EGP, standard shipping costs 35 EGP and express shipping costs 60 EGP.
                </div>
            </div>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> Do you ship internationally?
                </div>
                <div class="faq-answer">
                    Currently, we only ship within Egypt. We're planning to expand internationally in 2027.
                </div>
            </div>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> My order is delayed. What should I do?
                </div>
                <div class="faq-answer">
                    If your order is delayed beyond the estimated delivery date, please report an issue through our complaint system. Our team will investigate and update you within 24 hours.
                </div>
            </div>
        </div>
        
        <!-- Returns & Refunds Section -->
        <div class="faq-section" data-category="returns">
            <h2>🔄 Returns & Refunds</h2>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> What is your return policy?
                </div>
                <div class="faq-answer">
                    You can return any product within 14 days of delivery. Items must be unused, with original tags and packaging. Some items like swimwear and underwear cannot be returned for hygiene reasons.
                </div>
            </div>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> How do I request a refund?
                </div>
                <div class="faq-answer">
                    Go to your Order Details page and click "Request Refund". Select the items you want to return and the reason. You'll receive a return label via email.
                </div>
            </div>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> How long does it take to get a refund?
                </div>
                <div class="faq-answer">
                    Once we receive your returned item, refunds are processed within 5-7 business days. The money will be credited back to your original payment method.
                </div>
            </div>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> Can I exchange an item instead of returning?
                </div>
                <div class="faq-answer">
                    Yes, exchanges are free! Request an exchange through your order page. We'll ship the new item once we receive the original item back.
                </div>
            </div>
        </div>
        
        <!-- Account Section -->
        <div class="faq-section" data-category="account">
            <h2>👤 Account</h2>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> How do I create an account?
                </div>
                <div class="faq-answer">
                    Click the "Login" button in the top right corner, then select "Create New Account". Enter your email and password to register.
                </div>
            </div>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> I forgot my password. How do I reset it?
                </div>
                <div class="faq-answer">
                    On the Login page, click "Forgot Password". Enter your email address and we'll send you a link to reset your password.
                </div>
            </div>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> How do I update my profile information?
                </div>
                <div class="faq-answer">
                    After logging in, go to "My Account" > "Profile Settings". You can update your name, email, phone number, and shipping addresses there.
                </div>
            </div>
        </div>
        
        <!-- Complaints Section -->
        <div class="faq-section" data-category="complaints">
            <h2>⚠️ Complaints</h2>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> How do I report an issue with my order?
                </div>
                <div class="faq-answer">
                    Go to your Order Details page and click "Report an Issue". Choose the issue type (late delivery, missing item, refund, etc.) and describe your problem. Our support team will respond within 24-48 hours.
                </div>
            </div>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> How can I track my complaint?
                </div>
                <div class="faq-answer">
                    After submitting a complaint, you'll receive a Ticket ID. Use the "Track Complaint" page to check the status and see admin responses.
                </div>
            </div>
            
            <div class="faq-accordion">
                <div class="faq-question" onclick="toggleAnswer(this)">
                    <span>▶</span> What happens after I submit a complaint?
                </div>
                <div class="faq-answer">
                    Our support team reviews your complaint. You'll receive updates as the status changes: Open → In Progress → Resolved. We aim to resolve all complaints within 3 business days.
                </div>
            </div>
        </div>
        
        <!-- Still Need Help -->
        <div class="still-need-help">
            <h3>🤔 Still have questions?</h3>
            <p>Can't find what you're looking for? Our support team is here to help!</p>
            <div class="help-buttons">
                <button onclick="window.location.href='contact.html'">📧 Contact Us</button>
                <button onclick="window.location.href='complaint.html'">⚠️ Report an Issue</button>
            </div>
        </div>
        
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