<?php
session_start();
include("../db.php");
include("../includes/header.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "<p>Hostel not found.</p>";
    exit();
}

$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM hostels WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$hostel = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = $_POST['name'];
    $location = $_POST['location'];
    $rooms    = $_POST['rooms'];
    $price    = $_POST['price'];
    $desc     = $_POST['description'];

    $image = $hostel['image']; // default: keep old image

    if (!empty($_FILES['image']['name'])) {
        $img_name = uniqid() . "_" . basename($_FILES['image']['name']);
        $img_path = "../assets/images/" . $img_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $img_path);
        $image = $img_name; // update with new image
    }

    $stmt = $conn->prepare("UPDATE hostels SET name=?, location=?, rooms=?, price_per_night=?, description=?, image=? WHERE id=?");
    $stmt->bind_param("ssisssi", $name, $location, $rooms, $price, $desc, $image, $id);
    $stmt->execute();

    echo "<script>alert('Hostel updated successfully.'); window.location.href='manage_hostels.php';</script>";
    exit();
}
?>

<h2 style="text-align:center;">✏️ Edit Hostel Details</h2>
<form method="POST" enctype="multipart/form-data" style="width:60%; margin:auto;">
    <label>Name:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($hostel['name']) ?>" required><br><br>

    <label>Location:</label><br>
    <input type="text" name="location" value="<?= htmlspecialchars($hostel['location']) ?>" required><br><br>

    <label>Rooms:</label><br>
    <input type="number" name="rooms" value="<?= $hostel['rooms'] ?>" required><br><br>

    <label>Price per Night:</label><br>
    <input type="number" name="price" value="<?= $hostel['price_per_night'] ?>" required><br><br>

    <label>Description:</label><br>
    <textarea name="description" rows="5" required><?= htmlspecialchars($hostel['description']) ?></textarea><br><br>

    <label>Current Image:</label><br>
    <img src="../assets/images/<?= htmlspecialchars($hostel['image']) ?>" width="200" style="margin-bottom:10px;"><br><br>

    <label>Upload New Image (optional):</label><br>
    <input type="file" name="image"><br><br>

    <button type="submit" style="padding:10px 20px;">💾 Update Hostel</button>
</form>

<?php include("../includes/footer.php"); ?>
