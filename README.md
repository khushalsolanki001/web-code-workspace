# Web Code Workspace

Web Code Workspace is a collaborative web-based coding environment with a VS Code–like interface. It allows users to edit code, manage files and folders, and interact with an AI-powered chat assistant for real-time coding help directly in the browser.

## Features

- VS Code-style code editor with syntax highlighting
- Create, upload, delete, and organize files and folders
- Persistent storage using a MySQL database
- Integrated AI chat assistant for coding support
- Real-time workspace and file management
- Browser-based coding with a clean and simple UI

## Technologies Used

Backend:
- PHP

Database:
- MySQL

Frontend:
- HTML
- CSS
- JavaScript
- Code editor library (CodeMirror or Monaco Editor)

Server:
- Apache (tested using XAMPP on Windows)

## Installation & Setup (Windows 10 with XAMPP)

### Prerequisites
- XAMPP installed
- Git (optional)

### Step 1: Clone or Download the Repository
```bash
git clone https://github.com/khushalsolanki001/web-code-workspace.git
```
### Step 2: Copy Project to XAMPP
Copy the project folder to:
C:\xampp\htdocs\
Recommended folder name:
C:\xampp\htdocs\cp

### Step 3: Start XAMPP Services
Open XAMPP Control Panel and start Apache and MySQL.

### Step 4: Import the Database
Open http://localhost/phpmyadmin  
Create a database named chat_db  
Select the chat_db database  
Import the file: database/chat_db.sql  

If phpMyAdmin fails, use Command Prompt:
cd C:\xampp\mysql\bin  
mysql -u root -p chat_db < C:\xampp\htdocs\cp\database\chat_db.sql  

Press Enter when asked for a password.

### Step 5: Database Configuration
File: C:\xampp\htdocs\cp\db.php

Default configuration:
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'chat_db';

### Step 6: Run the Project
Open your browser and visit:
http://localhost/cp/
or
http://localhost/cp/index.php

## Usage

- Edit code directly in the browser editor
- Manage files and folders using the sidebar
- Chat with the AI assistant for instant coding help
- All changes are saved automatically to the database

## Troubleshooting

- Database errors: Re-import the SQL file and verify database name
- Blank page: Ensure Apache and MySQL are running
- UI issues: Clear browser cache (Ctrl + F5)

## Contributing

Contributions are welcome. Fork the repository, create a branch, and submit a pull request.

## License

MIT License. See the LICENSE file for details.

## Authors

Khushal Solanki  
GitHub: https://github.com/khushalsolanki001  
X / Twitter: https://twitter.com/tecnogame1234  

Mehul Solanki  
GitHub: https://github.com/Mehul203  
Instagram: https://instagram.com/m_b.solanki203  

Enjoy your collaborative code workspace! 🚀
