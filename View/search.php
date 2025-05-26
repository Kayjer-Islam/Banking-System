<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../Controller/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Banking System - Search & Filter</title>
  <link rel="stylesheet" href="../Asset/search.css" />
</head>
<body>
  <div class="container">
    <h1>Banking Services</h1>
    
    <div class="search-filter-panel">
      <input type="text" id="searchInput" placeholder="Search accounts, loans, services..." oninput="filterItems()" />

      <select id="categoryFilter" onchange="filterItems()">
        <option value="All">All Categories</option>
        <option value="Account">Account</option>
        <option value="Loan">Loan</option>
        <option value="CreditCard">Credit Card</option>
        <option value="Service">Service</option>
      </select>
    </div>

    <div class="results" id="results">
      <!-- Results inserted here by JS -->
    </div>
  </div>

  <script src="../Controller/search.js"></script>
</body>
</html>
