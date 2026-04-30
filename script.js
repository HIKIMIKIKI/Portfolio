const skills = [
    "HTML5",
    "CSS3",
    "JavaScript",
    "Responsive Design",
    "PHP",
    "Supabase",
    "AJAX",
    "Session Management"
];

const rotatingRoles = [
    "Responsive Web Design",
    "JavaScript Form Validation",
    "PHP CRUD Systems",
    "Supabase Database Integration"
];

const navLinks = document.getElementById("nav-links");
const menuToggle = document.getElementById("menu-toggle");
const themeToggle = document.getElementById("theme-toggle");
const skillsContainer = document.getElementById("skills-container");
const roleTarget = document.getElementById("changing-role");
const projectList = document.getElementById("project-list");
const projectStatus = document.getElementById("project-status");
const projectFilter = document.getElementById("project-filter");
const contactForm = document.getElementById("contact-form");
const formMessage = document.getElementById("form-message");

let projectsCache = [];
let currentRoleIndex = 0;

function initializeYear() {
    document.getElementById("year").textContent = new Date().getFullYear();
}

function initializeMenu() {
    menuToggle.addEventListener("click", () => {
        navLinks.classList.toggle("open");
    });
}

function initializeTheme() {
    const savedTheme = localStorage.getItem("portfolio-theme");
    if (savedTheme === "dark") {
        document.body.classList.add("dark-theme");
        themeToggle.textContent = "Light Mode";
    }

    themeToggle.addEventListener("click", () => {
        document.body.classList.toggle("dark-theme");
        const isDark = document.body.classList.contains("dark-theme");
        themeToggle.textContent = isDark ? "Light Mode" : "Dark Mode";
        localStorage.setItem("portfolio-theme", isDark ? "dark" : "light");
    });
}

function initializeSkills() {
    skills.forEach((skill) => {
        const tag = document.createElement("span");
        tag.className = "skill-tag";
        tag.textContent = skill;
        skillsContainer.appendChild(tag);
    });
}

function initializeRoleRotation() {
    setInterval(() => {
        currentRoleIndex = (currentRoleIndex + 1) % rotatingRoles.length;
        roleTarget.textContent = rotatingRoles[currentRoleIndex];
    }, 2200);
}

function renderProjects(projects) {
    projectList.innerHTML = "";

    if (!projects.length) {
        projectStatus.textContent = "No projects found for this category yet.";
        return;
    }

    projectStatus.textContent = `${projects.length} project(s) loaded successfully.`;

    projects.forEach((project) => {
        const article = document.createElement("article");
        article.className = "project-card";

        const image = document.createElement("img");
        image.src = project.image_url;
        image.alt = project.title;

        const content = document.createElement("div");
        content.className = "project-content";

        const category = document.createElement("span");
        category.className = "project-meta";
        category.textContent = project.category;

        const title = document.createElement("h3");
        title.textContent = project.title;

        const description = document.createElement("p");
        description.textContent = project.description;

        const tools = document.createElement("p");
        const toolsLabel = document.createElement("strong");
        toolsLabel.textContent = "Tools: ";
        tools.append(toolsLabel, document.createTextNode(project.technologies));

        const link = document.createElement("a");
        link.className = "button secondary";
        link.href = project.project_link;
        link.target = "_blank";
        link.rel = "noopener noreferrer";
        link.textContent = "Open Project";

        content.append(category, title, description, tools, link);
        article.append(image, content);
        projectList.appendChild(article);
    });
}

async function loadProjects() {
    projectStatus.textContent = "Loading projects from the database...";

    try {
        const response = await fetch("api/projects.php");
        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message || "Could not load projects.");
        }

        projectsCache = data.projects || [];
        applyProjectFilter();
    } catch (error) {
        projectStatus.textContent = "Projects could not be loaded. Please check the PHP to Supabase connection.";
        projectList.innerHTML = "";
    }
}

function applyProjectFilter() {
    const selected = projectFilter.value;
    const filtered = selected === "all"
        ? projectsCache
        : projectsCache.filter((project) => project.category === selected);

    renderProjects(filtered);
}

function validateContactForm() {
    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const reason = document.getElementById("reason").value.trim();
    const subject = document.getElementById("subject").value.trim();
    const message = document.getElementById("message").value.trim();
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (name.length < 2) {
        return "Please enter a valid name.";
    }

    if (!emailPattern.test(email)) {
        return "Please enter a valid email address.";
    }

    if (!reason) {
        return "Please select a reason for contact.";
    }

    if (subject.length < 3) {
        return "Please enter a subject with at least 3 characters.";
    }

    if (message.length < 10) {
        return "Please write a message with at least 10 characters.";
    }

    return "";
}

async function handleContactSubmit(event) {
    event.preventDefault();

    const validationError = validateContactForm();
    formMessage.className = "form-message";

    if (validationError) {
        formMessage.textContent = validationError;
        formMessage.classList.add("error");
        return;
    }

    const formData = new FormData(contactForm);

    try {
        const response = await fetch("api/contact.php", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message || "Message could not be sent.");
        }

        formMessage.textContent = data.message;
        formMessage.classList.add("success");
        contactForm.reset();
    } catch (error) {
        formMessage.textContent = error.message || "Message could not be sent.";
        formMessage.classList.add("error");
    }
}

document.addEventListener("DOMContentLoaded", () => {
    initializeYear();
    initializeMenu();
    initializeTheme();
    initializeSkills();
    initializeRoleRotation();
    loadProjects();
    projectFilter.addEventListener("change", applyProjectFilter);
    contactForm.addEventListener("submit", handleContactSubmit);
});
