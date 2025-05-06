<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
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
      display: none;
      max-width: 600px;
      margin: auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .page.active {
      display: block;
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
      font-size: 1.2em;
    }

    .captcha {
      margin: 10px 0;
    }
  </style>
</head>
<body>

  <!-- Contact Form Page -->
  <div id="contactFormPage" class="page active">
    <h2>Contact Us</h2>
    <form method="post" id="contactForm" action = "contactValid.php" >
      <label>Name:</label>
      <input type="text" id="name" name="name">

      <label>Email:</label>
      <input type="email" id="email" name="email">

      <label>Subject:</label>
      <input type="text" id="subject" name="subject">

      <label>Message:</label>
      <textarea id="message" rows="5" name="message"></textarea>

      <!-- CAPTCHA -->
      <div class="captcha">
        <label id="captchaLabel"></label>
        <input type="text" name="captchaInput" placeholder="Enter CAPTCHA" required>
        <input type="hidden" id="captchaAnswer" name="captchaAnswer">
      </div>

      <button type="submit">Submit</button>
    </form>
  </div>

  <script>
    function generateCaptcha() {
      const a = Math.floor(Math.random() * 10 + 1);
      const b = Math.floor(Math.random() * 10 + 1);
      const answer = a + b;
      document.getElementById('captchaLabel').textContent = `What is ${a} + ${b}?`;
      document.getElementById('captchaAnswer').value = answer;
    }

    generateCaptcha();
  </script>
</body>
</html>
