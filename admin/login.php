<?php
session_start();
require_once 'config.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
            $stmt->execute([$email]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                session_regenerate_id(true); // Prevent session fixation
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_email'] = $admin['email'];
                $_SESSION['admin_name'] = $admin['full_name'] ?? 'Admin';
                
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } catch (PDOException $e) {
            $error = 'Database error. Please try again.';
            error_log("Admin login error: " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | TEBS</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root { --primary: #0f172a; --accent: #d4af37; --accent-hover: #b8952a; }
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            color: #fff; margin: 0; padding: 20px;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px; padding: 2.5rem; width: 100%; max-width: 450px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }
        .login-header { text-align: center; margin-bottom: 2rem; }
        .login-header img { height: 50px; margin-bottom: 1rem; }
        .login-header h2 { margin: 0; font-weight: 700; color: var(--accent); }
        .login-header p { margin: 0.5rem 0 0; opacity: 0.9; font-size: 0.95rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; font-size: 0.9rem; }
        .form-control {
            width: 100%; padding: 0.75rem 1rem; border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px; background: rgba(255, 255, 255, 0.1); color: #fff;
            font-size: 1rem; transition: all 0.2s;
        }
        .form-control:focus {
            outline: none; border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
        }
        .form-control::placeholder { color: rgba(255, 255, 255, 0.6); }
        .btn-accent {
            background: var(--accent); color: #0f172a; border: none; padding: 0.75rem 1.5rem;
            border-radius: 10px; font-weight: 600; font-size: 1rem; cursor: pointer;
            transition: all 0.2s; width: 100%;
        }
        .btn-accent:hover { background: var(--accent-hover); transform: translateY(-2px); box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3); }
        .error-message {
            background: rgba(239, 68, 68, 0.2); border: 1px solid rgba(239, 68, 68, 0.4);
            color: #fecaca; padding: 0.75rem 1rem; border-radius: 10px; margin-bottom: 1rem; font-size: 0.9rem;
        }
        .back-link { text-align: center; margin-top: 1.5rem; }
        .back-link a { color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.9rem; }
        .back-link a:hover { color: var(--accent); }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="../assets/images/logo.png" alt="TEBS Logo">
            <h2>Admin Portal</h2>
            <p>Sign in to manage applications & users</p>
        </div>

        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="admin@tebs.co.za" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-accent">Sign In</button>
        </form>

        <div class="back-link">
            <a href="../index.html">← Back to TEBS Website</a>
        </div>
    </div>
</body>
</html>