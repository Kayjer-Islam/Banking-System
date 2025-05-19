<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Loan Application</title>
  <link rel="stylesheet" href="../public/loan.css">
</head>
<body>
  <div class="loan-container">
    <h2>Apply for a Loan</h2>
    
    <label for="loanType">Select Loan Type:</label>
    <select id="loanType" required>
      <option value="home">Home Loan</option>
      <option value="auto">Auto Loan</option>
      <option value="personal">Personal Loan</option>
    </select>

    <label for="income">Annual Income:</label>
    <input type="number" id="income" placeholder="Enter your income" required>

    <label for="esign">
      <input type="checkbox" id="esign"> I agree to e-sign the loan agreement
    </label>

    <button onclick="applyLoan()">Submit Application</button>
    <div id="loanOutput"></div>
  </div>
  <script src="../Controller/loan.js"></script>
</body>
</html>
