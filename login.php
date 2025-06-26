<?php
// เริ่ม session เพื่อเตรียมเก็บข้อมูลการล็อกอิน
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// ถ้าผู้ใช้ล็อกอินอยู่แล้ว ให้พาไปหน้าโปรไฟล์เลย
if (isset($_SESSION['user_logged_in'])) {
    header('Location: profile.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - ดูดวงกับเรฟ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Noto Sans Thai', sans-serif; background-color: #100a33; color: white; background-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/1231630/stars.png'); }
        .form-container { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem; }
        .glass-box { background: rgba(141, 133, 172, 0.2); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px); border-radius: 1rem; border: 1px solid rgba(255, 255, 255, 0.1); }
        .form-input { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); transition: all 0.3s ease; }
        .text-glow { text-shadow: 0 0 8px rgba(192, 132, 252, 0.6), 0 0 32px rgba(139, 92, 246, 0.4); }
        .submit-btn { background: #8B5CF6; transition: background-color 0.3s ease; }
        .submit-btn:hover { background: #A78BFA; }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="glass-box p-8 text-center w-full max-w-md">
            <h1 class="text-3xl font-bold text-glow mb-6">เข้าสู่ระบบ</h1>
            <form id="login-form" class="space-y-4">
                <input type="text" id="username" placeholder="ชื่อผู้ใช้ หรือ อีเมล" class="w-full p-3 rounded-lg form-input outline-none focus:ring-2 focus:ring-purple-400" required>
                <input type="password" id="password" placeholder="รหัสผ่าน" class="w-full p-3 rounded-lg form-input outline-none focus:ring-2 focus:ring-purple-400" required>
                <button type="submit" class="w-full submit-btn text-white font-bold py-3 rounded-lg">เข้าสู่ระบบ</button>
            </form>
            <div class="mt-6 text-sm">
                <p>ยังไม่มีบัญชี? <a href="register.php" class="text-purple-300 hover:underline">สมัครสมาชิกที่นี่</a></p>
                <p class="mt-2"><a href="/" class="text-gray-400 hover:underline">&larr; กลับสู่หน้าหลัก</a></p>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('login-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = {
            action: 'login',
            username: document.getElementById('username').value,
            password: document.getElementById('password').value,
        };

        try {
            const response = await fetch('admin/api/auth.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(formData)
            });
            const result = await response.json();

            if (result.success) {
                await Swal.fire({
                    icon: 'success',
                    title: 'เข้าสู่ระบบสำเร็จ!',
                    text: 'กำลังพาคุณไปยังหน้าโปรไฟล์...',
                    timer: 1500,
                    showConfirmButton: false,
                    background: '#100a33',
                    color: '#fff'
                });
                window.location.href = 'profile.php';
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด',
                    text: result.message,
                    background: '#100a33',
                    color: '#fff'
                });
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'ผิดพลาด',
                text: 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้',
                background: '#100a33',
                color: '#fff'
            });
        }
    });
    </script>
</body>
</html>