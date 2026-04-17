document.addEventListener('DOMContentLoaded', function() {
    const currentPath = window.location.pathname.split('/').pop();
    const links = document.querySelectorAll('nav a');
    links.forEach(function(link) {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
});

function confirmDelete() {
    return confirm('Are you sure you want to delete this complaint?');
}

function submitComplaint(e) {
    if (e) e.preventDefault();
    
    const orderId = document.getElementById('orderId')?.value;
    const email = document.getElementById('email')?.value;
    const issueType = document.getElementById('issueType')?.value;
    const message = document.getElementById('message')?.value;
    
    if (!orderId || !email || !issueType || !message) {
        alert('⚠️ Please fill all required fields');
        return;
    }
    
    const complaint = {
        id: Date.now(),
        orderId: orderId,
        email: email,
        issueType: issueType,
        issueTypeText: document.getElementById('issueType')?.options[document.getElementById('issueType').selectedIndex]?.text || issueType,
        message: message,
        status: 'open',
        date: new Date().toLocaleString(),
        adminReply: null,
        adminReplyDate: null
    };
    
    let allComplaints = JSON.parse(localStorage.getItem('allComplaints')) || [];
    allComplaints.push(complaint);
    localStorage.setItem('allComplaints', JSON.stringify(allComplaints));
    localStorage.setItem('currentComplaint', JSON.stringify(complaint));
    
    alert(`✅ Your complaint has been submitted successfully!\nTicket ID: ${complaint.id}`);
    window.location.href = 'track-complaint.html';
}

// ----------------------------------------
// 7. صفحة متابعة الشكوى (track-complaint.html)
// ----------------------------------------
function loadComplaintStatus() {
    const complaint = JSON.parse(localStorage.getItem('currentComplaint'));
    const container = document.getElementById('complaintContent');
    
    if (!complaint || !container) {
        if (container) {
            container.innerHTML = `
                <div class="order-card">
                    <p>❌ No complaint found.</p>
                    <a href="index.html" style="display:inline-block; margin-top:15px; color:#2c5aa0;">Go to Home</a>
                </div>
            `;
        }
        return;
    }
    
    let statusText = '';
    let statusClass = '';
    switch(complaint.status) {
        case 'open':
            statusText = '🟡 Open - Waiting for admin response';
            statusClass = 'pending';
            break;
        case 'in_progress':
            statusText = '🔵 In Progress - Admin is reviewing';
            statusClass = 'processing';
            break;
        case 'closed':
            statusText = '🟢 Closed - Resolved';
            statusClass = 'delivered';
            break;
        default:
            statusText = complaint.status;
            statusClass = 'pending';
    }
    
    let adminReplyHtml = '';
    if (complaint.adminReply) {
        adminReplyHtml = `
            <div class="admin-reply">
                <h4>💬 Admin Response:</h4>
                <p>${complaint.adminReply}</p>
                <small>📅 ${complaint.adminReplyDate || 'Recently'}</small>
            </div>
        `;
    } else {
        adminReplyHtml = `
            <div class="admin-reply" style="background:#fff3e0; border-left-color:#f39c12;">
                <h4>⏳ Waiting for Admin Response</h4>
                <p>Our team will review your complaint and respond within 24-48 hours.</p>
            </div>
        `;
    }
    
    const html = `
        <div class="order-card">
            <p><strong>🎫 Ticket ID:</strong> #${complaint.id}</p>
            <p><strong>🆔 Order ID:</strong> ${complaint.orderId}</p>
            <p><strong>📧 Email:</strong> ${complaint.email}</p>
            <p><strong>⚠️ Issue Type:</strong> ${complaint.issueTypeText}</p>
            <p><strong>📝 Your Message:</strong></p>
            <p style="background:white; padding:12px; border-radius:8px; margin-top:5px;">${complaint.message}</p>
            <p><strong>📅 Submitted:</strong> ${complaint.date}</p>
            
            <div class="status">
                <span class="status-label">📊 Complaint Status:</span>
                <span class="status-value ${statusClass}">${statusText}</span>
            </div>
            
            ${adminReplyHtml}
        </div>
        
        <div class="actions">
            <button onclick="window.location.href='complaint.html'">✏️ Edit / New Complaint</button>
            <button onclick="window.location.href='index.html'">🏠 Back to Home</button>
        </div>
    `;
    
    container.innerHTML = html;
}

// محاكاة رد من الأدمن بعد 3 ثواني
function simulateAdminReply() {
    setTimeout(function() {
        let complaint = JSON.parse(localStorage.getItem('currentComplaint'));
        if (complaint && !complaint.adminReply) {
            complaint.adminReply = "Thank you for reaching out. Our team is investigating your issue and will get back to you within 24 hours. Please provide your order number if you have any additional details.";
            complaint.adminReplyDate = new Date().toLocaleString();
            complaint.status = 'in_progress';
            localStorage.setItem('currentComplaint', JSON.stringify(complaint));
            
            let allComplaints = JSON.parse(localStorage.getItem('allComplaints')) || [];
            const index = allComplaints.findIndex(c => c.id === complaint.id);
            if (index !== -1) {
                allComplaints[index] = complaint;
                localStorage.setItem('allComplaints', JSON.stringify(allComplaints));
            }
            
            loadComplaintStatus();
        }
    }, 3000);
}

// ----------------------------------------
// 8. صفحة اتصل بنا (contact.html)
// ----------------------------------------
function submitContactMessage(e) {
    if (e) e.preventDefault();
    
    const fullName = document.getElementById('fullName')?.value;
    const email = document.getElementById('emailContact')?.value;
    const orderId = document.getElementById('orderIdContact')?.value || 'N/A';
    const subject = document.getElementById('subject')?.value;
    const message = document.getElementById('contactMessage')?.value;
    
    if (!fullName || !email || !subject || !message) {
        alert('⚠️ Please fill all required fields');
        return;
    }
    
    const contactData = {
        id: Date.now(),
        fullName: fullName,
        email: email,
        orderId: orderId,
        subject: subject,
        subjectText: document.getElementById('subject')?.options[document.getElementById('subject').selectedIndex]?.text || subject,
        message: message,
        date: new Date().toLocaleString(),
        status: 'unread'
    };
    
    let allMessages = JSON.parse(localStorage.getItem('contactMessages')) || [];
    allMessages.push(contactData);
    localStorage.setItem('contactMessages', JSON.stringify(allMessages));
    
    alert(`✅ Thank you ${contactData.fullName}!\n\nYour message has been sent successfully.\nReference ID: #${contactData.id}\n\nWe will respond within 24 hours.`);
    
    const form = document.getElementById('contactForm');
    if (form) form.reset();
}

function startChat() {
    alert('💬 Live Chat\n\nPlease wait while we connect you to a support agent...\n\n(This is a demo. In production, this would connect to a real chat system.)');
}

function toggleFAQ(element) {
    const answer = element.nextElementSibling;
    if (answer) {
        answer.classList.toggle('show');
        const icon = element.innerHTML.charAt(0);
        element.innerHTML = element.innerHTML.replace(icon, icon === '▶' ? '▼' : '▶');
    }
}

// ----------------------------------------
// 9. صفحة الأسئلة الشائعة (faq.html)
// ----------------------------------------
function toggleAnswer(element) {
    const answer = element.nextElementSibling;
    const span = element.querySelector('span');
    
    if (answer) {
        answer.classList.toggle('show');
        if (span) {
            span.innerHTML = answer.classList.contains('show') ? '▼' : '▶';
        }
    }
}

function filterCategory(category) {
    const buttons = document.querySelectorAll('.category-btn');
    buttons.forEach(btn => {
        btn.classList.remove('active');
        if ((category === 'all' && btn.textContent === 'All Questions') || 
            btn.textContent.toLowerCase().includes(category)) {
            btn.classList.add('active');
        }
    });
    
    const sections = document.querySelectorAll('.faq-section');
    sections.forEach(section => {
        if (category === 'all') {
            section.style.display = 'block';
        } else {
            if (section.getAttribute('data-category') === category) {
                section.style.display = 'block';
            } else {
                section.style.display = 'none';
            }
        }
    });
}

function searchFAQ() {
    const searchTerm = document.getElementById('faqSearchInput')?.value.toLowerCase();
    if (!searchTerm) {
        filterCategory('all');
        return;
    }
    
    const allQuestions = document.querySelectorAll('.faq-accordion');
    filterCategory('all');
    
    let hasResults = false;
    allQuestions.forEach(question => {
        const questionText = question.querySelector('.faq-question')?.textContent.toLowerCase() || '';
        const answerText = question.querySelector('.faq-answer')?.textContent.toLowerCase() || '';
        
        if (questionText.includes(searchTerm) || answerText.includes(searchTerm)) {
            question.style.display = 'block';
            hasResults = true;
        } else {
            question.style.display = 'none';
        }
    });
    
    if (!hasResults) {
        alert('❌ No results found. Try different keywords.');
    }
}

// ----------------------------------------
// 10. صفحة تسجيل الدخول (login.html)
// ----------------------------------------
function showRegister() {
    const loginBox = document.getElementById('loginBox');
    const registerBox = document.getElementById('registerBox');
    const forgotBox = document.getElementById('forgotBox');
    if (loginBox) loginBox.style.display = 'none';
    if (registerBox) registerBox.style.display = 'block';
    if (forgotBox) forgotBox.style.display = 'none';
}

function showLogin() {
    const loginBox = document.getElementById('loginBox');
    const registerBox = document.getElementById('registerBox');
    const forgotBox = document.getElementById('forgotBox');
    if (loginBox) loginBox.style.display = 'block';
    if (registerBox) registerBox.style.display = 'none';
    if (forgotBox) forgotBox.style.display = 'none';
}

function showForgotPassword() {
    const loginBox = document.getElementById('loginBox');
    const registerBox = document.getElementById('registerBox');
    const forgotBox = document.getElementById('forgotBox');
    if (loginBox) loginBox.style.display = 'none';
    if (registerBox) registerBox.style.display = 'none';
    if (forgotBox) forgotBox.style.display = 'block';
}

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.type = field.type === 'password' ? 'text' : 'password';
    }
}

