<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Fund Transfer</title>
<style>
  * {
    box-sizing: border-box;
  }
  body, html {
    margin: 0; padding: 0;
    height: 100%;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f0f4f8;
    color: #333;
  }

  .container {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
  }

  /* Form wrapper */
  .transfer-form {
    background: white;
    border-radius: 10px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
    width: 420px;
    max-width: 90%;
    padding: 30px 40px;
  }

  h1 {
    margin-bottom: 25px;
    color: #0a3d62;
    font-weight: 700;
    text-align: center;
    font-size: 2rem;
  }

  .form-group {
    margin-bottom: 20px;
  }

  label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #2c3e50;
  }

  select, input[type="text"] {
    width: 100%;
    padding: 12px 15px;
    border: 1.8px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
  }

  select:focus, input[type="text"]:focus {
    border-color: #3498db;
    outline: none;
  }

  button {
    width: 100%;
    padding: 14px 0;
    background-color: #3498db;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.25s ease;
    margin-top: 10px;
  }

  button:hover {
    background-color: #217dbb;
  }

  /* Responsive */
  @media (max-width: 480px) {
    .transfer-form {
      padding: 20px 25px;
      width: 100%;
    }
  }
</style>
</head>
<body>
  <div class="container">
    <form class="transfer-form" onsubmit="event.preventDefault(); transferFunds();">
      <h1>Fund Transfer</h1>
      <div class="form-group">
        <label for="from-account">From Account</label>
        <select id="from-account" required>
          <option value="checking">Checking (****1234)</option>
          <option value="savings">Savings (****5678)</option>
        </select>
      </div>
      <div class="form-group">
        <label for="to-account">To Account</label>
        <select id="to-account" required>
          <option value="">Select recipient</option>
          <option value="kayjer">SM Nahid (****9012)</option>
          <option value="kayjer">Salman (****9667)</option>
        </select>
      </div>
      <div class="form-group">
        <label for="amount">Amount</label>
        <input type="text" id="amount" placeholder="0.00" required />
      </div>
      <button type="submit">Transfer Money</button>
    </form>
  </div>

  <script>
    function transferFunds() {
      const from = document.getElementById('from-account').value;
      const to = document.getElementById('to-account').value;
      const amount = document.getElementById('amount').value.trim();

      if (!to) {
        alert("Please select a recipient account.");
        return;
      }
      if (amount === "" || isNaN(amount) || Number(amount) <= 0) {
        alert("Please enter a valid amount.");
        return;
      }

      alert("Fund has been successfully transferred!");

      // Reset form after successful transfer
      document.querySelector('.transfer-form').reset();
    }
  </script>
</body>
</html>
