<?php
// Database connection (use your actual XAMPP credentials)
$servername = "localhost";
$username = "root";
$password = ""; // Default for XAMPP
$dbname = "atsv2";

// Get the POST data
$sourceName = $_POST['sourceName'] ?? '';

// Validate input
if (empty($sourceName)) {
    die("Source name cannot be empty.");
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare insert statement
$stmt = $conn->prepare("INSERT INTO source (SourceName) VALUES (?)");
$stmt->bind_param("s", $sourceName);

// Execute and check for errors
if ($stmt->execute()) {
    echo "<p>Source added successfully. <a href='add_source.html'>Add another</a></p>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
