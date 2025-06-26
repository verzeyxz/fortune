<?php
// ตั้งค่าให้ PHP แสดงข้อผิดพลาดทั้งหมด
ini_set('display_errors', 1);
error_reporting(E_ALL);

// เรียกใช้ไฟล์เชื่อมต่อฐานข้อมูล
require_once __DIR__ . '/../includes/db.php';

// ตั้งค่า Header
header("Content-Type: application/json; charset=UTF-8");

try {
    $db = getDbConnection();
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            $stmt = $db->query("SELECT * FROM packages ORDER BY id ASC");
            $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($packages);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $sql = "INSERT INTO packages (type, name, price, duration, description) VALUES (:type, :name, :price, :duration, :description)";
            $stmt = $db->prepare($sql);
            $stmt->execute($data);
            echo json_encode(['status' => 'success', 'message' => 'เพิ่มแพ็กเกจสำเร็จ', 'id' => $db->lastInsertId()]);
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $sql = "UPDATE packages SET type = :type, name = :name, price = :price, duration = :duration, description = :description WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute($data);
            echo json_encode(['status' => 'success', 'message' => 'อัปเดตแพ็กเกจสำเร็จ']);
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            $sql = "DELETE FROM packages WHERE id = :id";
            $stmt = $db->prepare($sql);
            $stmt->execute(['id' => $data['id']]);
            echo json_encode(['status' => 'success', 'message' => 'ลบแพ็กเกจสำเร็จ']);
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
            break;
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server Error: ' . $e->getMessage()]);
}
?>