<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $subject  = trim($_POST['subject'] ?? '');
    $message  = trim($_POST['message'] ?? '');
    $captchaInput = trim($_POST['captchaInput'] ?? '');
    $captchaAnswer = trim($_POST['captchaAnswer'] ?? '');

    // Check for empty fields
    if ($name == '' || $email == '' || $subject == '' || $message == '' || $captchaInput == '') {
        die("Error: All fields are required.");
    }

    // CAPTCHA validation
    if (!is_numeric($captchaInput) || $captchaInput != $captchaAnswer) {
        die("Error: Incorrect CAPTCHA.");
    }

    // Simulate success
    echo "<h2>Thank You!</h2>";
    echo "<p>Your message is successfully submitted.</p>";

}
?>
