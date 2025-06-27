<?php
session_start();
include("../db.php");
include("../includes/header.php");

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$users = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<div class="container">
    <h2>Registered Users</h2>
    <?php while ($row = $users->fetch_assoc()) { ?>
        <div style="background:#fff; padding:12px; margin:10px 0; border-radius:8px;">
            <p><strong>Name:</strong> <?= $row['name']; ?></p>
            <p><strong>Email:</strong> <?= $row['email']; ?></p>
            <p><strong>Role:</strong> <?= ucfirst($row['role']); ?></p>
        </div>
    <?php } ?>
</div>

<?php include("../includes/footer.php"); ?>
