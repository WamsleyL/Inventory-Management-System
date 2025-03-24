<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$barcode = $_GET['barcode'] ?? '';
$data = null;
$message = '';

if (!empty($barcode)) {
    $stmt = $conn->prepare("SELECT LedgerID FROM barcode WHERE BarcodeData = ?");
    $stmt->bind_param("s", $barcode);
    $stmt->execute();
    $stmt->bind_result($ledger_id);
    if ($stmt->fetch()) {
        $stmt->close();

        $query = "
            SELECT l.*, 
                   s.StatusName, 
                   src.SourceName,
                   c.*,
                   lap.*,
                   mon.*,
                   man.name AS ManufacturerName,
                   g.grade_value AS GradeName,
                   cat.CategoryName
            FROM itemledger l
            LEFT JOIN status s ON l.StatusID = s.StatusID
            LEFT JOIN source src ON l.SourceID = src.SourceID
            LEFT JOIN commonitem c ON l.CommonItemID = c.CommonItemID
            LEFT JOIN laptopitem lap ON l.LaptopID = lap.LaptopID
            LEFT JOIN monitoritem mon ON l.MonitorID = mon.MonitorID
            LEFT JOIN manufacturer man ON man.manufacturer_id = COALESCE(c.manufacturer_id, lap.manufacturer_id, mon.manufacturer_id)
            LEFT JOIN grade g ON g.grade_id = COALESCE(c.grade_id, lap.grade_id, mon.grade_id)
            LEFT JOIN category cat ON c.CategoryID = cat.CategoryID
            WHERE l.LedgerID = ?
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $ledger_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
    } else {
        $message = "Barcode not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Barcode Lookup Result</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>Barcode Result</h2>
    <div class="scrollable-content">
        <?php if ($data): ?>
            <p><strong>Ledger ID:</strong> <?= $ledger_id ?></p>
            <p><strong>Date Received:</strong> <?= $data['DateReceived'] ?></p>
            <p><strong>Status:</strong> <?= $data['StatusName'] ?></p>
            <p><strong>Source:</strong> <?= $data['SourceName'] ?></p>
            <p><strong>Price:</strong> $<?= number_format($data['Price'], 2) ?></p>

            <hr>
            <?php if ($data['LaptopID']): ?>
                <h3>Laptop Details</h3>
                <?php foreach ([
                    'Model', 'Processor', 'RAM', 'StorageType', 'StorageSize', 'GPUType',
                    'GraphicsCard', 'OperatingSystem', 'ScreenSize', 'ScreenResolution',
                    'LaptopDesc', 'LaptopDescofCond', 'Warranty', 'WarrantyDate'
                ] as $field): ?>
                    <?php if (!empty($data[$field])): ?>
                        <p><strong><?= $field ?>:</strong> <?= htmlspecialchars($data[$field]) ?></p>
                    <?php endif; ?>
                <?php endforeach; ?>
                <p><strong>Manufacturer:</strong> <?= $data['ManufacturerName'] ?></p>
                <p><strong>Grade:</strong> <?= $data['GradeName'] ?></p>

            <?php elseif ($data['MonitorID']): ?>
                <h3>Monitor Details</h3>
                <p><strong>Model:</strong> <?= htmlspecialchars($data['Model']) ?></p>
                <p><strong>Screen Size:</strong> <?= $data['ScreenSize'] ?></p>
                <p><strong>Resolution:</strong> <?= $data['Resolution'] ?></p>
                <p><strong>Description:</strong> <?= $data['MonitorDesc'] ?></p>
                <p><strong>Condition:</strong> <?= $data['MonitorDescofCond'] ?></p>
                <p><strong>Manufacturer:</strong> <?= $data['ManufacturerName'] ?></p>
                <p><strong>Grade:</strong> <?= $data['GradeName'] ?></p>

            <?php elseif ($data['CommonItemID']): ?>
                <h3>Common Item Details</h3>
                <p><strong>Model:</strong> <?= htmlspecialchars($data['CommonModel']) ?></p>
                <p><strong>Description:</strong> <?= $data['CommonItDesc'] ?></p>
                <p><strong>Condition:</strong> <?= $data['CommonDescofCond'] ?></p>
                <p><strong>Category:</strong> <?= $data['CategoryName'] ?></p>
                <p><strong>Manufacturer:</strong> <?= $data['ManufacturerName'] ?></p>
                <p><strong>Grade:</strong> <?= $data['GradeName'] ?></p>
            <?php else: ?>
                <p>No item details found.</p>
            <?php endif; ?>
        <?php elseif ($message): ?>
            <p><?= $message ?></p>
        <?php else: ?>
            <p>No barcode entered.</p>
        <?php endif; ?>

        <br>
        <button onclick="window.location.href='barcode_lookup_form.HTML'">Scan Another</button>
    </div>
</div>
</body>
</html>
