<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name = trim($_POST['name'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $captchaInput = trim($_POST['captchaInput'] ?? '');
    $captchaAnswer = $_POST['captchaAnswer'] ?? '';

    // Preserve inputs
    $_SESSION['form_data'] = compact('name', 'email', 'subject', 'message');

    // Validations
    if (!$name || !$email || !$subject || !$message || !$captchaInput) {
        $_SESSION['message'] = "❌ All fields are required.";
        header("Location: /view/contact.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "❌ Invalid email format.";
        header("Location: /view/contact.php");
        exit();
    }

    if ((int)$captchaInput !== (int)$captchaAnswer) {
        $_SESSION['message'] = "❌ CAPTCHA is incorrect.";
        header("Location: /view/contact.php");
        exit();
    }

    // If successful – you can email or save here
    $_SESSION['message'] = "✅ Your message was submitted successfully.";
    unset($_SESSION['form_data']);
    header("Location: ../view/contact.php");
    exit();
} else {
    header("Location: ../view/contact.php");
    exit();
}
