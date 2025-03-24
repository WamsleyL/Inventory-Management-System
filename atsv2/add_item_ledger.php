<?php
session_start();
 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2"; // Fixed DB name
 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_type = $_POST['item_type'];
    $date_received = $_POST['date_received'];
    $source_id = $_POST['source_id'];
    $status_id = $_POST['status_id'];
 
    $item_id = null;
 
    // Insert a placeholder item into the appropriate table
    if ($item_type == 'laptop') {
        $sql = "INSERT INTO laptopitem (Model) VALUES ('Unknown')";
    } elseif ($item_type == 'common') {
        $sql = "INSERT INTO commonitem (CommonModel) VALUES ('Unknown')";
    } elseif ($item_type == 'monitor') {
        $sql = "INSERT INTO monitoritem (Model) VALUES ('Unknown')";
    } else {
        die("Invalid item type.");
    }
 
    if ($conn->query($sql) === TRUE) {
        $item_id = $conn->insert_id;
    } else {
        die("Error creating item: " . $conn->error);
    }
 
    // Prepare insert into itemledger
    $sql = "INSERT INTO itemledger (CommonItemID, LaptopID, MonitorID, DateReceived, StatusID, SourceID)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
 
    $common_item_id = ($item_type == 'common') ? $item_id : null;
    $laptop_id = ($item_type == 'laptop') ? $item_id : null;
    $monitor_id = ($item_type == 'monitor') ? $item_id : null;
 
    $stmt->bind_param("iiisii", $common_item_id, $laptop_id, $monitor_id, $date_received, $status_id, $source_id);
 
    if ($stmt->execute()) {
        $ledger_id = $stmt->insert_id;
        echo "<div class='container'>";
        echo "<h2>Item successfully added to the ledger</h2>";
        echo "<p><strong>Ledger ID:</strong> " . $ledger_id . "</p>";
 
        // Generate barcode string and insert into barcode table
        $barcode_data = "LEDGER-" . $ledger_id;
        $barcode_format = "Code128";
 
        $barcode_stmt = $conn->prepare("INSERT INTO barcode (LedgerID, BarcodeData, BarcodeFormat) VALUES (?, ?, ?)");
        $barcode_stmt->bind_param("iss", $ledger_id, $barcode_data, $barcode_format);
        $barcode_stmt->execute();
        $barcode_stmt->close();
 
        // Barcode preview
        $barcode_url = "https://barcode.tec-it.com/barcode.ashx?data=" . urlencode($barcode_data) . "&code=Code128";
        echo "<p><strong>Barcode Preview:</strong><br><img src='{$barcode_url}' alt='Barcode'></p>";
        echo "<p><a href='add_item_ledger_form.php'>Add another item</a></p>";
        echo "</div>";
    } else {
        echo "Error inserting into ledger: " . $stmt->error;
    }
 
    $stmt->close();
}
 
$conn->close();
?>