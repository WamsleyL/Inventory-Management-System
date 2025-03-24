<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all common items for dropdown
$common_items = $conn->query("SELECT CommonItemID, CommonModel FROM commonitem ORDER BY CommonItemID ASC");

// Check for selection
$selected_id = $_POST['common_item_id'] ?? '';
$common_item = null;

if (!empty($selected_id)) {
    $stmt = $conn->prepare("SELECT c.*, l.Price, l.StatusID 
                            FROM commonitem c 
                            LEFT JOIN itemledger l ON c.CommonItemID = l.CommonItemID 
                            WHERE c.CommonItemID = ?");
    $stmt->bind_param("i", $selected_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $common_item = $result->fetch_assoc();
}

// Reusable dropdown function
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
    <title>Edit Common Item</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>Edit Common Item</h2>

    <!-- STEP 1: Choose Item by ID -->
    <form method="POST">
        <label for="common_item_id">Select Common Item (by ID):</label>
        <select name="common_item_id" id="common_item_id" required>
            <option value="">-- Select Item --</option>
            <?php
            while ($row = $common_items->fetch_assoc()) {
                $id = $row['CommonItemID'];
                $model = $row['CommonModel'];
                $selected = ($id == $selected_id) ? 'selected' : '';
                echo "<option value='$id' $selected>[$id] $model</option>";
            }
            ?>
        </select>
        <button type="submit">Load Item</button>
    </form>

    <!-- STEP 2: Show Edit Form -->
    <?php if ($common_item): ?>
        <div class="scrollable-content">
            <form method="POST" action="common_item_edit.php">
                <input type="hidden" name="common_item_id" value="<?= $common_item['CommonItemID'] ?>">

                <label for="model">Model:</label>
                <input type="text" name="model" value="<?= htmlspecialchars($common_item['CommonModel']) ?>" required>

                <label for="description">Item Description:</label>
                <textarea name="description"><?= htmlspecialchars($common_item['CommonItDesc']) ?></textarea>

                <label for="condition">Condition Description:</label>
                <input type="text" name="condition" value="<?= htmlspecialchars($common_item['CommonDescofCond']) ?>">

                <label for="manufacturer">Manufacturer:</label>
                <select name="manufacturer" required>
                    <?= getOptions($conn, 'manufacturer', 'manufacturer_id', 'name', $common_item['manufacturer_id']) ?>
                </select>

                <label for="grade">Grade:</label>
                <select name="grade" required>
                    <?= getOptions($conn, 'grade', 'grade_id', 'grade_value', $common_item['grade_id']) ?>
                </select>

                <label for="category_id">Category:</label>
                <select name="category_id" required>
                    <?= getOptions($conn, 'category', 'CategoryID', 'CategoryName', $common_item['CategoryID']) ?>
                </select>

                <label for="price">Price:</label>
                <input type="number" step="0.01" name="price" value="<?= $common_item['Price'] ?>">

                <label for="status">Status:</label>
                <select name="status" required>
                    <?= getOptions($conn, 'status', 'StatusID', 'StatusName', $common_item['StatusID']) ?>
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

