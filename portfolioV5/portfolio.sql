CREATE DATABASE IF NOT EXISTS student_portfolio;
USE student_portfolio;

CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    salt VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    technologies VARCHAR(255) NOT NULL,
    project_link VARCHAR(255) NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL,
    reason VARCHAR(100) NOT NULL,
    subject VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (username, password_hash, salt) VALUES
('admin', '7be60437e1b5a6742629ce7f39ebd387a1f08ecc252355d2697724838b318eca', 'portfolio2026!');

INSERT INTO projects (title, category, description, technologies, project_link, image_url) VALUES
('Student Library Portal', 'Full Stack', 'A small portal where students can browse books and send borrow requests using PHP and MySQL.', 'HTML, CSS, JavaScript, PHP, MySQL', 'https://example.com/library-portal', 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=900&q=80'),
('Weather Dashboard', 'Frontend', 'A responsive interface that displays weather information and city cards using JavaScript and API data.', 'HTML, CSS, JavaScript', 'https://example.com/weather-dashboard', 'https://images.unsplash.com/photo-1504608524841-42fe6f032b4b?auto=format&fit=crop&w=900&q=80'),
('Task Manager Admin', 'PHP', 'A CRUD dashboard for adding, editing, and deleting tasks with session login support.', 'PHP, MySQL, Bootstrap', 'https://example.com/task-manager', 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=900&q=80');
