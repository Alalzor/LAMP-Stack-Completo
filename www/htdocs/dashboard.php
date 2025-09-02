<?php
// Validar método HTTP permitido
$allowed_methods = ['GET', 'POST', 'HEAD'];
if (!in_array($_SERVER['REQUEST_METHOD'], $allowed_methods)) {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: ' . implode(', ', $allowed_methods));
    exit('Method Not Allowed');
}

session_start();

// Verificación simple de login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Función simple para escape
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Project Delta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Project Delta</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    Welcome, <?php echo isset($_SESSION['user_name']) ? escape($_SESSION['user_name']) : 'User'; ?>
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1>Dashboard</h1>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Welcome to the system!</h5>
                        <p class="card-text">
                            You have successfully logged in as: <strong><?php echo isset($_SESSION['user_email']) ? escape($_SESSION['user_email']) : 'Unknown'; ?></strong>
                        </p>
                        <p class="card-text">
                            <small class="text-muted">User ID: <?php echo isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 'N/A'; ?></small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
