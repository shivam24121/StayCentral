<?php
session_start();
include("../db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$stmt = $conn->prepare("SELECT b.*, h.name, h.location, h.image 
                        FROM bookings b 
                        JOIN hostels h ON b.hostel_id = h.id 
                        WHERE b.user_id = ? 
                        ORDER BY b.id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <style>
        body {
            margin: 0;
            font-family: 'Calibri', sans-serif;
            background: linear-gradient(135deg, #f7e8c9, #f1d1b5);
            color: #3b2f2f;
        }

        .navbar {
            background-color: #c2876d;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .navbar a {
            color: #fff4e6;
            margin-left: 20px;
            text-decoration: none;
            font-weight: bold;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 30px;
            background: #fff7e6;
            border-radius: 12px;
            border: 2px dashed #d1a370;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #4b3b2f;
        }

        .booking {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            border-bottom: 1px dashed #c2876d;
            padding-bottom: 15px;
        }

        .booking img {
            width: 200px;
            height: 140px;
            border-radius: 10px;
            object-fit: cover;
        }

        .booking-details {
            flex: 1;
        }

        .booking-details h3 {
            margin-top: 0;
            color: #40332a;
        }

        .booking-details p {
            margin: 5px 0;
            color: #665040;
        }

        .no-booking {
            text-align: center;
            font-size: 18px;
            margin-top: 40px;
            color: #664e3d;
        }
    </style>
</head>
<body>

<!-- 🔗 Navigation -->
<div class="navbar">
    <div><strong>🌍 Hostel Travel Booking</strong></div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="my_bookings.php">My Bookings</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<!-- 📒 Bookings -->
<div class="container">
    <h2>📒 My Bookings</h2>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="booking">
            <img src="../assets/images/<?= htmlspecialchars($row['image']) ?>" alt="Hostel Image">
            <div class="booking-details">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p><strong>📍 Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
                <p><strong>🗓️ Dates:</strong> <?= $row['check_in'] ?> to <?= $row['check_out'] ?></p>
                <p><strong>👤 Guests:</strong> <?= $row['guests'] ?></p>
                <p><strong>💳 Total:</strong> ₹<?= $row['total_price'] ?></p>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-booking">No bookings found. Start your adventure from the <a href="dashboard.php">Dashboard</a>.</div>
    <?php endif; ?>
</div>

</body>
</html>
