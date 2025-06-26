<?php
require_once __DIR__ . '/../includes/db.php';
header("Content-Type: application/json; charset=UTF-8");

try {
    $db = getDbConnection();
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
        $stmt = $db->query("SELECT section_key, content_value FROM site_content");
        $contents = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // ทำให้ key-value ใช้งานง่าย
        echo json_encode(['success' => true, 'data' => $contents]);

    } elseif ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // ใช้ INSERT OR REPLACE เพื่ออัปเดตถ้ามี key อยู่แล้ว หรือสร้างใหม่ถ้ายังไม่มี
        $sql = "INSERT OR REPLACE INTO site_content (section_key, content_value) VALUES (:section_key, :content_value)";
        $stmt = $db->prepare($sql);

        // วนลูปเพื่อบันทึกข้อมูลทั้งหมดที่ส่งมา
        foreach ($data as $key => $value) {
            $stmt->execute(['section_key' => $key, 'content_value' => $value]);
        }
        
        echo json_encode(['success' => true, 'message' => 'บันทึกเนื้อหาเว็บไซต์เรียบร้อยแล้ว']);

    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()]);
}
?>