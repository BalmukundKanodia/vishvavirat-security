<?php
/**
 * VISHVAVIRAT SECURITY - Admin Panel
 * View and manage lead submissions
 */

session_start();

// Load configuration
require_once __DIR__ . '/../api/config.php';

// Simple authentication
if (!isset($_SESSION['admin_logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
            $_SESSION['admin_logged_in'] = true;
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid credentials';
        }
    }
    
    // Show login form
    include 'login.php';
    exit;
}

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = (int)$_POST['id'];
    $status = $_POST['status'];
    $notes = $_POST['notes'] ?? '';
    
    try {
        $db = new mysqli($db_host, $db_user, $db_pass, $db_name);
        $db->query("SET time_zone = '+05:30'");  // Set MySQL timezone to IST
        $stmt = $db->prepare("UPDATE contact_submissions SET status = ?, notes = ? WHERE id = ?");
        $stmt->bind_param("ssi", $status, $notes, $id);
        $stmt->execute();
        $stmt->close();
        $db->close();
        
        $success_message = 'Lead updated successfully';
    } catch (Exception $e) {
        $error_message = 'Error updating lead';
    }
}

// Get filter parameters
$filter_status = $_GET['status'] ?? 'all';
$filter_service = $_GET['service'] ?? 'all';
$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

// Connect to database
$db = new mysqli($db_host, $db_user, $db_pass, $db_name);
$db->query("SET time_zone = '+05:30'");  // Set MySQL timezone to IST
$db->set_charset('utf8mb4');

// Build query
$where_conditions = [];
$params = [];
$types = '';

if ($filter_status !== 'all') {
    $where_conditions[] = "status = ?";
    $params[] = $filter_status;
    $types .= 's';
}

if ($filter_service !== 'all') {
    $where_conditions[] = "service_type = ?";
    $params[] = $filter_service;
    $types .= 's';
}

if (!empty($search)) {
    $where_conditions[] = "(name LIKE ? OR email LIKE ? OR phone LIKE ? OR message LIKE ?)";
    $search_param = "%{$search}%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= 'ssss';
}

$where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

// Get total count
$count_query = "SELECT COUNT(*) as total FROM contact_submissions $where_clause";
$stmt = $db->prepare($count_query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$total_result = $stmt->get_result();
$total_rows = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $per_page);
$stmt->close();

// Get submissions
$query = "SELECT * FROM contact_submissions $where_clause ORDER BY created_at DESC LIMIT ? OFFSET ?";
$params[] = $per_page;
$params[] = $offset;
$types .= 'ii';

$stmt = $db->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$submissions = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get stats
$stats_query = "SELECT 
    COUNT(*) as total,
    COUNT(CASE WHEN status = 'new' THEN 1 END) as new_count,
    COUNT(CASE WHEN status = 'contacted' THEN 1 END) as contacted_count,
    COUNT(CASE WHEN status = 'converted' THEN 1 END) as converted_count,
    COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 END) as week_count,
    COUNT(CASE WHEN created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) as month_count
    FROM contact_submissions";
$stats_result = $db->query($stats_query);
$stats = $stats_result->fetch_assoc();

// Get unique service types
$services_result = $db->query("SELECT DISTINCT service_type FROM contact_submissions ORDER BY service_type");
$services = $services_result->fetch_all(MYSQLI_ASSOC);

