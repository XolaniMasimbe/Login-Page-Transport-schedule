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
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle form submissions for updating, adding, and removing bus schedules
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_bus_schedule'])) {
        // Update bus schedule
        $id = $_POST['id'];
        $route_from = $_POST['route_from'];
        $route_to = $_POST['route_to'];
        $bus_number = $_POST['bus_number'];
        $number_plate = $_POST['number_plate'];
        $departure_time = $_POST['departure_time'];
        $arrival_time = $_POST['arrival_time'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("UPDATE bus_schedule SET route_from = ?, route_to = ?, bus_number = ?, number_plate = ?, departure_time = ?, arrival_time = ?, status = ? WHERE id = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssssssi", $route_from, $route_to, $bus_number, $number_plate, $departure_time, $arrival_time, $status, $id);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            header("Location: admin.php");
            exit();
        } else {
            echo "No changes made or error: " . $stmt->error;
        }
    } elseif (isset($_POST['add_bus_schedule'])) {
        // Add new bus schedule
        $route_from = $_POST['route_from'];
        $route_to = $_POST['route_to'];
        $bus_number = $_POST['bus_number'];
        $number_plate = $_POST['number_plate'];
        $departure_time = $_POST['departure_time'];
        $arrival_time = $_POST['arrival_time'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("INSERT INTO bus_schedule (route_from, route_to, bus_number, number_plate, departure_time, arrival_time, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssssss", $route_from, $route_to, $bus_number, $number_plate, $departure_time, $arrival_time, $status);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error adding schedule: " . $stmt->error;
        }
    } elseif (isset($_POST['remove_bus_schedule'])) {
        // Remove bus schedule
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM bus_schedule WHERE id = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            header("Location: admin.php");
            exit();
        } else {
            echo "Error removing schedule: " . $stmt->error;
        }
    }
}

// Fetch bus schedule data
$scheduleQuery = $conn->query("SELECT * FROM bus_schedule ORDER BY departure_time");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Inter-Campus Time Table</title>
    <style>
       /* General Styles */
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

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
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

a {
    color: #003b6f;
    text-decoration: none;
    font-weight: bold;
}

a:hover {
    text-decoration: underline;
}

