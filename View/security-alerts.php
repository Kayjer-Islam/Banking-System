<?php
session_start();

 //Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location:login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Security Alerts</title>
  <link rel="stylesheet" href="../public/security-alerts.css">
</head>
<body>
  <div class="container">
    <h2>Security Alerts</h2>

    <section>
      <h3>🔔 Real-time Fraud Notifications</h3>
      <ul id="fraudNotifications"></ul>
    </section>

    <section>
      <h3>🕵️ Activity Review - Login Attempts</h3>
      <table>
        <thead>
          <tr>
            <th>Time</th>
            <th>IP Address</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody id="loginAttempts"></tbody>
      </table>
    </section>

    <section>
      <h3>⚠️ Report Suspicious Activity</h3>
      <textarea id="reportText" rows="4" placeholder="Describe suspicious activity..."></textarea>
      <button onclick="submitReport()">Submit Report</button>
      <p id="reportStatus"></p>
    </section>
  </div>

  <script src="../Controller/security-alerts.js"></script>
</body>
</html>
