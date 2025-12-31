-- Active: 1753944498707@@127.0.0.1@3306@chat_db
<?php
$file = $_GET['file'] ?? '';

if(!$file || !file_exists($file)){
    header("Content-Type: text/html");
    die("<div style='font-family:sans-serif; text-align:center; margin-top:100px;'>
            <h2 style='color:#ef4444;'>FILE NOT FOUND</h2>
            <p>The requested document has been removed or is unavailable.</p>
         </div>");
}

header("Content-Type: text/plain; charset=utf-8");
echo "PORTAL DOCUMENT VIEW\n";
echo "--------------------------\n\n";
readfile($file);
?>