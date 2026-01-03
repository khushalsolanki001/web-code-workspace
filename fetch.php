<?php
require_once __DIR__ . "/db.php";

$result = $conn->query("SELECT * FROM messages ORDER BY id ASC");

while ($row = $result->fetch_assoc()) {
    $id = (int)$row['id'];

    echo "<div class='bg-white p-3 rounded-lg border-l-4 border-indigo-500 shadow-sm relative mb-2'>";
    echo "<button onclick='deleteMsg($id)' class='absolute top-2 right-2 text-gray-300 hover:text-red-500'>&times;</button>";
    echo "<p class='text-[10px] font-bold text-gray-400 mb-1 uppercase'>User: " . htmlspecialchars($row['username']) . "</p>";

    if (!empty($row['message'])) {
        echo "<p class='text-sm text-slate-700'>" . nl2br(htmlspecialchars($row['message'])) . "</p>";
    }

    if (!empty($row['file_path'])) {
        echo "<div class='mt-2 pt-2 border-t border-dashed flex gap-4 text-[11px] font-bold'>";
        echo "<a href='view_text.php?file=" . urlencode($row['file_path']) . "' target='_blank' class='text-blue-500'>VIEW FILE</a>";
        echo "<a href='" . htmlspecialchars($row['file_path']) . "' download class='text-green-600'>DOWNLOAD</a>";
        echo "</div>";
    }

    echo "</div>";
}
