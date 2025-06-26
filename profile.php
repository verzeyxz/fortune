<?php
// เริ่ม session และตรวจสอบการล็อกอิน
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// ถ้ายังไม่ล็อกอิน ให้เด้งกลับไปหน้า login
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ของฉัน - ดูดวงกับเรฟ</title>
<script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="reviews.css">
    
    <style>
        /* --- THEME & BACKGROUND (Unchanged) --- */
        body { font-family: 'Noto Sans Thai', sans-serif; background-color: #100a33; color: white; overflow-x: hidden; }
        .space-bg { background: radial-gradient(ellipse at bottom, #0f0755 0%, #100a33 100%); }
        /* ... All other existing styles (planet, glass-box, text-glow, etc.) ... */

        /* ========================================================== */
        /* --- NEW: MOBILE BOTTOM NAVIGATION BAR STYLES --- */
        /* ========================================================== */
        #mobile-nav {
            display: none; /* ซ่อนบนเดสก์ท็อปเป็นค่าเริ่มต้น */
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 65px;
            background: rgba(28, 10, 51, 0.8);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 100;
        }

        .mobile-nav-container {
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 100%;
            position: relative;
        }

        .mobile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #D1D5DB; /* gray-300 */
            font-size: 0.75rem; /* text-xs */
            transition: color 0.3s ease;
        }
        .mobile-nav-item:hover {
            color: white;
        }
        .mobile-nav-item svg {
            width: 24px;
            height: 24px;
            margin-bottom: 2px;
        }

        .mobile-nav-center {
            position: absolute;
            left: 50%;
            bottom: 10px;
            transform: translateX(-50%);
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(45deg, #F44336, #D32F2F);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 0.75rem;
            font-weight: bold;
            text-decoration: none;
            box-shadow: 0 0 20px rgba(244, 67, 54, 0.5);
            border: 2px solid white;
        }
        .mobile-nav-center svg {
            width: 28px;
            height: 28px;
            margin-bottom: 0;
        }

        /* --- ทำให้เมนูแสดงผลเฉพาะบนมือถือ --- */
        @media (max-width: 768px) {
            body {
                /* เพิ่ม padding ด้านล่างเพื่อไม่ให้เนื้อหาถูกเมนูบัง */
                padding-bottom: 80px;
            }
            #mobile-nav {
                display: block;
            }
        }
    </style>
<body class="space-bg">
    <div class="relative z-10 container mx-auto max-w-4xl text-center px-4">
        
        <header class="my-12">
            <h1 class="text-5xl md:text-6xl font-bold text-glow mb-2">ยินดีต้อนรับ, <?= htmlspecialchars($_SESSION['user_username']) ?>!</h1>
            <p class="text-white/80">นี่คือพื้นที่ส่วนตัวและบันทึกการเดินทางของคุณ</p>
            <div class="mt-4">
                 <a href="index.php" class="text-purple-300 hover:underline">&larr; กลับหน้าหลัก</a>
            </div>
        </header>
        
        <main class="max-w-3xl mx-auto">
            <div class="mt-8">
                <h2 class="text-3xl font-bold text-violet-300 mb-6">ประวัติการเปิดไพ่</h2>
                <div id="readings-history-container" class="space-y-4">
                    <p class="text-gray-400 text-center">กำลังโหลดประวัติ...</p>
                </div>
            </div>
 
     <nav id="mobile-nav">
        <div class="mobile-nav-container">
            
    
            <a href="#" class="mobile-nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                <span>โปรไฟล์</span>
            </a>

            <a href="/" class="mobile-nav-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M22.55 12.5l-2.8-4.43a.75.75 0 00-1.01-.26l-1.95.8-3.9-6.17a.75.75 0 00-1.3-.01L8.8 8.6l-1.95-.8a.75.75 0 00-1.01.26L3.06 12.5H1.75a.75.75 0 000 1.5h1.31l2.79 4.43a.75.75 0 001.01.26l1.95-.8 3.9 6.17a.75.75 0 001.3.01l2.79-4.43 1.95.8a.75.75 0 001.01-.26l2.79-4.43h1.31a.75.75 0 000-1.5h-1.31z"></path></svg>
            </a>

            <a href="https://line.me/R/ti/p/@reffortune" target="_blank" class="mobile-nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M16.75 13.96c.25.02.5.03.74.03.21 0 .42-.01.62-.02.14-.01.29-.02.43-.04.53-.08.98-.18 1.4-.33.35-.12.68-.26.98-.44.25-.15.48-.31.69-.5.2-.18.39-.37.55-.58.3-.39.55-.82.74-1.28.18-.43.3-.88.37-1.35.07-.44.1-.89.1-1.36v-1.12c0-.52-.04-1.04-.12-1.54-.09-.57-.22-1.12-.4-1.66-.21-.63-.48-1.23-.8-1.78-.17-.29-.35-.57-.54-.84a.5.5 0 00-.8-.25c-.25.17-.49.35-.73.53-.41.3-.8.6-1.16.91-.2.16-.39.33-.57.5-.29.27-.57.55-.83.85-.31.34-.6.7-.85 1.07-.2.3-.39.6-.56.92-.25.46-.47.94-.64 1.44-.15.43-.27.88-.35 1.34-.07.41-.11.83-.13 1.25l-.01.21v.21c0 .17-.01.34-.01.51 0 .68.04 1.35.12 2.01.03.26.07.52.12.77.1.53.24 1.05.42 1.55.16.44.34.87.56 1.28.21.38.43.75.68 1.1l.01.02zM6.35 6.5c.57-.49 1.2-.91 1.88-1.26.7-.36 1.44-.66 2.2-.89.79-.24 1.6-.4 2.42-.48.81-.08 1.63-.08 2.44.02.8.09 1.59.26 2.36.51.74.24 1.46.56 2.14.95.66.38 1.29.83 1.88 1.34.52.44.98.94 1.38 1.48.38.51.7 1.06.94 1.64.22.55.37 1.12.46 1.7.08.53.11 1.06.1 1.59-.01.53-.07 1.06-.18 1.58-.12.56-.29 1.1-.51 1.62-.23.55-.52 1.08-.87 1.58-.33.48-.71.93-1.14 1.33-.45.42-.95.8-1.5 1.13-.55.33-1.14.6-1.76.81-.61.21-1.25.36-1.9.45-.63.09-1.27.13-1.91.13h-.42c-.67 0-1.34-.04-2-.11-.65-.07-1.3-.18-1.93-.34a15.7 15.7 0 01-3.32-1.01c-.57-.23-1.12-.5-1.65-.81-.51-.3-.99-.64-1.44-1.02-.43-.37-.82-.78-1.17-1.22-.33-.42-.62-.87-.86-1.35-.22-.45-.4-1.01-.6-1.85V8.5a6.3 6.3 0 01.37-2z"></path></svg>
                <span>ติดต่อ</span>
            </a>

        </div>
    </nav>
            <div class="text-center mt-12">
                <a href="logout.php" class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-full transition-colors">ออกจากระบบ</a>
            </div>
        </main>
    </div>
 
    <script>
    document.addEventListener('DOMContentLoaded', async () => {
        const historyContainer = document.getElementById('readings-history-container');
        if (!historyContainer) return;

        try {
            const response = await fetch('admin/api/readings.php');
            const result = await response.json();

            if (result.success && result.data.length > 0) {
                historyContainer.innerHTML = ''; // ล้างข้อความ "กำลังโหลด"
                result.data.forEach(reading => {
                    const date = new Date(reading.created_at).toLocaleDateString('th-TH', {
                        year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
                    });
                    
                    const readingElement = document.createElement('a');
                    // สร้างลิงก์สำหรับคลิกกลับไปดูผลย้อนหลังโดยใช้ระบบ URL Parameter
                    readingElement.href = `pick.php?cards=${reading.card_ids}`;
                    readingElement.className = 'block p-4 glass-box hover:bg-purple-900/50 transition-colors rounded-lg text-left';
                    readingElement.innerHTML = `
                        <p class="font-bold text-white text-lg">${reading.reading_title}</p>
                        <p class="text-sm text-gray-400">บันทึกเมื่อ: ${date}</p>
                    `;
                    historyContainer.appendChild(readingElement);
                });
            } else {
                historyContainer.innerHTML = '<p class="text-gray-400 text-center">ยังไม่มีประวัติการเปิดไพ่ที่บันทึกไว้</p>';
            }
        } catch (error) {
            console.error("Failed to load history:", error);
            historyContainer.innerHTML = '<p class="text-red-400 text-center">ไม่สามารถโหลดประวัติได้ โปรดลองอีกครั้ง</p>';
        }
    });
    </script>
</body>
</html>