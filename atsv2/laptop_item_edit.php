<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "UPDATE laptopitem SET 
        Model=?, Processor=?, RAM=?, StorageType=?, StorageSize=?, 
        GPUType=?, GraphicsCard=?, OperatingSystem=?, ScreenSize=?, ScreenResolution=?, 
        LaptopDesc=?, LaptopDescofCond=?, Warranty=?, WarrantyDate=?, 
        manufacturer_id=?, grade_id=?
        WHERE LaptopID=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssssiii",
        $_POST['Model'],
        $_POST['Processor'],
        $_POST['RAM'],
        $_POST['StorageType'],
        $_POST['StorageSize'],
        $_POST['GPUType'],
        $_POST['GraphicsCard'],
        $_POST['OperatingSystem'],
        $_POST['ScreenSize'],
        $_POST['ScreenResolution'],
        $_POST['LaptopDesc'],
        $_POST['LaptopDescofCond'],
        $_POST['Warranty'],
        $_POST['WarrantyDate'],
        $_POST['manufacturer_id'],
        $_POST['grade_id'],
        $_POST['laptop_id']
    );

    if ($stmt->execute()) {
        // Now update Price and StatusID in itemledger
        $updateLedger = $conn->prepare("UPDATE itemledger SET Price = ?, StatusID = ? WHERE LaptopID = ?");
        $updateLedger->bind_param("dii", $_POST['Price'], $_POST['StatusID'], $_POST['laptop_id']);
        $updateLedger->execute();
        $updateLedger->close();

        echo "<div class='container'><h2>Update Successful</h2><p>Laptop record updated.</p><a href='laptop_item_edit_form.php'>Edit Another Laptop</a></div>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
