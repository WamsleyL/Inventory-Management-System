<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Load dropdown options
function getSalespersonOptions($conn) {
    $options = "<option value=''>-- Any --</option>";
    $result = $conn->query("SELECT SalesPersonID, SalesPersonName FROM salesperson ORDER BY SalesPersonName ASC");
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='{$row['SalesPersonID']}'>{$row['SalesPersonName']}</option>";
    }
    return $options;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Sales Records</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>Search Sales</h2>
    <div class="scrollable-content">
        <form action="sales_search_results.php" method="GET">
            <label>Buyer Name (partial match):</label>
            <input type="text" name="BuyerName">

            <label>Buyer Email:</label>
            <input type="text" name="BuyerEmail">

            <label>Salesperson:</label>
            <select name="SalesPersonID"><?= getSalespersonOptions($conn) ?></select>

            <label>Ledger ID (optional):</label>
            <input type="number" name="LedgerID">

            <label>Sale Date From:</label>
            <input type="date" name="start_date">

            <label>Sale Date To:</label>
            <input type="date" name="end_date">

            <label>Minimum Price:</label>
            <input type="number" step="0.01" name="min_price">

            <label>Maximum Price:</label>
            <input type="number" step="0.01" name="max_price">

            <button type="submit">Search</button>
        </form>
    </div>
    <form action="atsv2_index.php" method="get" style="margin-top: 20px;">
<button type="submit">Back to Dashboard</button>
</form>
</div>
</body>
</html>
