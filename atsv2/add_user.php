<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['Username']);
    $email = trim($_POST['Email']);
    $password = $_POST['Password'];
    $role = $_POST['Role'];
    $status = $_POST['Status'];

    // Basic validation
    if (empty($username) || empty($email) || empty($password)) {
        die("All fields are required.");
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (Username, Email, PasswordHash, Role, Status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $hashedPassword, $role, $status);

    if ($stmt->execute()) {
        echo "<div class='container'>";
        echo "<h2>User successfully added</h2>";
        echo "<p><strong>Username:</strong> " . htmlspecialchars($username) . "</p>";
        echo "<p><a href='add_user_form.html'>Add Another User</a></p>";
        echo "</div>";
    } else {
        echo "<div class='container'><h2>Error:</h2><p>" . $stmt->error . "</p></div>";
    }

    $stmt->close();
}

$conn->close();
?>
