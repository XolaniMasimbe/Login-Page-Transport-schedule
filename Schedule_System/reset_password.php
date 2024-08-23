<?php
session_start();
include 'connect.php'; // Ensure this file includes the database connection code

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate the token
    $stmt = $conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Update the password and clear the reset token
            $stmt = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
            $stmt->bind_param("ss", $password, $token);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Your password has been reset successfully. <a href='index.php'>Login</a>";
            } else {
                echo "Failed to reset password.";
            }
        }
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <div class="container">
        <h1>Reset Password</h1>
        <form method="post">
            <input type="password" name="password" placeholder="New Password" required>
            <input type="submit" value="Reset Password">
        </form>
    </div>
</body>
</html>
