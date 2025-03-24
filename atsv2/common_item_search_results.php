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
$cat_lookup = [];
$status_lookup = [];

$mans = $conn->query("SELECT manufacturer_id, name FROM manufacturer");
while ($row = $mans->fetch_assoc()) $man_lookup[$row['manufacturer_id']] = $row['name'];

$grades = $conn->query("SELECT grade_id, grade_value FROM grade");
while ($row = $grades->fetch_assoc()) $grade_lookup[$row['grade_id']] = $row['grade_value'];

$cats = $conn->query("SELECT CategoryID, CategoryName FROM category");
while ($row = $cats->fetch_assoc()) $cat_lookup[$row['CategoryID']] = $row['CategoryName'];

$statuses = $conn->query("SELECT StatusID, StatusName FROM status");
while ($row = $statuses->fetch_assoc()) $status_lookup[$row['StatusID']] = $row['StatusName'];

// Build query
$conditions = [];
$params = [];
$types = "";

$fields = [
    'CommonModel', 'CommonItDesc', 'CommonDescofCond', 'manufacturer_id', 'grade_id', 'CategoryID'
];

foreach ($fields as $field) {
    if (!empty($_GET[$field])) {
        if (in_array($field, ['manufacturer_id', 'grade_id', 'CategoryID'])) {
            $conditions[] = "c.$field = ?";
            $types .= "i";
            $params[] = $_GET[$field];
        } else {
            $conditions[] = "c.$field LIKE ?";
            $types .= "s";
            $params[] = "%" . $_GET[$field] . "%";
        }
    }
}

// Handle optional price filters
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

// Final SQL with join
$sql = "
    SELECT c.*, l.Price, l.StatusID 
    FROM commonitem c
    LEFT JOIN itemledger l ON c.CommonItemID = l.CommonItemID
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
    <title>Common Item Results</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>Search Results</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>CommonItemID</th>
                    <th>Model</th>
                    <th>Description</th>
                    <th>Condition</th>
                    <th>Manufacturer</th>
                    <th>Grade</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['CommonItemID'] ?></td>
                    <td><?= htmlspecialchars($row['CommonModel']) ?></td>
                    <td><?= htmlspecialchars($row['CommonItDesc']) ?></td>
                    <td><?= htmlspecialchars($row['CommonDescofCond']) ?></td>
                    <td><?= htmlspecialchars($man_lookup[$row['manufacturer_id']] ?? 'Unknown') ?></td>
                    <td><?= htmlspecialchars($grade_lookup[$row['grade_id']] ?? 'Unknown') ?></td>
                    <td><?= htmlspecialchars($cat_lookup[$row['CategoryID']] ?? 'Unknown') ?></td>
                    <td><?= htmlspecialchars($row['Price']) ?></td>
                    <td><?= htmlspecialchars($status_lookup[$row['StatusID']] ?? 'Unknown') ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>

        <br>
        <button onclick="window.location.href='common_item_search_form.php'">Search Again</button>
    </div>
</div>
</body>
</html>
