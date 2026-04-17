# Customer Support Portal - Setup Guide

## Quick Start

### 1. Database Setup
Run this script to create all database tables:
```bash
php create_tables.php
```

This will:
- Create `support_portal` database
- Create `orders`, `complaints`, `admins` tables
- Insert demo admin accounts

### 2. Default Login Credentials
```
Admin 1:
Email: admin@test.com
Password: 123456

Admin 2:
Username: mo
Password: 123
```

### 3. Access the Portal
```
Home: http://localhost/protal/
Track Order: http://localhost/protal/order-details.php
Submit Complaint: http://localhost/protal/complaint.php
Admin Dashboard: http://localhost/protal/admin/dashboard.php
```

## Features

### Customer Features
- **Track Orders**: Enter order number and email to see order status
- **Auto-Create Orders**: Any order number auto-creates if it doesn't exist
- **Submit Complaints**: File complaints for orders
- **Complaint Types**: Delayed Order, Missing Item, Refund Issue, Wrong Product

### Admin Features
- **View Complaints**: See all submitted complaints
- **Check Orders**: Verify orders as legitimate
- **Close Complaints**: Mark complaints as resolved
- **Delete Complaints**: Remove complaint records
- **Statistics**: View total, open, and closed complaints

## Default Sample Orders
```
ORD-1001 → customer1@test.com (Verified)
ORD-1002 → customer2@test.com (Pending Review / Waiting)
ORD-1003 → customer3@test.com (Checked)
```

## Project Structure
```
/protal
├── config/
│   ├── db.php              (Database connection)
│   ├── session.php         (Session security)
│   └── error-handler.php   (Error logging)
├── admin/
│   └── dashboard.php       (Admin panel)
├── assets/
│   ├── style.css           (Styling)
│   ├── script.js           (JavaScript)
│   ├── photo1.png          (Hero image)
│   └── images/             (Additional images)
├── index.php               (Homepage)
├── order-details.php       (Order tracking)
├── complaint.php           (Complaint form)
├── login.php               (Admin login)
├── create_tables.php       (Database setup)
└── logs/                   (Error logs - auto-created)
```

## Database Schema

### Orders Table
- `id` - Primary key
- `order_number` - Unique identifier (searchable)
- `customer_email` - Customer email
- `status` - Pending Review / Verified
- `delivery_date` - Expected delivery
- `checked_by_admin` - Yes/No
- `created_at` - Creation timestamp

### Complaints Table
- `id` - Primary key
- `order_id` - Link to order
- `customer_email` - Complaint submitter
- `type` - Complaint type
- `message` - Complaint details
- `status` - Open / Closed
- `reply` - Admin response
- `created_at` - Submission timestamp

### Admins Table
- `id` - Primary key
- `name` - Admin name
- `email` - Login email
- `password` - Hashed password
- `created_at` - Account creation

## Security Features
- **Password Hashing**: bcrypt hashing for admin passwords
- **Email Validation**: All emails validated
- **Input Sanitization**: SQL injection & XSS prevention
- **Session Security**: Secure session handling with IP validation
- **CSRF Protection**: Token-based CSRF protection
- **Error Logging**: Errors logged to file, not shown to users
- **HTTP Security Headers**: CORS, X-Frame-Options, etc.

## Tips for First-Time Use

1. **Create Test Order**: Enter order "1" with any email during order tracking
2. **Submit Complaint**: Use the created order to submit test complaint
3. **Admin Check**: Login as "mo/123" and check the order
4. **Close Complaint**: When admin closes a complaint, the order automatically updates to "Checked" status
5. **View Results**: User sees "Checked" status when searching again

## Order Status Flow

Users will see different status indicators when tracking their orders:

| Backend Status | User Sees | Badge Color | Meaning |
|---|---|---|---|
| `Pending Review` | **Waiting** | Yellow | Order received, waiting for admin review |
| `Verified` | **Verified** | Blue | Admin has verified the order |
| `Checked` | **Checked** | Green | Complaint has been handled/closed by admin |

