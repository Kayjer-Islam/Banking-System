<?php
session_start();

$users = [
    'test@example.com' => 'password123',
];

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (isset($users[$email]) && $users[$email] === $password) {
        $_SESSION['user_id'] = $email;
        header("Location: ../View/dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    form {
      background: white;
      padding: 2em;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      margin-bottom: 1em;
    }
    input[type=\"email\"], input[type=\"password\"] {
      width: 100%;
      padding: 0.5em;
      margin-bottom: 1em;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    input[type=\"submit\"] {
      padding: 0.5em 1em;
      background: #007bff;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    input[type=\"submit\"]:hover {
      background: #0056b3;
    }
    .error {
      color: red;
      margin-bottom: 1em;
    }
  </style>
</head>
<body>
  <form method="POST" action="login.php">
    <h2>Login</h2>
    <?php if ($error): ?>
      <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" value="Login">
  </form>
</body>
</html>
