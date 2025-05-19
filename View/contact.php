<?php

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../Controller/login.php");
    exit();
}

// Retrieve previous input and messages
$name = $_SESSION['form_data']['name'] ?? '';
$email = $_SESSION['form_data']['email'] ?? '';
$subject = $_SESSION['form_data']['subject'] ?? '';
$message = $_SESSION['form_data']['message'] ?? '';
$form_message = $_SESSION['message'] ?? '';

// Clear after use
unset($_SESSION['form_data']);
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Contact Us</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 30px;
      background: #f4f4f4;
    }

    .page {
      max-width: 600px;
      margin: auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    input, textarea {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      margin-bottom: 15px;
      border: 1px solid #b4afaf;
      border-radius: 5px;
    }

    button {
      background: #8000ff;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }

    .success {
      color: green;
      font-size: 1.1em;
      margin-bottom: 15px;
    }

    .error {
      color: red;
      font-size: 1.1em;
      margin-bottom: 15px;
    }

    .captcha {
      margin: 10px 0;
    }
  </style>
</head>
<body>

  <div id="contactFormPage" class="page">
    <h2>Contact Us</h2>

    <?php if ($form_message): ?>
      <div class="<?= str_contains($form_message, 'successfully') ? 'success' : 'error' ?>">
        <?= htmlspecialchars($form_message) ?>
      </div>
    <?php endif; ?>

    <form method="post" id="contactForm" action="../Controller/contactValid.php">
      <label>Name:</label>
      <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>

      <label>Email:</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>

      <label>Subject:</label>
      <input type="text" id="subject" name="subject" value="<?= htmlspecialchars($subject) ?>" required>

      <label>Message:</label>
      <textarea id="message" rows="5" name="message" required><?= htmlspecialchars($message) ?></textarea>

      <!-- CAPTCHA -->
      <div class="captcha">
        <label id="captchaLabel"></label>
        <input type="text" name="captchaInput" placeholder="Enter CAPTCHA" required>
        <input type="hidden" id="captchaAnswer" name="captchaAnswer">
      </div>

      <button type="submit">Submit</button>
    </form>
  </div>

  <script src="../controller/contactValid.js"></script>
</body>
</html>