/* About Section */
.about-container {
    background: #003366;
    color: white;
    padding: 20px;
    text-align: center;
    margin: 20px auto;
    max-width: 800px;
    border-radius: 8px;
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

/* Styles for the Slideshow */
.slideshow {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: -1;
}

.slideshow-images {
    position: relative;
    width: 300%;
    height: 100%;
    display: flex;
    animation: slide 15s infinite;
}

.slideshow-images .slide {
    flex: 1 0 100%;
    height: 100%;
    position: absolute;
    opacity: 0;
    transition: opacity 1s ease-in-out;
}

.slideshow-images .slide:nth-child(1) { 
    opacity: 1;
}

@keyframes slide {
    0% { transform: translateX(0); }
    20% { transform: translateX(0); }
    25% { transform: translateX(-100%); }
    45% { transform: translateX(-100%); }
    50% { transform: translateX(-200%); }
    70% { transform: translateX(-200%); }
    75% { transform: translateX(-300%); }
    95% { transform: translateX(-300%); }
    100% { transform: translateX(0); }
}

/* Form Styles */
form {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

form input[type="text"] {
    margin: 5px 10px;
    padding: 8px;
    border: 1px solid #003b6f;
    border-radius: 4px;
    width: 200px;
}

form input[type="submit"] {
    margin: 5px;
    padding: 10px 20px;
    font-size: 1em;
    color: #ffffff;
    background-color: #003b6f;
    border: 2px solid #003b6f;
    border-radius: 5px;
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

/* Table Container */
.table-container {
    display: flex;
    justify-content: center;
    padding: 20px;
}

table {
    width: auto; 
    max-width: 100%; 
    border-collapse: collapse;
    margin: 20px 0;
    background-color: #ffffff; 
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
}

table input[type="text"] {
    width: 100%; 
    box-sizing: border-box; 
}

/* Button Styles */
.logout-button,
.button {
    display: inline-block;
    padding: 10px 20px;
    font-size: 1em;
    color: #ffffff;
    background-color: #003b6f;
    border: 2px solid #003b6f;
    border-radius: 5px;
    text-decoration: none;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    margin: 5px;
}

.logout-button:hover,
.button:hover {
    background-color: #ffffff;
    color: #003b6f;
    border-color: #003b6f;
}

.logout-button:active,
.button:active {
    background-color: #002a5e;
    border-color: #002a5e;
}
/* Button Styles for Update and Remove */
table input[type="submit"] {
    font-size: 0.9em;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    border: none;
    color: #ffffff;
    transition: background-color 0.3s ease, color 0.3s ease;
    margin: 0 5px;
}

input[name="update_bus_schedule"] {
    background-color: #28a745;
    border: 1px solid #218838;
}

input[name="update_bus_schedule"]:hover {
    background-color: #218838;
    border-color: #1e7e34;
}

input[name="update_bus_schedule"]:active {
    background-color: #1e7e34;
    border-color: #1c7430;
}

input[name="remove_bus_schedule"] {
    background-color: #dc3545;
    border: 1px solid #c82333;
}

input[name="remove_bus_schedule"]:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

input[name="remove_bus_schedule"]:active {
    background-color: #bd2130;
    border-color: #b21f2d;
}



    </style>
</head>
<body>
    <!-- Background Slideshow -->
    <div class="slideshow">
        <div class="slideshow-images">
            <div class="slide"><img src="img/slide1.jpg" alt="Slide 1" style="width: 100%; height: 100%; object-fit: cover;"></div>
            <div class="slide"><img src="img/slide2.jpg" alt="Slide 2" style="width: 100%; height: 100%; object-fit: cover;"></div>
            <div class="slide"><img src="img/slide3.jpg" alt="Slide 3" style="width: 100%; height: 100%; object-fit: cover;"></div>
        </div>
    </div>

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

   
    <!-- Admin Section -->
    <div class="container">
    <h1>Admin - Inter-Campus Time Table</h1>
    <p>Hello <?php echo htmlspecialchars($user['firstName']) . ' ' . htmlspecialchars($user['lastName']); ?>:</p>
    <a href="logout.php" class="logout-button">Logout</a>
    <a href="manage_users.php" class="button">Manage Users</a>

    <h2>Add New Bus Schedule</h2>
    <form method="post" action="">
        <div>
            <input type="text" name="route_from" placeholder="Route From" required>
            <input type="text" name="route_to" placeholder="Route To" required>
            <input type="text" name="bus_number" placeholder="Bus Number" required>
            <input type="text" name="number_plate" placeholder="Number Plate" required>
            <input type="text" name="departure_time" placeholder="Departure Time" required>
            <input type="text" name="arrival_time" placeholder="Arrival Time" required>
            <input type="text" name="status" placeholder="Status" required>
        </div>
        <input type="submit" name="add_bus_schedule" value="Add Schedule">
    </form>

    <h2>Bus Schedules</h2>
    <table>
        <tr>
            <th>From</th>
            <th>To</th>
            <th>Bus Number</th>
            <th>Number Plate</th>
            <th>Departure Time</th>
            <th>Arrival Time</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $scheduleQuery->fetch_assoc()) { ?>
            <tr>
                <form method="post" action="">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                    <td><input type="text" name="route_from" value="<?php echo htmlspecialchars($row['route_from']); ?>"></td>
                    <td><input type="text" name="route_to" value="<?php echo htmlspecialchars($row['route_to']); ?>"></td>
                    <td><input type="text" name="bus_number" value="<?php echo htmlspecialchars($row['bus_number']); ?>"></td>
                    <td><input type="text" name="number_plate" value="<?php echo htmlspecialchars($row['number_plate']); ?>"></td>
                    <td><input type="text" name="departure_time" value="<?php echo htmlspecialchars($row['departure_time']); ?>"></td>
                    <td><input type="text" name="arrival_time" value="<?php echo htmlspecialchars($row['arrival_time']); ?>"></td>
                    <td><input type="text" name="status" value="<?php echo htmlspecialchars($row['status']); ?>"></td>
                    <td>
                        <input type="submit" name="update_bus_schedule" value="Update">
                        <input type="submit" name="remove_bus_schedule" value="Remove">
                    </td>
                </form>
            </tr>
        <?php } ?>
    </table>
</div>
     <!-- About Section -->
     <div class="about-container">
        <h2>About Us</h2>
        <p>Welcome to our shuttle scheduling system. We provide real-time updates and schedule information for the CPUT shuttle services. Our mission is to make commuting easier and more efficient for students and staff. Thank you for using our service!</p>
    </div>

</body>
</html>
