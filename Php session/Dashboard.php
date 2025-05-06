<?php
session_start();

// Redirect unauthorized users
if (!isset($_SESSION['email']) || $_SESSION['role'] !== "user") {
    header("Location: User autho.php"); // Update filename if needed
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 20px;
    }
    #dashboard {
      max-width: 800px;
      margin: 0 auto;
    }
    fieldset {
      border: 1px solid #ddd;
      border-radius: 5px;
      padding: 20px;
      background-color: white;
    }
    legend {
      font-weight: bold;
      padding: 0 10px;
    }
    .widget h2 {
      color: #2c3e50;
      margin-bottom: 10px;
    }
    .stats-container {
      display: flex;
      gap: 20px;
      margin: 20px 0;
    }
    .stat-box {
      flex: 1;
      padding: 15px;
      background-color: #f8f9fa;
      border-radius: 5px;
      border-left: 3px solid #3498db;
    }
    .stat-value {
      font-size: 1.5em;
      font-weight: bold;
      color: #2c3e50;
    }
    .quick-actions {
      display: flex;
      gap: 15px;
      margin: 20px 0;
    }
    .action-btn {
      display: inline-block;
      padding: 10px 15px;
      background-color: #3498db;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      font-size: 14px;
      cursor: pointer;
    }
    .action-btn:hover {
      background-color: #2980b9;
    }
    .logout-btn {
      display: inline-block;
      padding: 10px 15px;
      background-color: #e74c3c;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      font-size: 14px;
      cursor: pointer;
    }
    .logout-btn:hover {
      background-color: #c0392b;
    }
    ul {
      list-style-type: none;
      padding: 0;
    }
    li {
      padding: 8px 0;
      border-bottom: 1px solid #eee;
    }
  </style>
</head>
<body>
  <div id="dashboard">
    <fieldset>
      <legend>Dashboard</legend>
      <div class="widget">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?></h2>
        <p>Here's your activity overview</p>

        <div class="stats-container">
          <div class="stat-box">
            <h3>Account Number</h3>
            <div class="stat-value" id="acc-num">1245</div>
          </div>
          <div class="stat-box">
            <h3>Account Balance</h3>
            <div class="stat-value" id="acc-bal">$2,800.00</div>
          </div>
        </div>

        <h3>Quick Actions</h3>
        <div class="quick-actions">
          <a href="AccDash.html" class="action-btn">Account Info</a>

          <form method="post" action="generate_pdf.php" style="display:inline;">
            <input type="hidden" name="acc_num" value="1245">
            <input type="hidden" name="acc_bal" value="$2,800.00">
            <button type="submit" class="action-btn">Generate Report</button>
          </form>
        </div>

        <h3>Recent Activity</h3>
        <ul>
          <li>Logged in: Just now</li>
          <li>Last transaction: May 15, 2023</li>
          <li>Account updated: May 10, 2023</li>
        </ul>

        <a href="Logout.php" class="logout-btn">Log Out</a>
      </div>
    </fieldset>
  </div>
</body>
</html>