function fillDemo(email, password) {
    const loginEmail = document.getElementById('loginEmail');
    const loginPassword = document.getElementById('loginPassword');
    if (loginEmail) loginEmail.value = email;
    if (loginPassword) loginPassword.value = password;
}

function handleLogin(e) {
    if (e) e.preventDefault();
    
    const email = document.getElementById('loginEmail')?.value;
    const password = document.getElementById('loginPassword')?.value;
    const rememberMe = document.getElementById('rememberMe')?.checked;
    
    const user = users.find(u => u.email === email && u.password === password);
    
    if (user) {
        const session = {
            id: user.id,
            name: user.name,
            email: user.email,
            role: user.role,
            loginTime: new Date().toISOString()
        };
        
        if (rememberMe) {
            localStorage.setItem('userSession', JSON.stringify(session));
        } else {
            sessionStorage.setItem('userSession', JSON.stringify(session));
        }
        
        alert(`✅ Welcome back, ${user.name}!\n\nRole: ${user.role.toUpperCase()}\nYou are now logged in.`);
        
        if (user.role === 'admin') {
            window.location.href = 'admin-dashboard.html';
        } else {
            window.location.href = 'index.html';
        }
    } else {
        alert('❌ Invalid email or password. Please try again.\n\nDemo accounts:\nCustomer: customer@maxfashion.com / customer123\nAdmin: admin@maxfashion.com / admin123');
    }
}

