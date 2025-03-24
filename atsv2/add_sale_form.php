<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Dropdown helpers
function getOptions($conn, $table, $id_col, $label_col) {
    $options = "<option value=''>-- Select --</option>";
    $result = $conn->query("SELECT $id_col, $label_col FROM $table ORDER BY $label_col ASC");
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='{$row[$id_col]}'>{$row[$label_col]}</option>";
    }
    return $options;
}

// Show ledger as ID + Model if available
$ledgerOptions = "<option value=''>-- Select --</option>";
$ledgerQuery = $conn->query("
    SELECT l.LedgerID, 
           COALESCE(lap.Model, com.CommonModel, mon.Model, 'Unknown') AS ItemName 
    FROM itemledger l
    LEFT JOIN laptopitem lap ON l.LaptopID = lap.LaptopID
    LEFT JOIN commonitem com ON l.CommonItemID = com.CommonItemID
    LEFT JOIN monitoritem mon ON l.MonitorID = mon.MonitorID
    ORDER BY l.LedgerID DESC
");
while ($row = $ledgerQuery->fetch_assoc()) {
    $ledgerOptions .= "<option value='{$row['LedgerID']}'>[{$row['LedgerID']}] {$row['ItemName']}</option>";
}

$salespersonOptions = getOptions($conn, 'salesperson', 'SalesPersonID', 'SalesPersonName');
$buyerOptions = getOptions($conn, 'buyer', 'buyer_id', "CONCAT(first_name, ' ', last_name)");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Sale</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>Record a Sale</h2>
    <form action="add_sale.php" method="POST">
        <label for="LedgerID">Item (by Ledger ID):</label>
        <select name="LedgerID" required><?= $ledgerOptions ?></select>

        <label for="SaleDate">Sale Date:</label>
        <input type="date" name="SaleDate" required>

        <label for="SalePrice">Sale Price:</label>
        <input type="number" name="SalePrice" step="0.01" required>

        <label for="SalesPersonID">Salesperson:</label>
        <select name="SalesPersonID"><?= $salespersonOptions ?></select>

        <label for="buyer_id">Buyer (optional):</label>
        <select name="buyer_id"><?= $buyerOptions ?></select>

        <label for="BuyerName">Buyer Name (if no ID):</label>
        <input type="text" name="BuyerName" placeholder="Name only if not selecting ID">

        <button type="submit">Add Sale</button>
    </form>
</div>
<form action="atsv2_index.php" method="get" style="margin-top: 20px;">
                <button type="submit">Back to Dashboard</button>
                </form>
</body>
</html>
