<?php
// Validar método HTTP permitido
$allowed_methods = ['GET', 'POST', 'HEAD'];
if (!in_array($_SERVER['REQUEST_METHOD'], $allowed_methods)) {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: ' . implode(', ', $allowed_methods));
    exit('Method Not Allowed');
}

// Configure and start session
require_once 'session_config.php';
session_start();

require_once 'config.php';
require_once 'security.php';

// Generate CSRF token
$csrf_token = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Project Delta</title>
    <!-- Bootstrap CSS desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
        }
        .form-signin {
            max-width: 330px;
            padding: 1rem;
        }
        .form-signin .form-floating:not(:last-child) {
            margin-bottom: -1px;
        }
        .form-signin .form-floating .form-control {
            border-radius: 0;
        }
        .form-signin .form-floating:first-child .form-control {
            border-top-left-radius: var(--bs-border-radius);
            border-top-right-radius: var(--bs-border-radius);
        }
        .form-signin .form-floating:last-child .form-control {
            border-bottom-left-radius: var(--bs-border-radius);
            border-bottom-right-radius: var(--bs-border-radius);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 bg-body-tertiary">
    <main class="form-signin">
        <form method="POST" action="login.php">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            <h1 class="h3 mb-3 fw-normal text-center">Project Delta</h1>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>
            
            <div class="form-floating">
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                <label for="email">Email address</label>
            </div>
            
            <div class="form-floating">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            
            <div class="form-check text-start my-3">
                <input class="form-check-input" type="checkbox" value="remember-me" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            
            <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
            
            <p class="mt-3 mb-3 text-body-secondary text-center">
                <small>Usuarios de prueba:<br>
                admin@projectdelta.local / admin123<br>
                user@projectdelta.local / admin123</small>
            </p>
            
            <p class="mt-5 mb-3 text-body-secondary text-center">© 2025 Project Delta</p>
        </form>
    </main>
    
    <!-- Bootstrap JS desde CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>