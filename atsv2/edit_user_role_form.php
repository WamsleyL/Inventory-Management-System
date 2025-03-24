<?php
session_start();

// Ensure only Admin can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    die("Access denied. Admins only.");
}

$conn = new mysqli("localhost", "root", "", "atsv2");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$users = $conn->query("SELECT UserID, Username, Role, Status FROM users ORDER BY Username ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User Role</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>Edit User Role</h2>
    <div class="scrollable-content">
        <form action="edit_user_role.php" method="POST">
            <label for="UserID">Select User:</label>
            <select name="UserID" id="UserID" required>
                <option value="">-- Select a user --</option>
                <?php while ($row = $users->fetch_assoc()): ?>
                    <option value="<?= $row['UserID'] ?>">
                        <?= htmlspecialchars($row['Username']) ?> (<?= $row['Role'] ?> / <?= $row['Status'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="Role">New Role:</label>
            <select name="Role" id="Role" required>
                <option value="User">User</option>
                <option value="Manager">Manager</option>
                <option value="Admin">Admin</option>
            </select>

            <label for="Status">New Status:</label>
            <select name="Status" id="Status" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>

            <button type="submit">Update User</button>
        </form>
    </div>
    <form action="atsv2_index.php" method="get" style="margin-top: 20px;">
                <button type="submit">Back to Dashboard</button>
                </form>
</div>
</body>
</html>
