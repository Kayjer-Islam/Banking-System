<?php
session_start();
require_once '../Model/db.php';  

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../view/contact.php");
    exit();
}

// Sanitize inputs
$name = trim($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
$captchaInput = trim($_POST['captchaInput'] ?? '');
$captchaAnswer = $_POST['captchaAnswer'] ?? '';

// Preserve form data for re-population on error
$_SESSION['form_data'] = compact('name', 'email', 'subject', 'message');

// Validate inputs
if (!$name || !$email || !$subject || !$message || !$captchaInput) {
    $_SESSION['message'] = "❌ All fields are required.";
    header("Location: ../view/contact.php");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['message'] = "❌ Invalid email format.";
    header("Location: ../view/contact.php");
    exit();
}

// Check if email matches logged-in user email stored in session
if (!isset($_SESSION['email']) || $email !== $_SESSION['email']) {
    $_SESSION['message'] = "❌ The email you entered must match your logged-in email.";
    header("Location: ../view/contact.php");
    exit();
}

if ((int)$captchaInput !== (int)$captchaAnswer) {
    $_SESSION['message'] = "❌ CAPTCHA is incorrect.";
    header("Location: ../view/contact.php");
    exit();
}

// Prepare and execute insert query
$stmt = $con->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");

if ($stmt === false) {
    $_SESSION['message'] = "❌ Database error: " . $con->error;
    header("Location: ../view/contact.php");
    exit();
}

$stmt->bind_param("ssss", $name, $email, $subject, $message);

if ($stmt->execute()) {
    $_SESSION['message'] = "✅ Your message was submitted successfully and will be reviewed soon.";
    unset($_SESSION['form_data']);  // Clear old data on success
} else {
    $_SESSION['message'] = "❌ Failed to save your message. Please try again.";
}

$stmt->close();
header("Location: ../view/contact.php");
exit();