### How Status Changes:
1. **New Order Created** → Status: "Pending Review" (User sees: "Waiting")
2. **Admin clicks "Check Order"** → Status: "Verified" (User sees: "Verified") 
3. **Admin closes complaint** → Status: "Checked" (User sees: "Checked")
4. When user searches again, they see the updated status with appropriate color badge

---

## Code Explanation: Frontend & Backend

### 📄 **index.php** (Homepage)

#### Frontend (What Users See)
- Hero section with title and image at top-right
- Quick order search form (enter order number + email)
- Feature cards explaining the portal
- Bootstrap styling for responsive design

**Key HTML:**
```html
<form method="POST" action="order-details.php">
  <input type="text" name="order_number" placeholder="Enter Order Number" required>
  <input type="email" name="customer_email" placeholder="Your Email" required>
  <button type="submit">Track Order</button>
</form>
```

#### Backend (PHP Logic)
- Displays static HTML content
- No database queries - just presentation layer
- Serves as entry point to the portal

---

### 📄 **order-details.php** (Order Tracking)

#### Frontend (What Users See)
- Form to enter order number and email
- Displays order details: order ID, status, delivery date
- Shows status with user-friendly label and color badge
- Possible statuses: **Waiting** (yellow), **Verified** (blue), **Checked** (green)
- Links to complaint submission page

**Key HTML:**
```html
<?php if ($order): ?>
  <div class="card">
    <p><strong>Order Number:</strong> <?php echo htmlspecialchars($order['order_number']); ?></p>
    <p>
      <strong>Status:</strong> 
      <?php 
      $statusDisplay = getStatusDisplay($order['status']);
      echo '<span class="badge bg-' . $statusDisplay['color'] . '">' . $statusDisplay['label'] . '</span>';
      ?>
    </p>
    <p><strong>Delivery Date:</strong> <?php echo $order['delivery_date']; ?></p>
  </div>
<?php endif; ?>
```

#### Backend (PHP Logic)
```php
// Status display function - converts backend status to user-friendly labels
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

// 1. Get form input
$order_number = $_POST['order_number'] ?? '';
$customer_email = $_POST['customer_email'] ?? '';

// 2. Validate email
if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
    $error = "Invalid email format";
}

// 3. Call helper function (from config/db.php)
$order = get_or_create_order($order_number, $customer_email);

// 4. Display order or error
if ($order) {
    $statusDisplay = getStatusDisplay($order['status']);
    echo "Order Status: " . $statusDisplay['label'];
} else {
    echo "Order not found";
}
```

**Backend Flow:**
- `getStatusDisplay()` function converts database status to user-friendly labels:
  - "Pending Review" → displays as "Waiting" with yellow badge
  - "Verified" → displays as "Verified" with blue badge
  - "Checked" → displays as "Checked" with green badge
- Validates email using `filter_var()`
- Calls `get_or_create_order()` helper
- If order doesn't exist, automatically creates it with status "Pending Review" and delivery date 5 days from now
- Displays order details with color-coded status badge

---

### 📄 **complaint.php** (Complaint Submission)

#### Frontend (What Users See)
- Form with fields: Order ID, Email, Complaint Type, Message
- Complaint types: "Delayed Order", "Missing Item", "Refund Issue", "Wrong Product"
- Message validation: 10-5000 characters
- Success/error messages

**Key HTML:**
```html
<form method="POST">
  <input type="text" name="order_id" placeholder="Order Number" required>
  <input type="email" name="customer_email" placeholder="Your Email" required>
  <select name="type">
    <option>Delayed Order</option>
    <option>Missing Item</option>
    <option>Refund Issue</option>
    <option>Wrong Product</option>
  </select>
  <textarea name="message" placeholder="Describe the issue..." minlength="10" maxlength="5000"></textarea>
  <button type="submit">Submit Complaint</button>
</form>
```

