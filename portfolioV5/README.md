# Student Full-Stack Portfolio

This is a simple university-level full-stack portfolio project built with HTML, CSS, JavaScript, PHP, and MySQL.

## Features

- Responsive portfolio homepage
- Project data loaded from MySQL with AJAX
- Contact form with JavaScript validation and PHP/MySQL storage
- Session-based admin login
- Cookie that stores the last admin login time
- Admin dashboard to add, edit, and delete projects

## Default Admin Login

- Username: `admin`
- Password: `admin123`

## Setup

1. Copy the project folder into your local server directory such as `htdocs` or `www`.
2. Create a MySQL database by importing `portfolio.sql`.
3. Open `config.php` and update the database username and password if needed.
4. Start Apache and MySQL from XAMPP, WAMP, or a similar local server.
5. Open `index.html` in your browser through the local server URL.

## Important Files

- `index.html`: portfolio homepage
- `style.css`: styling and responsive design
- `script.js`: frontend interactivity, AJAX, and form validation
- `config.php`: database connection and shared helpers
- `admin.php`: admin dashboard
- `login.php`: admin login page
- `portfolio.sql`: MySQL structure and sample data
