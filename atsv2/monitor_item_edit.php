<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sql = "UPDATE monitoritem SET 
        Model=?, ScreenSize=?, Resolution=?, MonitorDesc=?, MonitorDescofCond=?, 
        manufacturer_id=?, grade_id=? 
        WHERE MonitorID=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssii",
        $_POST['Model'],
        $_POST['ScreenSize'],
        $_POST['Resolution'],
        $_POST['MonitorDesc'],
        $_POST['MonitorDescofCond'],
        $_POST['manufacturer_id'],
        $_POST['grade_id'],
        $_POST['monitor_id']
    );

    if ($stmt->execute()) {
        // Update itemledger with Price and Status
        $updateLedger = $conn->prepare("UPDATE itemledger SET Price = ?, StatusID = ? WHERE MonitorID = ?");
        $updateLedger->bind_param("dii", $_POST['Price'], $_POST['StatusID'], $_POST['monitor_id']);
        $updateLedger->execute();
        $updateLedger->close();

        echo "<div class='container'><h2>Update Successful</h2><p>Monitor record updated.</p><a href='monitor_item_edit_form.php'>Edit Another Monitor</a></div>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
