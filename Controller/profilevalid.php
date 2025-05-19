<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];

    // Validate name
    $name = trim($_POST['name']);
    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    // Validate email
    $email = trim($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate phone
    $phone = trim($_POST['phone']);
    if (!preg_match('/^\d{11}$/', $phone)) {
        $errors[] = "Phone number must be 11 digits.";
    }

    // Password validation
    $current_pass = $_POST['current_pass'] ?? '';
    $new_pass = $_POST['new_pass'] ?? '';
    $confirm_pass = $_POST['confirm_pass'] ?? '';

    if (!empty($new_pass)) {
        if (strlen($new_pass) < 8) {
            $errors[] = "New password must be at least 8 characters.";
        }
        if ($new_pass !== $confirm_pass) {
            $errors[] = "New password and confirmation do not match.";
        }
    }

    // Avatar upload 
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        if (!in_array($file_ext, $allowed)) {
            $errors[] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        }

        if ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
            $errors[] = "Avatar file size must be under 2MB.";
        }

        if (empty($errors)) {
            $upload_path = "../uploads/" . basename($_FILES['avatar']['name']);
            move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_path);
        }
    }

    if (!empty($errors)) {
        echo "<h2>Errors:</h2><ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul><a href='../View/profile.php'>Go back</a>";
    } else {
        echo "<h2>Profile updated successfully </h2>";
        echo "<a href='../View/profile.php'>Go back</a>";
    }
}
?>
