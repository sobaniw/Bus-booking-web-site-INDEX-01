<?php
session_start();
require 'db.php';

$errors = [];
$success_msg = '';

// Check for successful registration redirect
if (isset($_GET['registered'])) {
    $success_msg = "Registration successful! Please login.";
}

// Process login form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username)) {
        $errors[] = "Username is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    // Authenticate user if no errors
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $errors[] = "Invalid username or password";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>HTML Login Form</title>
    <link rel="stylesheet" href="style1.css">
</head>

<body>
    <div class="main">
        <h1>Login</h1>
        <h3>Enter your login credentials</h3>

        <form action="">
            <label for="first">
                Username:
            </label>
            <input type="text" id="first" name="first" 
                placeholder="Enter your Username" required>

            <label for="password">
                Password:
            </label>
            <input type="password" id="password" name="password" 
                placeholder="Enter your Password" required>

            <div class="wrap">
                <button type="submit">
                    Submit
                </button>
            </div>
        </form>
        
        <p>Not registered?
            <a href="#" style="text-decoration: none;">
                Create an account
            </a>
        </p>
    </div>
</body>

</html>