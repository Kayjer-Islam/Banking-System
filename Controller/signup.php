<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../model/db.php'; 

    $fullName = mysqli_real_escape_string($con, trim($_POST['full-name']));
    $email = mysqli_real_escape_string($con, trim($_POST['email']));
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    $age = isset($_POST['age']) ? (int)$_POST['age'] : null;
    $agree = isset($_POST['agree']);

    $errors = [];

    if (empty($fullName)) {
        $errors[] = "Full name is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (!empty($age) && ($age < 18 || $age > 120)) {
        $errors[] = "Age must be between 18 and 120.";
    }

    if (!$agree) {
        $errors[] = "You must agree to the terms.";
    }

    if (empty($errors)) {
        $query = "INSERT INTO users (username, password, email, age)
                  VALUES ('$fullName', '$password', '$email', $age)";

        if (mysqli_query($con, $query)) {
            $_SESSION['user_data'] = [
                'name' => $fullName,
                'email' => $email,
                'password' => $password,
                'age' => $age
            ];
            mysqli_close($con);
            header("Location: Login.php");
            exit();
        } else {
            $errors[] = "Database error: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Registration Form</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0;}
        h1 { text-align: center; margin-top: 30px; }
        form { width: 400px; margin: 30px auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);}
        .form-group { margin-bottom: 15px; display: flex; flex-direction: column; }
        label { margin-bottom: 5px; font-weight: bold; }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"] {
            padding: 8px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .checkbox-group { display: flex; align-items: center; gap: 10px; }
        .error { color: red; font-size: 0.9em; margin-top: 5px; }
        .server-error { color: red; font-size: 1em; margin-bottom: 15px; }
        button {
            width: 100%;
            padding: 10px 15px;
            background-color: #2196F3;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover { background-color: #1976D2; }
    </style>
</head>
<body>
    <h1>Registration Form</h1>

    <form method="post" action="" id="registration-form" novalidate>
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($errors)): ?>
            <div class="server-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="full-name">Full Name:</label>
            <input type="text" id="full-name" name="full-name" required value="<?= isset($fullName) ? htmlspecialchars($fullName) : '' ?>">
            <div id="name-error" class="error" style="display:none;">Full name is required!</div>
        </div>

        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
            <div id="email-error" class="error" style="display:none;">Invalid email format!</div>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required minlength="8">
            <div id="password-error" class="error" style="display:none;">Password must be at least 8 characters!</div>
        </div>

        <div class="form-group">
            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required>
            <div id="confirm-error" class="error" style="display:none;">Passwords don't match!</div>
        </div>

        <div class="form-group">
            <label for="age">Age:</label>
            <input type="number" id="age" name="age" min="18" max="120" value="<?= isset($age) ? htmlspecialchars($age) : '' ?>">
            <div id="age-error" class="error" style="display:none;">Age must be a number between 18 and 120!</div>
        </div>

        <div class="form-group checkbox-group">
            <input type="checkbox" id="agree" name="agree" required <?= isset($agree) ? 'checked' : '' ?>>
            <label for="agree">I agree to the terms and conditions</label>
        </div>
        <div id="agree-error" class="error" style="display:none;">You must agree to the terms!</div>

        <button type="submit">Register</button>
    </form>

    <script>
        const name = document.getElementById('full-name');
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm-password');
        const age = document.getElementById('age');
        const agree = document.getElementById('agree');

        const nameError = document.getElementById('name-error');
        const emailError = document.getElementById('email-error');
        const passwordError = document.getElementById('password-error');
        const confirmError = document.getElementById('confirm-error');
        const ageError = document.getElementById('age-error');
        const agreeError = document.getElementById('agree-error');

        function isValidEmail(value) {
            let atFound = false;
            let dotFound = false;
            let atPosition = -1;
            let dotPosition = -1;

            for (let i = 0; i < value.length; i++) {
                if (value[i] === '@' && atPosition === -1) {
                    atFound = true;
                    atPosition = i;
                } else if (value[i] === '.' && atFound) {
                    dotFound = true;
                    dotPosition = i;
                }
            }

            if (!atFound || !dotFound || atPosition < 1 || dotPosition < atPosition + 2 || dotPosition + 2 >= value.length) {
                return false;
            }
            return true;
        }

        function validate() {
            let valid = true;

            if (name.value.trim() === '') {
                nameError.style.display = 'block';
                valid = false;
            } else {
                nameError.style.display = 'none';
            }

            if (!isValidEmail(email.value.trim())) {
                emailError.style.display = 'block';
                valid = false;
            } else {
                emailError.style.display = 'none';
            }

            if (password.value.length < 8) {
                passwordError.style.display = 'block';
                valid = false;
            } else {
                passwordError.style.display = 'none';
            }

            if (confirmPassword.value !== password.value || confirmPassword.value === '') {
                confirmError.style.display = 'block';
                valid = false;
            } else {
                confirmError.style.display = 'none';
            }

            if (age.value !== '') {
                const ageVal = Number(age.value);
                if (isNaN(ageVal) || ageVal < 18 || ageVal > 120) {
                    ageError.style.display = 'block';
                    valid = false;
                } else {
                    ageError.style.display = 'none';
                }
            } else {
                ageError.style.display = 'none';
            }

            if (!agree.checked) {
                agreeError.style.display = 'block';
                valid = false;
            } else {
                agreeError.style.display = 'none';
            }

            return valid;
        }

        document.getElementById('registration-form').addEventListener('submit', function(e) {
            if (!validate()) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
