# User-Authentication-System
🔐 User Authentication with PHP & MySQL
This project is a clean, beginner-friendly authentication system that demonstrates secure registration, login, and logout using PHP, MySQL, password hashing, and sessions. It follows best practices like prepared statements (to prevent SQL injection) and password_hash / password_verify for safe password handling. The UI uses Bootstrap 5 with a small custom stylesheet for a simple, modern look.

Features

Register with name, email, password, confirm password

Email uniqueness check and friendly validation errors

Login with session-based authentication

Protected dashboard page (“Welcome, [User Name]!”)

Logout that safely ends the session

Prevents logged-in users from accessing login or register

Setup

Import schema.sql in MySQL to create the database and users table.

Update credentials in db.php if needed.

Serve the folder (XAMPP/WAMP or php -S localhost:8000).

Visit register.php → create an account → login.php → dashboard.php.

Security Notes

All SQL uses prepared statements.

Passwords are hashed, never stored in plain text.

Session ID is regenerated on login.