#### Backend (PHP Logic)
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Get and validate inputs
    $order_id = sanitize_input($_POST['order_id'] ?? '');
    $customer_email = $_POST['customer_email'] ?? '';
    $type = $_POST['type'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // 2. Validate email
    if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email";
    }
    
    // 3. Validate message length
    if (strlen($message) < 10 || strlen($message) > 5000) {
        $error = "Message must be 10-5000 characters";
    }
    
    // 4. Insert into database (prepared statement = safe from SQL injection)
    $stmt = $conn->prepare('INSERT INTO complaints (order_id, customer_email, type, message) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $order_id, $customer_email, $type, $message);
    $stmt->execute();
    
    // 5. Show success message
    echo "Complaint submitted successfully!";
}
```

**Security in Backend:**
- `sanitize_input()` removes dangerous characters
- `filter_var()` validates email format
- Prepared statements prevent SQL injection
- Message length validation (10-5000 chars)

---

### 📄 **login.php** (Admin Authentication)

#### Frontend (What Users See)
- Simple login form: Email + Password
- "Submit" button
- Shows error messages if login fails

**Key HTML:**
```html
<form method="POST">
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit">Login</button>
</form>
```

#### Backend (PHP Logic)
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Verify CSRF token (prevents cross-site attacks)
    if (!isset($_POST['_csrf_token']) || !verify_csrf_token($_POST['_csrf_token'])) {
        $error = "Session expired. Try again.";
    } else {
        // 2. Get email and password from form
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        // 3. Query database for admin account
        $stmt = $conn->prepare('SELECT id, password FROM admins WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // 4. Verify password using bcrypt
        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                // 5. Regenerate session ID (prevent session fixation)
                session_regenerate_id(true);
                
                // 6. Store admin ID in session
                $_SESSION['admin_id'] = $row['id'];
                
                // 7. Redirect to dashboard
                header('Location: admin/dashboard.php');
                exit;
            }
        }
        $error = "Invalid email or password";
    }
}
```

**Security in Backend:**
- CSRF token check prevents form hijacking
- `password_verify()` compares password to bcrypt hash (timing-safe)
- `session_regenerate_id(true)` prevents session fixation
- Prepared statements prevent SQL injection

---

### 📄 **admin/dashboard.php** (Admin Panel)

#### Frontend (What Users See)
- Admin logged-in status
- Table of all complaints with columns: Order ID, Email, Type, Status
- Action buttons for each complaint:
  - "✓ Check Order" - marks order as verified
  - "Close" - marks complaint as closed
  - "Delete" - removes complaint
- Statistics: Total complaints, Open complaints, Closed complaints
- Logout button

**Key HTML:**
```html
<table>
  <tr>
    <td><?php echo $complaint['order_id']; ?></td>
    <td><?php echo $complaint['type']; ?></td>
    <td><?php echo $complaint['status']; ?></td>
    <td>
      <form method="POST" style="display:inline;">
        <input type="hidden" name="_csrf_token" value="<?php echo get_csrf_field(); ?>">
        <input type="hidden" name="action" value="check">
        <input type="hidden" name="order_id" value="<?php echo $complaint['order_id']; ?>">
        <button type="submit">✓ Check Order</button>
      </form>
    </td>
  </tr>
</table>
```

