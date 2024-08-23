<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

$scheduleQuery = $conn->query("SELECT * FROM bus_schedule ORDER BY departure_time");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inter-Campus Time Table</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff; /* White background */
            color: #003b6f; /* Dark Blue text */
            margin: 0;
            padding: 0;
        }

        /* Heading Styles */
        .heading-container {
            text-align: center;
            background-color: #003366; /* Dark blue background */
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
            max-width: 1000px; /* Ensure container width accommodates larger tables */
            margin: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 10px;
            border: 1px solid #003b6f; /* Dark Blue border */
        }

        th {
            background-color: #003b6f; /* Dark Blue background */
            color: #ffffff; /* White text */
        }

        tr:nth-child(even) {
            background-color: #e0f0ff; /* Light Blue for even rows */
        }

        a {
            color: #003b6f; /* Dark Blue links */
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* About Section */
        .about-container {
            background: #003366; /* Dark blue */
            color: white;
            padding: 20px;
            text-align: center;
            margin: 20px auto;
            max-width: 800px;
            border-radius: 8px;
        }

        /* Logo Container */
        .logo-container {
            position: absolute; /* Position the container absolutely */
            top: 0; /* Align it to the top of the page */
            left: 0; /* Align it to the left of the page */
            padding: 10px; /* Add some padding around the image */
            z-index: 1000; /* Ensure it appears above other content */
        }

        .logo {
            display: block; /* Ensure the image is treated as a block element */
            max-width: 300px; /* Adjust the size of the image as needed */
            height: auto; /* Maintain the aspect ratio of the image */
        }

        /* Styles for the Slideshow */
        .slideshow {
            position: fixed; /* To ensure it stays in place */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden; /* Hide overflowed images */
            z-index: -1; /* Ensure it stays in the background */
        }

        .slideshow-images {
            position: relative;
            width: 300%; /* Adjust based on number of images */
            height: 100%;
            display: flex;
            animation: slide 15s infinite; /* Adjust timing as needed */
        }

        .slideshow-images .slide {
            flex: 1 0 100%; /* Adjust to cover the full container */
            height: 100%;
            position: absolute;
            opacity: 0; /* Initially hide images */
            transition: opacity 1s ease-in-out; /* Smooth transition for fading */
        }

        .slideshow-images .slide:nth-child(1) { 
            opacity: 1; /* First image is visible initially */
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
        .logout-button {
    display: inline-block;
    padding: 10px 20px;
    margin: 10px 0;
    font-size: 1em;
    color: #ffffff; /* White text */
    background-color: #003b6f; /* Dark Blue background */
    border: 2px solid #003b6f; /* Dark Blue border */
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}

.logout-button:hover {
    background-color: #ffffff; /* White background on hover */
    color: #003b6f; /* Dark Blue text on hover */
    border-color: #003b6f; /* Dark Blue border on hover */
}

.logout-button:active {
    background-color: #002a5e; /* Darker Blue for active state */
    border-color: #002a5e; /* Darker Blue border for active state */
}

    </style>
</head>
<body>
    <!-- Background Slideshow -->
   

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

    <div class="container">
        <h1>Inter-Campus Time Table</h1>
        <p>Hello <?php echo htmlspecialchars($user['firstName']) . ' ' . htmlspecialchars($user['lastName']); ?>:</p>
        
        <a href="logout.php" class="logout-button">Logout</a>
        <table>
            <tr>
                <th>From</th>
                <th>To</th>
                <th>Bus Number</th>
                <th>Number Plate</th>
                <th>Departure Time</th>
                <th>Arrival Time</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $scheduleQuery->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['route_from']); ?></td>
                    <td><?php echo htmlspecialchars($row['route_to']); ?></td>
                    <td><?php echo htmlspecialchars($row['bus_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['number_plate']); ?></td>
                    <td><?php echo htmlspecialchars($row['departure_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['arrival_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
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
