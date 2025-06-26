<?php
// ใช้ __DIR__ เพื่อให้ Path ถูกต้องเสมอ
require_once __DIR__ . '/includes/functions.php';
check_admin_login();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการแพ็กเกจ - Ref Fortune</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <?php include '_navigation.php'; ?>
    <div class="main-content container">
        <h1>จัดการแพ็กเกจดูดวง</h1>

        <form id="package-form">
            <input type="hidden" id="package-id">
            <h2>เพิ่ม/แก้ไขแพ็กเกจ</h2>
            
            <select id="package-type" required>
                <option value="" disabled selected>-- เลือกประเภท --</option>
                <option value="main">แพ็กเกจหลัก</option>
                <option value="call">แพ็กเกจคอล</option>
                <option value="text">แพ็กเกจพิมพ์ตอบ</option>
                <option value="extra">แพ็กเกจเสริม</option>
            </select>
            
            <input type="text" id="package-name" placeholder="ชื่อแพ็กเกจ" required>
            <input type="text" id="package-price" placeholder="ราคา (เช่น 749 บาท)">
            <input type="text" id="package-duration" placeholder="ระยะเวลา (เช่น คอล 1 ชม.)">
            <textarea id="package-description" placeholder="รายละเอียด" rows="3"></textarea>
            
            <button type="submit" class="btn-save">บันทึกแพ็กเกจ</button>
            <button type="button" id="clear-form-btn">ล้างฟอร์ม</button>
        </form>

        <h2>รายการแพ็กเกจทั้งหมด</h2>
        <table id="packages-table">
            <thead>
                <tr>
                    <th>ชื่อแพ็กเกจ</th>
                    <th>ราคา</th>
                    <th>ประเภท</th>
                    <th>จัดการ</th>
                </tr>
            </thead>
            <tbody>
                </tbody>
        </table>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('package-form');
    const tableBody = document.querySelector('#packages-table tbody');
    const clearFormBtn = document.getElementById('clear-form-btn');

    // ฟังก์ชันสำหรับดึงและแสดงข้อมูลแพ็กเกจ
    async function fetchPackages() {
        try {
            const response = await fetch('api/packages.php');
            const result = await response.json();
            
            if (result && Array.isArray(result)) {
                renderTable(result);
            } else {
                 tableBody.innerHTML = `<tr><td colspan="4" style="text-align:center;">ไม่พบข้อมูลแพ็กเกจ หรือข้อมูลมีรูปแบบไม่ถูกต้อง</td></tr>`;
            }
        } catch (error) {
            console.error("Failed to fetch packages:", error);
            tableBody.innerHTML = `<tr><td colspan="4" style="text-align:center;">ไม่สามารถโหลดข้อมูลได้ เกิดข้อผิดพลาดในการเชื่อมต่อ</td></tr>`;
        }
    }
    
    // ฟังก์ชันสำหรับแสดงผลข้อมูลลงในตาราง (เหลือแค่ฟังก์ชันเดียวที่ถูกต้อง)
    function renderTable(packages) {
        tableBody.innerHTML = '';
        if (packages.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="4" style="text-align: center;">ยังไม่มีข้อมูลแพ็กเกจ</td></tr>`;
            return;
        }
        packages.forEach(pkg => {
            const row = tableBody.insertRow();
            row.innerHTML = `
                <td>${pkg.name || ''}</td>
                <td>${pkg.price || '-'}</td>
                <td>${pkg.type || ''}</td>
                <td class="actions">
                    <button class="btn-edit">แก้ไข</button>
                    <button class="btn-delete">ลบ</button>
                </td>
            `;

            // Event listener for Edit button
            row.querySelector('.btn-edit').addEventListener('click', () => {
                document.getElementById('package-id').value = pkg.id;
                document.getElementById('package-type').value = pkg.type;
                document.getElementById('package-name').value = pkg.name;
                document.getElementById('package-price').value = pkg.price;
                document.getElementById('package-duration').value = pkg.duration;
                document.getElementById('package-description').value = pkg.description;
                window.scrollTo(0, 0);
            });

            // Event listener for Delete button
            row.querySelector('.btn-delete').addEventListener('click', async () => {
                if (confirm(`คุณต้องการลบแพ็กเกจ "${pkg.name}" จริงๆ หรือ?`)) {
                    await fetch('api/packages.php', {
                        method: 'DELETE',
                        headers: {'Content-Type': 'application/json'},
                        body: JSON.stringify({ id: pkg.id })
                    });
                    fetchPackages();
                }
            });
        });
    }

    // Function to clear the form
    function clearForm() {
        form.reset();
        document.getElementById('package-id').value = '';
    }

    // Handle form submission (Add/Update)
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('package-id').value;
        const data = {
            type: document.getElementById('package-type').value,
            name: document.getElementById('package-name').value,
            price: document.getElementById('package-price').value,
            duration: document.getElementById('package-duration').value,
            description: document.getElementById('package-description').value
        };

        const method = id ? 'PUT' : 'POST';
        if (id) data.id = id;

        await fetch('api/packages.php', {
            method: method,
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });

        clearForm();
        fetchPackages();
    });

    // Event listener for clear form button
    clearFormBtn.addEventListener('click', clearForm);

    // Initial load
    fetchPackages();
});
</script>
</body>
</html>