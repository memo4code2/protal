<?php
require_once '../config/error-handler.php';
require_once '../config/session.php';
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}
require_once '../config/db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['_csrf_token']) || !verify_csrf_token($_POST['_csrf_token'])) {
        $message = '<div class="alert alert-danger">Session expired. Please try again.</div>';
    } else {
        $action = $_POST['action'] ?? '';
        $complaint_id = intval($_POST['complaint_id'] ?? 0);
        $order_id = intval($_POST['order_id'] ?? 0);
        
        if ($complaint_id > 0 && $action !== 'check') {
            if ($action === 'close') {
                // Get the order_id from the complaint
                $getOrderStmt = $conn->prepare('SELECT order_id FROM complaints WHERE id = ?');
                $getOrderStmt->bind_param('i', $complaint_id);
                $getOrderStmt->execute();
                $orderResult = $getOrderStmt->get_result();
                if ($orderRow = $orderResult->fetch_assoc()) {
                    $complaintOrderId = $orderRow['order_id'];
                    
                    // Update order status to "Checked"
                    $updateOrderStmt = $conn->prepare('UPDATE orders SET status = ? WHERE id = ?');
                    $orderStatus = 'Checked';
                    $updateOrderStmt->bind_param('si', $orderStatus, $complaintOrderId);
                    $updateOrderStmt->execute();
                    $updateOrderStmt->close();
                }
                $getOrderStmt->close();
                
                // Close the complaint
                $stmt = $conn->prepare('UPDATE complaints SET status = ? WHERE id = ?');
                $status = 'Closed';
                $stmt->bind_param('si', $status, $complaint_id);
                $stmt->execute();
                $stmt->close();
                $message = '<div class="alert alert-success">Complaint marked as closed and order status updated to "Checked".</div>';
            }
            if ($action === 'delete') {
                $stmt = $conn->prepare('DELETE FROM complaints WHERE id = ?');
                $stmt->bind_param('i', $complaint_id);
                $stmt->execute();
                $stmt->close();
                $message = '<div class="alert alert-success">Complaint deleted successfully.</div>';
            }
        }
        
        if ($action === 'check' && $order_id > 0) {
            $stmt = $conn->prepare('UPDATE orders SET status = ?, checked_by_admin = ? WHERE id = ?');
            $status = 'Verified';
            $checked = true;
            $stmt->bind_param('sbi', $status, $checked, $order_id);
            $stmt->execute();
            $stmt->close();
            $message = '<div class="alert alert-success">Order has been checked successfully.</div>';
        }
    }
}
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header('Location: ../login.php');
    exit;
}
$statsTotal = 0;
$statsOpen = 0;
$statsClosed = 0;
$complaints = [];
$result = $conn->query('SELECT COUNT(*) AS total FROM complaints');
if ($result) {
    $statsTotal = (int)$result->fetch_assoc()['total'];
    $result->free();
}
$result = $conn->query("SELECT COUNT(*) AS total FROM complaints WHERE status = 'Open'");
if ($result) {
    $statsOpen = (int)$result->fetch_assoc()['total'];
    $result->free();
}
$result = $conn->query("SELECT COUNT(*) AS total FROM complaints WHERE status = 'Closed'");
if ($result) {
    $statsClosed = (int)$result->fetch_assoc()['total'];
    $result->free();
}
$query = 'SELECT c.id, c.order_id, c.customer_email, c.type, c.message, c.status, c.created_at, o.status AS order_status, o.checked_by_admin FROM complaints c LEFT JOIN orders o ON c.order_id = o.id ORDER BY c.created_at DESC LIMIT 500';
$result = $conn->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $complaints[] = $row;
    }
    $result->free();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin Dashboard - Support Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header class="header bg-white shadow-sm">
        <div class="container d-flex align-items-center justify-content-between py-3">
            <a class="navbar-brand fw-bold text-primary" href="dashboard.php">Support Portal Admin</a>
            <div>
                <span class="me-3 text-secondary"><?php echo htmlspecialchars($_SESSION['admin_name'] ?? $_SESSION['admin_email']); ?></span>
                <a href="dashboard.php?action=logout" class="btn btn-outline-primary">Logout</a>
            </div>
        </div>
    </header>
    <main class="py-5">
        <div class="container">
            <div class="d-flex flex-column flex-md-row align-items-start justify-content-between mb-4 gap-3">
                <div>
                    <h1 class="fw-bold text-primary">Admin Dashboard</h1>
                    <p class="text-secondary">Manage complaints and monitor support status.</p>
                </div>
            </div>
            <?php echo $message; ?>
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card stats-card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-secondary">Total Complaints</h6>
                            <h3 class="fw-bold"><?php echo $statsTotal; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stats-card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-secondary">Open Complaints</h6>
                            <h3 class="fw-bold text-warning"><?php echo $statsOpen; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stats-card shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-secondary">Closed Complaints</h6>
                            <h3 class="fw-bold text-success"><?php echo $statsClosed; ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4">Complaint Records</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Order ID</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($complaints) === 0): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-secondary py-4">No complaints submitted yet.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($complaints as $complaint): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($complaint['id']); ?></td>
                                            <td><?php echo htmlspecialchars($complaint['order_id']); ?></td>
                                            <td><?php echo htmlspecialchars($complaint['customer_email']); ?></td>
                                            <td><?php echo htmlspecialchars($complaint['type']); ?></td>
                                            <td><?php echo htmlspecialchars($complaint['message']); ?></td>
                                            <td>
                                                <?php if ($complaint['status'] === 'Closed'): ?>
                                                    <span class="badge bg-success">Closed</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark">Open</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($complaint['created_at']); ?></td>
                                            <td class="text-end">
                                                <?php if ($complaint['order_status'] !== 'Verified'): ?>
                                                    <form method="post" action="dashboard.php" class="d-inline-block">
                                                        <?php echo get_csrf_field(); ?>
                                                        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($complaint['order_id']); ?>">
                                                        <input type="hidden" name="action" value="check">
                                                        <button type="submit" class="btn btn-sm btn-info me-1">Check Order</button>
                                                    </form>
                                                <?php endif; ?>
                                                <?php if ($complaint['status'] !== 'Closed'): ?>
                                                    <form method="post" action="dashboard.php" class="d-inline-block">
                                                        <?php echo get_csrf_field(); ?>
                                                        <input type="hidden" name="complaint_id" value="<?php echo htmlspecialchars($complaint['id']); ?>">
                                                        <input type="hidden" name="action" value="close">
                                                        <button type="submit" class="btn btn-sm btn-success me-1">Mark Closed</button>
                                                    </form>
                                                <?php endif; ?>
                                                <form method="post" action="dashboard.php" class="d-inline-block">
                                                    <?php echo get_csrf_field(); ?>
                                                    <input type="hidden" name="complaint_id" value="<?php echo htmlspecialchars($complaint['id']); ?>">
                                                    <input type="hidden" name="action" value="delete">
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this complaint?');">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <footer class="footer py-4 bg-primary text-white">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="mb-2 mb-md-0">© 2026 Support Portal Admin.</p>
            <div>
                <a href="../index.php" class="text-white text-decoration-none me-3">Portal Home</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
