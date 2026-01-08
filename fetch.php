<?php
require_once __DIR__ . "/db.php";

$workspace = $_GET['workspace'] ?? 'SET-A';
$workspace = mysqli_real_escape_string($conn, $workspace);

$result = $conn->query("SELECT * FROM messages WHERE workspace = '$workspace' ORDER BY id ASC");

while ($row = $result->fetch_assoc()) {
    $id = (int)$row['id'];

    echo "<div class='chat-message' style='position: relative;'>";
    echo "<button onclick='deleteMsg($id)' style='position: absolute; top: 4px; right: 4px; background: transparent; border: none; color: #858585; cursor: pointer; font-size: 18px; line-height: 1; padding: 2px 6px; border-radius: 3px;' onmouseover='this.style.color=\"#f48771\"; this.style.background=\"rgba(244,135,113,0.1)\"' onmouseout='this.style.color=\"#858585\"; this.style.background=\"transparent\"'>&times;</button>";
    echo "<div class='chat-message-header'>" . htmlspecialchars($row['username']) . "</div>";

    if (!empty($row['message'])) {
        echo "<div style='font-size: 13px; color: #cccccc; line-height: 1.5; margin-top: 4px;'>" . nl2br(htmlspecialchars($row['message'])) . "</div>";
    }

    if (!empty($row['file_path'])) {
        $fileName = basename($row['file_path']);
        echo "<div style='margin-top: 8px; padding-top: 8px; border-top: 1px solid #3e3e42; display: flex; gap: 12px; font-size: 11px;'>";
        echo "<a href='view_text.php?file=" . urlencode($row['file_path']) . "' target='_blank' style='color: #4ec9b0; text-decoration: none; font-weight: 500;' onmouseover='this.style.textDecoration=\"underline\"' onmouseout='this.style.textDecoration=\"none\"'>VIEW FILE</a>";
        echo "<a href='" . htmlspecialchars($row['file_path']) . "' download style='color: #4ec9b0; text-decoration: none; font-weight: 500;' onmouseover='this.style.textDecoration=\"underline\"' onmouseout='this.style.textDecoration=\"none\"'>DOWNLOAD</a>";
        echo "<span style='color: #858585; font-size: 10px;'>" . htmlspecialchars($fileName) . "</span>";
        echo "</div>";
    }

    echo "</div>";
}