function handleRegister(e) {
    if (e) e.preventDefault();
    
    const name = document.getElementById('regName')?.value;
    const email = document.getElementById('regEmail')?.value;
    const phone = document.getElementById('regPhone')?.value;
    const password = document.getElementById('regPassword')?.value;
    const confirmPassword = document.getElementById('regConfirmPassword')?.value;
    const agreeTerms = document.getElementById('agreeTerms')?.checked;
    
    if (password !== confirmPassword) {
        alert('❌ Passwords do not match!');
        return;
    }
    
    if (password.length < 6) {
        alert('❌ Password must be at least 6 characters!');
        return;
    }
    
    if (!agreeTerms) {
        alert('❌ Please agree to the Terms & Conditions');
        return;
    }
    
    const existingUser = users.find(u => u.email === email);
    if (existingUser) {
        alert('❌ This email is already registered. Please login instead.');
        return;
    }
    
    const newUser = {
        id: users.length + 1,
        name: name,
        email: email,
        phone: phone,
        password: password,
        role: 'customer'
    };
    
    users.push(newUser);
    localStorage.setItem('registeredUsers', JSON.stringify(users));
    
    alert(`✅ Account created successfully!\n\nWelcome ${name}!\nYou can now login with your credentials.`);
    
    showLogin();
    const loginEmail = document.getElementById('loginEmail');
    if (loginEmail) loginEmail.value = email;
}

