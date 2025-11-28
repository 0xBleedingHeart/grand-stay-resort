<?php
require_once '../config/database.php';
require_once '../config/session.php';
requireAdmin();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $price = (float)$_POST['price'];
        
        $sql = "INSERT INTO addons (name, price) VALUES ('$name', $price)";
        if (mysqli_query($conn, $sql)) {
            $success = 'Add-on added successfully!';
        } else {
            $error = 'Failed to add add-on';
        }
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM addons WHERE addon_id = $id");
    header('Location: addons.php');
    exit;
}

$addons = mysqli_query($conn, "SELECT * FROM addons ORDER BY addon_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Add-ons - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="brand">The Grand Stay Resort - Admin</div>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="reservations.php">Reservations</a>
            <a href="accommodations.php">Accommodations</a>
            <a href="guests.php">Guests</a>
            <a href="addons.php">Add-ons</a>
            <a href="../logout.php" class="btn-danger">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h1>Manage Add-ons</h1>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="booking-form" style="margin-bottom: 40px;">
            <h2>Add New Add-on</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Add-on Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" name="price" step="0.01" min="0" required>
                </div>
                <button type="submit" name="add" class="btn btn-primary">Add Add-on</button>
            </form>
        </div>

        <h2>All Add-ons</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($addon = mysqli_fetch_assoc($addons)): ?>
                    <tr>
                        <td><?php echo $addon['addon_id']; ?></td>
                        <td><?php echo htmlspecialchars($addon['name']); ?></td>
                        <td>₱<?php echo number_format($addon['price'], 2); ?></td>
                        <td>
                            <a href="?delete=<?php echo $addon['addon_id']; ?>" 
                               onclick="return confirm('Delete this add-on?')" 
                               style="color: red;">Delete</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
