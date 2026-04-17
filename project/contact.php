<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Max Fashion Support</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navbar -->
<header class="navbar">
    <div class="logo">🛍️ Max Fashion Support</div>
    <nav>
        <a href="index.html">Home</a>
        <a href="faq.html">FAQ</a>
        <a href="contact.html" style="color:#2c5aa0; font-weight:bold;">Contact Us</a>
        <a href="login.html" class="login-btn">Login</a>
    </nav>
</header>

<!-- Contact Section -->
<section class="contact-section">
    <div class="contact-container">
        
        <div class="contact-info">
            <h2>📞 Contact Us</h2>
            <p>We're here to help! Choose your preferred way to reach us.</p>
            
            <div class="info-cards">
                <div class="info-card">
                    <div class="icon">📞</div>
                    <h3>Call Us</h3>
                    <p>Sunday - Thursday: 9AM - 9PM</p>
                    <p class="highlight">+20 123 456 7890</p>
                    <p>+20 109 876 5432</p>
                </div>
                
                <div class="info-card">
                    <div class="icon">✉️</div>
                    <h3>Email Us</h3>
                    <p>Response within 24 hours</p>
                    <p class="highlight">support@maxfashion.com</p>
                    <p>complaints@maxfashion.com</p>
                </div>
                
                <div class="info-card">
                    <div class="icon">💬</div>
                    <h3>Live Chat</h3>
                    <p>Available 24/7 for urgent issues</p>
                    <button class="chat-btn" onclick="startChat()">Start Live Chat →</button>
                </div>
            </div>
        </div>
        
        <div class="contact-form-box">
            <h3>📝 Send us a Message</h3>
            <form id="contactForm" class="contact-form-style">
                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name *</label>
                        <input type="text" id="fullName" placeholder="Ahmed Ali" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" id="emailContact" placeholder="ahmed@example.com" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label>Order ID (Optional)</label>
                        <input type="text" id="orderIdContact" placeholder="#ORD-12345">
                    </div>
                    <div class="form-group">
                        <label>Subject *</label>
                        <select id="subject" required>
                            <option value="">Select Subject</option>
                            <option value="general">General Inquiry</option>
                            <option value="order">Order Issue</option>
                            <option value="refund">Refund Request</option>
                            <option value="return">Return Product</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Message *</label>
                    <textarea id="contactMessage" rows="5" placeholder="Please describe your issue or question in detail..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Attach File (Optional)</label>
                    <input type="file" id="attachment" accept="image/*,.pdf">
                    <small>You can attach a screenshot or receipt (Max 5MB)</small>
                </div>
                
                <button type="submit" class="submit-btn">✉️ Send Message</button>
            </form>
        </div>
        
        <div class="faq-preview">
            <h3>❓ Frequently Asked Questions</h3>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">▶ How long does delivery take?</div>
                <div class="faq-answer">Standard delivery takes 3-5 business days. Express delivery takes 1-2 business days.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">▶ How can I return a product?</div>
                <div class="faq-answer">You can return any product within 14 days of delivery. Visit your order page and click "Request Return".</div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">▶ When will I get my refund?</div>
                <div class="faq-answer">Refunds are processed within 5-7 business days after we receive the returned item.</div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">▶ Can I change my shipping address?</div>
                <div class="faq-answer">Yes, contact us within 2 hours of placing your order to update the address.</div>
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