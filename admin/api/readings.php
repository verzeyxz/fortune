<?php
// ตั้งค่าให้ PHP แสดงข้อผิดพลาดเพื่อการดีบัก
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/db.php';

// ใช้ session_start() จากที่นี่เพื่อให้แน่ใจว่าทำงานก่อนทุกอย่าง
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json; charset=UTF-8");

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id'])) {
    http_response_code(403); // Forbidden
    echo json_encode(['success' => false, 'message' => 'จำเป็นต้องเข้าสู่ระบบ']);
    exit;
}

try {
    $db = getDbConnection();
    $method = $_SERVER['REQUEST_METHOD'];
    $userId = $_SESSION['user_id'];

    if ($method === 'GET') {
        // ดึงประวัติการเปิดไพ่ทั้งหมดของผู้ใช้คนนี้
        $stmt = $db->prepare("SELECT id, reading_title, card_ids, created_at FROM saved_readings WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        $readings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $readings]);

    } elseif ($method === 'POST') {
        // บันทึกผลการเปิดไพ่ใหม่
        $data = json_decode(file_get_contents('php://input'), true);
        $title = $data['title'] ?? 'ผลการเปิดไพ่';
        $card_ids_array = $data['cards'] ?? [];

        if (empty($card_ids_array)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ไม่มีข้อมูลไพ่ที่จะบันทึก']);
            exit;
        }

        $cardIdsString = implode(',', $card_ids_array);
        $sql = "INSERT INTO saved_readings (user_id, reading_title, card_ids) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);
        
        if ($stmt->execute([$userId, $title, $cardIdsString])) {
            echo json_encode(['success' => true, 'message' => 'บันทึกผลคำทำนายเรียบร้อยแล้ว']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูลลงฐานข้อมูล']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()]);
}
?>