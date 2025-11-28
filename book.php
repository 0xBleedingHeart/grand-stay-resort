<?php
require_once 'config/database.php';
require_once 'config/session.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE user_id = $user_id");
$user = mysqli_fetch_assoc($user_query);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accommodation_id = (int)$_POST['accommodation_id'];
    $check_in = mysqli_real_escape_string($conn, $_POST['check_in']);
    $check_out = mysqli_real_escape_string($conn, $_POST['check_out']);
    $num_pax = (int)$_POST['num_pax'];
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $account_number = mysqli_real_escape_string($conn, $_POST['account_number'] ?? '');
    $reference_number = mysqli_real_escape_string($conn, $_POST['reference_number'] ?? '');
    $selected_addons = $_POST['addons'] ?? [];
    
    // Date validation
    $today = date('Y-m-d');
    if ($check_in < $today) {
        $error = 'Check-in date cannot be in the past.';
    } elseif ($check_out <= $check_in) {
        $error = 'Check-out date must be after check-in date.';
    } else {
        // Check availability
        $avail_check = mysqli_query($conn, "SELECT COUNT(*) as count FROM reservations 
            WHERE accommodation_id = $accommodation_id 
            AND reservation_status != 'Cancelled'
            AND (
                (check_in_date <= '$check_in' AND check_out_date > '$check_in')
                OR (check_in_date < '$check_out' AND check_out_date >= '$check_out')
                OR (check_in_date >= '$check_in' AND check_out_date <= '$check_out')
            )");
        $avail = mysqli_fetch_assoc($avail_check);
        
        if ($avail['count'] > 0) {
            $error = 'This accommodation is not available for the selected dates.';
        } else {
            // Use logged-in user info
            $guest_name = mysqli_real_escape_string($conn, $user['full_name']);
            $contact = mysqli_real_escape_string($conn, $user['phone']);
            $email = mysqli_real_escape_string($conn, $user['email']);
            
            // Insert guest
            $guest_sql = "INSERT INTO guests (full_name, contact_number, email) VALUES ('$guest_name', '$contact', '$email')";
            if (mysqli_query($conn, $guest_sql)) {
                $guest_id = mysqli_insert_id($conn);
                
                // Calculate total
                $acc_query = mysqli_query($conn, "SELECT price_per_night FROM accommodations WHERE accommodation_id = $accommodation_id");
                $acc = mysqli_fetch_assoc($acc_query);
                $days = (strtotime($check_out) - strtotime($check_in)) / 86400;
                $total = $acc['price_per_night'] * $days;
                
                // Add add-ons to total
                $addons_total = 0;
                foreach ($selected_addons as $addon_id => $quantity) {
                    if ($quantity > 0) {
                        $addon_query = mysqli_query($conn, "SELECT price FROM addons WHERE addon_id = $addon_id");
                        $addon = mysqli_fetch_assoc($addon_query);
                        $addons_total += $addon['price'] * $quantity;
                    }
                }
                $total += $addons_total;
                
                // Insert reservation
                $res_sql = "INSERT INTO reservations (guest_id, accommodation_id, check_in_date, check_out_date, num_pax, total_price) 
                            VALUES ($guest_id, $accommodation_id, '$check_in', '$check_out', $num_pax, $total)";
                if (mysqli_query($conn, $res_sql)) {
                    $reservation_id = mysqli_insert_id($conn);
                    
                    // Insert add-ons
                    foreach ($selected_addons as $addon_id => $quantity) {
                        if ($quantity > 0) {
                            $addon_query = mysqli_query($conn, "SELECT price FROM addons WHERE addon_id = $addon_id");
                            $addon = mysqli_fetch_assoc($addon_query);
                            $subtotal = $addon['price'] * $quantity;
                            mysqli_query($conn, "INSERT INTO reservation_addons (reservation_id, addon_id, quantity, subtotal) 
                                                VALUES ($reservation_id, $addon_id, $quantity, $subtotal)");
                        }
                    }
                    
                    // Insert payment record
                    $payment_sql = "INSERT INTO payments (reservation_id, amount_paid, payment_method, payment_status, account_number, reference_number) 
                                   VALUES ($reservation_id, $total, '$payment_method', 'Unpaid', '$account_number', '$reference_number')";
                    mysqli_query($conn, $payment_sql);
                    
                    $success = 'Booking successful! Your reservation has been created.';
                } else {
                    $error = 'Booking failed. Please try again.';
                }
            }
        }
    }
}

// Get available add-ons
$addons = mysqli_query($conn, "SELECT * FROM addons ORDER BY name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Stay - The Grand Stay Resort</title>
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
        <div class="hero">
            <h1>Book Your Stay</h1>
            <p>Reserve your perfect room at The Grand Stay Resort</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php $accommodations = mysqli_query($conn, "SELECT * FROM accommodations WHERE status = 'Available'"); ?>

        <div class="booking-form">
            <h2>Booking Details</h2>
            <form method="POST">
                <div class="info-box" style="margin-bottom: 20px;">
                    <h3>Guest Information</h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['full_name'] ?? 'Not set'); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone'] ?? 'Not set'); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    <p style="font-size: 0.9rem; color: #666;">Update your profile in the dashboard if needed.</p>
                </div>
                
                <div class="form-group">
                    <label>Room Type</label>
                    <select name="accommodation_id" required>
                        <option value="">Select Room/Cottage</option>
                        <?php while ($acc = mysqli_fetch_assoc($accommodations)): ?>
                            <option value="<?php echo $acc['accommodation_id']; ?>">
                                <?php echo $acc['name']; ?> - ₱<?php echo number_format($acc['price_per_night'], 2); ?>/night
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Check-in Date</label>
                    <input type="date" name="check_in" required>
                </div>
                <div class="form-group">
                    <label>Check-out Date</label>
                    <input type="date" name="check_out" required>
                </div>
                <div class="form-group">
                    <label>Number of Guests</label>
                    <input type="number" name="num_pax" min="1" required>
                </div>
                
                <h3 style="margin-top: 30px;">Add-ons (Optional)</h3>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                    <?php while ($addon = mysqli_fetch_assoc($addons)): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding: 10px; background: white; border-radius: 5px;">
                            <div>
                                <strong><?php echo htmlspecialchars($addon['name']); ?></strong>
                                <span style="color: #666; margin-left: 10px;">₱<?php echo number_format($addon['price'], 2); ?></span>
                            </div>
                            <input type="number" name="addons[<?php echo $addon['addon_id']; ?>]" min="0" value="0" style="width: 80px; padding: 5px;">
                        </div>
                    <?php endwhile; ?>
                </div>
                
                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method" required>
                        <option value="">Select Payment Method</option>
                        <option value="Cash">Cash</option>
                        <option value="GCash">GCash</option>
                        <option value="PayMaya">PayMaya</option>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Debit Card">Debit Card</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Account Number</label>
                    <input type="text" name="account_number" placeholder="Enter your account/mobile number" required>
                </div>
                <div class="form-group">
                    <label>Reference Number</label>
                    <input type="text" name="reference_number" placeholder="Enter payment reference number" required>
                </div>
                <button type="submit" class="btn btn-primary">Complete Booking</button>
            </form>
        </div>
    </div>
</body>
</html>
