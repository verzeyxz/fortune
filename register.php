<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - ดูดวงกับเรฟ</title>
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
        <div class="glass-box p-8 text-center">
            <h1 class="text-3xl font-bold text-glow mb-6">สมัครสมาชิก</h1>
            <form id="register-form" class="space-y-4">
                <input type="text" id="username" placeholder="ชื่อผู้ใช้" class="w-full p-3 rounded-lg form-input outline-none focus:ring-2 focus:ring-purple-400" required>
                <input type="email" id="email" placeholder="อีเมล" class="w-full p-3 rounded-lg form-input outline-none focus:ring-2 focus:ring-purple-400" required>
                <input type="password" id="password" placeholder="รหัสผ่าน" class="w-full p-3 rounded-lg form-input outline-none focus:ring-2 focus:ring-purple-400" required>
                <button type="submit" class="w-full bg-violet-600 hover:bg-violet-700 text-white font-bold py-3 rounded-lg transition-all">สมัครสมาชิก</button>
            </form>
            <p class="mt-6 text-sm">มีบัญชีอยู่แล้ว? <a href="login.php" class="text-purple-300 hover:underline">เข้าสู่ระบบที่นี่</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.getElementById('register-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = {
            action: 'register',
            username: document.getElementById('username').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
        };

        const response = await fetch('admin/api/auth.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });
        const result = await response.json();

        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'สมัครสมาชิกสำเร็จ!',
                text: 'คุณสามารถเข้าสู่ระบบได้ทันที',
                background: '#100a33',
                color: '#fff'
            }).then(() => {
                window.location.href = 'login.php';
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'เกิดข้อผิดพลาด',
                text: result.message,
                background: '#100a33',
                color: '#fff'
            });
        }
    });
    </script>
</body>
</html>