<?php
require_once __DIR__ . '/includes/functions.php';
check_admin_login();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการเนื้อหาเว็บไซต์ - Ref Fortune</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include '_navigation.php'; ?>
    <div class="main-content container">
        <h1>จัดการเนื้อหาเว็บไซต์</h1>
        <p>แก้ไขข้อความในส่วนต่างๆ ของหน้าเว็บไซต์หลัก</p>
        
        <form id="content-form">
            <h2>ส่วนหัว (Header)</h2>
            <label for="homepage_slogan">สโลแกน</label>
            <input type="text" id="homepage_slogan" name="homepage_slogan" placeholder="สโลแกน เช่น &quot;ชัด เคลียร์ ใช้ได้จริง&quot;">

            <h2 style="margin-top: 30px;">ส่วนท้าย (Footer)</h2>
            <label for="footer_line_contact">ข้อความติดต่อ LINE</label>
            <input type="text" id="footer_line_contact" name="footer_line_contact" placeholder="เช่น LINE @reffortune">

            <label for="footer_ig_contact">ข้อความติดต่อ Instagram</label>
            <input type="text" id="footer_ig_contact" name="footer_ig_contact" placeholder="เช่น IG @reffortune">

            <button type="submit" class="btn-save" style="margin-top: 20px;">บันทึกการเปลี่ยนแปลงทั้งหมด</button>
        </form>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('content-form');
    const inputs = form.querySelectorAll('input, textarea');

    // 1. โหลดข้อมูลเดิมมาแสดงในฟอร์ม
    async function loadContent() {
        try {
            const response = await fetch('api/content.php');
            const result = await response.json();
            if (result.success) {
                inputs.forEach(input => {
                    if (result.data[input.name]) {
                        input.value = result.data[input.name];
                    }
                });
            }
        } catch (error) {
            console.error('Failed to load content:', error);
        }
    }

    // 2. ส่งข้อมูลที่แก้ไขแล้วไปบันทึก
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const dataToSave = {};
        inputs.forEach(input => {
            dataToSave[input.name] = input.value;
        });

        try {
            const response = await fetch('api/content.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(dataToSave)
            });
            const result = await response.json();

            if (result.success) {
                Swal.fire('สำเร็จ!', 'บันทึกข้อมูลเรียบร้อยแล้ว', 'success');
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            Swal.fire('เกิดข้อผิดพลาด', error.message || 'ไม่สามารถบันทึกข้อมูลได้', 'error');
        }
    });

    loadContent();
});
</script>
</body>
</html>