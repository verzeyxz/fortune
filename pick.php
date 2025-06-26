<?php
// เริ่ม session เพื่อให้เราสามารถตรวจสอบสถานะการล็อกอินได้
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// สร้างตัวแปร PHP เพื่อเก็บสถานะการล็อกอิน
$isUserLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เลือกไพ่ - ดูดวงกับเรฟ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="pick.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;700&display=swap" rel="stylesheet">
</head>
<body class="space-bg">
    <div class="stars"></div>
    <div class="twinkling"></div>
    <div class="planet"></div>

    <div id="selection-screen" class="main-container">
        <header class="text-center mb-4">
            <a href="/" class="text-purple-300 hover:text-white mb-4 inline-block">&larr; กลับหน้าหลัก</a>
            <h1 id="page-title" class="text-3xl md:text-4xl font-bold text-glow">กำลังโหลด...</h1>
            <p class="text-white/80 mt-2">ตั้งสมาธิในคำถาม แล้วใช้ใจเลือกไพ่ที่ดึงดูดคุณที่สุด</p>
        </header>
        
        <div id="card-grid" class="card-grid-container">
            </div>

        <div id="controls" class="controls-container glass-box">
            <div id="selection-tray" class="selection-tray-container"></div>
            <div class="flex items-center gap-2 sm:gap-4">
                <button id="shuffle-button" class="shuffle-btn" title="สับไพ่">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M0 3.5A.5.5 0 0 1 .5 3H1c2.202 0 3.827 1.24 4.874 2.418C6.92 6.588 8 7.582 8 9.5s-1.08 2.912-2.126 4.082C4.827 14.76 3.202 16 1 16H.5a.5.5 0 0 1-.5-.5v-12zm2.291 1.672c.626-.956 1.6-1.587 2.755-1.587H6.5a.5.5 0 0 1 .5.5v12a.5.5 0 0 1-.5-.5H5.045c-1.155 0-2.129-.631-2.755-1.587C1.69 11.231 1 10.12 1 8.5c0-1.62.69-2.73 1.291-3.828z"/><path d="M16 3.5a.5.5 0 0 1-.5.5h-.793c-1.155 0-2.129.631-2.755 1.587C11.31 6.769 11 7.88 11 9.5c0 1.62.69 2.73 1.291 3.828.626.956 1.6 1.587 2.755 1.587h.793a.5.5 0 0 1 .5.5v-12a.5.5 0 0 1-.5-.5zM15 5.172c-.626.956-1.6 1.587-2.755-1.587h-.793c-1.155 0-2.129-.631-2.755-1.587C8.08 4.231 7.5 3.12 7.5 1.5a.5.5 0 0 1 .5-.5h6.5a.5.5 0 0 1 .5.5v3.672z"/></svg>
                </button>
                <div id="counter" class="counter-text"></div>
                <button id="confirm-button" class="confirm-btn" disabled>ยืนยันการเลือก</button>
            </div>
        </div>
    </div>

    <div id="results-screen" class="main-container hidden">
        <header class="text-center mb-6">
            <h1 id="result-title" class="text-3xl md:text-4xl font-bold text-glow"></h1>
        </header>
        <div id="results-grid" class="results-grid-container"></div>
        <div id="result-controls" class="text-center mt-8 flex flex-wrap gap-4 justify-center"></div>
    </div>

    <div id="card-modal-container" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-80 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
        <div id="card-modal-panel" class="w-full max-w-xs md:max-w-sm transform scale-95 transition-transform duration-300 text-center">
            <img id="modal-card-img" src="" alt="" class="w-full rounded-2xl shadow-2xl shadow-purple-500/50">
            <h2 id="modal-card-name" class="text-2xl font-bold text-white mt-4 text-glow"></h2>
            <button id="modal-close-btn" class="mt-4 text-gray-300 hover:text-white transition-colors">ปิด</button>
        </div>
    </div>
 <nav id="mobile-nav">
        <div class="mobile-nav-container">
            
            <?php
                // ใช้ PHP เพื่อกำหนดลิงก์ของปุ่มโปรไฟล์
                $profile_link = $isUserLoggedIn ? 'profile.php' : 'login.php';
            ?>
            <a href="<?= $profile_link ?>" class="mobile-nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
                <span>โปรไฟล์</span>
            </a>

            <div id="mobile-pick-card-btn" class="mobile-nav-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M22.55 12.5l-2.8-4.43a.75.75 0 00-1.01-.26l-1.95.8-3.9-6.17a.75.75 0 00-1.3-.01L8.8 8.6l-1.95-.8a.75.75 0 00-1.01.26L3.06 12.5H1.75a.75.75 0 000 1.5h1.31l2.79 4.43a.75.75 0 001.01.26l1.95-.8 3.9 6.17a.75.75 0 001.3.01l2.79-4.43 1.95.8a.75.75 0 001.01-.26l2.79-4.43h1.31a.75.75 0 000-1.5h-1.31z"></path></svg>
            </div>
            
            <a href="https://line.me/R/ti/p/@reffortune" target="_blank" class="mobile-nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M16.75 13.96c.25.02.5.03.74.03.21 0 .42-.01.62-.02.14-.01.29-.02.43-.04.53-.08.98-.18 1.4-.33.35-.12.68-.26.98-.44.25-.15.48-.31.69-.5.2-.18.39-.37.55-.58.3-.39.55-.82.74-1.28.18-.43.3-.88.37-1.35.07-.44.1-.89.1-1.36v-1.12c0-.52-.04-1.04-.12-1.54-.09-.57-.22-1.12-.4-1.66-.21-.63-.48-1.23-.8-1.78-.17-.29-.35-.57-.54-.84a.5.5 0 00-.8-.25c-.25.17-.49.35-.73.53-.41.3-.8.6-1.16.91-.2.16-.39.33-.57.5-.29.27-.57.55-.83.85-.31.34-.6.7-.85 1.07-.2.3-.39.6-.56.92-.25.46-.47.94-.64 1.44-.15.43-.27.88-.35 1.34-.07.41-.11.83-.13 1.25l-.01.21v.21c0 .17-.01.34-.01.51 0 .68.04 1.35.12 2.01.03.26.07.52.12.77.1.53.24 1.05.42 1.55.16.44.34.87.56 1.28.21.38.43.75.68 1.1l.01.02zM6.35 6.5c.57-.49 1.2-.91 1.88-1.26.7-.36 1.44-.66 2.2-.89.79-.24 1.6-.4 2.42-.48.81-.08 1.63-.08 2.44.02.8.09 1.59.26 2.36.51.74.24 1.46.56 2.14.95.66.38 1.29.83 1.88 1.34.52.44.98.94 1.38 1.48.38.51.7 1.06.94 1.64.22.55.37 1.12.46 1.7.08.53.11 1.06.1 1.59-.01.53-.07 1.06-.18 1.58-.12.56-.29 1.1-.51 1.62-.23.55-.52 1.08-.87 1.58-.33.48-.71.93-1.14 1.33-.45.42-.95.8-1.5 1.13-.55.33-1.14.6-1.76.81-.61.21-1.25.36-1.9.45-.63.09-1.27.13-1.91.13h-.42c-.67 0-1.34-.04-2-.11-.65-.07-1.3-.18-1.93-.34a15.7 15.7 0 01-3.32-1.01c-.57-.23-1.12-.5-1.65-.81-.51-.3-.99-.64-1.44-1.02-.43-.37-.82-.78-1.17-1.22-.33-.42-.62-.87-.86-1.35-.22-.45-.4-1.01-.6-1.85V8.5a6.3 6.3 0 01.37-2z"></path></svg>
                <span>ติดต่อ</span>
            </a>

        </div>
    </nav>
    <script>
        const IS_LOGGED_IN = <?= $isUserLoggedIn ? 'true' : 'false' ?>;
    </script>
    <script src="tarot-data.js"></script>
    <script src="pick.js"></script>
</body>
</html>