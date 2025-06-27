<?php
session_start();
include("../db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch hostels
$result = $conn->query("SELECT * FROM hostels ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Hostels</title>
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
            padding: 30px;
            background-color: #fff7e6;
            border: 2px dashed #d1a370;
            border-radius: 12px;
        }

        h2 {
            text-align: center;
            color: #4b3b2f;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px dashed #b48c6e;
        }

        th {
            background-color: #f4d3ae;
        }

        tr:hover {
            background-color: #f5f0e5;
        }

        img {
            width: 100px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
        }

        .edit-btn {
            background-color: #c2876d;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        }

        .edit-btn:hover {
            background-color: #a76b56;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div><strong>📋 Admin Panel</strong></div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="add_hostel.php">Add Hostel</a>
        <a href="view_bookings.php">View Bookings</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>🛠️ Manage Hostels</h2>

    <table>
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Location</th>
                <th>Rooms</th>
                <th>Price (₹)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><img src="../assets/images/<?= htmlspecialchars($row['image']) ?>" alt="Hostel"></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['location']) ?></td>
                <td><?= $row['rooms'] ?></td>
                <td><?= $row['price_per_night'] ?></td>
                <td><a href="edit_hostel.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
