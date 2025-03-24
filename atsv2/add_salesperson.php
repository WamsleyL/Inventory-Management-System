<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$salesperson_name = $_POST['salesperson_name'] ?? '';

// Validate input
if (empty($salesperson_name)) {
    die("Salesperson name is required.");
}

// Insert into table
$stmt = $conn->prepare("INSERT INTO salesperson (SalesPersonName) VALUES (?)");
$stmt->bind_param("s", $salesperson_name);

if ($stmt->execute()) {
    echo "<div class='container'>";
    echo "<h2>Salesperson successfully added</h2>";
    echo "<p><strong>ID:</strong> " . $stmt->insert_id . "</p>";
    echo "<p><a href='add_salesperson.html'>Add another</a></p>";
    echo "</div>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
