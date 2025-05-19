<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['signup'])) {
        header('Location: ../Controller/signup.php');
        exit();
    } elseif (isset($_POST['login'])) {
        header('Location: ../Controller/login.php');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 50px;
        }
        #landing {
            max-width: 800px;
            margin: 0 auto;
        }
        fieldset {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 50px;
            background-color: white;
        }
        legend {
            font-weight: bold;
            padding: 0 10px;
        }
        .hero {
            text-align: center;
            margin-bottom: 20px;
        }
        .hero h1 {
            color: #2c3e50;
        }
        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin: 30px 0;
        }
        .feature {
            flex: 1;
            min-width: 200px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 3px solid #3498db;
        }
        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
        .action-btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-signup {
            background-color: #3498db;
            color: white;
            border: none;
        }
        .btn-login {
            background-color: white;
            color: #3498db;
            border: 1px solid #3498db;
        }
        .action-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div id="landing">
        <fieldset>
            <legend>Banking Portal</legend>
            <div class="hero">
                <h1>Welcome</h1>
                <p>The complete solution for your banking needs</p>
                <form method="post" style="margin-top: 20px;">
                    <button type="submit" name="signup" class="action-btn btn-signup">Get Started</button>
                </form>
            </div>

            <div class="features">
                <div class="feature">
                    <h3>User Authentication</h3>
                    <p>Secure login and registration system with email verification and password recovery.</p>
                </div>
                <div class="feature">
                    <h3>Dashboard</h3>
                    <p>Comprehensive overview of your activities with quick access to important features.</p>
                </div>
                <div class="feature">
                    <h3>Notifications</h3>
                    <p>Get all alerts and information with digitized notifications.</p>
                </div>
            </div>

            <div class="btn-container">
                <form method="post" style="display:inline;">
                    <button type="submit" name="signup" class="action-btn btn-signup">Sign Up Now</button>
                </form>
                <form method="post" style="display:inline;">
                    <button type="submit" name="login" class="action-btn btn-login">Login</button>
                </form>
            </div>
        </fieldset>
    </div>
</body>
</html>