<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../Controller/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profile Management</title>
  <link rel="stylesheet" href="../Asset/profile.css" />
</head>
<body>
  <form class="profile-form" method="POST" enctype="multipart/form-data" action="../Controller/profilevalid.php" onsubmit="return validateProfileForm();">
    <h1>Profile Management</h1>

    <div class="avatar-group">
      <img id="avatar" src="https://via.placeholder.com/150" alt="Profile Picture" />
      <input type="file" id="avatar-upload" name="avatar" accept="image/*" hidden />
      <button type="button" onclick="document.getElementById('avatar-upload').click()">Change Avatar</button>
    </div>

    <label for="name">Full Name</label>
    <input type="text" id="name" name="name" required />

    <label for="email">Email</label>
    <input type="email" id="email" name="email" required />

    <label for="phone">Phone</label>
    <input type="text" id="phone" name="phone" required />

    <hr />

    <h2>Change Password</h2>

    <label for="current-pass">Current Password</label>
    <input type="password" id="current-pass" name="current_pass" />

    <label for="new-pass">New Password</label>
    <input type="password" id="new-pass" name="new_pass" />

    <label for="confirm-pass">Confirm Password</label>
    <input type="password" id="confirm-pass" name="confirm_pass" />

    <button type="submit">Save All Changes</button>
  </form>

  <script src="../Controller/profilevalid.js"></script>
</body>
</html>
