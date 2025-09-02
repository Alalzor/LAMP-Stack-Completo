<?php
require_once 'config.php';
requireLogin();
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
                    Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1>Dashboard</h1>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">¡Bienvenido al sistema!</h5>
                        <p class="card-text">
                            Has iniciado sesión correctamente como: <strong><?php echo htmlspecialchars($_SESSION['user_email']); ?></strong>
                        </p>
                        <p class="card-text">
                            <small class="text-muted">ID de usuario: <?php echo $_SESSION['user_id']; ?></small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
