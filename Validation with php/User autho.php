<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Authentication</title>
    <style>
        .error { color: red; }
        fieldset {
            width: 300px;
            margin: 50px auto;
            padding: 20px;
        }
        table {
            width: 100%;
        }
        td {
            padding: 5px;
        }
    </style>
</head>
<body>
    <?php
    $email = $password = "";
    $emailErr = $passwordErr = "";
    $loginSuccess = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate email
        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
        } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
            }
        }
        
        // Validate password
        if (empty($_POST["password"])) {
            $passwordErr = "Password is required";
        } else {
            $password = test_input($_POST["password"]);
            if (strlen($password) < 6) {
                $passwordErr = "Password must be at least 6 characters";
            }
        }
        
        // If no errors, consider login successful
        if (empty($emailErr) && empty($passwordErr)) {
            $loginSuccess = true;
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    ?>

    <form id="auth" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <fieldset>
            <legend>User Authentication</legend>
            <table>
                <tr>
                    <td colspan="2">
                        <h2>Login to Your Account</h2>
                        <p>Don't have an account? <a href="signup.html">Sign up</a> or <a href="forgot-password.html">reset password</a></p>
                        <?php if ($loginSuccess): ?>
                            <p style="color: green;">Login successful!</p>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="login-email">Email:</label></td>
                    <td>
                        <input type="email" id="login-email" name="email" value="<?php echo $email; ?>" required>
                        <span class="error"><?php echo $emailErr; ?></span>
                    </td>
                </tr>
                <tr>
                    <td><label for="login-password">Password:</label></td>
                    <td>
                        <input type="password" id="login-password" name="password" required>
                        <span class="error"><?php echo $passwordErr; ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Login">
                        <p><a href="forgot-password.html">Forgot Password?</a></p>
                    </td>
                </tr>
            </table>
        </fieldset>
    </form>
</body>
</html>