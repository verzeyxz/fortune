<?php
require_once 'includes/functions.php';
check_admin_login();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Ref Fortune</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php include '_navigation.php'; ?>
    <div class="main-content">
        <h1>ยินดีต้อนรับ, <?= htmlspecialchars($_SESSION['admin_username']) ?>!</h1>
        <p>นี่คือศูนย์บัญชาการของคุณ เลือกเมนูเพื่อเริ่มจัดการส่วนต่างๆ ของเว็บไซต์</p>
        <div class="dashboard-widgets">
             </div>
    </div>
</body>
</html>