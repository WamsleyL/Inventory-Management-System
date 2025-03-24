<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Gather inputs
$LedgerID = $_POST['LedgerID'] ?? null;
$SaleDate = $_POST['SaleDate'] ?? null;
$SalePrice = $_POST['SalePrice'] ?? null;
$SalesPersonID = $_POST['SalesPersonID'] ?? null;
$buyer_id = $_POST['buyer_id'] ?? null;
$BuyerName = $_POST['BuyerName'] ?? null;

if (!$LedgerID || !$SaleDate || !$SalePrice) {
    die("Ledger ID, Sale Date, and Sale Price are required.");
}

$stmt = $conn->prepare("
    INSERT INTO salesrecord (LedgerID, SaleDate, SalePrice, SalesPersonID, buyer_id, BuyerName)
    VALUES (?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("isdiss", 
    $LedgerID,
    $SaleDate,
    $SalePrice,
    $SalesPersonID,
    $buyer_id,
    $BuyerName
);

if ($stmt->execute()) {
    echo "<div class='container'>";
    echo "<h2>Sale Recorded Successfully</h2>";
    echo "<p>Sale ID: " . $stmt->insert_id . "</p>";
    echo "<a href='add_sale_form.php'>Record Another Sale</a>";
    echo "</div>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
