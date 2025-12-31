<?php
include 'db.php';

$user = mysqli_real_escape_string($conn, $_POST['username']);
$msg = mysqli_real_escape_string($conn, $_POST['message']);
$path = "";

if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){
    $path = "uploads/" . time() . "_" . $_FILES['file']['name'];
    if(!is_dir('uploads')) mkdir('uploads');
    move_uploaded_file($_FILES['file']['tmp_name'], $path);
}

$q = "INSERT INTO messages (username, message, file_path) VALUES ('$user', '$msg', '$path')";
mysqli_query($conn, $q);
?>