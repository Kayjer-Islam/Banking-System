<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Export Banking Data</title>
  <link rel="stylesheet" href="export.css" />
</head>
<body>
  <div class="container">
    <h1>Export Banking Services Data</h1>
    
    <div class="export-controls">
      <button onclick="exportToCSV()">Download CSV</button>
    </div>

    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Category</th>
        </tr>
      </thead>
      <tbody id="dataTable">
        <!-- Rows will be populated by JS -->
      </tbody>
    </table>
  </div>

  <script src="export.js"></script>
</body>
</html>
