# Web Code Workspace

A collaborative web-based code workspace with a **VS Code-like interface** for editing code, managing files/folders, and an integrated **AI-powered chat assistant** for coding help.

## Features

- VS Code-style code editor with syntax highlighting
- Upload, create, delete, and organize files & folders
- Persistent storage of code and files (database-backed)
- Integrated AI chat for asking coding questions
- Real-time workspace management

## Screenshots

*(Optional: Add screenshots to a `screenshots/` folder and link them here later)*

## Technologies Used

- **Backend**: PHP
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript (with editor library like CodeMirror or Monaco)
- **Server**: Apache (tested with XAMPP on Windows)

## Installation & Setup (Local Development on Windows 10 with XAMPP)

### Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) installed on Windows 10
- Git (optional, for cloning)

### Steps

1. **Clone or Download the Repository**
   ```bash
   git clone https://github.com/khushalsolanki001/web-code-workspace.git
   ```

   Or download and extract the ZIP file.

2 **Copy Files to XAMPP**
Copy the entire project folder (recommended name: cp) into C:\xampp\htdocs\
Final path: C:\xampp\htdocs\cp

3 **Start XAMPP Services**
Open XAMPP Control Panel
Start Apache and MySQL

4 **Import the Database**
Open phpMyAdmin: http://localhost/phpmyadmin
Click the Databases tab
Create a new database named chat_db
Select the chat_db database from the left sidebar
Click the Import tab
Click Choose File and select: database/chat_db.sql (from your project folder)
Leave all settings as default
Click Go at the bottom
You should see: "Import has been successfully finished"
Alternative (if phpMyAdmin fails due to large file):
Open Command Prompt as Administrator
Run:cmdcd C:\xampp\mysql\bin
mysql -u root -p chat_db < C:\xampp\htdocs\cp\database\chat_db.sql
Press Enter (no password for default XAMPP root)

5 **Configure Database Connection (usually not needed)**
Open C:\xampp\htdocs\cp\db.php
Default XAMPP settings (should work):

```bash
$host = 'localhost';
$username = 'root';
$password = '';          // Empty password
$database = 'chat_db';
```

6 **Run the Project**
Open your browser and go to: http://localhost/cp/
Or: http://localhost/cp/index.php
The workspace should load with all features working.


**Usage**

Edit code directly in the browser editor
Manage files and folders using the sidebar
Chat with the AI assistant for instant coding help
All changes are saved automatically to the database

**Troubleshooting**

Database errors → Re-import the SQL file or check database name
Blank page → Ensure Apache & MySQL are running in XAMPP
UI issues → Clear browser cache (Ctrl + F5)

**Contributing**
Contributions are welcome! Fork the repo, create a branch, and submit a Pull Request.
**License**
MIT License – see the LICENSE file for details.
**Author**
Khushal Solanki
GitHub: @khushalsolanki001
X/Twitter: @tecnogame1234

Enjoy your collaborative code workspace! 🚀
