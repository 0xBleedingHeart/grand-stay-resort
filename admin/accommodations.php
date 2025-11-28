<?php
require_once '../config/database.php';
require_once '../config/session.php';
requireAdmin();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $type = mysqli_real_escape_string($conn, $_POST['type']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $capacity = (int)$_POST['capacity'];
        $price = (float)$_POST['price'];
        
        $sql = "INSERT INTO accommodations (type, name, category, capacity, price_per_night) 
                VALUES ('$type', '$name', '$category', $capacity, $price)";
        if (mysqli_query($conn, $sql)) {
            $success = 'Accommodation added successfully!';
        } else {
            $error = 'Failed to add accommodation';
        }
    }
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    mysqli_query($conn, "DELETE FROM accommodations WHERE accommodation_id = $id");
    header('Location: accommodations.php');
    exit;
}

$accommodations = mysqli_query($conn, "SELECT * FROM accommodations ORDER BY accommodation_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Accommodations - Admin</title>
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
        <h1>Manage Accommodations</h1>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="booking-form" style="margin-bottom: 40px;">
            <h2>Add New Accommodation</h2>
            <form method="POST">
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" required>
                        <option value="Room">Room</option>
                        <option value="Cottage">Cottage</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="Standard">Standard</option>
                        <option value="Deluxe">Deluxe</option>
                        <option value="VIP">VIP</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Capacity</label>
                    <input type="number" name="capacity" min="1" required>
                </div>
                <div class="form-group">
                    <label>Price per Night</label>
                    <input type="number" name="price" step="0.01" min="0" required>
                </div>
                <button type="submit" name="add" class="btn btn-primary">Add Accommodation</button>
            </form>
        </div>

        <h2>All Accommodations</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Capacity</th>
                        <th>Price/Night</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($acc = mysqli_fetch_assoc($accommodations)): ?>
                    <tr>
                        <td><?php echo $acc['accommodation_id']; ?></td>
                        <td><?php echo htmlspecialchars($acc['type']); ?></td>
                        <td><?php echo htmlspecialchars($acc['name']); ?></td>
                        <td><?php echo htmlspecialchars($acc['category']); ?></td>
                        <td><?php echo $acc['capacity']; ?></td>
                        <td>₱<?php echo number_format($acc['price_per_night'], 2); ?></td>
                        <td><span class="badge badge-success"><?php echo $acc['status']; ?></span></td>
                        <td>
                            <a href="?delete=<?php echo $acc['accommodation_id']; ?>" 
                               onclick="return confirm('Delete this accommodation?')" 
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
