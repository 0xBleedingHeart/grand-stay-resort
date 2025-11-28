<?php
require_once 'config/database.php';
require_once 'config/session.php';

$accommodations = mysqli_query($conn, "SELECT * FROM accommodations WHERE status = 'Available'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms & Cottages - The Grand Stay Resort</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="brand">The Grand Stay Resort</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="rooms.php">Rooms & Cottages</a>
            <a href="dining.php">Dining & Add-ons</a>
            <a href="gallery.php">Gallery</a>
            <a href="contact.php">Contact</a>
            <?php if (isAdmin()): ?>
                <a href="admin/dashboard.php">Admin Portal</a>
            <?php endif; ?>
            <?php if (isLoggedIn()): ?>
                <a href="logout.php" class="btn-danger">Logout</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <div class="hero-two    ">
            <h1>Rooms & Cottages</h1>
            <p>Experience comfort and luxury with our wide selection of rooms and cottages designed for your relaxation.</p>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Category</th>
                        <th>Capacity</th>
                        <th>Price/Night</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($room = mysqli_fetch_assoc($accommodations)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($room['name']); ?></td>
                        <td><?php echo htmlspecialchars($room['type']); ?></td>
                        <td><?php echo htmlspecialchars($room['category']); ?></td>
                        <td><?php echo $room['capacity']; ?> pax</td>
                        <td>₱<?php echo number_format($room['price_per_night'], 2); ?></td>
                        <td><span class="badge badge-success"><?php echo $room['status']; ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
