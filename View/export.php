<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../Controller/login.php");
    exit();
}
$error = '';
$success = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Export Transactions - Banking System</title>


  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 40px;
      background-color: #f3f4f6;
      color: #333;
    }

    .export-container {
      max-width: 800px;
      margin: 0 auto;
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #1e3a8a;
    }

    .filters {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }

    .filters label {
      font-weight: bold;
      display: flex;
      flex-direction: column;
      font-size: 14px;
    }

    .filters input[type="date"] {
      padding: 6px 10px;
      font-size: 14px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    .buttons {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-bottom: 30px;
    }

    .buttons button {
      background-color: #2563eb;
      color: white;
      padding: 10px 20px;
      font-size: 14px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: 0.3s;
    }

    .buttons button:hover {
      background-color: #1d4ed8;
    }

    .table-container {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead {
      background-color: #1e40af;
      color: white;
    }

    th, td {
      padding: 12px;
      text-align: left;
      border: 1px solid #ddd;
    }

    tbody tr:nth-child(even) {
      background-color: #f9fafb;
    }
  </style>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>

<body>
  <div class="export-container">
    <h2>Export Transaction History</h2>

    <div class="filters">
      <label>From:
        <input type="date" id="startDate" />
      </label>
      <label>To:
        <input type="date" id="endDate" />
      </label>
    </div>

    <div class="buttons">
      <button onclick="exportCSV()">Export CSV</button>
      <button onclick="exportPDF()">Export PDF</button>
    </div>

    <div class="table-container">
      <table id="dataTable">
        <thead>
          <tr>
            <th>Transaction ID</th>
            <th>Date</th>
            <th>Account Holder</th>
            <th>Type</th>
            <th>Amount (৳)</th>
          </tr>
        </thead>
        <tbody id="tableBody">
          <tr>
            <td>TXN001</td>
            <td>2025-05-10</td>
            <td>Rahim Uddin</td>
            <td>Deposit</td>
            <td>5000</td>
          </tr>
          
        </tbody>
      </table>
    </div>
  </div>

  
  <script src="../Controller/exportValid.js"></script>
</body>
</html>
