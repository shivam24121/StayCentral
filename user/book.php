<?php
session_start();
include("../db.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "No hostel selected.";
    exit();
}

$hostel_id = intval($_GET['id']);

// Fetch hostel
$stmt = $conn->prepare("SELECT * FROM hostels WHERE id = ?");
$stmt->bind_param("i", $hostel_id);
$stmt->execute();
$hostel = $stmt->get_result()->fetch_assoc();

if (!$hostel) {
    echo "Hostel not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $checkin = $_POST['check_in'];
    $checkout = $_POST['check_out'];
    $guests = intval($_POST['guests']);

    $date1 = new DateTime($checkin);
    $date2 = new DateTime($checkout);
    $interval = $date1->diff($date2)->days;

    if ($interval <= 0) {
        $error = "Check-out date must be after check-in.";
    } else {
        $price = $hostel['price_per_night'];
        $total = $interval * $price;

        $stmt = $conn->prepare("INSERT INTO bookings (user_id, hostel_id, check_in, check_out, guests, total_price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissii", $_SESSION['user']['id'], $hostel_id, $checkin, $checkout, $guests, $total);
        $stmt->execute();

        header("Location: my_bookings.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Hostel</title>
    <style>
        body {
            background: linear-gradient(135deg, #f7e8c9, #f1d1b5);
            font-family: 'Calibri', sans-serif;
            margin: 0;
            padding: 0;
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
            margin-left: 20px;
            font-weight: bold;
        }

        .container {
            max-width: 500px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff7e6;
            border: 2px dashed #c2876d;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #4b3b2f;
        }

        form {
            margin-top: 20px;
        }

        input, select {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #d1a370;
            border-radius: 6px;
            font-size: 15px;
        }

        .btn {
            background-color: #c2876d;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #a76b56;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div><strong>🌍 Hostel Booking</strong></div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="my_bookings.php">My Bookings</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>🛏️ Book <?= htmlspecialchars($hostel['name']) ?></h2>

    <?php if (isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Check-in Date:</label>
        <input type="date" name="check_in" required>

        <label>Check-out Date:</label>
        <input type="date" name="check_out" required>

        <label>Number of Guests:</label>
        <input type="number" name="guests" min="1" max="10" required>

        <input type="submit" value="Confirm Booking" class="btn">
    </form>
</div>

</body>
</html>
