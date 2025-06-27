<?php
session_start();
include("../db.php");
include("../includes/header.php");

if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "user") {
    header("Location: ../login.php");
    exit();
}

$user = $_SESSION["user"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $new_name = $_POST["name"];
    $new_password = $_POST["password"];

    $query = "UPDATE users SET name=?";
    $params = [$new_name];

    if (!empty($new_password)) {
        $query .= ", password=?";
        $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);
        $params[] = $new_password_hashed;
    }

    $query .= " WHERE id=?";
    $params[] = $user["id"];

    $stmt = $conn->prepare($query);
    if (count($params) === 3) {
        $stmt->bind_param("ssi", $params[0], $params[1], $params[2]);
    } else {
        $stmt->bind_param("si", $params[0], $params[1]);
    }
    $stmt->execute();

    echo "<div class='container'><p style='color:green;'>Profile updated successfully!</p></div>";
    $_SESSION["user"]["name"] = $new_name;
}
?>

<div class="container">
    <h2>My Profile</h2>
    <form method="POST">
        <input type="text" name="name" value="<?= $user['name']; ?>" required>
        <input type="password" name="password" placeholder="New Password (optional)">
        <button type="submit">Update Profile</button>
        <a href="dashboard.php">⬅️ Back to Dashboard</a>
    </form>
</div>

<?php include("../includes/footer.php"); ?>