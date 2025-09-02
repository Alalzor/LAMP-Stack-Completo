<?php
require_once 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (!empty($email) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT id, email, password, name FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['name'];
                
                header('Location: dashboard.php');
                exit;
            } else {
                $error = "Email o contraseña incorrectos";
            }
        } catch (PDOException $e) {
            $error = "Error de conexión a la base de datos";
        }
    } else {
        $error = "Por favor, completa todos los campos";
    }
}

// Si hay error, redirigir al index con el mensaje
if (isset($error)) {
    header("Location: index.php?error=" . urlencode($error));
    exit;
}
?>
