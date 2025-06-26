<?php
require_once __DIR__ . '/../includes/db.php';
header("Content-Type: application/json; charset=UTF-8");

try {
    $db = getDbConnection();
    $userId = $_GET['id'] ?? null;

    if ($userId) {
        // --- ดึงข้อมูลผู้ใช้คนเดียว ---
        $stmt = $db->prepare("SELECT id, username, email, created_at FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if ($user) {
            // นับจำนวนครั้งที่เปิดไพ่
            $countStmt = $db->prepare("SELECT COUNT(*) as reading_count FROM saved_readings WHERE user_id = ?");
            $countStmt->execute([$userId]);
            $readingCount = $countStmt->fetchColumn();
            $user['reading_count'] = $readingCount;

            echo json_encode(['success' => true, 'data' => $user]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'ไม่พบผู้ใช้งาน']);
        }
    } else {
        // --- ดึงข้อมูลผู้ใช้ทั้งหมด (เหมือนเดิม) ---
        $stmt = $db->query("SELECT id, username, email, created_at FROM users ORDER BY id DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $users]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()]);
}
?>