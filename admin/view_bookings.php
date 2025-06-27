<?php
session_start();
include("../db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT b.*, u.name AS guest, u.email, h.name AS hostel 
                        FROM bookings b 
                        JOIN users u ON b.user_id = u.id 
                        JOIN hostels h ON b.hostel_id = h.id 
                        ORDER BY b.id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Bookings</title>
    <style>
        body {
            font-family: 'Calibri', sans-serif;
            background: linear-gradient(135deg, #f7e8c9, #f1d1b5);
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px dashed #b48c6e;
            text-align: left;
        }

        th {
            background-color: #f4d3ae;
        }

        tr:hover {
            background-color: #f5f0e5;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div><strong>📋 Admin Panel</strong></div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>📒 Bookings Overview</h2>

    <table>
        <thead>
            <tr>
                <th>Guest Name</th>
                <th>Email</th>
                <th>Hostel</th>
                <th>Check-in</th>
                <th>Check-out</th>
                <th>Guests</th>
                <th>Total ₹</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['guest']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['hostel']) ?></td>
                    <td><?= $row['check_in'] ?></td>
                    <td><?= $row['check_out'] ?></td>
                    <td><?= $row['guests'] ?></td>
                    <td><?= $row['total_price'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
