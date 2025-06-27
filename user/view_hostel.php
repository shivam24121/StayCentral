<?php
session_start();
include("../db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "Hostel not found.";
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM hostels WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$hostel = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Hostel</title>
    <style>
        body {
            margin: 0;
            font-family: 'Georgia', serif;
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
            max-width: 900px;
            margin: 40px auto;
            background: #fff7e6;
            border-radius: 12px;
            padding: 30px;
            border: 2px dashed #d1a370;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }

        img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        h2 {
            margin-top: 0;
            color: #40332a;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #c2876d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #a76b56;
        }

        p {
            color: #665040;
            line-height: 1.6;
        }

    </style>
</head>
<body>

<!-- 🧭 Navbar -->
<div class="navbar">
    <div><strong>🌍 Hostel Travel Booking</strong></div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="my_bookings.php">My Bookings</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<!-- 📄 Hostel Details -->
<div class="container">
    <img src="../assets/images/<?= htmlspecialchars($hostel['image']) ?>" alt="Hostel Image">
    <h2><?= htmlspecialchars($hostel['name']) ?></h2>
    <p><strong>📍 Location:</strong> <?= htmlspecialchars($hostel['location']) ?></p>
    <p><strong>🛏️ Rooms:</strong> <?= htmlspecialchars($hostel['rooms']) ?></p>
    <p><strong>💰 Price:</strong> ₹<?= htmlspecialchars($hostel['price_per_night']) ?>/night</p>
    <p><strong>📄 Description:</strong><br><?= nl2br(htmlspecialchars($hostel['description'])) ?></p>
    <br>
    <a href="book.php?id=<?= $hostel['id'] ?>" class="btn">Book Now</a>
   
</div>

</body>
</html>
