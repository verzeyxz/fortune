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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>ดูดวงกับเรฟ - REFFORTUNE</title>
    <meta name="description" content="ดูดวงกับเรฟ บริการดูดวงไพ่ทาโรต์ ไพ่ออราเคิล โหราศาสตร์ และมหาสัตตเลข พร้อมแพ็กเกจที่เหมาะกับคุณ เช็คดวงชะตา วางแผนชีวิต และรับคำแนะนำที่ชัดเจน ใช้ได้จริง" />
    <meta name="keywords" content="ดูดวง, ไพ่ทาโรต์, ไพ่ออราเคิล, โหราศาสตร์, มหาสัตตเลข, ดูดวงออนไลน์, เรฟฟอร์จูน, ดูดวงความรัก, ดูดวงการงาน, ดูดวงการเงิน" />

    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://reffortune.vercel.app/" />
    <meta property="og:title" content="ดูดวงกับเรฟ - ศาสตร์แห่งไพ่และจักรวาล" />
    <meta property="og:description" content="บริการดูดวงไพ่ทาโรต์, ไพ่ออราเคิล, โหราศาสตร์ และมหาสัตตเลข พร้อมแพ็กเกจที่เหมาะกับคุณ" />
    <meta property="og:image" content="https://i.postimg.cc/Gt6hFf1C/510410056-1025781239715126-7563405935526078934-n.jpg" />

    <meta property="twitter:card" content="summary_large_image" />
    <meta property="twitter:url" content="https://reffortune.vercel.app/" />
    <meta property="twitter:title" content="ดูดวงกับเรฟ - ศาสตร์แห่งไพ่และจักรวาล" />
    <meta property="twitter:description" content="บริการดูดวงไพ่ทาโรต์, ไพ่ออราเคิล, โหราศาสตร์ และมหาสัตตเลข พร้อมแพ็กเกจที่เหมาะกับคุณ" />
    <meta property="twitter:image" content="https://i.postimg.cc/Gt6hFf1C/510410056-1025781239715126-7563405935526078934-n.jpg" />
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="reviews.css">
    
    <style>
        /* --- THEME & BACKGROUND --- */
        body { font-family: 'Noto Sans Thai', sans-serif; background-color: #100a33; color: white; overflow-x: hidden; cursor: none; }
        a, button, .cursor-pointer { cursor: none; }
        .space-bg { background: radial-gradient(ellipse at bottom, #0f0755 0%, #100a33 100%); }
        .stars { position: fixed; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; display: block; background: transparent url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/1231630/stars.png) repeat top center; z-index: -2; }
        .twinkling { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: transparent url(https://s3-us-west-2.amazonaws.com/s.cdpn.io/1231630/stars.png) repeat top center; z-index: -1; animation: move-twinkle-back 200s linear infinite; }
        @keyframes move-twinkle-back { from { background-position: 0 0; } to { background-position: -10000px 5000px; } }
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
        /* --- PLANET --- */
        .planet { width: 300px; height: 300px; background: radial-gradient(circle at 30% 30%, #a29bfe, #6c5ce77a, #2c2d84); border-radius: 50%; box-shadow: 0 0 40px #6c5ce77c, 0 0 80px #a29bfe, inset -20px -10px 40px #352c84; animation: pulse 6s infinite ease-in-out; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: -1; }
        @keyframes pulse { 0%, 100% { transform: translate(-50%, -50%) scale(1); box-shadow: 0 0 40px #6c5ce7, 0 0 80px #a29bfe, inset -20px -10px 40px #3c2c84; } 50% { transform: translate(-50%, -50%) scale(1.05); box-shadow: 0 0 55px #8b80ff, 0 0 110px #c4bfff, inset -20px -10px 40px #3c2c84; } }
        @media (max-width: 768px) { .planet { width: 200px; height: 200px; } }

        /* --- GENERAL STYLES & ANIMATIONS --- */
        .text-glow { text-shadow: 0 0 8px rgba(192, 132, 252, 0.6), 0 0 32px rgba(139, 92, 246, 0.4); }
        .reveal-item { opacity: 0; transform: translateY(30px); transition: opacity 0.8s ease-out, transform 0.8s ease-out; }
        .reveal-item.is-visible { opacity: 1; transform: translateY(0); }
        .glass-box { background: rgba(141, 133, 172, 0.2); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px); border-radius: 1rem; border: 1px solid rgba(255, 255, 255, 0.1); transition: transform 0.4s ease, box-shadow 0.4s ease; }
        .glass-box:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(71, 8, 187, 0.5); }
        
        /* --- 1-CARD GAME STYLES --- */
        .card-wrapper{perspective:1000px;transition:transform .4s ease}.card-wrapper:hover{transform:translateY(-10px)}.card{width:120px;height:180px;transform-style:preserve-3d;transition:transform 1.5s cubic-bezier(.4,0,.2,1);cursor:pointer;position:relative}.card.revealed{transform:rotateY(180deg)}.card-face{position:absolute;width:100%;height:100%;backface-visibility:hidden;border-radius:8px;background-size:cover;background-position:center;box-shadow:0 5px 15px rgba(0,0,0,.3)}.card-back{background-image:url(https://i.postimg.cc/DZnx1m8c/tarot-cards-mystical-poster-meta.webp)}.card-front{transform:rotateY(180deg)}
        @media (max-width: 480px) { #card-container { gap: 0.75rem; } .card { width: 90px; height: 135px; } .card-wrapper:hover { transform: none; } }

        /* --- POPUP & MODAL STYLES --- */
        .popup-panel{transition:opacity .5s ease-out,transform .5s ease-out}.popup-hidden{opacity:0;pointer-events:none;transform:scale(.95)}.popup-visible{opacity:1;pointer-events:auto;transform:scale(1)}.service-popup-panel{transition:opacity .5s cubic-bezier(.4,0,.2,1),transform .5s cubic-bezier(.4,0,.2,1)}
        .swal2-popup{background-color:#111111!important;border:2px solid #FBBF24!important;border-radius:1rem!important}.swal2-title{color:#FBBF24!important}.swal2-html-container{color:#D1D5DB!important}.swal-card-option{display:block;width:100%;padding:1rem;margin-bottom:.75rem;border:1px solid #FBBF24;border-radius:.5rem;text-align:center;font-weight:700;color:#FBBF24;text-decoration:none;transition:all .3s ease}.swal-card-option:hover{background-color:#FBBF24;color:#111}
        
        /* --- BLACK & GOLD CARD MENU --- */
        .menu-gold-theme{background-color:#111;border:2px solid #FBBF24;border-radius:1rem;padding:2rem;box-shadow:0 0 25px rgba(251,191,36,.3)}.menu-card-item{background-color:rgba(17,17,17,.6);border:1px solid #FBBF24;border-radius:.75rem;padding:1.5rem;text-align:center;transition:all .3s ease-in-out}.menu-card-item:hover{transform:translateY(-10px);background-color:#1f1f1f;box-shadow:0 0 20px rgba(251,191,36,.4)}.menu-card-item h4{color:#FBBF24;font-size:1.25rem;font-weight:700}.menu-card-item p{color:#D1D5DB;font-size:.875rem}
        
        /* --- FLOATING ACTION BUTTON (FAB) STYLES --- */
        .fab-container{position:fixed;bottom:1.5rem;right:1.5rem;z-index:50;display:flex;flex-direction:column-reverse;align-items:center}.fab-actions{display:flex;flex-direction:column;gap:.75rem;margin-bottom:1rem;transition:all .3s ease-in-out;opacity:0;transform:translateY(10px);pointer-events:none}.fab-container.open .fab-actions{opacity:1;transform:translateY(0);pointer-events:auto}.fab-button{width:3.5rem;height:3.5rem;border-radius:50%;display:flex;align-items:center;justify-content:center;cursor:pointer;box-shadow:0 4px 12px rgba(0,0,0,.3);transition:all .3s ease-in-out}.fab-main{background:linear-gradient(45deg,#07bd59,#00821c)}.fab-main:hover{transform:scale(1.1)}.fab-container.open .fab-main{transform:rotate(135deg)}.fab-action-item{width:3rem;height:3rem;background-color:rgba(167,139,250,0)}.fab-action-item:hover{background-color:rgba(118,246,92,0);transform:scale(1.15)}
        
        /* --- MAGIC CURSOR --- */
        .magic-cursor{position:fixed;width:20px;height:20px;background:#c4bfff;border-radius:50%;z-index:1000;pointer-events:none;box-shadow:0 0 10px #c4bfff,0 0 20px #c4bfff,0 0 30px #8b80ff;transition:transform .2s ease-out,opacity .3s ease;transform:translate(-50%,-50%) scale(1)}.magic-cursor.hover{transform:translate(-50%,-50%) scale(1.5);opacity:.7}
        
        /* --- ZODIAC FEATURE STYLES --- */
        .zodiac-icon{transition:all .3s ease-in-out;opacity:.6}.zodiac-icon:hover{opacity:1;transform:scale(1.1);filter:drop-shadow(0 0 10px #A78BFA)}.zodiac-icon.active{opacity:1;transform:scale(1.15);filter:drop-shadow(0 0 15px #C4B5FD)}
    </style>
</head>
<body class="space-bg">
    <div class="stars"></div>
    <div class="twinkling"></div>
    <div class="planet"></div>
    <div id="magic-cursor" class="magic-cursor"></div>
    
     <div class="relative z-10">
        <div id="intro-section" class="container mx-auto max-w-4xl text-center px-4">
        <header class="my-12 reveal-item">
            <h1 class="text-5xl md:text-6xl font-bold text-glow mb-2">ดูดวงกับเรฟ</h1>
            <p class="text-white/80">REF FORTUNE</p>
            <p class="text-white/80 text-glow mb-2">ไพ่ทาโรต์-ออราเคิล-มหาสัตตเลข-โหราศาสตร์</p>
        </header>

        <main class="space-y-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="glass-box p-8 reveal-item text-left cursor-pointer" onclick="openServicePopup('tarot')">
                <h2 class="text-2xl font-bold text-violet-300 mb-2">ไพ่ทาโรต์ (Tarot Card)</h2>
                <p class="text-8F480F/70">ศาสตร์แห่งการทำนายอนาคตด้วยไพ่ 78 ใบ เพื่อเป็นแนวทางในการตัดสินใจ</p>
            </div>
            <div class="glass-box p-8 reveal-item text-left cursor-pointer" onclick="openServicePopup('oracle')">
                <h2 class="text-2xl font-bold text-violet-300 mb-2">ไพ่ออราเคิล (Oracle Card)</h2>
                <p class="text-white/70">เครื่องมือที่ให้คำแนะนำและแรงบันดาลใจ เพื่อฮีลใจและค้นพบศักยภาพในตนเอง</p>
            </div>
            <div class="glass-box p-8 reveal-item text-left cursor-pointer" onclick="openServicePopup('astrology')">
                <h2 class="text-2xl font-bold text-violet-300 mb-2">โหราศาสตร์ (Astrology)</h2>
                <p class="text-white/70">ศึกษาการเคลื่อนที่ของดวงดาว เพื่อเข้าใจพื้นฐานดวงชะตาและวางแผนชีวิต</p>
            </div>
            <div class="glass-box p-8 reveal-item text-left cursor-pointer" onclick="openServicePopup('numerology')">
                <h2 class="text-2xl font-bold text-violet-300 mb-2">มหาสัตตเลข (Numerology)</h2>
                <p class="text-white/70">ศาสตร์แห่งตัวเลขจากวันเกิดและชื่อ เพื่อค้นพบเส้นทางชีวิตที่เหมาะสม</p>
            </div>
            </div>
          
      
             <section class="py-12 reveal-item">
            <h2 class="text-4xl font-bold text-glow mb-4">แพ็กเกจดูดวงกับเรฟ</h2>
            <div class="text-center mb-8 max-w-2xl mx-auto">
                <p class="text-xl italic text-cyan-300">"ชัด เคลียร์ ใช้ได้จริง"</p>
                <p class="mt-2 text-white/80">ทุกแพ็กเกจเลือกใช้ไพ่ยิปซีและศาสตร์เสริมที่เหมาะกับคำถาม พร้อมคำแนะนำที่ชัดเจน ใช้ได้กับชีวิตจริง</p>
            </div>

            <div class="space-y-8 text-left">
                <div class="glass-box p-6 md:p-8">
                <h3 class="text-2xl font-bold text-purple-300 mb-4">แพ็กเกจหลัก</h3>
                <div class="space-y-6">
                    <div>
                    <h4 class="font-bold text-lg text-white">แพ็ก A — ดูลึก พื้นดวง+รายปี</h4>
                    <p class="text-amber-400 font-semibold">749 บาท | คอล 1 ชม.</p>
                    <p class="text-sm text-gray-300 mt-1">ใช้ ไพ่ยิปซี 10 ใบ 10 หัวข้อ + โหราศาสตร์ไทย วิเคราะห์ทั้ง พื้นดวงเดิม + แนวโน้มรายปี เหมาะกับลูกค้าที่ดูครั้งแรก/อยากวางแผนชีวิตล่วงหน้า</p>
                    </div>
                    <div class="border-t border-white/10 pt-6">
                    <h4 class="font-bold text-lg text-white">แพ็ก B — ดูชัด ราย 3 เดือน</h4>
                    <p class="text-amber-400 font-semibold">389 บาท | คอล 30 นาที หรือ อัดเสียง</p>
                    <p class="text-sm text-gray-300 mt-1">ใช้ไพ่ยิปซี 10 ใบ 10 หัวข้อ โฟกัสเรื่องราวช่วง 3 เดือนข้างหน้า เหมาะกับผู้ที่ต้องการเช็คทิศทาง ลูกค้าใหม่/เก่า</p>
                    </div>
                </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                <div class="glass-box p-6 md:p-8">
                    <h3 class="text-xl font-bold text-cyan-300 mb-2">แพ็กเกจคำถามแบบ “คอล”</h3>
                    <p class="text-sm text-gray-300 mb-3">ถามได้ไม่จำกัด เหมาะกับคนที่มีคำถามหลายคำถาม อยากคุยสด เคลียร์ใจทันที</p>
                    <p class="font-semibold text-white">15 นาที : <span class="text-amber-400">189 บาท</span></p>
                    <p class="font-semibold text-white">30 นาที : <span class="text-amber-400">359 บาท</span></p>
                </div>
                <div class="glass-box p-6 md:p-8">
                    <h3 class="text-xl font-bold text-cyan-300 mb-2">แพ็กเกจคำถามแบบ “พิมพ์ตอบกลับ”</h3>
                    <p class="text-sm text-gray-300 mb-3">ตอบละเอียด ตรงประเด็น อธิบายชัดเจน ไม่ใช่แค่ “ใช่/ไม่ใช่”</p>
                    <p class="font-semibold text-white">คำถามเฉพาะเจาะจง : <span class="text-cyan-400">45 บาท/คำถาม</span></p>
                    <p class="text-sm text-green-400">โปร: 3 คำถาม 125.- | 5 คำถาม 195.-</p>
                    <p class="mt-2 font-semibold text-white">คำถามเปรียบเทียบ : <span class="text-cyan-400">85 บาท</span></p>
                    <p class="text-xs text-gray-400 mt-1">เช่น "เลือกงานไหนดี?" "ทางA ทางB เป็นอย่างไร?"</p>
                </div>
                </div>

                <div class="glass-box p-6 md:p-8">
                    <h3 class="text-2xl font-bold text-cyan-300 mb-4">แพ็กเกจเสริม</h3>
                    <div class="space-y-6">
                        <div>
                            <h4 class="font-bold text-lg text-white">ภาพรวมรายเดือน</h4>
                            <p class="text-amber-400 font-semibold">259 บาท | ข้อความ หรือ อัดเสียง</p>
                            <p class="text-sm text-gray-300 mt-1">ดูครบ งาน เงิน ความรัก สุขภาพ คำแนะนำ การเสริมดวง เหมาะกับคนที่อยากเช็คแนวทางชีวิตภาพรวม</p>
                        </div>
                        <div class="border-t border-white/10 pt-6">
                            <h4 class="font-bold text-lg text-white">ดวงรายปี (เลข 7 ตัว 4 ฐาน)</h4>
                            <p class="text-amber-400 font-semibold">349 บาท | PDF file</p>
                            <p class="text-sm text-gray-300 mt-1">วิเคราะห์ดวงรายปีเฉพาะบุคคล โฟกัสวันเกิดปีปัจจุบันถึงปีถัดไป เหมาะสำหรับผู้ที่ต้องการเช็คแนวโน้มดวงชะตารายปี</p>
                        </div>
                        <div class="border-t border-white/10 pt-6">
                            <h4 class="font-bold text-lg text-white">พื้นดวง + ดวงรายปี (เลข 7 ตัว 4 ฐาน)</h4>
                            <p class="text-amber-400 font-semibold">489 บาท | PDF file</p>
                            <p class="text-sm text-gray-300 mt-1">เหมาะสำหรับผู้ที่ยังไม่เคยดูดวง หรืออยากเข้าใจทั้งพื้นฐานชีวิต และแนวโน้มของปีปัจจุบัน</p>
                        </div>
                    </div>
                </div>
            </div>
       
   <section class="py-12 reveal-item">
            <div class="menu-gold-theme">
                <h3 class="text-3xl font-bold text-amber-300 mb-2 text-center" style="text-shadow: 0 0 8px rgba(252, 211, 77, 0.5);">เข้าสู่เมนูเลือกการ์ด</h3>
                <p class="text-center text-gray-300 mb-8">เลือกรูปแบบการเปิดไพ่ที่คุณต้องการ</p>
                <div class="grid md:grid-cols-1 gap-6">
                    <div id="start-game-button"></div>
                        <div id="open-pick-modal-btn" class="menu-card-item cursor-pointer">
                                <h4>เลือกไพ่</h4>
                                <p class="mt-2"> เปิดไพ่ดวงชะตาตัวคุณเอง</p>
                            </div>
            </div>
        </section>
        

                
            </section>
                
                <footer class="py-12 border-t border-white/10 reveal-item">
                    <p class="text-lg text-green-300 mb-4">ทุกยอดดูดวง ร่วมทำบุญ 10% เสริมบุญให้ลูกดวงทุกคน</p>
                    <p class="text-white/80 mb-6">ทักเรฟมาได้เลย พร้อมดูดวงให้ด้วยความตั้งใจ ชัดเจน ทุกเคส</p>
                    <div class="flex justify-center items-center gap-6 text-white/70">
                        <a href="https://line.me/R/ti/p/@reffortune" target="_blank" class="flex items-center gap-2 hover:text-white transition-colors duration-300">
                            <span>LINE @reffottune</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 10h-2.5c-2.25 0-2.5-1.5-2.5-2.5V6.5c0-1-.5-1.5-1.5-1.5S10 5.5 10 6.5v1c0 1.25-.75 1.5-1.5 1.5H6c-1.5 0-2 .5-2 2v2c0 1.5.5 2 2 2h2.5c2.25 0 2.5 1.5 2.5 2.5V17.5c0 1 .5 1.5 1.5 1.5s1.5-.5 1.5-1.5v-1c0-1.25.75-1.5 1.5-1.5H18c1.5 0 2-.5 2-2v-2c0-1.5-.5-2-2-2z"></path></svg>
                        </a>
                        <a href="https://www.instagram.com/reffortune/" target="_blank" class="flex items-center gap-2 hover:text-white transition-colors duration-300">
                            <span>IG @reffortune</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                        </a>
                    </div>
                </footer>
            </main>
        </div>
        
        <div id="game-section" class="hidden container mx-auto max-w-5xl text-center px-4">
            <h1 class="text-3xl lg:text-4xl font-bold mb-4">เลือกไพ่ออราเคิล 1 ใบ</h1>
            <p class="text-white/70 mb-8 max-w-lg mx-auto">จงตั้งสมาธิถึงคำถามที่อยากรู้ ใช้สัญชาตญาณของคุณ แล้วเลือกไพ่ 1 ใบจากด้านล่างนี้</p>
            <div id="card-container" class="flex gap-4 flex-wrap justify-center mb-8"></div>
            <div class="flex justify-center gap-4">
                <button id="back-to-intro-button" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-full transition-all">กลับหน้าหลัก</button>
                <button id="reset-button" class="hidden bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-full transition-all">เลือกอีกครั้ง</button>
            </div>
        </div>
    </div>

    <div id="popup" class="fixed inset-0 bg-black/70 backdrop-blur-md flex items-center justify-center z-50 p-4 popup-hidden popup-panel">
        <div class="bg-gradient-to-br from-[#1f1536] to-[#120e29] p-6 rounded-xl shadow-2xl max-w-md w-11/12 text-left relative border border-white/20">
            <button onclick="closePopup()" class="absolute top-3 right-3 text-white/50 hover:text-red-400 transition-colors">✕</button>
            <div class="flex flex-col sm:flex-row items-center gap-4">
                <img id="popup-img" src="" alt="Fortune Card Image" class="w-24 h-40 object-cover rounded-md flex-shrink-0">
                <div>
                    <h2 id="popup-title" class="text-xl font-bold mb-1 text-purple-300"></h2>
                    <p id="popup-text" class="text-gray-300"></p>
                </div>
            </div>
        </div>
    </div>

    <div id="service-modal-container" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm opacity-0 pointer-events-none service-popup-panel">
        <div id="service-modal-panel" class="w-full max-w-lg transform scale-95 bg-gradient-to-br from-[#1f1536] to-[#120e29] p-6 md:p-8 rounded-2xl shadow-2xl relative border border-white/20 service-popup-panel">
            <button onclick="closeServicePopup()" class="absolute top-4 right-4 text-white/50 hover:text-red-400 transition-colors duration-300 hover:rotate-90">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
            <div id="service-modal-content"></div>
        </div>
    </div>

    <div class="hidden">
        <div id="tarot-content">
            <h2 class="text-3xl md:text-4xl font-bold text-violet-300 mb-4 text-glow">ไพ่ทาโรต์ (Tarot Card)</h2>
            <div class="w-full aspect-video bg-violet-400/80 rounded-lg flex items-center justify-center mb-6 shadow-lg shadow-purple-900/50"><p class="text-white text-3xl font-bold">Tarot Reading</p></div>
            <p class="text-gray-200 text-base md:text-lg">ศาสตร์แห่งการทำนายอนาคตด้วยไพ่ทาโรต์ 78 ใบ ที่จะช่วยให้คุณเข้าใจสถานการณ์ต่างๆ ในชีวิต ทั้งเรื่องความรัก การงาน การเงิน และสุขภาพ ไพ่แต่ละใบมีความหมายเฉพาะตัวที่สามารถตีความได้อย่างลึกซึ้ง เพื่อเป็นแนวทางในการตัดสินใจและเตรียมพร้อมรับมือกับสิ่งที่จะเกิดขึ้น</p>
            <a href="pick.php" class="modal-action-button">ดูรายละเอียดเพิ่มเติม</a>
        </div>
        <div id="oracle-content">
            <h2 class="text-3xl md:text-4xl font-bold text-violet-300 mb-4 text-glow">ไพ่ออราเคิล (Oracle Card)</h2>
            <div class="w-full aspect-video bg-purple-400/80 rounded-lg flex items-center justify-center mb-6 shadow-lg shadow-purple-900/50"><p class="text-white text-3xl font-bold">Oracle Wisdom</p></div>
            <p class="text-gray-200 text-base md:text-lg">ไพ่ออราเคิลเป็นเครื่องมือที่ให้คำแนะนำและแรงบันดาลใจ ไม่ได้มีโครงสร้างตายตัวเหมือนไพ่ทาโรต์ ทำให้มีความหลากหลายและอิสระในการตีความสูง เหมาะสำหรับผู้ที่ต้องการคำตอบที่ตรงไปตรงมา คำปลอบโยน หรือมุมมองใหม่ๆ เพื่อฮีลใจและค้นพบศักยภาพในตนเอง</p>
            <a href="#packages-section" onclick="closeServicePopup()" class="modal-action-button">ดูแพ็กเกจที่เกี่ยวข้อง</a>
        </div>
        <div id="astrology-content">
            <h2 class="text-3xl md:text-4xl font-bold text-violet-300 mb-4 text-glow">โหราศาสตร์ (Astrology)</h2>
            <div class="w-full aspect-video bg-indigo-400/80 rounded-lg flex items-center justify-center mb-6 shadow-lg shadow-indigo-900/50"><p class="text-white text-3xl font-bold">Zodiac Signs</p></div>
            <p class="text-gray-200 text-base md:text-lg">การศึกษาการเคลื่อนที่ของดวงดาวและตำแหน่งของเทห์ฟากฟ้า ที่เชื่อว่ามีอิทธิพลต่อชีวิตและบุคลิกภาพของมนุษย์ การผูกดวงชะตาส่วนบุคคลจะช่วยให้คุณเข้าใจพื้นฐานดวงชะตา พรสวรรค์ และอุปสรรคต่างๆ เพื่อวางแผนชีวิตได้อย่างแม่นยำและมีประสิทธิภาพ</p>
            <a href="#packages-section" onclick="closeServicePopup()" class="modal-action-button">ดูแพ็กเกจที่เกี่ยวข้อง</a>
        </div>
        <div id="numerology-content">
            <h2 class="text-3xl md:text-4xl font-bold text-violet-300 mb-4 text-glow">มหาสัตตเลข (Numerology)</h2>
            <div class="w-full aspect-video bg-blue-900/80 rounded-lg flex items-center justify-center mb-6 shadow-lg shadow-blue-900/50"><p class="text-white text-3xl font-bold">Numerology Chart</p></div>
            <p class="text-gray-200 text-base md:text-lg">ศาสตร์แห่งตัวเลขที่วิเคราะห์ความหมายที่ซ่อนอยู่ในวันเดือนปีเกิดและชื่อของคุณ ตัวเลขแต่ละตัวจะสะท้อนถึงพลังงานและลักษณะนิสัยที่แตกต่างกัน การวิเคราะห์เลขศาสตร์จะช่วยให้คุณรู้จักตัวเองมากขึ้น ค้นพบเส้นทางชีวิตที่เหมาะสม และเข้าใจความสัมพันธ์กับผู้อื่นได้ดียิ่งขึ้น</p>
            <a href="#packages-section" onclick="closeServicePopup()" class="modal-action-button">ดูแพ็กเกจที่เกี่ยวข้อง</a>
        </div>
    </div>

    <div id="fab-container" class="fab-container">
        <div id="fab-actions" class="fab-actions">
            <a href="https://www.instagram.com/reffortune/" target="_blank" class="fab-button fab-action-item" title="Instagram">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="48" height="48" viewBox="0 0 48 48">
                    <radialGradient id="yOrnEVTdx3GevYoSo5Iy4a" cx="19.38" cy="42.035" r="44.899" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#fd5"></stop><stop offset=".328" stop-color="#ff543f"></stop><stop offset=".348" stop-color="#fc5245"></stop><stop offset=".504" stop-color="#e64771"></stop><stop offset=".643" stop-color="#d53e91"></stop><stop offset=".761" stop-color="#cc39a4"></stop><stop offset=".841" stop-color="#c837ab"></stop></radialGradient><path fill="url(#yOrnEVTdx3GevYoSo5Iy4a)" d="M34.017,41.99l-20,0.019c-4.4,0.004-8.003-3.592-8.008-7.992l-0.019-20	c-0.004-4.4,3.592-8.003,7.992-8.008l20-0.019c4.4-0.004,8.003,3.592,8.008,7.992l0.019,20	C42.014,38.383,38.417,41.986,34.017,41.99z"></path><radialGradient id="yOrnEVTdx3GevYoSo5Iy4b" cx="11.786" cy="5.54" r="29.813" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#4168c9"></stop><stop offset=".999" stop-color="#4168c9" stop-opacity="0"></stop></radialGradient><path fill="url(#yOrnEVTdx3GevYoSo5Iy4b)" d="M34.017,41.99l-20,0.019c-4.4,0.004-8.003-3.592-8.008-7.992l-0.019-20	c-0.004-4.4,3.592-8.003,7.992-8.008l20-0.019c4.4-0.004,8.003,3.592,8.008,7.992l0.019,20	C42.014,38.383,38.417,41.986,34.017,41.99z"></path><path fill="#fff" d="M24,31c-3.859,0-7-3.141-7-7s3.141-7,7-7s7,3.141,7,7S27.859,31,24,31z M24,19c-2.757,0-5,2.243-5,5	s2.243,5,5,5s5-2.243,5-5S26.757,19,24,19z"></path><circle cx="31.5" cy="16.5" r="1.5" fill="#fff"></circle><path fill="#fff" d="M30,37H18c-3.859,0-7-3.141-7-7V18c0-3.859,3.141-7,7-7h12c3.859,0,7,3.141,7,7v12	C37,33.859,33.859,37,30,37z M18,13c-2.757,0-5,2.243-5,5v12c0,2.757,2.243,5,5,5h12c2.757,0,5-2.243,5-5V18c0-2.757-2.243-5-5-5H18z"></path>
                </svg>
            </a>
            <a href="https://line.me/R/ti/p/@reffortune" target="_blank" class="fab-button fab-action-item" title="LINE Official Account">
                <img src="https://i.postimg.cc/9FmD1P1H/1025781239715126-7563405935526078934-n.webp" alt="line-icon">
            </a>
        </div>
        <button id="fab-main" class="fab-button fab-main">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-white"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        </button>
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
    document.addEventListener('DOMContentLoaded', () => {
        // --- DOM ELEMENT REFERENCES ---
        const introSection = document.getElementById('intro-section');
        const gameSection = document.getElementById('game-section');
        const startGameButton = document.getElementById('start-game-button');
        const backToIntroButton = document.getElementById('back-to-intro-button');
        const cardContainer = document.getElementById("card-container");
        const popup = document.getElementById("popup");
        const resetButton = document.getElementById("reset-button");
        const serviceModalContainer = document.getElementById('service-modal-container');
        const serviceModalPanel = document.getElementById('service-modal-panel');
        const serviceModalContent = document.getElementById('service-modal-content');
        const fabContainer = document.getElementById('fab-container');
        const fabMainButton = document.getElementById('fab-main');
        const magicCursor = document.getElementById('magic-cursor');
        const openPickModalBtn = document.getElementById('open-pick-modal-btn');
        const packagesContainer = document.getElementById('packages-container');

        // --- DATA ---

        // --- CORE FUNCTIONS ---
        // Function to open service detail modals
        function openServicePopup(serviceName) {
            const contentSource = document.getElementById(`${serviceName}-content`);
            if (contentSource && serviceModalContainer && serviceModalPanel && serviceModalContent) {
                serviceModalContent.innerHTML = contentSource.innerHTML;
                serviceModalContainer.classList.remove('opacity-0', 'pointer-events-none');
                serviceModalPanel.classList.remove('scale-95');
                document.body.style.overflow = 'hidden';
            }
        }

        // Function to close service detail modals
        function closeServicePopup() {
            if (serviceModalPanel && serviceModalContainer) {
                serviceModalPanel.classList.add('scale-95');
                serviceModalContainer.classList.add('opacity-0');
                setTimeout(() => {
                    serviceModalContainer.classList.add('pointer-events-none');
                    document.body.style.overflow = 'auto';
                }, 500);
            }
        }

        // Functions for the 1-card game
        function showOneCardGame() {
            if (introSection && gameSection) {
                introSection.classList.add('hidden');
                gameSection.classList.remove('hidden');
                setupDeck();
            }
        }

        function showIntroFromGame() {
            if (gameSection && introSection) {
                gameSection.classList.add('hidden');
                introSection.classList.remove('hidden');
                closePopup();
                if (resetButton) resetButton.classList.add('hidden');
            }
        }

        function shuffle(e) {
            let t, n, o = e.length;
            for (; 0 !== o;) n = Math.floor(Math.random() * o), o--, (t = e[o], e[o] = e[n], e[n] = t);
            return e;
        }

        function setupDeck() {
            if (!cardContainer) return;
            cardContainer.innerHTML = "", shuffledFortunes = shuffle([...fortunes]), shuffledFortunes.forEach((e, t) => {
                const n = document.createElement("div");
                n.classList.add("card-wrapper");
                const o = document.createElement("div");
                o.classList.add("card"), o.dataset.fortuneIndex = t;
                const d = document.createElement("div");
                d.classList.add("card-face", "card-back");
                const a = document.createElement("div");
                a.classList.add("card-face", "card-front"), a.style.backgroundImage = `url('${e.img}')`, o.appendChild(d), o.appendChild(a), n.appendChild(o), o.addEventListener("click", revealFortune), cardContainer.appendChild(n)
            })
        }

        function revealFortune(e) {
            const cardElement = e.currentTarget;
            if (cardElement.classList.contains("revealed")) return;
            document.querySelectorAll(".card").forEach(card => { card.style.pointerEvents = "none" });
            const fortuneIndex = cardElement.dataset.fortuneIndex;
            const fortuneData = shuffledFortunes[fortuneIndex];
            cardElement.classList.add("revealed");
            setTimeout(() => {
                document.getElementById("popup-title").textContent = fortuneData.title;
                document.getElementById("popup-text").textContent = fortuneData.text;
                document.getElementById("popup-img").src = fortuneData.img;
                popup.classList.remove("popup-hidden");
                popup.classList.add("popup-visible");
                if (resetButton) resetButton.classList.remove("hidden")
            }, 1200);
        }

        function closePopup() { if (popup) popup.classList.add("popup-hidden"), popup.classList.remove("popup-visible") }

        function resetGame() {
            closePopup();
            if (resetButton) resetButton.classList.add("hidden");
            document.querySelectorAll(".card").forEach(e => { e.classList.remove("revealed") });
            setTimeout(() => {
                setupDeck();
                document.querySelectorAll(".card").forEach(e => { e.style.pointerEvents = "auto" })
            }, 800)
        }
        
        // Function for Zodiac section
        function showZodiacInfo(signKey) {
            document.querySelectorAll('.zodiac-icon').forEach(icon => icon.classList.remove('active'));
            const activeIcon = document.querySelector(`.zodiac-icon[data-zodiac="${signKey}"]`);
            if(activeIcon) activeIcon.classList.add('active');

            const data = zodiacData[signKey];
            if(zodiacResultDiv && data) {
                zodiacResultDiv.innerHTML = `
                    <h4 class="text-2xl font-bold text-cyan-300">${data.name}</h4>
                    <div class="mt-2 text-gray-200">
                        <p><span class="font-semibold text-purple-300">พลังงานด้านบวก:</span> ${data.positive}</p>
                        <p><span class="font-semibold text-amber-300">สิ่งที่ควรโฟกัส:</span> ${data.focus}</p>
                    </div>
                `;
            }
        }
        
    
        


        // --- EVENT LISTENERS & INITIALIZATION ---

        // On Load Animations
        const itemsToReveal = document.querySelectorAll('.reveal-item');
        itemsToReveal.forEach((item, index) => {
            setTimeout(() => { item.classList.add('is-visible'); }, (index + 1) * 200);
        });

        // Magic Cursor
        document.addEventListener('mousemove', (e) => {
            if(magicCursor) {
                magicCursor.style.left = e.clientX + 'px';
                magicCursor.style.top = e.clientY + 'px';
            }
        });
        document.querySelectorAll('a, button, [onclick], .cursor-pointer').forEach(el => {
            el.addEventListener('mouseenter', () => magicCursor.classList.add('hover'));
            el.addEventListener('mouseleave', () => magicCursor.classList.remove('hover'));
        });

        // Main Menu Buttons
        if (startGameButton) startGameButton.addEventListener('click', showOneCardGame);
        
        if (openPickModalBtn) {
            openPickModalBtn.addEventListener('click', () => {
                Swal.fire({
                    title: 'เลือกจำนวนไพ่',
                    html: `
                        <p class="mb-4">คุณต้องการเปิดไพ่เพื่อดูคำทำนายกี่ใบ?</p>
                        <div class="flex flex-col">
                         <a href="pick.php?count=1" class="swal-card-option">1 ใบ  (Quick Answer)</a>
                         <a href="pick.php?count=2" class="swal-card-option">2 ใบ  (A/B Choice)</a>
                         <a href="pick.php?count=3" class="swal-card-option">3 ใบ  (Past-Present-Future)</a>
                         <a href="pick.php?count=4" class="swal-card-option">4 ใบ  (Guidance)</a>
                         <a href="pick.php?count=10" class="swal-card-option">10 ใบ  (Celtic Cross)</a>
                        </div>
                    `,
                    showConfirmButton: false,
                    showCloseButton: true,
                });
            });
        }
        
        // 1-Card Game Buttons
        if (backToIntroButton) backToIntroButton.addEventListener('click', showIntroFromGame);
        if (resetButton) resetButton.addEventListener('click', resetGame);

        // Service Modal
        // The onclick attributes in the HTML handle the opening of service modals.
        if (serviceModalContainer) {
            serviceModalContainer.addEventListener('click', (event) => { if (event.target === serviceModalContainer) closeServicePopup(); });
        }
        
        // FAB
        if (fabMainButton) {
            fabMainButton.addEventListener('click', (e) => { 
                e.stopPropagation(); 
                fabContainer.classList.toggle('open'); 
            });
        }
        document.addEventListener('click', (event) => { 
            if (fabContainer && !fabContainer.contains(event.target)) { 
                fabContainer.classList.remove('open'); 
            }
        });

        // Zodiac
        if (zodiacSelector) {
            Object.keys(zodiacData).forEach(key => {
                const sign = zodiacData[key];
                const iconDiv = document.createElement('div');
                iconDiv.className = 'zodiac-icon cursor-pointer p-2 flex flex-col items-center';
                iconDiv.dataset.zodiac = key;
                iconDiv.innerHTML = `<span class="text-4xl">${sign.icon}</span><span class="text-xs">${sign.name}</span>`;
                iconDiv.onclick = () => showZodiacInfo(key);
                zodiacSelector.appendChild(iconDiv);
            });
        }

        // Global Keyboard Listener
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                if (serviceModalContainer && !serviceModalContainer.classList.contains('pointer-events-none')) closeServicePopup();
                if (popup && popup.classList.contains('popup-visible')) closePopup();
                if (fabContainer && fabContainer.classList.contains('open')) fabContainer.classList.remove('open');
            }
        });
   async function loadPackages() {
            if (!packagesContainer) return;
            try {
                // CORRECT API PATH
                const response = await fetch('admin/api/packages.php'); 
                
                if (!response.ok) {
                    throw new Error(`เกิดข้อผิดพลาดจากเซิร์ฟเวอร์: ${response.status}`);
                }

                // API should return a JSON array directly
                const packages = await response.json();

                packagesContainer.innerHTML = ''; 

                if (!Array.isArray(packages) || packages.length === 0) {
                     packagesContainer.innerHTML = '<p class="text-center text-xl text-yellow-400">ยังไม่มีข้อมูลแพ็กเกจในระบบ</p>';
                     return;
                }

                const grouped = packages.reduce((acc, pkg) => {
                    (acc[pkg.type] = acc[pkg.type] || []).push(pkg);
                    return acc;
                }, {});

                function createPackageItemHTML(pkg) {
                    return `
                        <div class="border-t border-white/10 pt-6 first:pt-0 first:border-none">
                          <h4 class="font-bold text-lg text-white">${pkg.name || ''}</h4>
                          <p class="text-amber-400 font-semibold">${pkg.price || ''} ${pkg.duration ? `| ${pkg.duration}` : ''}</p>
                          <p class="text-sm text-gray-300 mt-1 whitespace-pre-line">${pkg.description || ''}</p>
                        </div>
                    `;
                }

                // Render Main Packages
                if (grouped.main && grouped.main.length > 0) {
                    const section = document.createElement('div');
                    section.className = 'glass-box p-6 md:p-8';
                    let html = `<h3 class="text-2xl font-bold text-purple-300 mb-4">แพ็กเกจหลัก</h3><div class="space-y-6">`;
                    grouped.main.forEach(pkg => html += createPackageItemHTML(pkg));
                    html += `</div>`;
                    section.innerHTML = html;
                    packagesContainer.appendChild(section);
                }
                
                // You can add logic here to render other package types like 'call', 'text', 'extra'
                // in a similar way if needed.

            } catch (error) {
                console.error("Fetch packages error:", error);
                packagesContainer.innerHTML = `<p class="text-center text-xl text-red-400">ไม่สามารถโหลดข้อมูลแพ็กเกจได้<br><span class="text-sm text-gray-400">${error.message}</span></p>`;
            }
        }
        loadPackages(); // Call the function to load packages on page load
    });
    </script>
</body>
</html>