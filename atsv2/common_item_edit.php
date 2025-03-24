<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "atsv2";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $common_item_id = $_POST['common_item_id'];
    $manufacturer = $_POST['manufacturer'];
    $model = $_POST['model'];
    $description = $_POST['description'];
    $condition = $_POST['condition'];
    $grade = $_POST['grade'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $status = $_POST['status'];

    // Update commonitem
    $sql = "UPDATE commonitem 
            SET CommonModel=?, CommonItDesc=?, CommonDescofCond=?, manufacturer_id=?, grade_id=?, CategoryID=? 
            WHERE CommonItemID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiiii", $model, $description, $condition, $manufacturer, $grade, $category_id, $common_item_id);
    $stmt->execute();

    // Update itemledger (price + status)
    $sql2 = "UPDATE itemledger 
             SET Price=?, StatusID=? 
             WHERE CommonItemID=?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("dii", $price, $status, $common_item_id);
    $stmt2->execute();

    echo "<div class='container'>";
    echo "<h2>Update Successful</h2>";
    echo "<p>Common item information has been updated.</p>";
    echo "<a href='common_item_edit_form.php'>Select Another Item</a>";
    echo "</div>";
}

$conn->close();
?>
