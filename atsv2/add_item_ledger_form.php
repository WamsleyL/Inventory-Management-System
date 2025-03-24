<?php
$conn = new mysqli("localhost", "root", "", "atsv2");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
 
function getOptions($conn, $table, $id_col, $label_col) {
    $options = "";
    $result = $conn->query("SELECT $id_col, $label_col FROM $table ORDER BY $label_col ASC");
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='{$row[$id_col]}'>{$row[$label_col]}</option>";
    }
    return $options;
}
 
$sourceOptions = getOptions($conn, "source", "SourceID", "SourceName");
$statusOptions = getOptions($conn, "status", "StatusID", "StatusName");
$conn->close();
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Item to Ledger</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
<h2>Add Item to Ledger</h2>
<form method="POST" action="add_item_ledger.php">
<label for="item_type">Item Type:</label>
<select name="item_type" id="item_type" required>
<option value="laptop">Laptop</option>
<option value="common">Common Item</option>
<option value="monitor">Monitor</option>
</select>
 
        <label for="date_received">Date Received:</label>
<input type="date" name="date_received" id="date_received" required>
 
        <label for="source_id">Source:</label>
<select name="source_id" id="source_id" required>
<?= $sourceOptions ?>
</select>
 
        <label for="status_id">Status:</label>
<select name="status_id" id="status_id" required>
<?= $statusOptions ?>
</select>
 
        <button type="submit">Add Item</button>
</form>
 
    <form action="atsv2_index.php" method="get" style="margin-top: 20px;">
<button type="submit">Back to Dashboard</button>
</form>
</div>
</body>
</html>