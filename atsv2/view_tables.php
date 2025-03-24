<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Define allowed tables and user-friendly labels
$tables = [
    'laptopitem' => 'Laptops',
    'monitoritem' => 'Monitors',
    'commonitem' => 'Common Items',
    'itemledger' => 'Inventory Ledger',
    'salesrecord' => 'Sales Records',
    'buyer' => 'Buyers',
    'salesperson' => 'Salespeople',
    'manufacturer' => 'Manufacturers',
    'grade' => 'Grades',
    'category' => 'Categories',
    'source' => 'Sources',
    'status' => 'Statuses',
    'barcode' => 'Barcodes',
    'users' => 'Users'
];

$selected = $_GET['table'] ?? '';
$data = [];
$columns = [];

if (in_array($selected, array_keys($tables))) {
    $result = $conn->query("SELECT * FROM `$selected`");
    if ($result && $result->num_rows > 0) {
        $columns = array_keys($result->fetch_assoc());
        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Database Tables</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>View Table Contents</h2>
    <form method="GET">
        <label for="table">Select a Table:</label>
        <select name="table" id="table" required onchange="this.form.submit()">
            <option value="">-- Select Table --</option>
            <?php foreach ($tables as $key => $label): ?>
                <option value="<?= $key ?>" <?= $key == $selected ? 'selected' : '' ?>>
                    <?= $label ?>
                </option>
            <?php endforeach; ?>
        </select>
        <noscript><button type="submit">View</button></noscript>
    </form>

    <?php if (!empty($selected)): ?>
        <h3>Showing: <?= $tables[$selected] ?></h3>
        <?php if (!empty($data)): ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <?php foreach ($columns as $col): ?>
                                <th><?= htmlspecialchars($col) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $row): ?>
                            <tr>
                                <?php foreach ($columns as $col): ?>
                                    <td><?= htmlspecialchars($row[$col]) ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No records found for this table.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>
<form action="atsv2_index.php" method="get" style="margin-top: 20px;">
<button type="submit">Back to Dashboard</button>
</form>
</body>
</html>
