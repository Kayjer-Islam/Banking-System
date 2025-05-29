<?php
session_start();
require_once '../model/dbuser.php'; 

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$from_account = $_SESSION['email'];
$successMessage = "";
$errorMessage = "";

$recipients = [];
$userQuery = "SELECT username, account_number FROM users WHERE email != '$from_account'";
$result = mysqli_query($con, $userQuery);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $recipients[] = $row;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to_account = trim($_POST['to_account'] ?? '');
    $amount = trim($_POST['amount'] ?? '');

    if (empty($to_account)) {
        $errorMessage = "Please select a recipient.";
    } elseif (!is_numeric($amount) || $amount <= 0) {
        $errorMessage = "Please enter a valid amount greater than 0.";
    } else {
        preg_match('/\((.*?)\)/', $to_account, $matches);
        $account_number = $matches[1] ?? null;

        if (!$account_number) {
            $errorMessage = "Invalid recipient format.";
        } else {
            $to_account_safe = mysqli_real_escape_string($con, $account_number);
            $amount_safe = mysqli_real_escape_string($con, $amount);
            $from_account_safe = mysqli_real_escape_string($con, $from_account);

            $query = "INSERT INTO fund_transfers (user_email, from_account, to_account, amount) 
                      VALUES ('$from_account_safe', '$from_account_safe', '$to_account_safe', '$amount_safe')";

            if (mysqli_query($con, $query)) {
                $successMessage = "Fund has been successfully transferred!";
            } else {
                $errorMessage = "Error: " . mysqli_error($con);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Fund Transfer</title>
  <style>
    body, html {
      margin: 0; padding: 0;
      height: 100%;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f0f4f8;
    }
    .container {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
    }
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
    }
    .form-group {
      margin-bottom: 20px;
    }
    label {
      display: block;
      font-weight: 600;
      margin-bottom: 8px;
    }
    select, input[type="text"] {
      width: 100%;
      padding: 12px 15px;
      border: 1.8px solid #ccc;
      border-radius: 6px;
      font-size: 1rem;
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
    }
    .message {
      padding: 10px;
      text-align: center;
      margin-bottom: 15px;
      border-radius: 5px;
    }
    .success { background-color: #d4edda; color: #155724; }
    .error { background-color: #f8d7da; color: #721c24; }
  </style>
</head>
<body>
  <div class="container">
    <form class="transfer-form" method="POST" action="">
      <h1>Fund Transfer</h1>

      <?php if (!empty($successMessage)): ?>
        <div class="message success"><?= $successMessage ?></div>
      <?php elseif (!empty($errorMessage)): ?>
        <div class="message error"><?= $errorMessage ?></div>
      <?php endif; ?>

      <div class="form-group">
        <label>From Account (Your Email)</label>
        <input type="text" value="<?= htmlspecialchars($from_account) ?>" readonly />
      </div>

      <div class="form-group">
        <label for="to_account">To Account</label>
        <select name="to_account" id="to_account" required>
          <option value="">-- Select recipient --</option>
          <?php foreach ($recipients as $user): ?>
            <option value="<?= $user['username'] ?> (<?= $user['account_number'] ?>)">
              <?= $user['username'] ?> (<?= $user['account_number'] ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="amount">Amount</label>
        <input type="text" name="amount" id="amount" placeholder="0.00" required />
      </div>

      <button type="submit">Transfer Money</button>
    </form>
  </div>
</body>
</html>
