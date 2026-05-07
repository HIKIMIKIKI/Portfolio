<?php
require_once __DIR__ . '/config.php';
require_admin_login();

$response = supabase_request(
    'GET',
    'projects?select=id,title,category,description,technologies,project_link,image_url,created_at&order=created_at.desc'
);

$projects = $response['status'] < 400 ? $response['data'] : [];
$lastLogin = $_COOKIE['last_admin_login'] ?? 'First login on this browser';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Roboto+Slab:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar container">
            <a class="brand" href="index.html">Eldar Ibrahimli <span>Portfolio</span></a>
            <div class="hero-actions">
                <a class="button secondary" href="index.html">View Site</a>
                <a class="button primary" href="logout.php">Logout</a>
            </div>
        </nav>
    </header>

    <main class="section">
        <div class="container">
            <div class="section-heading">
                <p class="eyebrow">Dashboard</p>
                <h1 style="font-size: 2.5rem;">Admin Panel</h1>
                <p>Welcome, <?= escape_html($_SESSION['admin_username']) ?>. Last login cookie: <?= escape_html($lastLogin) ?></p>
            </div>

            <div class="skills-layout">
                <section class="card">
                    <h2 style="font-size: 1.7rem;">Add or edit project</h2>
                    <form id="admin-project-form" class="contact-form" action="api/project-save.php" method="post">
                        <input type="hidden" id="project-id" name="id" value="0">

                        <label for="project-title">Title</label>
                        <input type="text" id="project-title" name="title" required>

                        <label for="project-category">Category</label>
                        <select id="project-category" name="category" required>
                            <option value="Frontend">Frontend</option>
                            <option value="Full Stack">Full Stack</option>
                            <option value="PHP">PHP</option>
                        </select>

                        <label for="project-description">Description</label>
                        <textarea id="project-description" name="description" rows="4" required></textarea>

                        <label for="project-technologies">Technologies</label>
                        <input type="text" id="project-technologies" name="technologies" required>

                        <label for="project-link">Project Link</label>
                        <input type="url" id="project-link" name="project_link" required>

                        <label for="project-image">Image URL</label>
                        <input type="url" id="project-image" name="image_url" required>

                        <div class="hero-actions">
                            <button class="button primary" type="submit">Save Project</button>
                            <button class="button secondary" type="button" id="reset-project-form">Clear</button>
                        </div>
                        <p id="admin-form-message" class="form-message" aria-live="polite"></p>
                    </form>
                </section>

                <section class="card">
                    <h2 style="font-size: 1.7rem;">Current projects</h2>
                    <div id="admin-project-list">
                        <?php foreach ($projects as $project): ?>
                            <article class="project-card" style="margin-bottom: 1rem;">
                                <div class="project-content">
                                    <span class="project-meta"><?= escape_html($project['category'] ?? '') ?></span>
                                    <h3><?= escape_html($project['title'] ?? '') ?></h3>
                                    <p><?= escape_html($project['description'] ?? '') ?></p>
                                    <p><strong>Tools:</strong> <?= escape_html($project['technologies'] ?? '') ?></p>
                                    <div class="hero-actions">
                                        <button
                                            class="button secondary edit-project-button"
                                            type="button"
                                            data-id="<?= (int) ($project['id'] ?? 0) ?>"
                                            data-title="<?= escape_html($project['title'] ?? '') ?>"
                                            data-category="<?= escape_html($project['category'] ?? '') ?>"
                                            data-description="<?= escape_html($project['description'] ?? '') ?>"
                                            data-technologies="<?= escape_html($project['technologies'] ?? '') ?>"
                                            data-link="<?= escape_html($project['project_link'] ?? '') ?>"
                                            data-image="<?= escape_html($project['image_url'] ?? '') ?>"
                                        >
                                            Edit
                                        </button>
                                        <button class="button primary delete-project-button" type="button" data-id="<?= (int) ($project['id'] ?? 0) ?>">Delete</button>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <script src="admin.js"></script>
</body>
</html>
