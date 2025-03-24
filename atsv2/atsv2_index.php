<?php
session_start();

// For testing without login system
if (!isset($_SESSION['role'])) {
    $_SESSION['role'] = 'User'; // Change this manually during development
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Management Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>Inventory Management</h2>
    <p><strong>Logged in as:</strong> <?= htmlspecialchars($role) ?></p>

    <div class="scrollable-content">
        <ul>
            <!-- Shared: Search & View -->
            <li><strong>Search & View</strong></li>
            <li><a href="view_tables.php">View Any Table</a></li>
            <li><a href="common_item_search_form.php">Search Common Items</a></li>
            <li><a href="monitor_search_form.php">Search Monitor Items</a></li>
            <li><a href="laptop_search_form.php">Search Laptop Items</a></li>
            <li><a href="sales_search_form.php">Search Sales Records</a></li>
            <li><a href="barcode_lookup_form.html">Scan Barcode</a></li>

            <?php if ($role === 'Admin' || $role === 'Manager'): ?>
                <!-- Managers and Admins -->
                <li><strong>Inventory Management</strong></li>
                <li><a href="add_item_ledger_form.php">Add New Item to Ledger</a></li>
                <li><a href="common_item_edit_form.php">Edit Common Items</a></li>
                <li><a href="monitor_item_edit_form.php">Edit Monitor Items</a></li>
                <li><a href="laptop_item_edit_form.php">Edit Laptop Items</a></li>

                <li><strong>Sales & People</strong></li>
                <li><a href="add_sale_form.php">Add Sale</a></li>
                <li><a href="add_buyer.html">Add Buyer</a></li>
                <li><a href="add_salesperson.html">Add Salesperson</a></li>
                <li><a href="add_manufacturer.html">Add Manufacturer</a></li>
                <li><a href="add_source.html">Add Source</a></li>
            <?php endif; ?>

            <?php if ($role === 'Admin'): ?>
                <!-- Admin Only -->
                <li><strong>Admin Tools</strong></li>
                <li><a href="add_user_form.html">Add New User</a></li>
                <li><a href="edit_user_role_form.php">Change User Roles</a></li>
            <?php endif; ?>
        </ul>

        <br><br>
        <form method="POST" action="logout.php">
            <button type="submit">Log Out</button>
        </form>
    </div>
</div>
</body>
</html>
