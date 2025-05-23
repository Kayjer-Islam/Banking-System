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
  <title>Bill Pay</title>
  <style>
    .container {
      max-width: 400px;
      margin: auto;
      background: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Add shadow for depth */
      border: 1px solid #e0e0e0;
    }
    h2 {
      color: #003366; /* Darker blue for heading */
      text-align: center;
      margin-bottom: 20px;
    }
    form {
      display: flex;
      flex-direction: column;
    }
    input, button {
      width: 100%;
      margin: 10px 0;
      padding: 10px;
      border-radius: 5px;
      border: 1px solid #b0bec5; /* Light grey border */
      box-sizing: border-box; /* Include padding and border in element's total width and height */
    }
    input {
      background-color: #f8f8f8; /* Very light grey background for inputs */
      color: #333;
    }
    input:focus {
      outline: none; /* Remove default outline */
      border-color: #42a5f5; /* Highlight border on focus (a brighter blue) */
      box-shadow: 0 0 5px rgba(66, 165, 245, 0.3); /* Add a subtle shadow on focus */
    }
    button {
      background-color: #003366; /* Dark blue for button */
      color: white;
      border: none;
      cursor: pointer;
      transition: background-color 0.3s ease; /* Smooth transition */
    }
    button:hover {
      background-color: #002140; /* Darker shade on hover */
    }
    button:active {
      background-color: #001320;
       box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2)
    }

    h3 {
      color: #003366;
      margin-top: 30px;
      text-align: center;
    }
    #billList {
      list-style: none;
      padding: 0;
      margin-top: 10px;
    }
    #billList li {
      background-color: #f0f4c3;  /* Light yellow for list items, to be different from form */
      padding: 12px;
      margin-bottom: 8px;
      border-radius: 5px;
      border: 1px solid #e0e0e0;
      color: #333;
      display: flex; /* Use flexbox for layout */
      justify-content: space-between; /* Space items evenly */
      align-items: center; /* Vertically center items */
    }
    #billList li span {
        font-weight: bold;
        color: #003366;
    }

    #billList li button {
      background-color: #e53935; /* Red for delete button */
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      font-size: 0.9em;
    }
    #billList li button:hover {
      background-color: #c62828; /* Darker red on hover */
    }
    #billList li button:active {
       box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2)
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Schedule Bill Payment</h2>
    <form id="billForm">
      <input type="text" placeholder="Biller Name" required>
      <input type="number" placeholder="Amount" required>
      <input type="date" required>
      <button type="submit">Schedule Payment</button>
    </form>

    <h3>Scheduled Payments</h3>
    <ul id="billList"></ul>
  </div>
  <script src="../Controller/billpay.js"></script>
</body>
</html>
