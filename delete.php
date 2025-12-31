<?php
include 'db.php';
$id = (int)$_GET['id'];

$q = mysqli_query($conn, "SELECT file_path FROM messages WHERE id=$id");
$data = mysqli_fetch_assoc($q);

// Delete file from server
if(!empty($data['file_path']) && file_exists($data['file_path'])){
    unlink($data['file_path']);
}
// testing pull request
// Delete from DB
mysqli_query($conn, "DELETE FROM messages WHERE id=$id");
?>