<?php
include 'db.php';

// Agar user Save button dabata hai
if(isset($_POST['code'])){
    $code = mysqli_real_escape_string($conn, $_POST['code']);
    mysqli_query($conn, "UPDATE live_code SET code='$code' WHERE id=1");
}

// Code fetch karein (index.php ke refresh ke liye)
$res = mysqli_query($conn, "SELECT code FROM live_code WHERE id=1");
$row = mysqli_fetch_assoc($res);
echo $row['code'] ?? '';
?>