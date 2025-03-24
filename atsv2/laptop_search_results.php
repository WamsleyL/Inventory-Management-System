<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Map manufacturer and grade for friendly display
$man_lookup = [];
$grade_lookup = [];
$status_lookup = [];

$mans = $conn->query("SELECT manufacturer_id, name FROM manufacturer");
while ($row = $mans->fetch_assoc()) $man_lookup[$row['manufacturer_id']] = $row['name'];

$grades = $conn->query("SELECT grade_id, grade_value FROM grade");
while ($row = $grades->fetch_assoc()) $grade_lookup[$row['grade_id']] = $row['grade_value'];

$statuses = $conn->query("SELECT StatusID, StatusName FROM status");
while ($row = $statuses->fetch_assoc()) $status_lookup[$row['StatusID']] = $row['StatusName'];

// Build dynamic WHERE clause
$conditions = [];
$params = [];
$types = "";

$fields = [
    'Model', 'Processor', 'RAM', 'StorageType', 'StorageSize',
    'GPUType', 'GraphicsCard', 'OperatingSystem', 'ScreenSize',
    'ScreenResolution', 'manufacturer_id', 'grade_id'
];

foreach ($fields as $field) {
    if (!empty($_GET[$field])) {
        if (in_array($field, ['manufacturer_id', 'grade_id'])) {
            $conditions[] = "l.$field = ?";
            $types .= "i";
            $params[] = $_GET[$field];
        } else {
            $conditions[] = "l.$field LIKE ?";
            $types .= "s";
            $params[] = "%" . $_GET[$field] . "%";
        }
    }
}

// Optional price filters
if (!empty($_GET['min_price'])) {
    $conditions[] = "i.Price >= ?";
    $types .= "d";
    $params[] = $_GET['min_price'];
}
if (!empty($_GET['max_price'])) {
    $conditions[] = "i.Price <= ?";
    $types .= "d";
    $params[] = $_GET['max_price'];
}

// Final query with JOIN to itemledger
$sql = "
    SELECT l.*, i.Price, i.StatusID 
    FROM laptopitem l
    LEFT JOIN itemledger i ON l.LaptopID = i.LaptopID
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
    <title>Laptop Search Results</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>Search Results</h2>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>LaptopID</th>
                    <th>Model</th>
                    <th>Processor</th>
                    <th>RAM</th>
                    <th>Storage</th>
                    <th>GPU</th>
                    <th>Graphics Card</th>
                    <th>OS</th>
                    <th>Screen</th>
                    <th>Resolution</th>
                    <th>Manufacturer</th>
                    <th>Grade</th>
                    <th>Price</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['LaptopID'] ?></td>
                    <td><?= htmlspecialchars($row['Model']) ?></td>
                    <td><?= htmlspecialchars($row['Processor']) ?></td>
                    <td><?= htmlspecialchars($row['RAM']) ?></td>
                    <td><?= htmlspecialchars($row['StorageType'] . ' ' . $row['StorageSize']) ?></td>
                    <td><?= htmlspecialchars($row['GPUType']) ?></td>
                    <td><?= htmlspecialchars($row['GraphicsCard']) ?></td>
                    <td><?= htmlspecialchars($row['OperatingSystem']) ?></td>
                    <td><?= htmlspecialchars($row['ScreenSize']) ?></td>
                    <td><?= htmlspecialchars($row['ScreenResolution']) ?></td>
                    <td><?= htmlspecialchars($man_lookup[$row['manufacturer_id']] ?? 'Unknown') ?></td>
                    <td><?= htmlspecialchars($grade_lookup[$row['grade_id']] ?? 'Unknown') ?></td>
                    <td><?= htmlspecialchars($row['Price']) ?></td>
                    <td><?= htmlspecialchars($status_lookup[$row['StatusID']] ?? 'Unknown') ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <form action="atsv2_index.php" method="get" style="margin-top: 20px;">
                <button type="submit">Back to Dashboard</button>
                </form>
</div>
</body>
</html>
