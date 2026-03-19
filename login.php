<?php
require_once __DIR__ . '/auth.php';

if (current_user()) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        set_flash('error', 'Username and password are required.');
        redirect('login.php');
    }

    $stmt = $pdo->prepare('SELECT id, username, full_name, password FROM users WHERE username = :username LIMIT 1');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if (!$user || $user['password'] !== $password) {
        set_flash('error', 'Invalid credentials.');
        redirect('login.php');
    }

    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'username' => $user['username'],
        'full_name' => $user['full_name'],
    ];

    set_flash('success', 'Welcome back, ' . $user['full_name'] . '.');
    redirect('index.php');
}

$pageTitle = 'Login';
require_once __DIR__ . '/header.php';
?>

<div class="card">
    <h2>Login</h2>
    <form method="post">
        <div class="row">
            <div>
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
        </div>
        <button class="btn" type="submit">Login</button>
    </form>
</div>

<?php require_once __DIR__ . '/footer.php';
