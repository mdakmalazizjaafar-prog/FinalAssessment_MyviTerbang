<?php
session_start();
include 'db.php';

$error = '';
$notice = isset($_GET['registered']) ? 'Account created. You can now log in.' : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare('SELECT username, password FROM users WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['username'] = $user['username'];

        header('Location: index.php');
        exit;
    }

    $error = 'Invalid username or password.';

    if ($user && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    $_SESSION['username'] = $user['username'];

    header('Location: index.php');
    exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log in</title>
</head>
<body>
    <h1>Log in</h1>

    <p><?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?></p>

    <form method="POST" action="login.php">
        <label>Username <input type="text" name="username" required></label><br>
        <label>Password <input type="password" name="password" required></label><br>
        <button type="submit">Log in</button>
    </form>

    <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <a href="signup.php">Create an account</a>
</body>
</html>