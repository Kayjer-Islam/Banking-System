<?php
// PHP validation logic
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full-name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm-password'] ?? '';
    $age = $_POST['age'] ?? '';
    $agree = $_POST['agree'] ?? null;

    // Full Name
    if ($name === '') {
        $errors['name'] = "Please enter your full name";
    }

    // Email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email address";
    }

    // Password
    if (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters";
    }

    // Confirm Password
    if ($password !== $confirm) {
        $errors['confirm'] = "Passwords must match";
    }

    // Age
    if ($age !== '' && ($age < 18 || $age > 120)) {
        $errors['age'] = "Must be between 18 and 120";
    }

    // Terms agreement
    if (!$agree) {
        $errors['agree'] = "You must agree to the terms";
    }

    if (empty($errors)) {
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Validation</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div id="validation">
        <fieldset>
            <legend>Form Validation</legend>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="form-group">
                    <label for="full-name">Full Name:</label>
                    <input type="text" id="full-name" name="full-name" value="<?php echo htmlspecialchars($_POST['full-name'] ?? ''); ?>" required>
                    <?php if (!empty($errors['name'])): ?><span class="error"><?php echo htmlspecialchars($errors['name']); ?></span><?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    <?php if (!empty($errors['email'])): ?><span class="error"><?php echo htmlspecialchars($errors['email']); ?></span><?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <?php if (!empty($errors['password'])): ?><span class="error"><?php echo htmlspecialchars($errors['password']); ?></span><?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="confirm-password">Confirm Password:</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                    <?php if (!empty($errors['confirm'])): ?><span class="error"><?php echo htmlspecialchars($errors['confirm']); ?></span><?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="age">Age:</label>
                    <input type="number" id="age" name="age" min="18" max="120" value="<?php echo htmlspecialchars($_POST['age'] ?? ''); ?>">
                    <?php if (!empty($errors['age'])): ?><span class="error"><?php echo htmlspecialchars($errors['age']); ?></span><?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="agree" name="agree" <?php echo isset($_POST['agree']) ? 'checked' : ''; ?>> I agree to the terms and conditions
                    </label>
                    <?php if (!empty($errors['agree'])): ?><span class="error"><?php echo htmlspecialchars($errors['agree']); ?></span><?php endif; ?>
                </div>
                
                <input type="submit" value="Submit Form">
                <?php if ($success): ?><span class="success">Form submitted successfully!</span><?php endif; ?>
            </form>
        </fieldset>
    </div>
</body>
</html>