#### Backend (PHP Logic)
```php
// 1. Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

// 2. Handle form submissions (POST requests)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 3. Verify CSRF token
    if (!isset($_POST['_csrf_token']) || !verify_csrf_token($_POST['_csrf_token'])) {
        $error = "Session expired";
    } else {
        $action = $_POST['action'] ?? '';
        
        // 4. CHECK ORDER (mark as verified)
        if ($action === 'check') {
            $order_id = intval($_POST['order_id'] ?? 0);
            $stmt = $conn->prepare('UPDATE orders SET status = ?, checked_by_admin = ? WHERE id = ?');
            $status = 'Verified';
            $checked = true;
            $stmt->bind_param('sbi', $status, $checked, $order_id);
            $stmt->execute();
            $message = "Order verified!";
        }
        
        // 5. CLOSE COMPLAINT (also updates order status to "Checked")
        if ($action === 'close') {
            $complaint_id = intval($_POST['complaint_id'] ?? 0);
            
            // a) Get the order_id from the complaint
            $getOrderStmt = $conn->prepare('SELECT order_id FROM complaints WHERE id = ?');
            $getOrderStmt->bind_param('i', $complaint_id);
            $getOrderStmt->execute();
            $orderResult = $getOrderStmt->get_result();
            
            if ($orderRow = $orderResult->fetch_assoc()) {
                $complaintOrderId = $orderRow['order_id'];
                
                // b) Update order status to "Checked" (user will see "Checked" badge)
                $updateOrderStmt = $conn->prepare('UPDATE orders SET status = ? WHERE id = ?');
                $orderStatus = 'Checked';
                $updateOrderStmt->bind_param('si', $orderStatus, $complaintOrderId);
                $updateOrderStmt->execute();
                $updateOrderStmt->close();
            }
            $getOrderStmt->close();
            
            // c) Mark complaint as closed
            $stmt = $conn->prepare('UPDATE complaints SET status = ? WHERE id = ?');
            $status = 'Closed';
            $stmt->bind_param('si', $status, $complaint_id);
            $stmt->execute();
            $message = "Complaint marked as closed and order status updated to 'Checked'!";
        }
        
        // 6. DELETE COMPLAINT
        if ($action === 'delete') {
            $complaint_id = intval($_POST['complaint_id'] ?? 0);
            $stmt = $conn->prepare('DELETE FROM complaints WHERE id = ?');
            $stmt->bind_param('i', $complaint_id);
            $stmt->execute();
            $message = "Complaint deleted!";
        }
    }
}

// 7. Fetch all complaints from database
$result = $conn->query('SELECT c.*, o.status as order_status FROM complaints c LEFT JOIN orders o ON c.order_id = o.id');
while ($row = $result->fetch_assoc()) {
    // Display in table
}
```

**Security in Backend:**
- Session check prevents unauthorized access
- CSRF token verification on all actions
- SQL injection protection with prepared statements
- User ID stored in session (not in URL/cookie)

---

### ⚙️ **config/db.php** (Database Connection & Helpers)

#### Backend (PHP Logic Only - No Frontend)
**Main Functions:**

1. **Connection:**
```php
$conn = new mysqli($host, $db_user, $db_pass);
$conn->select_db($db_name);
```

2. **Helper: get_or_create_order()**
```php
function get_or_create_order($order_number, $customer_email) {
    global $conn;
    
    // 1. Validate email
    if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
        return null;
    }
    
    // 2. Sanitize order number (alphanumeric only)
    $order_number = preg_replace('/[^a-zA-Z0-9\-]/', '', $order_number);
    
    // 3. Check if order exists
    $stmt = $conn->prepare('SELECT * FROM orders WHERE order_number = ?');
    $stmt->bind_param('s', $order_number);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return $row; // Order exists
    }
    
    // 4. Create new order if doesn't exist
    $status = 'Pending Review';
    $delivery_date = date('Y-m-d', strtotime('+5 days'));
    
    $stmt = $conn->prepare('INSERT INTO orders (order_number, customer_email, status, delivery_date) VALUES (?, ?, ?, ?)');
    $stmt->bind_param('ssss', $order_number, $customer_email, $status, $delivery_date);
    $stmt->execute();
    
    return get_or_create_order($order_number, $customer_email); // Recursive call to return created order
}
```

3. **Helper: sanitize_input()**
```php
function sanitize_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}
```

---

### ⚙️ **config/session.php** (Session Security)

#### Backend (PHP Logic Only)
**Security Functions:**

```php
// 1. Start secure session
session_start([
    'use_strict_mode' => 1,
    'cookie_httponly' => 1,
    'cookie_secure' => 0, // Set to 1 if using HTTPS
    'cookie_samesite' => 'Lax',
]);

// 2. Timeout after 3600 seconds (1 hour)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 3600)) {
    session_unset();
    session_destroy();
}
$_SESSION['last_activity'] = time();

// 3. IP validation (prevent session hijacking)
if (!isset($_SESSION['ip'])) {
    $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
} elseif ($_SESSION['ip'] !== $_SERVER['REMOTE_ADDR']) {
    session_unset();
    session_destroy();
}

// 4. Generate CSRF token
function generate_csrf_token() {
    if (!isset($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}

// 5. Verify CSRF token (timing-safe comparison)
function verify_csrf_token($token) {
    if (!isset($_SESSION['_csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['_csrf_token'], $token);
}

// 6. Print CSRF token field in form
function get_csrf_field() {
    return '<input type="hidden" name="_csrf_token" value="' . htmlspecialchars(generate_csrf_token()) . '">';
}
```

