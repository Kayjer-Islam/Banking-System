<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        form {
            width: 400px;
            margin: 30px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        h1 {
            text-align: center;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"] {
            padding: 8px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error {
            color: red;
            font-size: 0.9em;
        }

        button {
            padding: 10px;
            width: 100%;
            font-size: 1em;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
        
        .success {
            color: green;
            text-align: center;
            margin: 20px;
        }
    </style>
</head>
<body>
    <h1>Registration Form</h1>
    
    <?php
    // Initialize variables
    $fullName = $email = $password = $confirmPassword = $age = '';
    $message = '';
    $isValid = true;

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form data
        $fullName = $_REQUEST['full-name'];
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
        $confirmPassword = $_REQUEST['confirm-password'];
        $age = $_REQUEST['age'];
        $agree = isset($_REQUEST['agree']) ? true : false;

        // Validate fields
        if ($fullName == "") {
            $message = "Full name is required!";
            $isValid = false;
        }
        elseif ($email == "") {
            $message = "Email is required!";
            $isValid = false;
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Invalid email format!";
            $isValid = false;
        }
        elseif ($password == "") {
            $message = "Password is required!";
            $isValid = false;
        }
        elseif (strlen($password) < 8) {
            $message = "Password must be at least 8 characters!";
            $isValid = false;
        }
        elseif ($confirmPassword == "") {
            $message = "Please confirm your password!";
            $isValid = false;
        }
        elseif ($password != $confirmPassword) {
            $message = "Passwords don't match!";
            $isValid = false;
        }
        elseif ($age != "" && ($age < 18 || $age > 120)) {
            $message = "Age must be between 18 and 120!";
            $isValid = false;
        }
        elseif (!$agree) {
            $message = "You must agree to the terms!";
            $isValid = false;
        }

        // If valid, show success
        if ($isValid) {
            $message = "Registration successful!<br>Name: $fullName<br>Email: $email";
        }
    }
    ?>

    <?php if ($isValid && $message != ''): ?>
        <div class="success">
            <h2><?php echo $message; ?></h2>
        </div>
    <?php elseif (!$isValid): ?>
        <div class="error" style="text-align: center; margin-bottom: 20px;">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form id="registration-form" method="post" action="">
        <div class="form-group">
            <label for="full-name">Full Name:</label>
            <input type="text" id="full-name" name="full-name" value="<?php echo htmlspecialchars($fullName); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required minlength="8">
        </div>

        <div class="form-group">
            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required>
        </div>

        <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" min="18" max="120" value="<?php echo htmlspecialchars($age); ?>">
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="agree" name="agree" <?php echo isset($_POST['agree']) ? 'checked' : ''; ?> required>
            <label for="agree">I agree to the terms and conditions</label>
        </div>

        <button type="submit">Register</button>
    </form>
</body>
</html>