$db->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - VISHVAVIRAT SECURITY</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f5f5;
            color: #333;
        }
        .header {
            background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
            color: #c9a961;
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 { font-size: 1.5rem; }
        .logout-btn {
            background: #c9a961;
            color: #1a1a1a;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
        }
        .logout-btn:hover { background: #b89551; }
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .stat-label {
            font-size: 0.875rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #1a1a1a;
        }
        .filters {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .filters form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: end;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
        }
        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.875rem;
        }
        .btn {
            background: #c9a961;
            color: #1a1a1a;
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.875rem;
        }
        .btn:hover { background: #b89551; }
        .btn-secondary {
            background: #666;
            color: white;
        }
        .btn-secondary:hover { background: #555; }
        .leads-table {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #f9f9f9;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.875rem;
            color: #666;
            border-bottom: 2px solid #eee;
        }
        td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.875rem;
        }
        tr:hover { background: #fafafa; }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
        }
        .status-new { background: #fef3c7; color: #92400e; }
        .status-contacted { background: #dbeafe; color: #1e40af; }
        .status-converted { background: #d1fae5; color: #065f46; }
        .status-closed { background: #f3f4f6; color: #374151; }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        .modal-content {
            background: white;
            max-width: 600px;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 8px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .modal-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #eee;
        }
        .close {
            float: right;
            font-size: 2rem;
            cursor: pointer;
            color: #666;
        }
        .close:hover { color: #333; }
        .field { margin-bottom: 1rem; }
        .field-label {
            font-weight: 600;
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }
        .field-value {
            color: #444;
            line-height: 1.6;
        }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }
        .pagination a, .pagination span {
            padding: 0.5rem 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #333;
        }
        .pagination a:hover { background: #f0f0f0; }
        .pagination .active { background: #c9a961; color: #1a1a1a; border-color: #c9a961; }
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #34d399;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-content">
            <h1>VISHVAVIRAT SECURITY - Admin Panel</h1>
            <a href="?logout=1" class="logout-btn">Logout</a>
        </div>
    </div>

    <div class="container">
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-error"><?= $error_message ?></div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Leads</div>
                <div class="stat-value"><?= $stats['total'] ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">New Leads</div>
                <div class="stat-value" style="color: #92400e;"><?= $stats['new_count'] ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Contacted</div>
                <div class="stat-value" style="color: #1e40af;"><?= $stats['contacted_count'] ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Converted</div>
                <div class="stat-value" style="color: #065f46;"><?= $stats['converted_count'] ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Last 7 Days</div>
                <div class="stat-value"><?= $stats['week_count'] ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Last 30 Days</div>
                <div class="stat-value"><?= $stats['month_count'] ?></div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <form method="GET">
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="all" <?= $filter_status === 'all' ? 'selected' : '' ?>>All Status</option>
                        <option value="new" <?= $filter_status === 'new' ? 'selected' : '' ?>>New</option>
                        <option value="contacted" <?= $filter_status === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                        <option value="converted" <?= $filter_status === 'converted' ? 'selected' : '' ?>>Converted</option>
                        <option value="closed" <?= $filter_status === 'closed' ? 'selected' : '' ?>>Closed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Service Type</label>
                    <select name="service" class="form-control">
                        <option value="all" <?= $filter_service === 'all' ? 'selected' : '' ?>>All Services</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?= htmlspecialchars($service['service_type']) ?>" 
                                <?= $filter_service === $service['service_type'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($service['service_type']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Search</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Name, email, phone..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn">Filter</button>
                </div>
                <div class="form-group">
                    <a href="index.php" class="btn btn-secondary" style="display: inline-block; text-align: center; text-decoration: none;">Clear</a>
                </div>
            </form>
        </div>

        <!-- Leads Table -->
        <div class="leads-table">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Service</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($submissions)): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem; color: #666;">
                                No leads found
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($submissions as $sub): ?>
                            <tr>
                                <td>#<?= $sub['id'] ?></td>
                                <td><?= date('d M Y', strtotime($sub['created_at'])) ?><br>
                                    <small style="color: #666;"><?= date('h:i A', strtotime($sub['created_at'])) ?></small>
                                </td>
                                <td><strong><?= htmlspecialchars($sub['name']) ?></strong></td>
                                <td>
                                    <a href="mailto:<?= htmlspecialchars($sub['email']) ?>"><?= htmlspecialchars($sub['email']) ?></a><br>
                                    <a href="tel:<?= htmlspecialchars($sub['phone']) ?>"><?= htmlspecialchars($sub['phone']) ?></a>
                                </td>
                                <td><?= htmlspecialchars($sub['service_type']) ?></td>
                                <td><?= htmlspecialchars($sub['location'] ?: '-') ?></td>
                                <td><span class="status-badge status-<?= $sub['status'] ?>"><?= ucfirst($sub['status']) ?></span></td>
                                <td>
                                    <button onclick="viewLead(<?= htmlspecialchars(json_encode($sub)) ?>)" 
                                            class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">
                                        View
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>&status=<?= $filter_status ?>&service=<?= $filter_service ?>&search=<?= urlencode($search) ?>">
                        « Previous
                    </a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                    <?php if ($i == $page): ?>
                        <span class="active"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?>&status=<?= $filter_status ?>&service=<?= $filter_service ?>&search=<?= urlencode($search) ?>">
                            <?= $i ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?>&status=<?= $filter_status ?>&service=<?= $filter_service ?>&search=<?= urlencode($search) ?>">
                        Next »
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Lead Detail Modal -->
    <div id="leadModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Lead Details</h2>
            </div>
            <div id="modalBody"></div>
        </div>
    </div>

    <script>
        function viewLead(lead) {
            const modal = document.getElementById('leadModal');
            const body = document.getElementById('modalBody');
            
            body.innerHTML = `
                <div class="field">
                    <div class="field-label">Lead ID:</div>
                    <div class="field-value">#${lead.id}</div>
                </div>
                <div class="field">
                    <div class="field-label">Submitted On:</div>
                    <div class="field-value">${new Date(lead.created_at).toLocaleString()}</div>
                </div>
                <div class="field">
                    <div class="field-label">Name:</div>
                    <div class="field-value">${lead.name}</div>
                </div>
                <div class="field">
                    <div class="field-label">Email:</div>
                    <div class="field-value"><a href="mailto:${lead.email}">${lead.email}</a></div>
                </div>
                <div class="field">
                    <div class="field-label">Phone:</div>
                    <div class="field-value"><a href="tel:${lead.phone}">${lead.phone}</a></div>
                </div>
                <div class="field">
                    <div class="field-label">Service Type:</div>
                    <div class="field-value">${lead.service_type}</div>
                </div>
                <div class="field">
                    <div class="field-label">Location:</div>
                    <div class="field-value">${lead.location || '-'}</div>
                </div>
                <div class="field">
                    <div class="field-label">Message:</div>
                    <div class="field-value" style="white-space: pre-wrap;">${lead.message}</div>
                </div>
                <div class="field">
                    <div class="field-label">Source Page:</div>
                    <div class="field-value">${lead.source_page || '-'}</div>
                </div>
                <div class="field">
                    <div class="field-label">IP Address:</div>
                    <div class="field-value">${lead.ip_address || '-'}</div>
                </div>
                
                <form method="POST" style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #eee;">
                    <input type="hidden" name="id" value="${lead.id}">
                    <input type="hidden" name="update_status" value="1">
                    
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label>Update Status:</label>
                        <select name="status" class="form-control">
                            <option value="new" ${lead.status === 'new' ? 'selected' : ''}>New</option>
                            <option value="contacted" ${lead.status === 'contacted' ? 'selected' : ''}>Contacted</option>
                            <option value="converted" ${lead.status === 'converted' ? 'selected' : ''}>Converted</option>
                            <option value="closed" ${lead.status === 'closed' ? 'selected' : ''}>Closed</option>
                        </select>
                    </div>
                    
                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label>Notes:</label>
                        <textarea name="notes" class="form-control" rows="4" placeholder="Add internal notes...">${lead.notes || ''}</textarea>
                    </div>
                    
                    <button type="submit" class="btn" style="width: 100%;">Update Lead</button>
                </form>
            `;
            
            modal.style.display = 'block';
        }
        
        function closeModal() {
            document.getElementById('leadModal').style.display = 'none';
        }
        
        window.onclick = function(event) {
            const modal = document.getElementById('leadModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
