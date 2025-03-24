<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
 
// Build dynamic WHERE clause
$conditions = [];
$params = [];
$types = "";
 
if (!empty($_GET['BuyerName'])) {
    $conditions[] = "(b.first_name LIKE ? OR b.last_name LIKE ?)";
    $types .= "ss";
    $nameParam = "%" . $_GET['BuyerName'] . "%";
    $params[] = $nameParam;
    $params[] = $nameParam;
}
 
if (!empty($_GET['BuyerEmail'])) {
    $conditions[] = "b.email LIKE ?";
    $types .= "s";
    $params[] = "%" . $_GET['BuyerEmail'] . "%";
}
 
if (!empty($_GET['SalesPersonID'])) {
    $conditions[] = "s.SalesPersonID = ?";
    $types .= "i";
    $params[] = $_GET['SalesPersonID'];
}
 
if (!empty($_GET['LedgerID'])) {
    $conditions[] = "s.LedgerID = ?";
    $types .= "i";
    $params[] = $_GET['LedgerID'];
}
 
if (!empty($_GET['start_date'])) {
    $conditions[] = "s.SaleDate >= ?";
    $types .= "s";
    $params[] = $_GET['start_date'];
}
 
if (!empty($_GET['end_date'])) {
    $conditions[] = "s.SaleDate <= ?";
    $types .= "s";
    $params[] = $_GET['end_date'];
}
 
if (!empty($_GET['min_price'])) {
    $conditions[] = "s.SalePrice >= ?";
    $types .= "d";
    $params[] = $_GET['min_price'];
}
 
if (!empty($_GET['max_price'])) {
    $conditions[] = "s.SalePrice <= ?";
    $types .= "d";
    $params[] = $_GET['max_price'];
}
 
// SQL with joins
$sql = "
    SELECT s.SaleID, s.LedgerID, s.SaleDate, s.SalePrice, 
           sp.SalesPersonName, b.first_name, b.last_name, b.email AS BuyerEmail
    FROM salesrecord s
    LEFT JOIN salesperson sp ON s.SalesPersonID = sp.SalesPersonID
    LEFT JOIN buyer b ON s.buyer_id = b.buyer_id
";
 
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}
 
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
 
// Sum total sales
$total = 0;
foreach ($result as $r) $total += $r['SalePrice'];
$result->data_seek(0); // rewind result for display
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sales Search Results</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
<h2>Sales Search Results</h2>
<div class="table-container">
<table>
<thead>
<tr>
<th>Sale ID</th>
<th>Ledger ID</th>
<th>Buyer Name</th>
<th>Buyer Email</th>
<th>Salesperson</th>
<th>Sale Date</th>
<th>Sale Price</th>
</tr>
</thead>
<tbody>
<?php if ($result->num_rows > 0): ?>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['SaleID'] ?></td>
<td><?= $row['LedgerID'] ?></td>
<td><?= htmlspecialchars(trim($row['first_name'] . ' ' . $row['last_name'])) ?></td>
<td><?= htmlspecialchars($row['BuyerEmail']) ?></td>
<td><?= htmlspecialchars($row['SalesPersonName'] ?? 'N/A') ?></td>
<td><?= htmlspecialchars($row['SaleDate']) ?></td>
<td>$<?= number_format($row['SalePrice'], 2) ?></td>
</tr>
<?php endwhile; ?>
<!-- Total Row -->
<tr style="font-weight: bold; background-color: #f2f2f2;">
<td colspan="6" style="text-align: right;">Total Sales:</td>
<td>$<?= number_format($total, 2) ?></td>
</tr>
<?php else: ?>
<tr><td colspan="7">No results found.</td></tr>
<?php endif; ?>
</tbody>
</table>
<br>
<button onclick="window.location.href='sales_search_form.php'">Search Again</button>
</div>
</div>
</body>
</html>