<?php
require_once '../config/database.php';
require_once '../config/session.php';
requireLogin();

$reservation_id = (int)$_GET['id'];

// Get reservation details
$query = mysqli_query($conn, "SELECT r.*, a.name as room_name, a.type, a.category, g.full_name, g.contact_number, g.email, g.address,
                               p.payment_method, p.payment_status, p.account_number, p.reference_number
                               FROM reservations r 
                               JOIN accommodations a ON r.accommodation_id = a.accommodation_id 
                               JOIN guests g ON r.guest_id = g.guest_id 
                               LEFT JOIN payments p ON r.reservation_id = p.reservation_id
                               WHERE r.reservation_id = $reservation_id AND g.email = '{$_SESSION['email']}'");
$reservation = mysqli_fetch_assoc($query);

if (!$reservation) {
    die('Reservation not found');
}

// Get add-ons
$addons_query = mysqli_query($conn, "SELECT ra.*, a.name 
                                     FROM reservation_addons ra 
                                     JOIN addons a ON ra.addon_id = a.addon_id 
                                     WHERE ra.reservation_id = $reservation_id");
$days = (strtotime($reservation['check_out_date']) - strtotime($reservation['check_in_date'])) / 86400;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt - #<?php echo $reservation_id; ?></title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; }
        .header { text-align: center; border-bottom: 3px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        .header h1 { margin: 0; color: #1a3a52; }
        .section { margin-bottom: 30px; }
        .section h2 { background: #f0f0f0; padding: 10px; margin: 0 0 15px 0; }
        .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #eee; }
        .info-label { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table th, table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        table th { background: #f0f0f0; }
        .total { font-size: 1.3rem; font-weight: bold; text-align: right; margin-top: 20px; padding: 15px; background: #f9f9f9; }
        .status { display: inline-block; padding: 5px 15px; border-radius: 5px; font-weight: bold; }
        .status-confirmed { background: #4caf50; color: white; }
        .status-pending { background: #ff9800; color: white; }
        .status-cancelled { background: #f44336; color: white; }
        .print-btn { background: #1a3a52; color: white; padding: 10px 30px; border: none; cursor: pointer; font-size: 1rem; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" class="print-btn">Print Receipt</button>
    </div>

    <div class="header">
        <h1>The Grand Stay Resort</h1>
        <p>450 Grand Avenue, Paradise City | +1 (800) 555-4422 | reservations@grandstay.com</p>
        <h2>BOOKING RECEIPT</h2>
    </div>

    <div class="section">
        <h2>Reservation Details</h2>
        <div class="info-row">
            <span class="info-label">Reservation ID:</span>
            <span>#<?php echo $reservation['reservation_id']; ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Booking Date:</span>
            <span><?php echo date('F d, Y', strtotime($reservation['date_reserved'])); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Status:</span>
            <span class="status status-<?php echo strtolower($reservation['reservation_status']); ?>">
                <?php echo $reservation['reservation_status']; ?>
            </span>
        </div>
    </div>

    <div class="section">
        <h2>Guest Information</h2>
        <div class="info-row">
            <span class="info-label">Name:</span>
            <span><?php echo htmlspecialchars($reservation['full_name']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Email:</span>
            <span><?php echo htmlspecialchars($reservation['email']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Contact:</span>
            <span><?php echo htmlspecialchars($reservation['contact_number']); ?></span>
        </div>
    </div>

    <div class="section">
        <h2>Accommodation Details</h2>
        <div class="info-row">
            <span class="info-label">Room/Cottage:</span>
            <span><?php echo htmlspecialchars($reservation['room_name']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Type:</span>
            <span><?php echo $reservation['type']; ?> - <?php echo $reservation['category']; ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Check-in:</span>
            <span><?php echo date('F d, Y', strtotime($reservation['check_in_date'])); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Check-out:</span>
            <span><?php echo date('F d, Y', strtotime($reservation['check_out_date'])); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Number of Nights:</span>
            <span><?php echo $days; ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Number of Guests:</span>
            <span><?php echo $reservation['num_pax']; ?></span>
        </div>
    </div>

    <?php if (mysqli_num_rows($addons_query) > 0): ?>
    <div class="section">
        <h2>Add-ons</h2>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($addon = mysqli_fetch_assoc($addons_query)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($addon['name']); ?></td>
                    <td><?php echo $addon['quantity']; ?></td>
                    <td>₱<?php echo number_format($addon['subtotal'], 2); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <div class="section">
        <h2>Payment Information</h2>
        <div class="info-row">
            <span class="info-label">Payment Method:</span>
            <span><?php echo htmlspecialchars($reservation['payment_method']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Account Number:</span>
            <span><?php echo htmlspecialchars($reservation['account_number']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Reference Number:</span>
            <span><?php echo htmlspecialchars($reservation['reference_number']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Payment Status:</span>
            <span class="status status-<?php echo strtolower($reservation['payment_status']); ?>">
                <?php echo $reservation['payment_status']; ?>
            </span>
        </div>
    </div>

    <div class="total">
        TOTAL AMOUNT: ₱<?php echo number_format($reservation['total_price'], 2); ?>
    </div>

    <div style="margin-top: 50px; padding-top: 20px; border-top: 2px solid #333; text-align: center; color: #666;">
        <p>Thank you for choosing The Grand Stay Resort!</p>
        <p>For inquiries, please contact us at reservations@grandstay.com or +1 (800) 555-4422</p>
    </div>
</body>
</html>
