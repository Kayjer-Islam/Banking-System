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
  <title>Check Deposit</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');

    body {
      font-family: 'Inter', Arial, sans-serif;
      background-color: #f5f7fa;
      min-height: 100vh;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      color: #333;
    }

    .deposit-container {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      max-width: 480px;
      width: 100%;
      padding: 35px 40px;
    }

    h1 {
      margin-bottom: 25px;
      font-weight: 700;
      font-size: 1.8rem;
      color: #2c3e50;
      text-align: center;
      letter-spacing: 0.03em;
    }

    .photo-box {
      border: 2px dashed #cbd5e1;
      border-radius: 10px;
      background-color: #f9fafb;
      padding: 28px;
      margin-bottom: 10px;
      text-align: center;
      color: #64748b;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: border-color 0.3s ease, background-color 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .photo-box:hover {
      border-color: #3b82f6;
      background-color: #eff6ff;
      color: #3b82f6;
    }

    .photo-box input[type="file"] {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      opacity: 0;
      cursor: pointer;
    }

    .file-name {
      margin-top: 6px;
      font-size: 0.9rem;
      color: #334155;
      font-weight: 500;
      min-height: 18px;
    }

    .form-group {
      margin-bottom: 22px;
      position: relative;
    }

    label {
      display: block;
      margin-bottom: 9px;
      font-weight: 600;
      color: #334155;
      font-size: 1rem;
    }

    select, input[type="text"] {
      width: 100%;
      padding: 14px 15px 14px 44px; /* space for Tk symbol */
      font-size: 1rem;
      border: 1.7px solid #cbd5e1;
      border-radius: 8px;
      outline-offset: 2px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
      color: #334155;
      font-weight: 500;
      box-sizing: border-box;
    }

    select:focus, input[type="text"]:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 8px rgba(59, 130, 246, 0.5);
    }

    .tk-symbol {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-weight: 700;
      font-size: 1.15rem;
      color: #3b82f6;
      pointer-events: none;
      user-select: none;
    }

    button {
      width: 100%;
      background-color: #3b82f6;
      color: white;
      border: none;
      padding: 16px 0;
      font-size: 1.15rem;
      font-weight: 700;
      border-radius: 12px;
      cursor: pointer;
      transition: background-color 0.3s ease;
      box-shadow: 0 10px 20px rgba(59, 130, 246, 0.4);
    }

    button:hover {
      background-color: #2563eb;
      box-shadow: 0 12px 24px rgba(37, 99, 235, 0.6);
    }
  </style>
</head>
<body>
  <div class="deposit-container">
    <h1>Check Deposit</h1>

    <form id="depositForm" method="POST" enctype="multipart/form-data" novalidate>
      <label class="photo-box" for="frontCheck">
        Upload photo of check front (JPG)
        <input type="file" id="frontCheck" name="frontCheck" accept=".jpg,.jpeg" required />
        <div id="frontFileName" class="file-name"></div>
      </label>

      <label class="photo-box" for="backCheck">
        Upload photo of check back (JPG)
        <input type="file" id="backCheck" name="backCheck" accept=".jpg,.jpeg" required />
        <div id="backFileName" class="file-name"></div>
      </label>

      <div class="form-group">
        <label for="account">Deposit To</label>
        <select id="account" name="account" required>
          <option value="" disabled selected>-- Select Account --</option>
          <option value="checking">Checking (****1234)</option>
          <option value="savings">Savings (****5678)</option>
        </select>
      </div>

      <div class="form-group" style="position: relative;">
        <label for="amount">Amount</label>
        <span class="tk-symbol">৳</span>
        <input
          type="text"
          id="amount"
          name="amount"
          placeholder="0.00"
          autocomplete="off"
          required
        />
      </div>

      <button type="submit">Submit Deposit</button>
    </form>
  </div>

  <script>
    const form = document.getElementById('depositForm');

    const frontInput = document.getElementById('frontCheck');
    const backInput = document.getElementById('backCheck');

    const frontFileName = document.getElementById('frontFileName');
    const backFileName = document.getElementById('backFileName');

   
    frontInput.addEventListener('change', () => {
      if (frontInput.files.length > 0) {
        frontFileName.textContent = frontInput.files[0].name;
      } else {
        frontFileName.textContent = '';
      }
    });

    backInput.addEventListener('change', () => {
      if (backInput.files.length > 0) {
        backFileName.textContent = backInput.files[0].name;
      } else {
        backFileName.textContent = '';
      }
    });

    form.addEventListener('submit', function(e) {
      e.preventDefault();

      const frontFile = frontInput.files[0];
      const backFile = backInput.files[0];
      const account = document.getElementById('account').value;
      const amount = document.getElementById('amount').value.trim();

     
      function isValidJPEG(file) {
        if (!file) return false;
        return (file.type === "image/jpeg" || file.type === "image/jpg");
      }

      if (!frontFile) {
        alert("Please upload the front photo of the check.");
        frontInput.focus();
        return;
      } 
      if (!isValidJPEG(frontFile)) {
        alert("Front check photo must be a JPEG file.");
        frontInput.focus();
        return;
      }

      if (!backFile) {
        alert("Please upload the back photo of the check.");
        backInput.focus();
        return;
      }
      if (!isValidJPEG(backFile)) {
        alert("Back check photo must be a JPEG file.");
        backInput.focus();
        return;
      }

      if (!account) {
        alert("Please select an account.");
        document.getElementById('account').focus();
        return;
      }

      if (!amount || !/^\d+(\.\d{1,2})?$/.test(amount) || parseFloat(amount) <= 0) {
        alert("Please enter a valid amount greater than zero (max 2 decimals).");
        document.getElementById('amount').focus();
        return;
      }

      alert("Check deposited successfully!");

      
      form.reset();
      frontFileName.textContent = '';
      backFileName.textContent = '';
    });
  </script>
</body>
</html>
