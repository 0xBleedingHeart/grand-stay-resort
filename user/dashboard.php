<?php
require_once '../config/database.php';
require_once '../config/session.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    
    $sql = "UPDATE users SET full_name = '$full_name', phone = '$phone', address = '$address' WHERE user_id = $user_id";
    if (mysqli_query($conn, $sql)) {
        $_SESSION['full_name'] = $full_name;
        $success = 'Profile updated successfully!';
    } else {
        $error = 'Failed to update profile.';
    }
}

// Handle booking cancellation
if (isset($_GET['cancel_booking'])) {
    $reservation_id = (int)$_GET['cancel_booking'];
    mysqli_query($conn, "UPDATE reservations r 
                        JOIN guests g ON r.guest_id = g.guest_id 
                        SET r.reservation_status = 'Cancelled' 
                        WHERE r.reservation_id = $reservation_id AND g.email = '{$_SESSION['email']}'");
    $success = 'Booking cancelled successfully!';
}

// Get user data
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
$user = mysqli_fetch_assoc($user_query);

// Get payment history
$payment_history = mysqli_query($conn, "SELECT p.*, r.reservation_id, a.name as room_name 
    FROM payments p 
    JOIN reservations r ON p.reservation_id = r.reservation_id 
    JOIN accommodations a ON r.accommodation_id = a.accommodation_id 
    JOIN guests g ON r.guest_id = g.guest_id 
    WHERE g.email = '{$_SESSION['email']}' 
    ORDER BY p.payment_date DESC");

$reservations = mysqli_query($conn, "SELECT r.*, a.name as room_name, a.type, g.full_name 
    FROM reservations r 
    JOIN accommodations a ON r.accommodation_id = a.accommodation_id 
    JOIN guests g ON r.guest_id = g.guest_id 
    WHERE g.email = '{$_SESSION['email']}' 
    ORDER BY r.date_reserved DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - The Grand Stay Resort</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="brand">The Grand Stay Resort</div>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="../book.php">Book</a>
            <a href="../gallery.php">Gallery</a>
            <a href="../contact.php">Contact Us</a>
            <a href="../logout.php" class="btn-danger">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User'); ?>!</h1>
        <p>Manage your profile and book your next luxury experience.</p>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="dashboard-tabs">
            <button class="tab-btn active" data-tab="profile">Status & Profile</button>
            <button class="tab-btn" data-tab="bookings">Active Bookings</button>
            <button class="tab-btn" data-tab="payment">Payment Method</button>
            <button class="tab-btn" data-tab="rooms">Rooms & Cottages</button>
            <button class="tab-btn" data-tab="dining">Dining & Add-ons</button>
        </div>

        <div id="profile-section" class="tab-content">
            <div class="info-box">
                <h3>Account Status</h3>
                <p><strong>Member Tier: Standard Member</strong></p>
                <p>Thank you for signing up! Your status will update based on your activity and completed bookings.</p>
            </div>

            <div class="info-box">
                <h3>Update Profile Settings</h3>
                <form method="POST">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <textarea name="address" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>

        <div id="bookings-section" class="tab-content" style="display: none;">
            <h2>My Reservations</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Reservation ID</th>
                            <th>Room/Cottage</th>
                            <th>Check-in</th>
                            <th>Check-out</th>
                            <th>Guests</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($reservations) > 0): ?>
                            <?php while ($res = mysqli_fetch_assoc($reservations)): ?>
                            <tr>
                                <td>#<?php echo $res['reservation_id']; ?></td>
                                <td><?php echo htmlspecialchars($res['room_name']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($res['check_in_date'])); ?></td>
                                <td><?php echo date('M d, Y', strtotime($res['check_out_date'])); ?></td>
                                <td><?php echo $res['num_pax']; ?></td>
                                <td>₱<?php echo number_format($res['total_price'], 2); ?></td>
                                <td><span class="badge badge-<?php echo $res['reservation_status'] === 'Confirmed' ? 'success' : ($res['reservation_status'] === 'Cancelled' ? 'danger' : 'warning'); ?>"><?php echo $res['reservation_status']; ?></span></td>
                                <td>
                                    <a href="print_receipt.php?id=<?php echo $res['reservation_id']; ?>" target="_blank" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.9rem; display: inline-block; margin: 2px;">Print</a>
                                    <?php if ($res['reservation_status'] === 'Pending' || $res['reservation_status'] === 'Confirmed'): ?>
                                        <a href="?cancel_booking=<?php echo $res['reservation_id']; ?>" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.9rem; display: inline-block; margin: 2px;" onclick="return confirm('Cancel this booking?')">Cancel</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" style="text-align: center;">No reservations found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="payment-section" class="tab-content" style="display: none;">
            <h2>Payment History</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Reservation ID</th>
                            <th>Room/Cottage</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Payment Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($payment_history) > 0): ?>
                            <?php while ($payment = mysqli_fetch_assoc($payment_history)): ?>
                            <tr>
                                <td>#<?php echo $payment['payment_id']; ?></td>
                                <td>#<?php echo $payment['reservation_id']; ?></td>
                                <td><?php echo htmlspecialchars($payment['room_name']); ?></td>
                                <td>₱<?php echo number_format($payment['amount_paid'], 2); ?></td>
                                <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($payment['payment_date'])); ?></td>
                                <td><span class="badge badge-<?php echo $payment['payment_status'] === 'Paid' ? 'success' : 'warning'; ?>"><?php echo $payment['payment_status']; ?></span></td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center;">No payment history found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tab = this.getAttribute('data-tab');
                
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                document.querySelectorAll('.tab-content').forEach(section => section.style.display = 'none');
                
                if (tab === 'profile') {
                    document.getElementById('profile-section').style.display = 'block';
                } else if (tab === 'bookings') {
                    document.getElementById('bookings-section').style.display = 'block';
                } else if (tab === 'payment') {
                    document.getElementById('payment-section').style.display = 'block';
                } else if (tab === 'rooms') {
                    window.location.href = '../rooms.php';
                } else if (tab === 'dining') {
                    window.location.href = '../dining.php';
                }
            });
        });
    </script>
</body>
</html>
