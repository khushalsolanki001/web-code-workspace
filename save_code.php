<?php
require_once __DIR__ . "/db.php";

if (isset($_POST['code'])) {
    $stmt = $conn->prepare("UPDATE live_code SET code=? WHERE id=1");
    $stmt->bind_param("s", $_POST['code']);
    $stmt->execute();
}

$res = $conn->query("SELECT code FROM live_code WHERE id=1");
$row = $res->fetch_assoc();

echo $row['code'] ?? '';
?>