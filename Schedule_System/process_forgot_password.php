<?php
session_start();
include 'connect.php'; // Ensure this file includes the database connection code

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    
    // Generate a unique reset token
    $token = bin2hex(random_bytes(50));
    $expiry = date("Y-m-d H:i:s", strtotime('+1 hour')); 

    // Prepare and execute the update query
    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $expiry, $email);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $resetLink = "http://localhost/reset_password.php?token=$token";
        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: $resetLink";
        $headers = "From: no-reply@yourdomain.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "A password reset link has been sent to your email address.";
        } else {
            echo "Failed to send email.";
        }
    } else {
        echo "Email address not found or update failed.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
</head>
<body>
    <div class="container">
        <h1>Forgot Password</h1>
        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="submit" value="Send Reset Link">
        </form>
    </div>
</body>
</html>
