<?php
require 'db.php'; // Include your database connection file

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);

    // Validation
    if (empty($username)) $errors[] = "Username is required";
    if (empty($password)) $errors[] = "Password is required";
    if (empty($contact)) $errors[] = "Contact number is required";
    if (empty($email)) $errors[] = "Email is required";
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email format";
    if (!preg_match('/^[0-9]{10,15}$/', $contact)) $errors[] = "Invalid contact number";

    // Check if username/email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) $errors[] = "Username or email already exists";

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, password, contact_number, email) VALUES (?, ?, ?, ?)");
        
        if ($stmt->execute([$username, $hashed_password, $contact, $email])) {
            $success = true;
            header("Refresh: 2; url=login.php");
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h1>Sign Up</h1>
            
            <?php if (!empty($errors)): ?>
                <div class="error-box">
                    <?php foreach ($errors as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-box">
                    <p>Registration successful! Redirecting to login...</p>
                </div>
            <?php else: ?>
                <form method="POST">
                    <div class="input-group">
                        <label>Username</label>
                        <input type="text" name="username" placeholder="Calls your Utilities" required>
                    </div>
                    
                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    
                    <div class="input-group">
                        <label>Contact</label>
                        <input type="tel" name="contact" placeholder="Enter your contact number" required>
                    </div>
                    
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Enter your Email" required>
                    </div>
                    
                    <button type="submit" class="submit-btn">Submit</button>
                </form>
                
                <div class="footer">
                    <p>Not registered? <a href="register.php">Create an account</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>