<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: logout.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Panel</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f0f8ff;
      margin: 0;
      padding: 20px;
      color: #2c3e50;
    }

    #admin {
      max-width: 1000px;
      margin: 0 auto;
    }

    fieldset {
      border: 1px solid #b0d4f1;
      border-radius: 8px;
      padding: 25px;
      background-color: #ffffff;
      box-shadow: 0 4px 8px rgba(52, 152, 219, 0.1);
    }

    legend {
      font-weight: bold;
      font-size: 1.5em;
      color: #2980b9;
      padding: 0 10px;
    }

    h2, h3 {
      color: #2c3e50;
      margin-bottom: 15px;
    }

    .widget {
      margin-bottom: 30px;
    }

    .stats-container {
      display: flex;
      gap: 20px;
      margin-top: 15px;
    }

    .stat-box {
      flex: 1;
      background-color: #eaf4fc;
      border-left: 5px solid #3498db;
      border-radius: 6px;
      padding: 15px;
      transition: background-color 0.3s;
    }

    .stat-box:hover {
      background-color: #d6eaf8;
    }

    .stat-value {
      font-size: 1.6em;
      font-weight: bold;
      color: #2c3e50;
    }

    .admin-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      font-size: 0.95em;
    }

    .admin-table th, .admin-table td {
      padding: 10px 15px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    .admin-table thead {
      background-color: #3498db;
      color: white;
    }

    .admin-table tbody tr:hover {
      background-color: #f0f8ff;
    }

    button, input[type="submit"] {
      background-color: #3498db;
      color: white;
      border: none;
      padding: 8px 14px;
      margin: 5px 5px 0 0;
      border-radius: 4px;
      cursor: pointer;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }

    button:hover, input[type="submit"]:hover {
      background-color: #2980b9;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-bottom: 6px;
      color: #2c3e50;
    }

    select {
      width: 200px;
      padding: 6px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    #searchInput {
      width: 100%;
      max-width: 400px;
      padding: 8px 10px;
      margin-bottom: 15px;
      border-radius: 4px;
      border: 1px solid #ccc;
    }

    .logout-btn {
      background-color: #e74c3c;
      margin-top: 15px;
    }

    .logout-btn:hover {
      background-color: #c0392b;
    }

    @media (max-width: 768px) {
      .stats-container {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>
  <div id="admin">
    <fieldset>
      <legend>Admin Panel</legend>
      <h2>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?></h2>

      <h2>User Management</h2>

      <div class="widget">
        <h3>System Statistics</h3>
        <div class="stats-container">
          <div class="stat-box">
            <h3>Total Users</h3>
            <div class="stat-value">1,245</div>
          </div>
          <div class="stat-box">
            <h3>Active Accounts</h3>
            <div class="stat-value">342</div>
          </div>
          <div class="stat-box">
            <h3>New This Week</h3>
            <div class="stat-value">56</div>
          </div>
        </div>
      </div>

      <div class="widget">
        <h3>User List</h3>

        <input type="text" id="searchInput" placeholder="Search users...">

        <table class="admin-table" id="userTable">
          <thead>
            <tr>
              <th>Account Number</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1001</td>
              <td>John Smith</td>
              <td>john@gmail.com</td>
              <td>User</td>
              <td>Active</td>
              <td>
                <button onclick="editUser(this)">Edit</button>
                <button onclick="toggleStatus(this)">Suspend</button>
              </td>
            </tr>
            <tr>
              <td>1002</td>
              <td>Kayjer Islam</td>
              <td>kayjer@gmail.com</td>
              <td>User</td>
              <td>Active</td>
              <td>
                <button onclick="editUser(this)">Edit</button>
                <button onclick="toggleStatus(this)">Suspend</button>
              </td>
            </tr>
            <tr>
              <td>1003</td>
              <td>Md Fahim</td>
              <td>fahim@gmail.com</td>
              <td>User</td>
              <td>Pending</td>
              <td>
                <button onclick="editUser(this)">Edit</button>
                <button onclick="toggleStatus(this)">Approve</button>
              </td>
            </tr>
          </tbody>
        </table>

        <div style="margin-top: 15px;">
          <button onclick="addNewUser()">Add New User</button>
          <button onclick="exportCSV()">Export to CSV</button>
        </div>
      </div>

      <div class="widget">
        <h3>System Settings</h3>
        <form onsubmit="saveSettings(event)">
          <div class="form-group">
            <label for="maintenance">Maintenance Mode:</label>
            <select id="maintenance">
              <option value="0">Disabled</option>
              <option value="1">Enabled</option>
            </select>
          </div>
          <input type="submit" value="Save Settings">
        </form>
      </div>

      <form method="post">
        <input type="submit" name="logout" value="Log Out" class="logout-btn">
      </form>
    </fieldset>
  </div>

  <script>
    document.getElementById("searchInput").addEventListener("keyup", function () {
      const filter = this.value.toLowerCase();
      const rows = document.querySelectorAll("#userTable tbody tr");

      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
      });
    });

    function toggleStatus(button) {
      const statusCell = button.parentElement.previousElementSibling;
      if (statusCell.innerText === "Active") {
        statusCell.innerText = "Suspended";
        button.innerText = "Activate";
      } else {
        statusCell.innerText = "Active";
        button.innerText = "Suspend";
      }
    }

    function editUser(button) {
      alert("Edit functionality not implemented yet.");
    }

    function addNewUser() {
      const acc = prompt("Enter Account Number:");
      const name = prompt("Enter Full Name:");
      const email = prompt("Enter Email:");
      const role = prompt("Enter Role (e.g. User/Admin):");
      const status = prompt("Enter Status (e.g. Active/Suspended):");

      if (!acc || !name || !email || !role || !status) {
        alert("All fields are required!");
        return;
      }

      const table = document.getElementById("userTable").getElementsByTagName('tbody')[0];
      const newRow = table.insertRow();

      newRow.innerHTML = `
        <td>${acc}</td>
        <td>${name}</td>
        <td>${email}</td>
        <td>${role}</td>
        <td>${status}</td>
        <td>
          <button onclick="editUser(this)">Edit</button>
          <button onclick="toggleStatus(this)">${status === "Active" ? "Suspend" : "Activate"}</button>
        </td>
      `;
    }

    function exportCSV() {
      let csv = [];
      const rows = document.querySelectorAll("table tr");
      for (let row of rows) {
        const cols = Array.from(row.querySelectorAll("th, td")).map(cell => `"${cell.innerText}"`);
        csv.push(cols.join(","));
      }

      const csvContent = csv.join("\n");
      const blob = new Blob([csvContent], { type: "text/csv" });
      const url = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url;
      a.download = "user_list.csv";
      a.click();
      URL.revokeObjectURL(url);
    }

    function saveSettings(event) {
      event.preventDefault();
      const mode = document.getElementById("maintenance").value;
      alert("Settings saved. Maintenance mode is " + (mode === "1" ? "Enabled" : "Disabled"));
    }
  </script>
</body>
</html>