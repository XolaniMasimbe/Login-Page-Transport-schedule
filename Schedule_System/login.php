<?php
session_start();
include 'connect.php';

if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); 
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $user['role'];

        // Redirect based on user role
        if ($user['role'] === 'admin') {
            header("Location: admin.php");
        } else {
            header("Location: homepage.php");
        }
        exit();
    } else {
        echo "Incorrect Email or Password";
    }
}
?>
