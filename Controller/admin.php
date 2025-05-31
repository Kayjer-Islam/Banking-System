<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once '../model/dbuser.php';

if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: logout.php");
    exit();
}

$totalUsers = 0;
$activeUsers = 0;
$newThisWeek = 0;
$users = [];

$result = mysqli_query($con, "SELECT COUNT(*) as count FROM users");
if ($result) {
    $totalUsers = mysqli_fetch_assoc($result)['count'];
} else {
    die("Error fetching total users: " . mysqli_error($con));
}

$result = mysqli_query($con, "SELECT COUNT(*) as count FROM users WHERE status='active'");
if ($result) {
    $activeUsers = mysqli_fetch_assoc($result)['count'];
} else {
    die("Error fetching active users: " . mysqli_error($con));
}

$result = mysqli_query($con, "SELECT COUNT(*) as count FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
if ($result) {
    $newThisWeek = mysqli_fetch_assoc($result)['count'];
} else {
    die("Error fetching new users: " . mysqli_error($con));
}

$result = mysqli_query($con, "SELECT * FROM users ORDER BY id DESC");
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
} else {
    die("Error fetching users: " . mysqli_error($con));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        $accountNumber = mysqli_real_escape_string($con, $_POST['account_number']);
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $role = mysqli_real_escape_string($con, $_POST['role']);
        $status = mysqli_real_escape_string($con, $_POST['status']);
        
        $query = "INSERT INTO users (account_number, username, email, role, status) 
                  VALUES ('$accountNumber', '$name', '$email', '$role', '$status')";
        if (!mysqli_query($con, $query)) {
            die("Error adding user: " . mysqli_error($con));
        }
        header("Location: admin.php"); 
        exit();
    } 
    elseif (isset($_POST['edit_user'])) {
        $id = (int)$_POST['user_id'];
        $accountNumber = mysqli_real_escape_string($con, $_POST['account_number']);
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $role = mysqli_real_escape_string($con, $_POST['role']);
        $status = mysqli_real_escape_string($con, $_POST['status']);
        
        $query = "UPDATE users SET 
                  account_number = '$accountNumber',
                  username = '$name',
                  email = '$email',
                  role = '$role',
                  status = '$status'
                  WHERE id = $id";
        if (!mysqli_query($con, $query)) {
            die("Error updating user: " . mysqli_error($con));
        }
        header("Location: admin.php"); 
        exit();
    } 
    elseif (isset($_POST['toggle_status'])) {
        $id = (int)$_POST['user_id'];
        $query = "UPDATE users SET 
                  status = IF(status='active', 'suspended', 'active')
                  WHERE id = $id";
        if (!mysqli_query($con, $query)) {
            die("Error toggling status: " . mysqli_error($con));
        }
        header("Location: admin.php"); 
        exit();
    } 
    elseif (isset($_POST['delete_user'])) {
        $id = (int)$_POST['user_id'];
        $query = "DELETE FROM users WHERE id = $id";
        if (!mysqli_query($con, $query)) {
            die("Error deleting user: " . mysqli_error($con));
        }
        header("Location: admin.php"); 
        exit();
    }
}

