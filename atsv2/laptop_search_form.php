<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Dropdowns
function getOptions($conn, $table, $id_col, $label_col) {
    $options = "<option value=''>-- Any --</option>";
    $result = $conn->query("SELECT $id_col, $label_col FROM $table ORDER BY $label_col ASC");
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='{$row[$id_col]}'>{$row[$label_col]}</option>";
    }
    return $options;
}

$manufacturerOptions = getOptions($conn, 'manufacturer', 'manufacturer_id', 'name');
$gradeOptions = getOptions($conn, 'grade', 'grade_id', 'grade_value');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Laptops</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>Search Laptops</h2>
    <div class="scrollable-content">
        <form action="laptop_search_results.php" method="GET">
            <label>Model:</label>
            <input type="text" name="Model">

            <label>Processor:</label>
            <input type="text" name="Processor">

            <label>RAM:</label>
            <input type="text" name="RAM">

            <label>Storage Type:</label>
            <input type="text" name="StorageType">

            <label>Storage Size:</label>
            <input type="text" name="StorageSize">

            <label>GPU Type:</label>
            <input type="text" name="GPUType">

            <label>Graphics Card:</label>
            <input type="text" name="GraphicsCard">

            <label>Operating System:</label>
            <input type="text" name="OperatingSystem">

            <label>Screen Size:</label>
            <input type="text" name="ScreenSize">

            <label>Screen Resolution:</label>
            <input type="text" name="ScreenResolution">

            <label>Manufacturer:</label>
            <select name="manufacturer_id"><?= $manufacturerOptions ?></select>

            <label>Grade:</label>
            <select name="grade_id"><?= $gradeOptions ?></select>

            <label>Minimum Price:</label>
            <input type="number" name="min_price" step="0.01" min="0">

            <label>Maximum Price:</label>
            <input type="number" name="max_price" step="0.01" min="0">

            <button type="submit">Search</button>
        </form>
    </div>
    <form action="atsv2_index.php" method="get" style="margin-top: 20px;">
<button type="submit">Back to Dashboard</button>
</form>
</div>
</body>
</html>
