<?php
session_start();
include("includes/header.php");

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];

if ($user['role'] === 'admin') {
    header("Location: admin/dashboard.php");
    exit();
} elseif ($user['role'] === 'user') {
    header("Location: user/dashboard.php");
    exit();
}
?>

<!-- Just in case redirect fails -->
<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
    <p>You are logged in as <strong><?php echo $user['role']; ?></strong></p>
    <a href="logout.php">Logout</a>
</div>

<?php include("includes/footer.php"); ?>
<a href="admin_login.php">🔐 Admin Login</a>
