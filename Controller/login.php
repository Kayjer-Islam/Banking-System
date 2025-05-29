<?php
session_start();
require_once '../model/dbuser.php';

$email = $password = "";
$loginError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $remember = isset($_POST['remember']) && $_POST['remember'] === '1';

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format.']);
        exit;
    }

    if (empty($password) || strlen($password) < 8) {
        echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters.']);
        exit;
    }

    if ($email === "admin@mail.com" && $password === "admin123") {
        $_SESSION['email'] = $email;
        $_SESSION['role'] = "admin";
        echo json_encode(['status' => 'success', 'redirect' => 'admin.php', 'remember' => $remember]);
        exit;
    }

    $emailSafe = mysqli_real_escape_string($con, $email);
    $query = "SELECT * FROM users WHERE email = '$emailSafe' LIMIT 1";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = "user";
            echo json_encode(['status' => 'success', 'redirect' => '../View/dashboard.php', 'remember' => $remember]);
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Incorrect password.']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No account found with that email.']);
        exit;
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
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.9em;
            color: #333;
        }
    </style>
</head>
<body>
    <form id="login-form" method="post">
        <fieldset>
            <legend>User Authentication</legend>
            <table>
                <tr>
                    <td colspan="2">
                        <h2>Login to Your Account</h2>
                        <p>Don't have an account? <a href="signup.php">Sign up</a></p>
                        <p id="server-error" class="error"></p>
                    </td>
                </tr>
                <tr>
                    <td><label for="login-email">Email:</label></td>
                    <td>
                        <input type="email" id="login-email" name="email" />
                        <span class="error" id="email-error"></span>
                    </td>
                </tr>
                <tr>
                    <td><label for="login-password">Password:</label></td>
                    <td>
                        <input type="password" id="login-password" name="password" />
                        <span class="error" id="password-error"></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label class="checkbox-label">
                            <input type="checkbox" id="remember" name="remember" value="1" />
                            Remember Me
                        </label>
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
        window.onload = function () {
            const match = document.cookie.match(/(?:^|; )savedEmail=([^;]*)/);
            if (match) {
                document.getElementById('login-email').value = decodeURIComponent(match[1]);
                document.getElementById('remember').checked = true;
            }
        };

        document.getElementById('login-form').addEventListener('submit', function (e) {
            e.preventDefault();

            document.getElementById('email-error').textContent = '';
            document.getElementById('password-error').textContent = '';
            document.getElementById('server-error').textContent = '';

            let email = document.getElementById('login-email').value.trim();
            let password = document.getElementById('login-password').value.trim();
            let remember = document.getElementById('remember').checked;
            let valid = true;

            if (email === '') {
                document.getElementById('email-error').textContent = "Email is required";
                valid = false;
            } else if (email.indexOf('@') === -1 || email.indexOf('.') === -1 || email.indexOf('@') > email.lastIndexOf('.')) {
                document.getElementById('email-error').textContent = "Invalid email format";
                valid = false;
            }

            if (password === '') {
                document.getElementById('password-error').textContent = "Password is required";
                valid = false;
            } else if (password.length < 8) {
                document.getElementById('password-error').textContent = "Password must be at least 8 characters";
                valid = false;
            }

            if (!valid) return;

            const formData = new FormData();
            formData.append('email', email);
            formData.append('password', password);
            formData.append('ajax', '1');
            formData.append('remember', remember ? '1' : '0');

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if (data.remember) {
                        document.cookie = `savedEmail=${encodeURIComponent(email)}; path=/; max-age=604800`; // 7 days
                    } else {
                        document.cookie = "savedEmail=; path=/; max-age=0";
                    }
                    window.location.href = data.redirect;
                } else {
                    document.getElementById('server-error').textContent = data.message;
                }
            })
            .catch(() => {
                document.getElementById('server-error').textContent = "Network or server error.";
            });
        });
    </script>
</body>
</html>
