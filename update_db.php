<?php
// Run this once to add workspace support to database
require_once __DIR__ . "/db.php";

// Add workspace column if it doesn't exist
$conn->query("ALTER TABLE messages ADD COLUMN IF NOT EXISTS workspace VARCHAR(50) DEFAULT 'SET-A'");

// Update existing records to SET-A
$conn->query("UPDATE messages SET workspace = 'SET-A' WHERE workspace IS NULL OR workspace = ''");

echo "Database updated successfully!";
?>

