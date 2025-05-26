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
  <title>Transaction History</title>
  <link rel="stylesheet" href="../Asset/transactionHistory.css" />
</head>
<body>
  <div class="container">
    <h1>Transaction History</h1>

    <div class="filters">
      <input type="date" id="dateFilter" />
      <input type="number" id="amountFilter" placeholder="Amount" />
      <select id="typeFilter">
        <option value="">All Types</option>
        <option value="credit">Credit</option>
        <option value="debit">Debit</option>
      </select>
      <input type="text" id="searchRef" placeholder="Search by Reference" />
      <button onclick="applyFilters()">Search</button>
      <button onclick="downloadCSV()">Export CSV</button>
    </div>

    <table id="transactionTable">
      <thead>
        <tr>
          <th>Date</th>
          <th>Amount</th>
          <th>Type</th>
          <th>Reference</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <script src="../Controller/transactionHistory.js"></script>
</body>
</html>
