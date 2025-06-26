<?php
// ตั้งค่าให้ PHP แสดงข้อผิดพลาดทั้งหมด เพื่อให้เราเห็นว่าปัญหาเกิดจากอะไร
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูล
require_once __DIR__ . '/../includes/db.php';

// ตั้งค่า Header ให้ถูกต้อง
header("Content-Type: application/json; charset=UTF-8");

// เริ่ม session เพื่อจัดการการล็อกอิน
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = getDbConnection();
// รับข้อมูลที่ถูกส่งมาแบบ JSON
$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';

// --- ส่วนของการสมัครสมาชิก (Register) ---
if ($action === 'register') {
    $username = $data['username'] ?? '';
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    // ตรวจสอบข้อมูลเบื้องต้น
    if (empty($username) || empty($email) || empty($password)) {
        http_response_code(400); // Bad Request
        echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
        exit;
    }

    // ตรวจสอบว่ามีชื่อผู้ใช้หรืออีเมลนี้ในระบบแล้วหรือยัง
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
        http_response_code(409); // Conflict
        echo json_encode(['success' => false, 'message' => 'ชื่อผู้ใช้หรืออีเมลนี้มีผู้ใช้งานแล้ว']);
        exit;
    }

    // เข้ารหัสรหัสผ่านและเพิ่มผู้ใช้ใหม่ลงฐานข้อมูล
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    
    if ($stmt->execute([$username, $email, $hashed_password])) {
        echo json_encode(['success' => true, 'message' => 'สมัครสมาชิกสำเร็จ']);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการสมัครสมาชิก']);
    }
} 
// --- ส่วนของการเข้าสู่ระบบ (Login) ---
elseif ($action === 'login') {
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';

    if (empty($username) || empty($password)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'กรุณากรอกชื่อผู้ใช้และรหัสผ่าน']);
        exit;
    }

    // ค้นหาผู้ใช้จาก username หรือ email
    $stmt = $db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();

    // ตรวจสอบรหัสผ่าน
    if ($user && password_verify($password, $user['password'])) {
        // เมื่อสำเร็จ ให้เก็บข้อมูลลง session
        $_SESSION['user_logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_username'] = $user['username'];
        echo json_encode(['success' => true, 'message' => 'เข้าสู่ระบบสำเร็จ']);
    } else {
        http_response_code(401); // Unauthorized
        echo json_encode(['success' => false, 'message' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง']);
    }
}
// --- หากไม่มี action ที่ถูกต้อง ---
else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>