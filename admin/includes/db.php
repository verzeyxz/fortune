<?php
function getDbConnection() {
    try {
        $db_path = __DIR__ . '/../reffortune.db';
        $db = new PDO('sqlite:' . $db_path);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $db;
    } catch (PDOException $e) {
        http_response_code(500);
        die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage());
    }
}
?>