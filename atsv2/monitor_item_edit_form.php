<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$monitors = $conn->query("SELECT MonitorID, Model FROM monitoritem ORDER BY MonitorID ASC");

$selected_id = $_POST['monitor_id'] ?? '';
$monitor = null;
$ledger = null;

if (!empty($selected_id)) {
    $stmt = $conn->prepare("SELECT * FROM monitoritem WHERE MonitorID = ?");
    $stmt->bind_param("i", $selected_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $monitor = $result->fetch_assoc();

    $ledgerResult = $conn->query("SELECT Price, StatusID FROM itemledger WHERE MonitorID = $selected_id");
    $ledger = $ledgerResult->fetch_assoc();
}

function getOptions($conn, $table, $id_col, $label_col, $selected_id = null) {
    $options = "";
    $result = $conn->query("SELECT $id_col, $label_col FROM $table ORDER BY $label_col ASC");
    while ($row = $result->fetch_assoc()) {
        $selected = ($row[$id_col] == $selected_id) ? 'selected' : '';
        $options .= "<option value='{$row[$id_col]}' $selected>{$row[$label_col]}</option>";
    }
    return $options;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Monitor</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>Edit Monitor Item</h2>

    <!-- Step 1: Select Monitor -->
    <form method="POST">
        <label for="monitor_id">Select Monitor (by ID):</label>
        <select name="monitor_id" id="monitor_id" required>
            <option value="">-- Select Monitor --</option>
            <?php
            while ($row = $monitors->fetch_assoc()) {
                $id = $row['MonitorID'];
                $model = $row['Model'];
                $selected = ($id == $selected_id) ? 'selected' : '';
                echo "<option value='$id' $selected>[$id] $model</option>";
            }
            ?>
        </select>
        <button type="submit">Load Monitor</button>
    </form>

    <!-- Step 2: Edit Form -->
    <?php if ($monitor): ?>
    <div class="scrollable-content">
        <form method="POST" action="monitor_item_edit.php">
            <input type="hidden" name="monitor_id" value="<?= $monitor['MonitorID'] ?>">

            <label>Model:</label>
            <input type="text" name="Model" value="<?= htmlspecialchars($monitor['Model']) ?>" required>

            <label>Screen Size:</label>
            <input type="text" name="ScreenSize" value="<?= htmlspecialchars($monitor['ScreenSize']) ?>">

            <label>Resolution:</label>
            <input type="text" name="Resolution" value="<?= htmlspecialchars($monitor['Resolution']) ?>">

            <label>Description:</label>
            <textarea name="MonitorDesc"><?= htmlspecialchars($monitor['MonitorDesc']) ?></textarea>

            <label>Condition Description:</label>
            <input type="text" name="MonitorDescofCond" value="<?= htmlspecialchars($monitor['MonitorDescofCond']) ?>">

            <label>Manufacturer:</label>
            <select name="manufacturer_id" required>
                <?= getOptions($conn, 'manufacturer', 'manufacturer_id', 'name', $monitor['manufacturer_id']) ?>
            </select>

            <label>Grade:</label>
            <select name="grade_id" required>
                <?= getOptions($conn, 'grade', 'grade_id', 'grade_value', $monitor['grade_id']) ?>
            </select>

            <label>Price:</label>
            <input type="number" step="0.01" name="Price" value="<?= htmlspecialchars($ledger['Price'] ?? '') ?>" required>

            <label>Status:</label>
            <select name="StatusID" required>
                <?= getOptions($conn, 'status', 'StatusID', 'StatusName', $ledger['StatusID'] ?? null) ?>
            </select>

            <button type="submit">Save Changes</button>
        </form>
    </div>
    <?php endif; ?>
</div>
<form action="atsv2_index.php" method="get" style="margin-top: 20px;">
                <button type="submit">Back to Dashboard</button>
                </form>
</body>
</html>
