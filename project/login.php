<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Max Fashion Support</title>
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
        <a href="login.html" class="login-btn" style="background:#2c5aa0;">Login</a>
    </nav>
</header>

<!-- Login Section -->
<section class="login-section">
    <div class="login-container">
        
        <!-- Login Box -->
        <div class="login-box" id="loginBox">
            <div class="login-header">
                <h2>Welcome Back! 👋</h2>
                <p>Login to your account to track orders and manage complaints</p>
            </div>
            
            <form id="loginForm">
                <div class="input-group">
                    <label>📧 Email Address</label>
                    <input type="email" id="loginEmail" placeholder="ahmed@example.com" required>
                </div>
                
                <div class="input-group">
                    <label>🔒 Password</label>
                    <input type="password" id="loginPassword" placeholder="Enter your password" required>
                    <span class="toggle-password" onclick="togglePassword('loginPassword')">👁️</span>
                </div>
                
                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" id="rememberMe"> Remember me
                    </label>
                    <a href="#" class="forgot-link" onclick="showForgotPassword()">Forgot Password?</a>
                </div>
                
                <button type="submit" class="login-btn-main">Login →</button>
            </form>
            
            <div class="register-link">
                Don't have an account? <a href="#" onclick="showRegister()">Create Account</a>
            </div>
            
            <div class="demo-accounts">
                <p>🔐 Demo Accounts (for testing):</p>
                <div class="demo-buttons">
                    <button onclick="fillDemo('customer@maxfashion.com', 'customer123')">👤 Customer</button>
                    <button onclick="fillDemo('admin@maxfashion.com', 'admin123')">👑 Admin</button>
                </div>
            </div>
        </div>
        
        <!-- Register Box (Hidden by default) -->
        <div class="login-box" id="registerBox" style="display: none;">
            <div class="login-header">
                <h2>Create Account ✨</h2>
                <p>Join Max Fashion for faster support and order tracking</p>
            </div>
            
            <form id="registerForm">
                <div class="input-group">
                    <label>👤 Full Name</label>
                    <input type="text" id="regName" placeholder="Ahmed Ali" required>
                </div>
                
                <div class="input-group">
                    <label>📧 Email Address</label>
                    <input type="email" id="regEmail" placeholder="ahmed@example.com" required>
                </div>
                
                <div class="input-group">
                    <label>📱 Phone Number</label>
                    <input type="tel" id="regPhone" placeholder="+20 123 456 7890">
                </div>
                
                <div class="input-group">
                    <label>🔒 Password</label>
                    <input type="password" id="regPassword" placeholder="Minimum 6 characters" required>
                    <span class="toggle-password" onclick="togglePassword('regPassword')">👁️</span>
                </div>
                
                <div class="input-group">
                    <label>🔒 Confirm Password</label>
                    <input type="password" id="regConfirmPassword" placeholder="Re-enter password" required>
                </div>
                
                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" id="agreeTerms" required> I agree to the <a href="#">Terms & Conditions</a>
                    </label>
                </div>
                
                <button type="submit" class="login-btn-main">Create Account →</button>
            </form>
            
            <div class="register-link">
                Already have an account? <a href="#" onclick="showLogin()">Login here</a>
            </div>
        </div>
        
        <!-- Forgot Password Box (Hidden by default) -->
        <div class="login-box" id="forgotBox" style="display: none;">
            <div class="login-header">
                <h2>Reset Password 🔐</h2>
                <p>Enter your email to receive a password reset link</p>
            </div>
            
            <form id="forgotForm">
                <div class="input-group">
                    <label>📧 Email Address</label>
                    <input type="email" id="forgotEmail" placeholder="ahmed@example.com" required>
                </div>
                
                <button type="submit" class="login-btn-main">Send Reset Link →</button>
            </form>
            
            <div class="register-link">
                <a href="#" onclick="showLogin()">← Back to Login</a>
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