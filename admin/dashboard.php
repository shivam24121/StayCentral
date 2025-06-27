<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
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
            max-width: 1100px;
            margin: 40px auto;
            background-color: #fff7e6;
            border: 2px dashed #d1a370;
            border-radius: 12px;
            padding: 30px;
        }

        h2 {
            text-align: center;
            color: #4b3b2f;
        }

        .card-link {
            display: block;
            background-color: #c2876d;
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            text-align: center;
        }

        .card-link:hover {
            background-color: #a76b56;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div><strong>📋 Admin Panel</strong></div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="view_bookings.php">View Bookings</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>🧭 Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></h2>
    <a href="view_bookings.php" class="card-link">📒 View All Bookings</a>
    <a href="add_hostel.php" class="card-link">🏨 Add New Hostel</a>
    <a href="manage_hostels.php" class="card-link">✏️ Manage Hostels</a>
</div>

</body>
</html>
