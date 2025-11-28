<?php
require_once 'config/database.php';
require_once 'config/session.php';

$addons = mysqli_query($conn, "SELECT * FROM addons");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dining & Add-ons - The Grand Stay Resort</title>
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
        <div class="hero-two">
            <h1>Dining & Add-ons</h1>
            <p>Enhance your stay with our premium add-ons and services.</p>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Add-on Name</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($addon = mysqli_fetch_assoc($addons)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($addon['name']); ?></td>
                        <td>₱<?php echo number_format($addon['price'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
