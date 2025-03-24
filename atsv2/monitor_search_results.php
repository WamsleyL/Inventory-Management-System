<?php

$servername = "localhost";

$username = "root";

$password = "";

$dbname = "atsv2";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
 
// Lookup tables

$man_lookup = [];

$grade_lookup = [];

$status_lookup = [];
 
$mans = $conn->query("SELECT manufacturer_id, name FROM manufacturer");

while ($row = $mans->fetch_assoc()) $man_lookup[$row['manufacturer_id']] = $row['name'];
 
$grades = $conn->query("SELECT grade_id, grade_value FROM grade");

while ($row = $grades->fetch_assoc()) $grade_lookup[$row['grade_id']] = $row['grade_value'];
 
$statuses = $conn->query("SELECT StatusID, StatusName FROM status");

while ($row = $statuses->fetch_assoc()) $status_lookup[$row['StatusID']] = $row['StatusName'];
 
// Build WHERE clause

$conditions = [];

$params = [];

$types = "";
 
$fields = [

    'Model', 'ScreenSize', 'Resolution', 'MonitorDesc', 'MonitorDescofCond', 'manufacturer_id', 'grade_id'

];
 
foreach ($fields as $field) {

    if (!empty($_GET[$field])) {

        if (in_array($field, ['manufacturer_id', 'grade_id'])) {

            $conditions[] = "m.$field = ?";

            $types .= "i";

            $params[] = $_GET[$field];

        } else {

            $conditions[] = "m.$field LIKE ?";

            $types .= "s";

            $params[] = "%" . $_GET[$field] . "%";

        }

    }

}
 
// Optional price filters

if (!empty($_GET['min_price'])) {

    $conditions[] = "l.Price >= ?";

    $types .= "d";

    $params[] = $_GET['min_price'];

}

if (!empty($_GET['max_price'])) {

    $conditions[] = "l.Price <= ?";

    $types .= "d";

    $params[] = $_GET['max_price'];

}
 
// Final SQL with JOIN to itemledger

$sql = "

    SELECT m.*, l.Price, l.StatusID 

    FROM monitoritem m

    LEFT JOIN itemledger l ON m.MonitorID = l.MonitorID

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

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Monitor Search Results</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
<h2>Search Results</h2>
<div class="table-container">
<table>
<thead>
<tr>
<th>MonitorID</th>
<th>Model</th>
<th>Screen Size</th>
<th>Resolution</th>
<th>Description</th>
<th>Condition</th>
<th>Manufacturer</th>
<th>Grade</th>
<th>Price</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['MonitorID'] ?></td>
<td><?= htmlspecialchars($row['Model']) ?></td>
<td><?= htmlspecialchars($row['ScreenSize']) ?></td>
<td><?= htmlspecialchars($row['Resolution']) ?></td>
<td><?= htmlspecialchars($row['MonitorDesc']) ?></td>
<td><?= htmlspecialchars($row['MonitorDescofCond']) ?></td>
<td><?= htmlspecialchars($man_lookup[$row['manufacturer_id']] ?? 'Unknown') ?></td>
<td><?= htmlspecialchars($grade_lookup[$row['grade_id']] ?? 'Unknown') ?></td>
<td><?= htmlspecialchars($row['Price']) ?></td>
<td><?= htmlspecialchars($status_lookup[$row['StatusID']] ?? 'Unknown') ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
 
        <br>
<button onclick="window.location.href='monitor_search_form.php'">Search Again</button>
</div>
</div>
</body>
</html>

 