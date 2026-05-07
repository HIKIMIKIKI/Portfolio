<?php
require_once __DIR__ . '/config.php';

if (is_admin_logged_in()) {
    header('Location: admin.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_id'] = 1;
        $_SESSION['admin_username'] = 'admin';
        setcookie('last_admin_login', date('Y-m-d H:i:s'), time() + (86400 * 30), '/');
        header('Location: admin.php');
        exit;
    }

    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto+Slab:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main class="section">
        <div class="container" style="display: flex; justify-content: flex-end; margin-bottom: 1rem; max-width: 520px;">
            <button class="theme-toggle" id="theme-toggle" type="button">Dark Mode</button>
        </div>
        <div class="container" style="max-width: 520px;">
            <div class="card">
                <p class="eyebrow">Admin Access</p>
                <h1 style="font-size: 2.3rem;">Login</h1>
                <p>Use username <strong>admin</strong> and password <strong>admin123</strong> to manage portfolio projects.</p>
                <?php if ($error !== ''): ?>
                    <p class="form-message error"><?= escape_html($error) ?></p>
                <?php endif; ?>
                <form method="post" class="contact-form">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>

                    <button class="button primary" type="submit">Login</button>
                </form>
                <p style="margin-top: 1rem;"><a href="index.html">Back to portfolio</a></p>
            </div>
        </div>
    </main>
    <script>
        const themeToggle = document.getElementById("theme-toggle");
        const savedTheme = localStorage.getItem("portfolio-theme");

        if (savedTheme === "dark") {
            document.body.classList.add("dark-theme");
            themeToggle.textContent = "Light Mode";
        }

        themeToggle.addEventListener("click", () => {
            document.body.classList.toggle("dark-theme");
            const isDark = document.body.classList.contains("dark-theme");
            themeToggle.textContent = isDark ? "Light Mode" : "Dark Mode";
            localStorage.setItem("portfolio-theme", isDark ? "dark" : "light");
        });
    </script>
</body>
</html>

