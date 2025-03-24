<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$laptops = $conn->query("SELECT LaptopID, Model FROM laptopitem ORDER BY LaptopID ASC");

$selected_id = $_POST['laptop_id'] ?? '';
$laptop = null;
$ledger = null;

if (!empty($selected_id)) {
    $stmt = $conn->prepare("SELECT * FROM laptopitem WHERE LaptopID = ?");
    $stmt->bind_param("i", $selected_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $laptop = $result->fetch_assoc();

    // Get Price and Status from itemledger
    $ledgerResult = $conn->query("SELECT Price, StatusID FROM itemledger WHERE LaptopID = $selected_id");
    $ledger = $ledgerResult->fetch_assoc();
}

// Reusable dropdown generator
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
    <title>Edit Laptop</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>Edit Laptop Item</h2>

    <!-- Step 1: Select Laptop -->
    <form method="POST">
        <label for="laptop_id">Select Laptop (by ID):</label>
        <select name="laptop_id" id="laptop_id" required>
            <option value="">-- Select Laptop --</option>
            <?php
            while ($row = $laptops->fetch_assoc()) {
                $id = $row['LaptopID'];
                $model = $row['Model'];
                $selected = ($id == $selected_id) ? 'selected' : '';
                echo "<option value='$id' $selected>[$id] $model</option>";
            }
            ?>
        </select>
        <button type="submit">Load Laptop</button>
    </form>

    <!-- Step 2: Edit Form -->
    <?php if ($laptop): ?>
    <div class="scrollable-content">
        <form method="POST" action="laptop_item_edit.php">
            <input type="hidden" name="laptop_id" value="<?= $laptop['LaptopID'] ?>">

            <label>Model:</label>
            <input type="text" name="Model" value="<?= htmlspecialchars($laptop['Model']) ?>" required>

            <label>Processor:</label>
            <input type="text" name="Processor" value="<?= htmlspecialchars($laptop['Processor']) ?>">

            <label>RAM:</label>
            <input type="text" name="RAM" value="<?= htmlspecialchars($laptop['RAM']) ?>">

            <label>Storage Type:</label>
            <input type="text" name="StorageType" value="<?= htmlspecialchars($laptop['StorageType']) ?>">

            <label>Storage Size:</label>
            <input type="text" name="StorageSize" value="<?= htmlspecialchars($laptop['StorageSize']) ?>">

            <label>GPU Type:</label>
            <input type="text" name="GPUType" value="<?= htmlspecialchars($laptop['GPUType']) ?>">

            <label>Graphics Card:</label>
            <input type="text" name="GraphicsCard" value="<?= htmlspecialchars($laptop['GraphicsCard']) ?>">

            <label>Operating System:</label>
            <input type="text" name="OperatingSystem" value="<?= htmlspecialchars($laptop['OperatingSystem']) ?>">

            <label>Screen Size:</label>
            <input type="text" name="ScreenSize" value="<?= htmlspecialchars($laptop['ScreenSize']) ?>">

            <label>Screen Resolution:</label>
            <input type="text" name="ScreenResolution" value="<?= htmlspecialchars($laptop['ScreenResolution']) ?>">

            <label>Laptop Description:</label>
            <textarea name="LaptopDesc"><?= htmlspecialchars($laptop['LaptopDesc']) ?></textarea>

            <label>Condition Description:</label>
            <input type="text" name="LaptopDescofCond" value="<?= htmlspecialchars($laptop['LaptopDescofCond']) ?>">

            <label>Warranty:</label>
            <textarea name="Warranty"><?= htmlspecialchars($laptop['Warranty']) ?></textarea>

            <label>Warranty Date:</label>
            <input type="date" name="WarrantyDate" value="<?= $laptop['WarrantyDate'] ?>">

            <label>Manufacturer:</label>
            <select name="manufacturer_id" required>
                <?= getOptions($conn, 'manufacturer', 'manufacturer_id', 'name', $laptop['manufacturer_id']) ?>
            </select>

            <label>Grade:</label>
            <select name="grade_id" required>
                <?= getOptions($conn, 'grade', 'grade_id', 'grade_value', $laptop['grade_id']) ?>
            </select>

            <label>Price:</label>
            <input type="number" step="0.01" name="Price" value="<?= htmlspecialchars($ledger['Price']) ?>" required>

            <label>Status:</label>
            <select name="StatusID" required>
                <?= getOptions($conn, 'status', 'StatusID', 'StatusName', $ledger['StatusID']) ?>
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
