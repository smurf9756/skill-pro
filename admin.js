// Fetch all skills
async function loadSkills() {
  const res = await fetch("admin_actions.php?action=listSkills");
  const data = await res.json();
  const tableBody = document.querySelector("#skillsTable tbody");
  tableBody.innerHTML = "";

  if (data.success) {
    data.skills.forEach(skill => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${skill.skill_id}</td>
        <td>${skill.skill_name}</td>
        <td>${skill.trainer}</td>
        <td>${skill.category}</td>
        <td>${skill.price}</td>
        <td><img src="uploads/${skill.image}" width="60" height="60"></td>
        <td><button onclick="deleteSkill(${skill.skill_id})">Delete</button></td>
      `;
      tableBody.appendChild(row);
    });
  }
}

// Delete a skill
async function deleteSkill(id) {
  if (!confirm("Are you sure you want to delete this skill?")) return;

  const formData = new FormData();
  formData.append("action", "deleteSkill");
  formData.append("id", id);

  const res = await fetch("admin_actions.php", { method: "POST", body: formData });
  const data = await res.json();

  alert(data.message || (data.success ? "Deleted successfully!" : "Failed to delete"));
  loadSkills();
}

// Fetch all users
async function loadUsers() {
  const res = await fetch("admin_actions.php?action=listUsers");
  const data = await res.json();
  const tableBody = document.querySelector("#usersTable tbody");
  tableBody.innerHTML = "";

  if (data.success) {
    data.users.forEach(user => {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${user.id}</td>
        <td>${user.fullname}</td>
        <td>${user.email}</td>
        <td>${user.phone}</td>
        <td>${user.role}</td>
        <td><button onclick="deleteUser(${user.id})">Delete</button></td>
      `;
      tableBody.appendChild(row);
    });
  }
}

// Delete a user
async function deleteUser(id) {
  if (!confirm("Are you sure you want to delete this user?")) return;

  const formData = new FormData();
  formData.append("action", "deleteUser");
  formData.append("id", id);

  const res = await fetch("admin_actions.php", { method: "POST", body: formData });
  const data = await res.json();

  alert(data.message || (data.success ? "User deleted successfully!" : "Failed to delete user"));
  loadUsers();
}

// Initialize
window.onload = () => {
  loadSkills();
  loadUsers();
};