mysqli_close($con);
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
            <div class="stat-value"><?php echo number_format($totalUsers); ?></div>
          </div>
          <div class="stat-box">
            <h3>Active Accounts</h3>
            <div class="stat-value"><?php echo number_format($activeUsers); ?></div>
          </div>
          <div class="stat-box">
            <h3>New This Week</h3>
            <div class="stat-value"><?php echo number_format($newThisWeek); ?></div>
          </div>
        </div>
      </div>

      <div class="widget">
        <h3>User List</h3>

        <input type="text" id="searchInput" placeholder="Search users...">

        <table class="admin-table" id="userTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Account Number</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
              <td><?php echo $user['id']; ?></td>
              <td><?php echo htmlspecialchars($user['account_number']); ?></td>
              <td><?php echo htmlspecialchars($user['username']); ?></td>
              <td><?php echo htmlspecialchars($user['email']); ?></td>
              <td><?php echo htmlspecialchars($user['role']); ?></td>
              <td><?php echo htmlspecialchars($user['status']); ?></td>
              <td>
                <button onclick="showEditModal(<?php echo $user['id']; ?>, '<?php echo $user['account_number']; ?>', '<?php echo addslashes($user['username']); ?>', '<?php echo $user['email']; ?>', '<?php echo $user['role']; ?>', '<?php echo $user['status']; ?>')">Edit</button>
                <form method="post" style="display:inline;">
                  <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                  <input type="hidden" name="toggle_status">
                  <button type="submit"><?php echo $user['status'] === 'active' ? 'Suspend' : 'Activate'; ?></button>
                </form>
                <form method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                  <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                  <input type="hidden" name="delete_user">
                  <button type="submit" style="background-color:#e74c3c;">Delete</button>
                </form>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <div style="margin-top: 15px;">
          <button onclick="showAddModal()">Add New User</button>
          <button onclick="exportCSV()">Export to CSV</button>
        </div>
      </div>

     
      <div id="userModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:1000;">
        <div style="background-color:white; width:400px; margin:100px auto; padding:20px; border-radius:5px;">
          <h2 id="modalTitle">Add New User</h2>
          <form method="post" id="userForm">
            <input type="hidden" name="user_id" id="modalUserId">
            <input type="hidden" name="add_user" id="modalAddUser">
            <input type="hidden" name="edit_user" id="modalEditUser">
            
            <div class="form-group">
              <label for="modalAccountNumber">Account Number:</label>
              <input type="text" id="modalAccountNumber" name="account_number" required style="width:100%;">
            </div>
            
            <div class="form-group">
              <label for="modalName">Name:</label>
              <input type="text" id="modalName" name="name" required style="width:100%;">
            </div>
            
            <div class="form-group">
              <label for="modalEmail">Email:</label>
              <input type="email" id="modalEmail" name="email" required style="width:100%;">
            </div>
            
            <div class="form-group">
              <label for="modalRole">Role:</label>
              <select id="modalRole" name="role" style="width:100%;">
                <option value="user">User</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="modalStatus">Status:</label>
              <select id="modalStatus" name="status" style="width:100%;">
                <option value="active">Active</option>
                <option value="suspended">Suspended</option>
                <option value="pending">Pending</option>
              </select>
            </div>
            
            <div style="margin-top:20px;">
              <button type="submit">Save</button>
              <button type="button" onclick="hideModal()" style="background-color:#e74c3c;">Cancel</button>
            </div>
          </form>
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

    
    function showAddModal() {
      document.getElementById('modalTitle').textContent = 'Add New User';
      document.getElementById('modalAddUser').value = '1';
      document.getElementById('modalEditUser').removeAttribute('name');
      document.getElementById('userForm').reset();
      document.getElementById('userModal').style.display = 'block';
    }

    function showEditModal(id, accountNumber, name, email, role, status) {
      document.getElementById('modalTitle').textContent = 'Edit User';
      document.getElementById('modalUserId').value = id;
      document.getElementById('modalAccountNumber').value = accountNumber;
      document.getElementById('modalName').value = name;
      document.getElementById('modalEmail').value = email;
      document.getElementById('modalRole').value = role;
      document.getElementById('modalStatus').value = status;
      document.getElementById('modalEditUser').value = '1';
      document.getElementById('modalAddUser').removeAttribute('name');
      document.getElementById('userModal').style.display = 'block';
    }

    function hideModal() {
      document.getElementById('userModal').style.display = 'none';
    }

    function exportCSV() {
      let csv = [];
      const headers = Array.from(document.querySelectorAll("table th")).map(th => `"${th.innerText}"`);
      csv.push(headers.join(","));
      
      const rows = document.querySelectorAll("table tbody tr");
      rows.forEach(row => {
        if (row.style.display !== 'none') {
          const cols = Array.from(row.querySelectorAll("td")).map(td => `"${td.innerText}"`);
          csv.push(cols.join(","));
        }
      });

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