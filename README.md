Web Code Workspace
A collaborative web-based code workspace with a VS Code-like interface for editing code, managing files/folders, and an integrated AI-powered chat assistant for coding help.
Features

VS Code-style code editor with syntax highlighting
Upload, create, delete, and organize files & folders
Persistent storage of code and files (database-backed)
Integrated AI chat for asking coding questions
Real-time workspace management

Screenshots
(Add your own screenshots to a screenshots/ folder and link them here for better visuals)
Technologies Used

Backend: PHP
Database: MySQL
Frontend: HTML, CSS, JavaScript (with editor library like CodeMirror or Monaco)
Server: Apache (tested with XAMPP on Windows)

Installation & Setup (Local Development on Windows 10 with XAMPP)
Prerequisites

XAMPP installed on Windows 10
PHP 7+
Git (optional, for cloning)

Steps

Clone or Download the RepositoryBashgit clone https://github.com/khushalsolanki001/web-code-workspace.gitOr download the ZIP and extract it.
Copy Files to XAMPP
Copy the entire project folder (e.g., name it cp) to C:\xampp\htdocs\
Final path: C:\xampp\htdocs\cp

Start XAMPP
Open XAMPP Control Panel
Start Apache and MySQL

Import the Database
Open phpMyAdmin in your browser: http://localhost/phpmyadmin
Click on "Databases" tab
Create a new database named chat_db (or any name – if different, update in db.php)
Select the newly created chat_db from the left sidebar
Click on the "Import" tab at the top
Click "Choose File" and select the SQL file: database/chat_db.sql from your project folder
Leave default settings (Format: SQL)
Click "Go" at the bottom
Wait for the success message ("Import has been successfully finished")
Note: If the SQL file is large and import fails in phpMyAdmin, use Command Prompt:cmdcd C:\xampp\mysql\bin
mysql -u root -p chat_db < C:\xampp\htdocs\cp\database\chat_db.sql(Press Enter, no password for default XAMPP root, then wait)
Configure Database Connection (if needed)
Open db.php in C:\xampp\htdocs\cp\db.php
Default for XAMPP (should work out-of-the-box):PHP$host = 'localhost';
$username = 'root';
$password = '';  // Empty for default XAMPP
$database = 'chat_db';

Run the Project
Open in browser: http://localhost/cp/ (or http://localhost/cp/index.php)
The workspace should load fully with all tables and structure from the imported SQL.


Usage

Edit code in the editor panel
Manage files/folders via the sidebar
Use the AI chat for instant coding assistance
Everything is saved persistently

Troubleshooting

If you see database errors: Double-check the import succeeded and database name matches db.php
Clear browser cache if UI issues occur
Check Apache/MySQL logs in XAMPP if server errors

Contributing
Feel free to fork, improve, and submit Pull Requests!
License
MIT License – see LICENSE file for details.
Author
Khushal Solanki
GitHub: @khushalsolanki001
X/Twitter: @tecnogame1234

Enjoy your collaborative code workspace! 🚀
