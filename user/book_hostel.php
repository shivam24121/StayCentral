<?php
session_start();
include("../db.php");
include("../includes/header.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'user') {
    header("Location: ../login.php");
    exit();
}

$hostel_id = $_GET['id'];
$user_id = $_SESSION['user']['id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];

    $stmt = $conn->prepare("INSERT INTO bookings (user_id, hostel_id, checkin, checkout) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $hostel_id, $checkin, $checkout);
    $stmt->execute();

    echo "<div class='container'><p style='color:green;'>Booking successful!</p></div>";
}

$hostel = $conn->query("SELECT * FROM hostels WHERE id=$hostel_id")->fetch_assoc();
?>

<div class="container">
    <h2>Book: <?php echo $hostel['name']; ?></h2>
    <form method="POST">
        <label>Check-in Date:</label>
        <input type="date" name="checkin" required>
        <label>Check-out Date:</label>
        <input type="date" name="checkout" required>
        <button type="submit">Confirm Booking</button>
        <a href="dashboard.php">Back to Hostels</a>
    </form>
</div>

<?php include("../includes/footer.php"); ?>
