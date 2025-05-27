<?php
session_start();

require_once '../model/db.php'; 

$email = $password = "";
$emailErr = $passwordErr = "";
$loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email)) {
        $emailErr = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }

    if (empty($password)) {
        $passwordErr = "Password is required";
    } elseif (strlen($password) < 8) {
        $passwordErr = "Password must be at least 8 characters";
    }

    if (empty($emailErr) && empty($passwordErr)) {
        if ($email === "admin@mail.com" && $password === "admin123") {
            $_SESSION['email'] = $email;
            $_SESSION['role'] = "admin";
            header("Location: admin.php");
            exit();
        }

        $emailSafe = mysqli_real_escape_string($con, $email);
        $query = "SELECT * FROM users WHERE email = '$emailSafe' LIMIT 1";
        $result = mysqli_query($con, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            if ($user['password'] === $password) { 
                $_SESSION['email'] = $email;
                $_SESSION['role'] = "user";
                header("Location: ../View/dashboard.php");
                exit();
            } else {
                $loginError = "Incorrect password.";
            }
        } else {
            $loginError = "No account found with that email.";
        }

        mysqli_close($con);
    } else {
        $loginError = "Please fix the errors below.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Authentication</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        fieldset {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 20px;
        }

        legend {
            font-weight: bold;
            padding: 0 10px;
        }

        h2 {
            margin-top: 0;
            color: #333;
            text-align: center;
        }

        table {
            width: 100%;
        }

        td {
            padding: 8px 0;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        a {
            color: #4CAF50;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        p {
            text-align: center;
            margin: 15px 0;
        }

        .error {
            color: red;
            font-size: 0.85em;
            margin-top: 4px;
            display: block;
        }
    </style>
</head>
<body>
    <form id="auth" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateLogin()">
        <fieldset>
            <legend>User Authentication</legend>
            <table>
                <tr>
                    <td colspan="2">
                        <h2>Login to Your Account</h2>
                        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
                        <?php if (!empty($loginError)): ?>
                            <p class="error"><?php echo $loginError; ?></p>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="login-email">Email:</label></td>
                    <td>
                        <input type="email" id="login-email" name="email" value="<?php echo htmlspecialchars($email); ?>" />
                        <?php if (!empty($emailErr)): ?>
                            <span class="error"><?php echo $emailErr; ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="login-password">Password:</label></td>
                    <td>
                        <input type="password" id="login-password" name="password" />
                        <?php if (!empty($passwordErr)): ?>
                            <span class="error"><?php echo $passwordErr; ?></span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Login" />
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>

    <script>
        function validateLogin() {
            let email = document.getElementById('login-email').value.trim();
            let password = document.getElementById('login-password').value.trim();
            let isValid = true;

            document.querySelectorAll('.js-error').forEach(e => e.remove());

            if (email === "") {
                showJsError('login-email', "Email cannot be empty!");
                isValid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showJsError('login-email', "Please enter a valid email address!");
                isValid = false;
            }

            if (password === "") {
                showJsError('login-password', "Password cannot be empty!");
                isValid = false;
            } else if (password.length < 8) {
                showJsError('login-password', "Password must be at least 8 characters!");
                isValid = false;
            }

            return isValid;
        }

        function showJsError(fieldId, message) {
            let field = document.getElementById(fieldId);
            field.style.borderColor = 'red';

            let error = document.createElement('span');
            error.className = 'error js-error';
            error.textContent = message;

            field.parentNode.appendChild(error);
        }
    </script>
</body>
</html>