**What Each Security Feature Does:**
- `use_strict_mode`: Rejects invalid session IDs
- `cookie_httponly`: JavaScript can't access session cookie (prevents XSS)
- `cookie_samesite`: Sessions only work on your site (prevents CSRF)
- IP validation: If hacker steals cookie from different IP, session dies
- CSRF token: Random 64-char string prevents form forgery
- `hash_equals()`: Timing-safe comparison (prevents timing attacks)

---

### ⚙️ **config/error-handler.php** (Error Logging)

#### Backend (PHP Logic Only)
```php
// 1. Disable error display to users
ini_set('display_errors', '0');
ini_set('error_reporting', E_ALL);
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../logs/error.log');

// 2. Custom error handler (catches warnings, notices, etc)
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (!is_dir(__DIR__ . '/../logs')) {
        mkdir(__DIR__ . '/../logs', 0755, true);
    }
    
    $logMessage = date('Y-m-d H:i:s') . " | IP: {$_SERVER['REMOTE_ADDR']} | Error: $errstr | File: $errfile:$errline\n";
    file_put_contents(__DIR__ . '/../logs/error.log', $logMessage, FILE_APPEND);
    
    // Don't execute internal PHP error handler
    return true;
});

// 3. Exception handler (catches thrown exceptions)
set_exception_handler(function($exception) {
    $logMessage = date('Y-m-d H:i:s') . " | IP: {$_SERVER['REMOTE_ADDR']} | Exception: " . $exception->getMessage() . " | File: " . $exception->getFile() . ":" . $exception->getLine() . "\n";
    file_put_contents(__DIR__ . '/../logs/error.log', $logMessage, FILE_APPEND);
});

// 4. Shutdown handler (catches fatal errors)
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null) {
        $logMessage = date('Y-m-d H:i:s') . " | Fatal Error: " . $error['message'] . " | " . $error['file'] . ":" . $error['line'] . "\n";
        file_put_contents(__DIR__ . '/../logs/error.log', $logMessage, FILE_APPEND);
    }
});
```

**What It Does:**
- Hides errors from website visitors (security)
- Logs all errors to `/logs/error.log` with timestamp + IP
- Creates `/logs` folder automatically if missing
- Catches all error types: warnings, notices, exceptions, fatal errors

---

### ⚙️ **create_tables.php** (Database Initialization)

#### Backend (PHP Logic Only)
```php
// 1. Connect to MySQL (not the database yet)
$pdo = new PDO("mysql:host=$host;charset=utf8mb4", $db_user, $db_pass);

// 2. Create database
$pdo->exec("CREATE DATABASE IF NOT EXISTS `support_portal`");

// 3. Select database
$pdo->exec("USE `support_portal`");

// 4. Create tables (with schemas, indexes, foreign keys)
// ... creates orders, complaints, admins tables ...

// 5. Insert admin accounts
foreach ($adminAccounts as $account) {
    $hash = password_hash($password, PASSWORD_DEFAULT); // Bcrypt hashing
    $stmt = $pdo->prepare('INSERT INTO admins (name, email, password) VALUES (?, ?, ?)');
    $stmt->execute([$name, $email, $hash]);
}
```

**Run This Once:**
```bash
php create_tables.php
```

It will:
- Create `support_portal` database
- Create 3 tables with proper structure
- Insert admin accounts with hashed passwords

---

## Troubleshooting

### Database Connection Error
- Check MySQL is running
- Verify credentials in `config/db.php`
- Ensure `support_portal` database exists

### Login Not Working
- Use exact credentials: `mo` / `123` or `admin@test.com` / `123456`
- Check browser cookies are enabled
- Clear browser cache

### Complaints Not Appearing
- Ensure order exists with correct order_number
- Check database connection
- Review error logs in `/logs/error.log`

## Support
For issues or questions, check the error logs in `/logs/error.log`
