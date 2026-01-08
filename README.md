# 🚀 Web Code Workspace

![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**Web Code Workspace** is a collaborative, web-based coding environment designed to replicate the feel of VS Code. It allows users to edit code, manage files, and interact with an AI-powered chat assistant for real-time coding help—all directly within the browser.

---

## 🖼️ Screenshots

- <img width="1920" height="1080" alt="Screenshot (211)" src="https://github.com/user-attachments/assets/0869a2d7-1573-4f07-8f57-a017764e0870" />

- <img width="1920" height="1080" alt="Screenshot (210)" src="https://github.com/user-attachments/assets/f49dcd44-7794-415e-837e-3c608d5ed42a" />


---

## ✨ Features

- **💻 VS Code-like Interface:** Familiar layout with syntax highlighting and line numbering.
- **📂 File Management:** Create, upload, delete, and organize files and folders dynamically.
- **💾 Persistent Storage:** All project data is securely stored using a MySQL database.
- **🤖 AI Integration:** Built-in AI chat assistant for debugging and code suggestions.
- **⚡ Real-time Updates:** Smooth file handling and workspace management.
- **☁️ Self-Hosted:** Runs on standard Apache/PHP environments (e.g., XAMPP, LAMP).

---

## 🛠️ Technologies Used

### Backend
- **PHP:** Core logic and file operations.
- **MySQL:** Database for storing file metadata and chat logs.

### Frontend
- **HTML5 / CSS3:** Structure and styling.
- **JavaScript (Vanilla):** DOM manipulation and asynchronous requests.
- **Editor Library:** CodeMirror or Monaco Editor.

### Server Environment
- **Apache:** Web server (Tested on Windows 10 via XAMPP).

---

## ⚙️ Installation & Setup

Follow these steps to set up the project locally using **XAMPP** on Windows.

### Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) installed and running.
- Git (Optional).

### Step 1: Clone the Repository
Open your terminal or command prompt:
```bash
git clone [https://github.com/khushalsolanki001/web-code-workspace.git](https://github.com/khushalsolanki001/web-code-workspace.git)
```

### Step 2: Move Project to Web Root
Copy the project folder into your XAMPP `htdocs` directory.
- **Source:** Your cloned folder.
- **Destination:** `C:\xampp\htdocs\cp`

### Step 3: Start Services
Open the **XAMPP Control Panel** and start:
1.  **Apache**
2.  **MySQL**

### Step 4: Database Setup
You can import the database via phpMyAdmin or the Command Line.

**Option A: via phpMyAdmin**
1.  Go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
2.  Create a new database named `chat_db`.
3.  Select `chat_db` and click **Import**.
4.  Choose the file: `C:\xampp\htdocs\cp\database\chat_db.sql`.

**Option B: via Command Line**
```bash
cd C:\xampp\mysql\bin
mysql -u root -p chat_db < C:\xampp\htdocs\cp\database\chat_db.sql
# Press Enter if you have no root password set
```

### Step 5: Configure Connection
Ensure your database credentials match in `db.php`.
*File Location:* `C:\xampp\htdocs\cp\db.php`

```php
<?php
$host = 'localhost';
$username = 'root';
$password = ''; // Default XAMPP password is empty
$database = 'chat_db';
?>
```

### Step 6: Launch
Open your browser and navigate to:
[http://localhost/cp/](http://localhost/cp/)

---

## 📂 Project Structure

```text
web-code-workspace/
├── database/
│   └── chat_db.sql       # Database import file
├── assets/               # CSS, JS, and Images
├── php/                  # Backend logic files
├── index.php             # Main entry point
├── db.php                # Database configuration
└── README.md             # Documentation
```

---

## 🎮 Usage

- **Code Editor:** Write and edit code directly in the browser with syntax highlighting.
- **Sidebar:** Manage your project structure by creating, deleting, or renaming files and folders.
- **AI Assistant:** Use the built-in chat window to ask coding questions or get debugging help.
- **Auto-Save:** All changes are automatically synchronized with the database.

---

## 🐛 Troubleshooting

| Issue | Solution |
| :--- | :--- |
| **Database Connection Error** | Check `db.php` credentials and ensure MySQL is running in XAMPP. |
| **Blank Page** | Check Apache error logs (`C:\xampp\apache\logs\error.log`) for PHP syntax errors. |
| **Styling/JS Issues** | Hard refresh your browser using `Ctrl + F5` to clear the cache. |
| **404 Not Found** | Ensure the folder is named `cp` inside `htdocs`. |

---

## 🤝 Contributing

Contributions are welcome! Please follow these steps:
1.  **Fork** the repository.
2.  Create a new branch (`git checkout -b feature/AmazingFeature`).
3.  Commit your changes (`git commit -m 'Add some AmazingFeature'`).
4.  Push to the branch (`git push origin feature/AmazingFeature`).
5.  Open a **Pull Request**.

---

## 👥 Authors

**Khushal Solanki**
- GitHub: [@khushalsolanki001](https://github.com/khushalsolanki001)
- X / Twitter: [@tecnogame1234](https://twitter.com/tecnogame1234)

**Mehul Solanki**
- GitHub: [@Mehul203](https://github.com/Mehul203)
- Instagram: [@m_b.solanki203](https://instagram.com/m_b.solanki203)

---

## 📄 License

Distributed under the MIT License. See `LICENSE` for more information.
