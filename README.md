
# Personal Contact Manager

This is a web-based contact manager built for the COP4331 Small Project assignment. It allows users to register, log in, and manage their personal contacts securely.

## Features
- User registration and login (with hashed passwords)
- Add, edit, delete, and search contacts
- Responsive, modern frontend (HTML/CSS/JS)
- Backend API in PHP (LAMP stack)
- MySQL database with user and contact tables

## Project Structure

- `contacts.html` — Main contacts dashboard (requires login)
- `login.html` — User login page
- `register.html` — User registration page
- `backend/` — PHP backend API and database connection
- `js/` — Frontend JavaScript (login, register, contacts)
- `css/` — Stylesheets
- `images/` — Icons and images
- `docs/` — Additional documentation

## Setup Instructions

1. **Database:**
	- Import the schema: `backend/schema.sql` into your MySQL server.
	- Create a MySQL user and grant it access to the `contact_manager` database.
	- Update `backend/connection.php` with your database credentials.

2. **Web Server:**
	- Deploy all files to your LAMP server (Linux, Apache, MySQL, PHP).
	- Make sure the `backend/` directory is accessible to PHP and the web server.

3. **Usage:**
	- Visit `register.html` to create a new account.
	- Log in via `login.html`.
	- Manage your contacts in `contacts.html` (add, edit, delete, search).

## Team Members
- Renard Ali
- Anthony Fontana
- Jalen Henry
- Samuel Leavitt
- Jose Serrano
- Linh Tran
- Leonardo Vargas

## License
Apache License 2.0 — see LICENSE file for details.

## Additional Documentation
See the `docs/` folder for more details and design notes.
