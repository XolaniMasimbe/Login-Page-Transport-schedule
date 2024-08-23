<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>
    <!-- Image in Top Left Corner -->
    <div class="logo-container">
    <img src="img/CPUTLOGO.png" alt="Logo" class="logo">

   
</div>

    <div class="heading-container">
        <div class="slideshow-container">
            <h1 class="slideshow-heading">CPUT</h1>
            <h1 class="slideshow-heading">SHUTTLE</h1>
            <h1 class="slideshow-heading">SCHEDULE</h1>
        </div>
    </div>
    <div class="container" id="signIn">
        <h1 class="form-title">Sign In</h1>
        <form method="post" action="login.php">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <p class="recover">
                <a href="forgot_password.php">Forgot Password?</a>
            </p>
            <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>
    </div>
    <!-- New About Section -->
    <div class="about-container">
        <h2>About Us</h2>
        <p>Welcome to our shuttle scheduling system. We provide real-time updates and schedule information for the CPUT shuttle services. Our mission is to make commuting easier and more efficient for students and staff. Thank you for using our service!</p>
    </div>
   

    <script src="script.js"></script>
</body>
</html>
