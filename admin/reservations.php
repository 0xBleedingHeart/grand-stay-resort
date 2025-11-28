<?php
require_once '../config/database.php';
require_once '../config/session.php';
requireAdmin();

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    if ($action === 'confirm') {
        mysqli_query($conn, "UPDATE reservations SET reservation_status = 'Confirmed' WHERE reservation_id = $id");
    } elseif ($action === 'cancel') {
        mysqli_query($conn, "UPDATE reservations SET reservation_status = 'Cancelled' WHERE reservation_id = $id");
    }
    header('Location: reservations.php');
    exit;
}

$reservations = mysqli_query($conn, "SELECT r.*, a.name as room_name, g.full_name, g.contact_number, g.email 
    FROM reservations r 
    JOIN accommodations a ON r.accommodation_id = a.accommodation_id 
    JOIN guests g ON r.guest_id = g.guest_id 
    ORDER BY r.date_reserved DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reservations - Admin</title>
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
        <h1>Manage Reservations</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Guest</th>
                        <th>Contact</th>
                        <th>Room</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                        <th>Guests</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($res = mysqli_fetch_assoc($reservations)): ?>
                    <tr>
                        <td>#<?php echo $res['reservation_id']; ?></td>
                        <td><?php echo htmlspecialchars($res['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($res['contact_number']); ?></td>
                        <td><?php echo htmlspecialchars($res['room_name']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($res['check_in_date'])); ?></td>
                        <td><?php echo date('M d, Y', strtotime($res['check_out_date'])); ?></td>
                        <td><?php echo $res['num_pax']; ?></td>
                        <td>₱<?php echo number_format($res['total_price'], 2); ?></td>
                        <td>
                            <?php if ($res['reservation_status'] === 'Pending'): ?>
                                <span class="badge badge-warning">Pending</span>
                            <?php elseif ($res['reservation_status'] === 'Confirmed'): ?>
                                <span class="badge badge-success">Confirmed</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Cancelled</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($res['reservation_status'] === 'Pending'): ?>
                                <a href="?action=confirm&id=<?php echo $res['reservation_id']; ?>" style="color: green;">Confirm</a> | 
                                <a href="?action=cancel&id=<?php echo $res['reservation_id']; ?>" style="color: red;">Cancel</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
