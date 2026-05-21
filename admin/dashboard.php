<?php
session_start();
require_once 'config.php';

// Require admin login
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Fetch dashboard stats
try {
    $stats = [
        'total' => $pdo->query("SELECT COUNT(*) FROM applications")->fetchColumn(),
        'new' => $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'new'")->fetchColumn(),
        'reviewed' => $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'reviewed'")->fetchColumn(),
        'hired' => $pdo->query("SELECT COUNT(*) FROM applications WHERE status = 'hired'")->fetchColumn(),
    ];
    $stmt = $pdo->query("SELECT * FROM applications ORDER BY applied_at DESC LIMIT 50");
    $applications = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Database error loading dashboard.";
    error_log($e->getMessage());
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $app_id = (int)$_POST['app_id'];
    $new_status = $_POST['new_status'];
    $allowed = ['new', 'reviewed', 'shortlisted', 'hired', 'rejected'];

    if (in_array($new_status, $allowed)) {
        try {
            $stmt = $pdo->prepare("UPDATE applications SET status = ?, reviewed_at = NOW(), reviewed_by = ? WHERE id = ?");
            $stmt->execute([$new_status, $_SESSION['admin_id'], $app_id]);
            header('Location: dashboard.php?updated=1');
            exit;
        } catch (PDOException $e) {
            $error = "Failed to update status.";
        }
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $app_id = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("SELECT cv_path FROM applications WHERE id = ?");
        $stmt->execute([$app_id]);
        $file = $stmt->fetch();
        if ($file && file_exists($file['cv_path'])) unlink($file['cv_path']);
        
        $pdo->prepare("DELETE FROM applications WHERE id = ?")->execute([$app_id]);
        header('Location: dashboard.php?deleted=1');
        exit;
    } catch (PDOException $e) {
        $error = "Failed to delete application.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | TEBS</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #0f172a; --accent: #d4af37; --accent-hover: #b8952a;
            --text-dark: #1e293b; --text-light: #64748b; --bg-light: #f8fafc;
        }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg-light); color: var(--text-dark); }
        .admin-header {
            background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.1); padding: 1rem 0;
            position: sticky; top: 0; z-index: 1000;
        }
        .stat-card {
            background: #fff; border-radius: 12px; padding: 1.5rem;
            border: 1px solid #e2e8f0; transition: all 0.3s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        .stat-card .icon {
            width: 48px; height: 48px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin-bottom: 1rem;
        }
        .stat-card h3 { font-size: 1.75rem; font-weight: 700; margin: 0; }
        .stat-card p { margin: 0.25rem 0 0; color: var(--text-light); font-size: 0.9rem; }
        .status-badge {
            padding: 0.35em 0.7em; border-radius: 50px; font-size: 0.75rem; font-weight: 600;
        }
        .status-new { background: #fef3c7; color: #92400e; }
        .status-reviewed { background: #dbeafe; color: #1e40af; }
        .status-shortlisted { background: #dcfce7; color: #166534; }
        .status-hired { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .table-container { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
        .logout-btn { color: #fff; text-decoration: none; padding: 0.5rem 1rem; border-radius: 8px; transition: all 0.2s; }
        .logout-btn:hover { background: rgba(255,255,255,0.1); color: var(--accent); }
    </style>
</head>
<body>
    <!-- Admin Header -->
    <header class="admin-header">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <img src="../assets/images/logo.png" alt="TEBS" height="36">
                <span class="text-white fw-bold d-none d-md-inline">Admin Dashboard</span>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white opacity-75 d-none d-md-inline">Welcome, <?= htmlspecialchars($_SESSION['admin_name']) ?></span>
                <a href="logout.php" class="logout-btn"><i class="bi bi-box-arrow-right me-1"></i> Logout</a>
            </div>
        </div>
    </header>

    <main class="container py-4">
        <?php if (isset($_GET['updated'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">Status updated successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">Application deleted successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= htmlspecialchars($error) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="icon" style="background: rgba(15,23,42,0.1); color: var(--primary);"><i class="bi bi-inbox"></i></div>
                    <h3><?= number_format($stats['total']) ?></h3><p>Total Applications</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="icon" style="background: rgba(212,175,55,0.1); color: var(--accent);"><i class="bi bi-hourglass-split"></i></div>
                    <h3><?= number_format($stats['new']) ?></h3><p>Pending Review</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="icon" style="background: rgba(59,130,246,0.1); color: #3b82f6;"><i class="bi bi-check-circle"></i></div>
                    <h3><?= number_format($stats['reviewed']) ?></h3><p>Reviewed</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="icon" style="background: rgba(34,197,94,0.1); color: #22c55e;"><i class="bi bi-person-check"></i></div>
                    <h3><?= number_format($stats['hired']) ?></h3><p>Hired</p>
                </div>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="table-container">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Recent Applications</h5>
                <span class="text-muted small"><?= count($applications) ?> records</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Applied</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($applications)): ?>
                            <tr><td colspan="6" class="text-center py-4 text-muted">No applications found</td></tr>
                        <?php else: ?>
                            <?php foreach ($applications as $app): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($app['full_name']) ?></strong></td>
                                    <td><?= htmlspecialchars($app['email']) ?></td>
                                    <td><?= htmlspecialchars($app['preferred_role'] ?? 'N/A') ?></td>
                                    <td><span class="status-badge status-<?= $app['status'] ?>"><?= ucfirst($app['status']) ?></span></td>
                                    <td><?= date('M d, Y', strtotime($app['applied_at'])) ?></td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="../admin/uploads/<?= basename($app['cv_path']) ?>" target="_blank" class="btn btn-outline-primary btn-sm" title="View CV"><i class="bi bi-eye"></i></a>
                                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#statusModal" data-id="<?= $app['id'] ?>" data-status="<?= $app['status'] ?>" title="Change Status"><i class="bi bi-pencil"></i></button>
                                            <a href="?delete=<?= $app['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this application and its CV?')" title="Delete"><i class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Status Change Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Application Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="app_id" id="modalAppId">
                        <input type="hidden" name="update_status" value="1">
                        <div class="mb-3">
                            <label class="form-label">Select New Status</label>
                            <select name="new_status" class="form-select" required>
                                <option value="new">New</option>
                                <option value="reviewed">Reviewed</option>
                                <option value="shortlisted">Shortlisted</option>
                                <option value="hired">Hired</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-accent">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('statusModal').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            document.getElementById('modalAppId').value = button.getAttribute('data-id');
            document.querySelector('#statusModal select').value = button.getAttribute('data-status');
        });
    </script>
</body>
</html>