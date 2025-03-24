<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    die("Access denied. Admins only.");
}

$conn = new mysqli("localhost", "root", "", "atsv2");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userID = $_POST['UserID'];
    $role = $_POST['Role'];
    $status = $_POST['Status'];

    $stmt = $conn->prepare("UPDATE users SET Role = ?, Status = ? WHERE UserID = ?");
    $stmt->bind_param("ssi", $role, $status, $userID);

    if ($stmt->execute()) {
        echo "<div class='container'><h2>User Updated</h2><p>User role and status updated successfully.</p><a href='edit_user_role_form.php'>Edit Another</a></div>";
    } else {
        echo "<div class='container'><h2>Error</h2><p>" . $stmt->error . "</p></div>";
    }

    $stmt->close();
}

$conn->close();
?>
