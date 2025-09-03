<?php
require_once 'config.php';
require_once 'security.php';
requireLogin();

// Get current user data from database
$currentUser = getUserById($_SESSION['user_id']);
if (!$currentUser) {
    // Fallback to session data if database user not found
    $currentUser = [
        'id' => $_SESSION['user_id'],
        'email' => $_SESSION['user_email'] ?? 'Unknown',
        'first_name' => $_SESSION['user_name'] ?? 'Unknown',
        'last_name' => 'User',
        'job_title' => $_SESSION['user_job_title'] ?? 'Not specified',
        'department' => $_SESSION['user_department'] ?? 'Not specified',
        'employee_id' => $_SESSION['user_employee_id'] ?? 'N/A'
    ];
}

// Get dashboard statistics
$stats = getDashboardStats();
$recentActivities = getRecentActivities(5);

// Get recent employees for quick view
$recentEmployees = [];
try {
    if ($pdo) {
        $stmt = $pdo->query("
            SELECT first_name, last_name, job_title, department, hire_date 
            FROM users 
            WHERE status = 'active' 
            ORDER BY hire_date DESC 
            LIMIT 5
        ");
        $recentEmployees = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    error_log("Error fetching recent employees: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Project Delta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/dashboard-styles.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-building"></i>
                Project Delta
            </a>
            <div class="navbar-nav">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="user-avatar me-2">
                            <?= strtoupper(substr($currentUser['first_name'] ?? 'U', 0, 1)) ?>
                        </div>
                        <?= escape($currentUser['first_name'] ?? 'User') ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="nav flex-column nav-pills">
            <a class="nav-link active" href="dashboard.php">
                <i class="fas fa-home"></i>Dashboard
            </a>
            <a class="nav-link" href="#employees">
                <i class="fas fa-users"></i>Employees
            </a>
            <a class="nav-link" href="#departments">
                <i class="fas fa-building"></i>Departments
            </a>
            <a class="nav-link" href="#projects">
                <i class="fas fa-project-diagram"></i>Projects
            </a>
            <a class="nav-link" href="#reports">
                <i class="fas fa-chart-bar"></i>Reports
            </a>
            <a class="nav-link" href="#settings">
                <i class="fas fa-cog"></i>Settings
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Welcome Section -->
        <div class="welcome-section fade-in">
            <h1 class="welcome-title">Welcome back, <?= escape($currentUser['first_name'] ?? 'User') ?>!</h1>
            <p class="welcome-subtitle">Here's what's happening with your team today.</p>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card primary">
                    <div class="stats-icon primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stats-number"><?= $stats['users']['total_users'] ?? 0 ?></div>
                    <div class="stats-label">Total Employees</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card success">
                    <div class="stats-icon success">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stats-number"><?= $stats['users']['active_users'] ?? 0 ?></div>
                    <div class="stats-label">Active Employees</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card warning">
                    <div class="stats-icon warning">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="stats-number"><?= $stats['users']['new_hires'] ?? 0 ?></div>
                    <div class="stats-label">New Hires This Month</div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card info">
                    <div class="stats-icon info">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stats-number"><?= count($stats['departments'] ?? []) ?></div>
                    <div class="stats-label">Departments</div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="row">
            <!-- Recent Employees -->
            <div class="col-xl-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Recent Employees</h5>
                            <span class="badge bg-primary"><?= count($recentEmployees) ?> employees</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Position</th>
                                        <th>Department</th>
                                        <th>Hire Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recentEmployees as $employee): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3 sm">
                                                    <?= strtoupper(substr($employee['first_name'], 0, 1) . substr($employee['last_name'], 0, 1)) ?>
                                                </div>
                                                <span><?= escape($employee['first_name'] . ' ' . $employee['last_name']) ?></span>
                                            </div>
                                        </td>
                                        <td><?= escape($employee['job_title']) ?></td>
                                        <td><?= escape($employee['department']) ?></td>
                                        <td><?= date('M d, Y', strtotime($employee['hire_date'])) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions & User Info -->
            <div class="col-xl-4 mb-4">
                <!-- Quick Actions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>New Employee
                            </button>
                            <button class="btn btn-outline-success">
                                <i class="fas fa-file-export me-2"></i>Export Data
                            </button>
                            <button class="btn btn-outline-info">
                                <i class="fas fa-chart-bar me-2"></i>View Reports
                            </button>
                            <button class="btn btn-outline-secondary">
                                <i class="fas fa-cog me-2"></i>Settings
                            </button>
                        </div>
                    </div>
                </div>

                <!-- User Info Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">My Profile</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="user-avatar me-3">
                                <?= strtoupper(substr($currentUser['first_name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <div>
                                <div class="fw-bold"><?= escape($currentUser['first_name'] . ' ' . ($currentUser['last_name'] ?? '')) ?></div>
                                <div class="text-muted small"><?= escape($currentUser['email']) ?></div>
                            </div>
                        </div>
                        <div class="small text-muted">
                            <div><strong>Position:</strong> <?= escape($currentUser['job_title'] ?? 'Not specified') ?></div>
                            <div><strong>Department:</strong> <?= escape($currentUser['department'] ?? 'Not specified') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>