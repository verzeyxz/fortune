<?php
require_once 'includes/db.php';
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = getDbConnection();
    $stmt = $db->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user['username'];
        header('Location: index.php');
        exit;
    } else {
        $error = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แอดมินล็อกอิน</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="login-container">
        <form class="login-form" method="POST">
            <h2>แอดมินล็อกอิน</h2>
            <?php if ($error): ?>
                <p style="color: red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <label for="username">ชื่อผู้ใช้:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">รหัสผ่าน:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">ล็อกอิน</button>
        </form>
    </div>
</body>
</html>