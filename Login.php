<?php
session_start();

$email = $password = "";
$emailErr = $passwordErr = "";
$loginError = "";

// Connect to the database
$con = mysqli_connect('127.0.0.1', 'root', '', 'webtech section a');
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

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
    } elseif (strlen($password) < 2) {
        $passwordErr = "Password must be at least 8 characters";
    }

    if (empty($emailErr) && empty($passwordErr)) {
        $stmt = $con->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $row = $result->fetch_assoc()) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role']; // assumes there's a 'role' column

            if ($row['role'] === "admin") {
                header("Location: Admin.html");
            } else {
                header("Location: Dashboard.php");
            }
            exit();
        } else {
            $loginError = "Invalid email or password.";
        }

        $stmt->close();
    }
}

mysqli_close($con);

include '../View/login-view.php';
?>
