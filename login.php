
<?php
session_start();
include 'db.php';

$error = '';
$notice = isset($_GET['registered'])
    ? 'Account created. You can now log in.'
    : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare(
        'SELECT username, password FROM users WHERE username = ?'
    );

    $stmt->bind_param('s', $username);
    $stmt->execute();

    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['username'] = $user['username'];

        header('Location: index.php');
        exit;

    } else {
        $error = 'Invalid username or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Log in</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="auth-container">

    <h1>Log in</h1>

    <?php if ($notice != ''): ?>
    <p class="success">
        <?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

    <form method="POST" action="login.php">

        <label>
            Username
            <input type="text" name="username" required>
        </label>

        <br>

        <label>
            Password
            <input type="password" name="password" required>
        </label>

        <br>

        <button type="submit">Log in</button>

    </form>

<?php if ($error != ''): ?>
    <p class="error">
        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

    <a href="signup.php">Create an account</a>

    </div>
</body>
</html>
