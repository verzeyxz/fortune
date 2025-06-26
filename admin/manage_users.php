<?php
require_once __DIR__ . '/includes/functions.php';
check_admin_login();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการผู้ใช้งาน - Ref Fortune</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php include '_navigation.php'; ?>
    <div class="main-content container">
        <h1>จัดการผู้ใช้งาน</h1>
        <p>รายชื่อผู้ใช้งานทั้งหมดที่ลงทะเบียนในระบบ</p>
        
        <input type="text" id="user-search" placeholder="ค้นหาด้วยชื่อผู้ใช้หรืออีเมล..." style="margin-top: 20px; margin-bottom: 20px;">

        <table id="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ชื่อผู้ใช้</th>
                    <th>อีเมล</th>
                    <th>วันที่สมัคร</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                </tbody>
        </table>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableBody = document.querySelector('#users-table tbody');
    const searchInput = document.getElementById('user-search');
    let allUsers = []; // เก็บข้อมูลผู้ใช้ทั้งหมดไว้เพื่อการค้นหา

    // ฟังก์ชันสำหรับดึงข้อมูลผู้ใช้จาก API
    async function fetchUsers() {
        try {
            const response = await fetch('api/users.php');
            const result = await response.json();
            if (result.success) {
                allUsers = result.data;
                renderTable(allUsers);
            } else {
                tableBody.innerHTML = `<tr><td colspan="5">เกิดข้อผิดพลาด: ${result.message}</td></tr>`;
            }
        } catch (error) {
            console.error("Failed to fetch users:", error);
            tableBody.innerHTML = `<tr><td colspan="5">ไม่สามารถโหลดข้อมูลผู้ใช้งานได้</td></tr>`;
        }
    }
    
    // ฟังก์ชันสำหรับแสดงผลข้อมูลลงในตาราง (เหลือแค่ฟังก์ชันเดียวที่ถูกต้อง)
    function renderTable(users) {
        tableBody.innerHTML = '';
        if (users.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="5" style="text-align: center;">ไม่พบข้อมูลผู้ใช้งาน</td></tr>`;
            return;
        }
        users.forEach(user => {
            const date = new Date(user.created_at).toLocaleDateString('th-TH', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            const row = tableBody.insertRow();
            row.innerHTML = `
                <td>${user.id}</td>
                <td>${user.username}</td>
                <td>${user.email}</td>
                <td>${date}</td>
                <td class="actions">
                    <a href="view_user.php?id=${user.id}" class="btn-edit" style="text-decoration: none;">ดูรายละเอียด</a>
                </td>
            `;
        });
    }

    // Event Listener สำหรับช่องค้นหา
    searchInput.addEventListener('input', (e) => {
        const searchTerm = e.target.value.toLowerCase();
        const filteredUsers = allUsers.filter(user => 
            user.username.toLowerCase().includes(searchTerm) ||
            user.email.toLowerCase().includes(searchTerm)
        );
        renderTable(filteredUsers);
    });

    // เรียกใช้ฟังก์ชันเพื่อโหลดข้อมูลเมื่อหน้าเว็บพร้อมใช้งาน
    fetchUsers();
});
</script>
</body>
</html>