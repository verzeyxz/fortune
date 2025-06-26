<?php
header("Content-Type: application/json; charset=UTF-8");

$db = new PDO('sqlite:reffortune.db');
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $stmt = $db->query("SELECT * FROM packages ORDER BY id DESC");
        $packages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($packages);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO packages (type, name, price, duration, description) VALUES (:type, :name, :price, :duration, :description)";
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        echo json_encode(['status' => 'success', 'id' => $db->lastInsertId()]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "UPDATE packages SET type = :type, name = :name, price = :price, duration = :duration, description = :description WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        echo json_encode(['status' => 'success']);
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "DELETE FROM packages WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $data['id']]);
        echo json_encode(['status' => 'success']);
        break;
}
?>