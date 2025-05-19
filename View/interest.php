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
  <meta charset="UTF-8">
  <title>Interest Calculator</title>
  <link rel="stylesheet" href="../public/interest.css">
</head>
<body>
  <div class="container">
    <h2>Interest Calculator</h2>

    <label for="principal">Principal Amount ($):</label>
    <input type="number" id="principal" placeholder="e.g. 10000">

    <label for="rate">Annual Interest Rate (%):</label>
    <input type="number" id="rate" placeholder="e.g. 5">

    <label for="years">Term (Years):</label>
    <input type="number" id="years" placeholder="e.g. 5">

    <label for="extraPayment">Extra Monthly Payment (Optional):</label>
    <input type="number" id="extraPayment" placeholder="e.g. 500">

    <button onclick="calculateInterest()">Calculate</button>

    <div id="output"></div>
  </div>

  <script src="../Controller/interest.js"></script>
</body>
</html>
