<?php
session_start();
include("../db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$hostels = $conn->query("SELECT * FROM hostels WHERE rooms > 0 ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Retro Travel Dashboard</title>
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
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
            padding: 40px 20px;
            width: 95%;
            margin: 0 auto;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-family: 'Georgia', serif;
            font-size: 28px;
            color: #4b3b2f;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 25px;
        }

        .card {
            background: #fff7e6;
            border-radius: 10px;
            overflow: hidden;
            border: 2px dashed #d1a370;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-6px);
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .card-content {
            padding: 12px 15px;
        }

        .card h3 {
            margin: 0;
            font-size: 18px;
            color: #40332a;
        }

        .card p {
            margin: 5px 0;
            color: #665040;
        }

        .card a {
            display: inline-block;
            margin-top: 10px;
            padding: 6px 10px;
            background-color: #c2876d;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
        }

        .card a:hover {
            background-color: #a76b56;
        }
    </style>
</head>
<body>

<!-- 🧭 Navbar -->
<div class="navbar">
    <div><strong>🌍 StayCentral Hostels</strong></div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="my_bookings.php">My Bookings</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<!-- 🧳 Main Container -->
<div class="container">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?>! ✈️</h2>
    <p style="text-align:center; font-family:'Courier New'; font-size:14px;">Find your next stay like a true explorer!</p>

    <div class="grid-container">
        <?php while ($row = $hostels->fetch_assoc()): ?>
        <div class="card">
            <img src="../assets/images/<?= htmlspecialchars($row['image']) ?>" alt="Hostel Image">
            <div class="card-content">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <p>📍 <?= htmlspecialchars($row['location']) ?></p>
                <p>💰 ₹<?= htmlspecialchars($row['price_per_night']) ?>/night</p>
                <a href="view_hostel.php?id=<?= $row['id'] ?>">View Details</a>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>
