<?php
session_start();
include 'db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || strlen($password) < 8) {
        $error = 'Enter a username and a password of at least 8 characters.';
    } else {
        $check = $conn->prepare('SELECT username FROM users WHERE username = ?');
        $check->bind_param('s', $username);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {
            $error = 'That username is already taken.';
        } else {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                'INSERT INTO users (username, password) VALUES (?, ?)'
            );
            $stmt->bind_param('ss', $username, $passwordHash);

            if ($stmt->execute()) {
                header('Location: login.php?registered=1');
                exit;
            }

            $error = 'Could not create your account. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign up</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
    <h1>Create an account</h1>

    <form method="POST" action="signup.php">
        <label>Username <input type="text" name="username" required></label><br>
        <label>Password <input type="password" name="password" minlength="8" required></label><br>
        <button type="submit">Sign up</button>
    </form>

    <p><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
    <a href="login.php">Already have an account? Log in</a>
    </div>
</body>
</html>