<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$first_name = $_POST['first_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$email = $_POST['email'] ?? null;
$phone_number = $_POST['phone_number'] ?? null;

// Validate required fields
if (empty($first_name) || empty($last_name)) {
    die("First and Last Name are required.");
}

// Prepare SQL insert
$stmt = $conn->prepare("INSERT INTO buyer (first_name, last_name, email, phone_number) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $first_name, $last_name, $email, $phone_number);

// Execute and check
if ($stmt->execute()) {
    echo "<div class='container'>";
    echo "<h2>Buyer successfully added</h2>";
    echo "<p><strong>ID:</strong> " . $stmt->insert_id . "</p>";
    echo "<p><a href='add_buyer.html'>Add another buyer</a></p>";
    echo "</div>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
