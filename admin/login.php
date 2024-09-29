<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: dash');
        exit;
    } else {
        $error = "Ungültige Anmeldeinformationen";
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="/src/style.css">
    <link rel="stylesheet" href="/src/admin.css">
</head>
<body>
    <h1>Admin Login</h1>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Benutzername" required>
        <input type="password" name="password" placeholder="Passwort" required>
        <button type="submit">👤 Anmelden</button>
    </form>
</body>
</html>