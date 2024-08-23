<?php
session_start();
include 'connect.php';

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Fetch user information
$email = $_SESSION['email'];
$query = $conn->query("SELECT * FROM users WHERE email='$email'");
$user = $query->fetch_assoc();

// Handle form submissions for adding new users
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_user'])) {
        // Retrieve form data
        $new_email = $_POST['new_email'];
        $new_password = md5($_POST['new_password']); // Use MD5 for hashing
        $new_role = $_POST['new_role'];
        $new_first_name = $_POST['new_first_name'];
        $new_last_name = $_POST['new_last_name'];

        // Prepare and execute insert statement
        $stmt = $conn->prepare("INSERT INTO users (email, password, role, firstName, lastName) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssss", $new_email, $new_password, $new_role, $new_first_name, $new_last_name);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "New user added successfully.";
        } else {
            echo "Error adding user: " . $stmt->error;
        }
    } elseif (isset($_POST['update_user'])) {
        // Handle form submissions for updating users
        $edit_email = $_POST['edit_email'];
        $edit_first_name = $_POST['edit_first_name'];
        $edit_last_name = $_POST['edit_last_name'];

        // Prepare and execute update statement
        $stmt = $conn->prepare("UPDATE users SET firstName=?, lastName=? WHERE email=?");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sss", $edit_first_name, $edit_last_name, $edit_email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "User updated successfully.";
        } else {
            echo "Error updating user: " . $stmt->error;
        }
    } elseif (isset($_POST['delete_user'])) {
        // Handle form submissions for deleting users
        $delete_email = $_POST['delete_email'];

        // Prepare and execute delete statement
        $stmt = $conn->prepare("DELETE FROM users WHERE email=?");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $delete_email);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "User deleted successfully.";
        } else {
            echo "Error deleting user: " . $stmt->error;
        }
    }
}

// Fetch user count
$countQuery = $conn->query("SELECT COUNT(*) AS user_count FROM users");
$countResult = $countQuery->fetch_assoc();
$user_count = $countResult['user_count'];

// Fetch all users
$usersQuery = $conn->query("SELECT email, firstName, lastName FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Users</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #003b6f;
            margin: 0;
            padding: 0;
        }

        /* Heading Styles */
        .heading-container {
            text-align: center;
            background-color: #003366;
            color: white;
            padding: 20px;
        }

        .slideshow-heading {
            font-size: 2.5em;
            margin: 0;
            opacity: 0;
            animation: fadeIn 2s ease-in-out infinite;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            50% { opacity: 1; }
            100% { opacity: 0; }
        }

        /* Container Styles */
        .container {
            text-align: center;
            padding: 5%;
            max-width: 1000px;
            margin: auto;
        }

        /* Form Styles */
        form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 20px auto;
            width: 80%;
            max-width: 500px;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="password"],
        form input[type="submit"] {
            margin: 5px 10px;
            padding: 8px;
            border: 1px solid #003b6f;
            border-radius: 4px;
            width: 200px;
            box-sizing: border-box;
        }

        form input[type="submit"] {
            background-color: #003b6f;
            color: #ffffff;
            border: 2px solid #003b6f;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        form input[type="submit"]:hover {
            background-color: #ffffff;
            color: #003b6f;
            border-color: #003b6f;
        }

        form input[type="submit"]:active {
            background-color: #002a5e;
            border-color: #002a5e;
        }

        /* Table Styles */
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 10px;
            border: 1px solid #003b6f;
        }

        th {
            background-color: #003b6f;
            color: #ffffff;
        }

        tr:nth-child(even) {
            background-color: #e0f0ff;
        }

        /* Button Styles */
        .logout-button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1em;
            color: #ffffff;
            background-color: #dc3545;
            border: none;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 10px 2px;
        }

        .logout-button:hover {
            background-color: #c82333;
        }

        .about-container {
            background-color: #003366;
            color: white;
            padding: 20px;
            text-align: center;
            margin: 20px auto;
            max-width: 800px;
            border-radius: 8px;
        }

        .about-container h2 {
            margin-top: 0;
        }

        /* Logo Container */
        .logo-container {
            position: absolute;
            top: 0;
            left: 0;
            padding: 10px;
            z-index: 1000;
        }

        .logo {
            display: block;
            max-width: 300px;
            height: auto;
        }
    </style>
</head>
<body>
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

<div class="container">
    <h1>Admin - Manage Users</h1>
    <p>Hello <?php echo htmlspecialchars($user['firstName']) . ' ' . htmlspecialchars($user['lastName']); ?>:</p>
    <a href="logout.php" class="logout-button">Logout</a>
    <p>Total number of users: <?php echo htmlspecialchars($user_count); ?></p>

    <h2>Add New User</h2>
    <form method="post" action="">
        <input type="text" name="new_first_name" placeholder="First Name" required>
        <input type="text" name="new_last_name" placeholder="Last Name" required>
        <input type="email" name="new_email" placeholder="Email" required>
        <input type="password" name="new_password" placeholder="Password" required>
        <input type="text" name="new_role" placeholder="Role (e.g., user, admin)" required>
        <input type="submit" name="add_user" value="Add User">
    </form>

    <h2>Users List</h2>
    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>First Name</th>
                <th>Last Name</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $usersQuery->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['firstName']); ?></td>
                    <td><?php echo htmlspecialchars($row['lastName']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2>Update User</h2>
    <form method="post" action="">
        <input type="text" name="edit_email" placeholder="Email to Edit" required>
        <input type="text" name="edit_first_name" placeholder="New First Name">
        <input type="text" name="edit_last_name" placeholder="New Last Name">
        <input type="submit" name="update_user" value="Update User">
    </form>

    <h2>Delete User</h2>
    <form method="post" action="">
        <input type="text" name="delete_email" placeholder="Email to Delete" required>
        <input type="submit" name="delete_user" value="Delete User">
    </form>
</div>

<div class="about-container">
    <h2>About This System</h2>
    <p>This is the user management system for CPUT Shuttle Schedule.</p>
</div>

</body>
</html>
