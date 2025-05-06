<?php
session_start();

$email = $password = "";
$emailErr = $passwordErr = "";
$loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate email
    if (empty($email)) {
        $emailErr = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }

    // Validate password
    if (empty($password)) {
        $passwordErr = "Password is required";
    } elseif (strlen($password) < 8) {
        $passwordErr = "Password must be at least 8 characters";
    }

    // Login logic if no errors
    if (empty($emailErr) && empty($passwordErr)) {
        if ($email == "admin@mail.com" && $password == "admin123") {
            $_SESSION['email'] = $email;
            $_SESSION['role'] = "admin";
            header("Location: Admin.html");
            exit();
        } else {
            $_SESSION['email'] = $email;
            $_SESSION['role'] = "user";
            header("Location: Dashboard.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>User Authentication</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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

        .button-link {
            display: inline-block;
            text-align: center;
            width: 100%;
            padding: 10px 15px;
            background-color: #f1f1f1;
            color: #333;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 10px;
            font-size: 16px;
            text-decoration: none;
            box-sizing: border-box;
        }

        .button-link:hover {
            background-color: #ddd;
            text-decoration: none;
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
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <fieldset>
            <legend>User Authentication</legend>
            <table>
                <tr>
                    <td colspan="2">
                        <h2>Login to Your Account</h2>
                        <p>Don't have an account? <a href="Registration.html">Sign up</a> or <a href="forgot-password.html">Reset password</a></p>
                    </td>
                </tr>
                <tr>
                    <td><label for="login-email">Email:</label></td>
                    <td>
                        <input type="email" id="login-email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                        <?php if ($emailErr): ?><div class="error"><?php echo $emailErr; ?></div><?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="login-password">Password:</label></td>
                    <td>
                        <input type="password" id="login-password" name="password" required>
                        <?php if ($passwordErr): ?><div class="error"><?php echo $passwordErr; ?></div><?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Login">
                        <a class="button-link" href="forgot-password.html">Forgot Password?</a>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</body>
</html>