function handleForgotPassword(e) {
    if (e) e.preventDefault();
    
    const email = document.getElementById('forgotEmail')?.value;
    const user = users.find(u => u.email === email);
    
    if (user) {
        alert(`📧 Password reset link sent to ${email}\n\n(Reset link would be sent in production)\n\nDemo password: ${user.password}`);
    } else {
        alert('❌ No account found with this email address.');
    }
}

// ----------------------------------------
// 11. تشغيل الدوال المناسبة لكل صفحة (DOMContentLoaded)
// ----------------------------------------
document.addEventListener('DOMContentLoaded', function() {
    
    // تحديث الـ Navbar
    updateNavbarForLogin();
    
    // الصفحة الحالية
    const currentPage = window.location.pathname.split('/').pop();
    
    // index.html
    if (currentPage === 'index.html' || currentPage === '') {
        const trackBtn = document.querySelector('.search-box button');
        const orderInput = document.getElementById('orderInput');
        if (trackBtn) trackBtn.onclick = trackOrder;
        if (orderInput) {
            orderInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') trackOrder();
            });
        }
    }
    
    // order-details.html
    if (currentPage === 'order-details.html') {
        loadOrderDetails();
    }
    
    // complaint.html
    if (currentPage === 'complaint.html') {
        loadComplaintForm();
        const complaintForm = document.getElementById('complaintForm');
        if (complaintForm) {
            complaintForm.addEventListener('submit', submitComplaint);
        }
    }
    
    // track-complaint.html
    if (currentPage === 'track-complaint.html') {
        loadComplaintStatus();
        simulateAdminReply();
    }
    
    // contact.html
    if (currentPage === 'contact.html') {
        const contactForm = document.getElementById('contactForm');
        if (contactForm) {
            contactForm.addEventListener('submit', submitContactMessage);
        }
    }
    
    // faq.html
    if (currentPage === 'faq.html') {
        const searchBtn = document.querySelector('.faq-search button');
        const searchInput = document.getElementById('faqSearchInput');
        if (searchBtn) searchBtn.onclick = searchFAQ;
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') searchFAQ();
            });
        }
    }
    
    // login.html
    if (currentPage === 'login.html') {
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const forgotForm = document.getElementById('forgotForm');
        
        if (loginForm) loginForm.addEventListener('submit', handleLogin);
        if (registerForm) registerForm.addEventListener('submit', handleRegister);
        if (forgotForm) forgotForm.addEventListener('submit', handleForgotPassword);
    }
});