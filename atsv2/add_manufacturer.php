<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$manufacturer_name = trim($_POST['manufacturer_name'] ?? '');

// Validate input
if (empty($manufacturer_name)) {
    die("Manufacturer name is required.");
}

// Check for duplicate (optional but helpful)
$check = $conn->prepare("SELECT manufacturer_id FROM manufacturer WHERE name = ?");
$check->bind_param("s", $manufacturer_name);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "<div class='container'>";
    echo "<h2>Manufacturer already exists!</h2>";
    echo "<p><a href='add_manufacturer.html'>Try again</a></p>";
    echo "</div>";
    $check->close();
    $conn->close();
    exit;
}
$check->close();

// Insert new manufacturer
$stmt = $conn->prepare("INSERT INTO manufacturer (name) VALUES (?)");
$stmt->bind_param("s", $manufacturer_name);

if ($stmt->execute()) {
    echo "<div class='container'>";
    echo "<h2>Manufacturer successfully added</h2>";
    echo "<p><strong>ID:</strong> " . $stmt->insert_id . "</p>";
    echo "<p><a href='add_manufacturer.html'>Add another</a></p>";
    echo "</div>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
