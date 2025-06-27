<?php
session_start();
include("../db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $rooms = $_POST['rooms'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $image = $_FILES['image']['name'];
    $imageTmp = $_FILES['image']['tmp_name'];

    $filename = uniqid() . "_" . basename($image);
    $destination = "../assets/images/" . $filename;

    if (move_uploaded_file($imageTmp, $destination)) {
        $stmt = $conn->prepare("INSERT INTO hostels (name, location, rooms, price_per_night, description, image) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssids", $name, $location, $rooms, $price, $description, $filename);
        $stmt->execute();
        $success = "Hostel added successfully!";
    } else {
        $error = "Failed to upload image.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Hostel - Admin</title>
    <style>
        body {
            background: linear-gradient(135deg, #f7e8c9, #f1d1b5);
            font-family: 'Calibri', sans-serif;
            margin: 0;
        }

        .navbar {
            background-color: #c2876d;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-left: 20px;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            background-color: #fff7e6;
            padding: 30px;
            border-radius: 12px;
            border: 2px dashed #d1a370;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #4b3b2f;
        }

        form {
            margin-top: 20px;
        }

        input, textarea {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 6px;
            border: 1px solid #d1a370;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #c2876d;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #a76b56;
        }

        .message {
            text-align: center;
            font-weight: bold;
            margin: 15px 0;
            color: green;
        }

        .error {
            text-align: center;
            font-weight: bold;
            margin: 15px 0;
            color: red;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div><strong>📋 Admin Panel</strong></div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="view_bookings.php">View Bookings</a>
        <a href="manage_hostels.php">Manage Hostels</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>🏨 Add New Hostel</h2>

    <?php if (isset($success)): ?>
        <div class="message"><?= $success ?></div>
    <?php elseif (isset($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Hostel Name:</label>
        <input type="text" name="name" required>

        <label>Location:</label>
        <input type="text" name="location" required>

        <label>Number of Rooms:</label>
        <input type="number" name="rooms" min="1" required>

        <label>Price per Night (₹):</label>
        <input type="number" name="price" step="0.01" required>

        <label>Description:</label>
        <textarea name="description" rows="4" required></textarea>

        <label>Hostel Image:</label>
        <input type="file" name="image" accept="image/*" required>

        <input type="submit" value="Add Hostel">
    </form>
</div>

</body>
</html>
