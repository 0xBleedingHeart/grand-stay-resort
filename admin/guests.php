<?php
require_once '../config/database.php';
require_once '../config/session.php';
requireAdmin();

$guests = mysqli_query($conn, "SELECT g.*, COUNT(r.reservation_id) as total_bookings 
    FROM guests g 
    LEFT JOIN reservations r ON g.guest_id = r.guest_id 
    GROUP BY g.guest_id 
    ORDER BY g.guest_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Guests - Admin</title>
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
        <h1>Manage Guests</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Total Bookings</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($guest = mysqli_fetch_assoc($guests)): ?>
                    <tr>
                        <td><?php echo $guest['guest_id']; ?></td>
                        <td><?php echo htmlspecialchars($guest['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($guest['contact_number']); ?></td>
                        <td><?php echo htmlspecialchars($guest['email'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($guest['address'] ?? 'N/A'); ?></td>
                        <td><?php echo $guest['total_bookings']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
