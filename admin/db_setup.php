<?php
// ตั้งค่าให้แสดงผล error ทั้งหมดเพื่อการดีบัก
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');
echo "<style>body { font-family: monospace; line-height: 1.6; padding: 20px; color: #333; background-color: #fdfdfd; } h1, h2 { color: #333; } .success { color: green; } .info { color: blue; } .error { color: red; }</style>";

try {
    // ใช้ไฟล์ db.php เพื่อการเชื่อมต่อที่สอดคล้องกัน
    require_once __DIR__ . '/includes/db.php';
    $db = getDbConnection();

    echo "<h1>ผลการตั้งค่าฐานข้อมูล</h1>";

    // --- สร้างตารางที่จำเป็น ---

    // 1. ตาราง Admins
    $db->exec("CREATE TABLE IF NOT EXISTS admins ( id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT NOT NULL UNIQUE, password TEXT NOT NULL )");
    echo "1. ตรวจสอบตาราง 'admins' เรียบร้อย...<br>";
    $stmt_admin = $db->query("SELECT COUNT(*) FROM admins");
    if ($stmt_admin->fetchColumn() == 0) {
        $username = 'admin';
        $password = password_hash('password123', PASSWORD_DEFAULT);
        $db->prepare("INSERT INTO admins (username, password) VALUES (?, ?)")->execute([$username, $password]);
        echo "<span class='info'>   - สร้างผู้ใช้แอดมินเริ่มต้น (Username: admin, Password: password123)</span><br>";
    }

    // 2. ตาราง Users
    $db->exec("CREATE TABLE IF NOT EXISTS users ( id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT NOT NULL UNIQUE, email TEXT NOT NULL UNIQUE, password TEXT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP )");
    echo "2. ตรวจสอบตาราง 'users' เรียบร้อย...<br>";

    // 3. ตาราง Packages
    $db->exec("CREATE TABLE IF NOT EXISTS packages ( id INTEGER PRIMARY KEY AUTOINCREMENT, type TEXT NOT NULL, name TEXT NOT NULL, price TEXT, duration TEXT, description TEXT )");
    echo "3. ตรวจสอบตาราง 'packages' เรียบร้อย...<br>";

    // 4. ตาราง Saved Readings
    $db->exec("CREATE TABLE IF NOT EXISTS saved_readings ( id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER NOT NULL, reading_title TEXT NOT NULL, card_ids TEXT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (user_id) REFERENCES users(id) )");
    echo "4. ตรวจสอบตาราง 'saved_readings' เรียบร้อย...<br>";
    
    // --- เพิ่มข้อมูลแพ็กเกจเริ่มต้น ---
    $stmt_pkg = $db->query("SELECT COUNT(*) FROM packages");
    if ($stmt_pkg->fetchColumn() == 0) {
        echo "<hr><h2 class='info'>กำลังเพิ่มข้อมูลแพ็กเกจเริ่มต้น...</h2>";
        
        $initial_packages = [
            // แพ็กเกจหลัก
            ['type' => 'main', 'name' => 'แพ็ก A — ดูลึก พื้นดวง+รายปี', 'price' => '749 บาท', 'duration' => 'คอล 1 ชม.', 'description' => "ใช้ ไพ่ยิปซี 10 ใบ 10 หัวข้อ + โหราศาสตร์ไทย\nวิเคราะห์ทั้ง พื้นดวงเดิม + แนวโน้มรายปี\nเหมาะกับลูกค้าที่ดูครั้งแรก/อยากวางแผนชีวิตล่วงหน้า"],
            ['type' => 'main', 'name' => 'แพ็ก B — ดูชัด ราย 3 เดือน', 'price' => '389 บาท', 'duration' => 'คอล 30 นาที หรือ อัดเสียง', 'description' => "ใช้ไพ่ยิปซี 10 ใบ 10 หัวข้อ\nโฟกัสเรื่องราวช่วง 3 เดือนข้างหน้า\nเหมาะกับผู้ที่ต้องการเช็คทิศทาง ลูกค้าใหม่/เก่า"],
            
            // แพ็กเกจคอล
            ['type' => 'call', 'name' => '15 นาที', 'price' => '189 บาท', 'duration' => 'คุยสด', 'description' => "ถามได้ไม่จำกัด เหมาะกับคนที่มีคำถามหลายคำถาม อยากคุยสด เคลียร์ใจทันที"],
            ['type' => 'call', 'name' => '30 นาที', 'price' => '359 บาท', 'duration' => 'คุยสด', 'description' => "เวลาเพิ่มเติมสำหรับคำถามที่มากขึ้น"],

            // แพ็กเกจพิมพ์ตอบ
            ['type' => 'text', 'name' => 'คำถามเฉพาะเจาะจง', 'price' => '45 บาท/คำถาม', 'duration' => 'พิมพ์ตอบกลับ', 'description' => "เช่น \"งานนี้เวิร์คมั้ย?\" / \"เขาคิดยังไงกับเรา?\""],
            ['type' => 'text', 'name' => 'โปร: 3 คำถาม 125.-', 'price' => '', 'duration' => '', 'description' => "ตอบละเอียด ตรงประเด็น เคลียร์ใจแน่นอน"],
            ['type' => 'text', 'name' => 'โปร: 5 คำถาม 195.-', 'price' => '', 'duration' => '', 'description' => "สำหรับผู้ที่มีหลายคำถามในใจ"],
            ['type' => 'text', 'name' => 'คำถามเปรียบเทียบ', 'price' => '85 บาท', 'duration' => 'พิมพ์ตอบกลับ', 'description' => "เช่น \"เลือกงานไหนดี?\" \"ทางA หรือ ทางB เป็นอย่างไร?\""],

            // แพ็กเกจเสริม
            ['type' => 'extra', 'name' => 'ภาพรวมรายเดือน', 'price' => '259 บาท', 'duration' => 'ข้อความ หรือ อัดเสียง', 'description' => "ดูครบ งาน เงิน ความรัก สุขภาพ คำแนะนำ การเสริมดวง\nเหมาะกับคนที่อยากเช็คแนวทางชีวิตภาพรวม"],
            ['type' => 'extra', 'name' => 'ดวงรายปี (เลข 7 ตัว 4 ฐาน)', 'price' => '349 บาท', 'duration' => 'PDF file', 'description' => "วิเคราะห์ดวงรายปีเฉพาะบุคคล โฟกัสวันเกิดปีปัจจุบันถึงปีถัดไป\nเหมาะสำหรับผู้ที่ต้องการเช็คแนวโน้มดวงชะตารายปี"],
            ['type' => 'extra', 'name' => 'พื้นดวง + ดวงรายปี (เลข 7 ตัว 4 ฐาน)', 'price' => '489 บาท', 'duration' => 'PDF file', 'description' => "เหมาะสำหรับผู้ที่ยังไม่เคยดูดวง หรืออยากเข้าใจทั้งพื้นฐานชีวิต และแนวโน้มของปีปัจจุบัน"],
        ];

        $sql = "INSERT INTO packages (type, name, price, duration, description) VALUES (:type, :name, :price, :duration, :description)";
        $stmt_insert = $db->prepare($sql);

        foreach ($initial_packages as $pkg) {
            $stmt_insert->execute($pkg);
        }

        echo "<span class='success'>เพิ่มข้อมูลแพ็กเกจเริ่มต้นทั้งหมดเรียบร้อยแล้ว!</span><br>";
    } else {
        echo "<span class='info'>ฐานข้อมูลมีข้อมูลแพ็กเกจอยู่แล้ว ไม่มีการเพิ่มข้อมูลซ้ำ</span><br>";
    }

    echo "<hr><h2 class='success'>การตั้งค่าทั้งหมดเสร็จสมบูรณ์!</h2>";

} catch (PDOException $e) {
    die("<h2 class='error'>เกิดข้อผิดพลาดในการตั้งค่าฐานข้อมูล: " . $e->getMessage() . "</h2>");
}
?>