<?php
session_start();
// Include database connection if you have one set up in a shared config
// require_once 'includes/config.php'; 

// Redirect if already logged in
// if (isset($_SESSION['user_id'])) { header('Location: dashboard.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        // TODO: Connect to DB and verify user
        // $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        // $stmt->execute([$email]);
        // $user = $stmt->fetch();
        
        // if ($user && password_verify($password, $user['password_hash'])) {
        //     $_SESSION['user_id'] = $user['id'];
        //     header('Location: dashboard.php');
        //     exit;
        // } else {
            $error = 'Invalid credentials. (Placeholder until DB is connected)';
        // }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login | TEBS</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
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
        .form-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2); }
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
        .footer-links { text-align: center; margin-top: 1.5rem; font-size: 0.9rem; opacity: 0.9; }
        .footer-links a { color: var(--accent); text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="assets/images/logo.png" alt="TEBS Logo">
            <h2>Welcome Back</h2>
            <p>Sign in to access your dashboard</p>
        </div>

        <?php if ($error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="you@company.co.za" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-accent">Sign In</button>
        </form>

        <div class="footer-links">
            <p>New to TEBS? <a href="register.php">Create an account</a></p>
            <a href="index.html">← Back to TEBS</a>
        </div>
    </div>
</body>
</html>