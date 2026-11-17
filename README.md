# Library Member Hub — CIS 344 PHP + MySQL Project

**Student:** AbdulMateen Mudasiru  
**Technology:** PHP 8.1+, MySQL/MariaDB, PDO, HTML, CSS  
**Purpose:** Register library members through a PHP form and save their information in a MySQL database.

## Features
- Responsive custom-designed member registration page
- Required fields: full name, email, phone, membership type, favorite book genre
- Optional notes field
- Server-side validation and CSRF token protection
- PDO prepared SQL statements for safe database inserts and searches
- Unique email addresses enforced in the database
- Searchable directory of the latest 100 saved members
- Clear success and error messages; safe HTML escaping

## Setup using XAMPP
1. Install XAMPP with PHP and MySQL/MariaDB. Start **Apache** and **MySQL** in the XAMPP control panel.
2. Copy the `Library_Member_PHP_Platform` folder into the XAMPP `htdocs` directory. On macOS, this is commonly `/Applications/XAMPP/xamppfiles/htdocs/` (verify your installation path).
3. Open `http://localhost/phpmyadmin`.
4. Use the **Import** tab to import `sql/database.sql`. This creates the `library_platform` database and `members` table.
5. The default local connection settings are host `127.0.0.1`, port `3306`, database `library_platform`, username `root`, and empty password. If your local MySQL setup differs, set environment variables `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, and `DB_PASSWORD` in your web server environment. Do **not** commit credentials to GitHub.
6. Visit `http://localhost/Library_Member_PHP_Platform/index.php`.
7. Fill in the registration form and click **Save member to database**. The application redirects to `members.php`, where the new record appears.

## Alternative: PHP built-in development server
After configuring MySQL and importing the SQL file, run from the project folder:

```bash
DB_HOST=127.0.0.1 DB_PORT=3306 DB_NAME=library_platform DB_USER=root DB_PASSWORD='your_password' php -S localhost:8000
```

Then open `http://localhost:8000`. The PHP built-in server is for local development only, not public production hosting.

## How form data reaches the database
1. The browser sends a `POST` request from `index.php`.
2. PHP verifies a session-based CSRF token and validates every field on the server.
3. `config/database.php` creates a PDO connection using the MySQL driver and UTF-8 encoding.
4. PHP runs a prepared `INSERT INTO members` query with bound parameters.
5. MySQL stores the row, automatically assigning an ID and timestamp.
6. The user is redirected to `members.php`, which fetches and displays saved records.

## Database fields
| Field | Purpose |
| --- | --- |
| `id` | Auto-generated primary key |
| `full_name` | Member's name |
| `email` | Unique contact email |
| `phone` | Contact number |
| `membership_type` | Student, Faculty, or Community |
| `favorite_genre` | Reading preference |
| `notes` | Optional comments |
| `created_at` | Automatic registration timestamp |

## Test checklist
- [ ] Apache and MySQL are running
- [ ] Database SQL imported without errors
- [ ] Valid registration appears in the directory
- [ ] Same email submitted twice produces a clear error
- [ ] Invalid email and missing required fields are rejected
- [ ] Search finds a member by name or genre
- [ ] A submitted HTML tag is displayed as text, not executed

## GitHub submission
1. Create a new GitHub repository named `Library-Member-PHP-Platform`.
2. Upload all files in this folder, including `sql/database.sql` and this README.
3. Do not upload private credentials or `.env` files.
4. Copy your repository URL and submit it to your instructor.

**Important:** A GitHub repository hosts the source code, not a running PHP/MySQL site. The grader must run the setup steps locally or deploy to a PHP/MySQL host. The project is intended for coursework demonstration. The directory currently shows member contact details without authentication, so do not expose it publicly with real personal data; add login, authorization, HTTPS, and appropriate privacy controls for production use.

## Creativity
Unlike a generic contact form, this project tracks member categories and favorite book genres, supporting future library events, reading recommendations, and targeted outreach.
