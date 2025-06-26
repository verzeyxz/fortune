<?php
require_once __DIR__ . '/includes/functions.php';
check_admin_login();

$userId = $_GET['id'] ?? null;
if (!$userId) {
    header('Location: manage_users.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ข้อมูลผู้ใช้งาน - Ref Fortune</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php include '_navigation.php'; ?>
    <div class="main-content container">
        <h1 id="user-title">กำลังโหลดข้อมูลผู้ใช้งาน...</h1>
        <div id="user-details" class="user-details-grid">
            </div>
        <a href="manage_users.php" style="margin-top: 20px; display: inline-block;">&larr; กลับไปหน้ารายชื่อผู้ใช้</a>
    </div>

<style>
    .user-details-grid { display: grid; grid-template-columns: 150px 1fr; gap: 10px; margin-top: 20px; }
    .user-details-grid strong { font-weight: bold; }
</style>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const userId = <?= json_encode($userId) ?>;
    const userTitle = document.getElementById('user-title');
    const userDetails = document.getElementById('user-details');

    try {
        const response = await fetch(`api/users.php?id=${userId}`);
        const result = await response.json();

        if (result.success) {
            const user = result.data;
            userTitle.textContent = `ข้อมูลผู้ใช้งาน: ${user.username}`;
            userDetails.innerHTML = `
                <strong>User ID:</strong>       <span>${user.id}</span>
                <strong>ชื่อผู้ใช้:</strong>   <span>${user.username}</span>
                <strong>อีเมล:</strong>        <span>${user.email}</span>
                <strong>วันที่สมัคร:</strong>   <span>${new Date(user.created_at).toLocaleDateString('th-TH')}</span>
                <strong style="color: purple;">จำนวนครั้งที่เปิดไพ่:</strong> <span style="font-weight: bold; font-size: 1.2em; color: purple;">${user.reading_count} ครั้ง</span>
            `;
        } else {
            userTitle.textContent = 'ไม่พบข้อมูล';
            userDetails.innerHTML = `<p style="color: red;">${result.message}</p>`;
        }
    } catch (error) {
        userTitle.textContent = 'เกิดข้อผิดพลาด';
        userDetails.innerHTML = '<p style="color: red;">ไม่สามารถโหลดข้อมูลได้</p>';
    }
});
</script>
</body>
</html>