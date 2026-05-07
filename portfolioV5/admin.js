const adminForm = document.getElementById("admin-project-form");
const adminMessage = document.getElementById("admin-form-message");
const resetButton = document.getElementById("reset-project-form");

function resetProjectForm() {
    adminForm.reset();
    document.getElementById("project-id").value = "0";
    adminMessage.textContent = "";
    adminMessage.className = "form-message";
}

function fillProjectForm(button) {
    document.getElementById("project-id").value = button.dataset.id;
    document.getElementById("project-title").value = button.dataset.title;
    document.getElementById("project-category").value = button.dataset.category;
    document.getElementById("project-description").value = button.dataset.description;
    document.getElementById("project-technologies").value = button.dataset.technologies;
    document.getElementById("project-link").value = button.dataset.link;
    document.getElementById("project-image").value = button.dataset.image;
    window.scrollTo({ top: 0, behavior: "smooth" });
}

async function saveProject(event) {
    event.preventDefault();
    adminMessage.textContent = "Saving project...";
    adminMessage.className = "form-message";

    try {
        const response = await fetch(adminForm.action, {
            method: "POST",
            body: new FormData(adminForm)
        });

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message || "Could not save project.");
        }

        adminMessage.textContent = data.message + " Refreshing the dashboard...";
        adminMessage.classList.add("success");
        setTimeout(() => window.location.reload(), 800);
    } catch (error) {
        adminMessage.textContent = error.message;
        adminMessage.classList.add("error");
    }
}

async function deleteProject(id) {
    if (!confirm("Delete this project?")) {
        return;
    }

    try {
        const formData = new FormData();
        formData.append("id", id);

        const response = await fetch("api/project-delete.php", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        if (!data.success) {
            throw new Error(data.message || "Could not delete project.");
        }

        window.location.reload();
    } catch (error) {
        adminMessage.textContent = error.message;
        adminMessage.className = "form-message error";
    }
}

document.addEventListener("click", (event) => {
    const editButton = event.target.closest(".edit-project-button");
    const deleteButton = event.target.closest(".delete-project-button");

    if (editButton) {
        fillProjectForm(editButton);
    }

    if (deleteButton) {
        deleteProject(deleteButton.dataset.id);
    }
});

adminForm.addEventListener("submit", saveProject);
resetButton.addEventListener("click", resetProjectForm);
