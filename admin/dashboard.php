<?php
require_once '../config/database.php';
require_once '../config/session.php';
requireAdmin();

$success = '';
$error = '';

// Handle Reservation Actions
if (isset($_GET['confirm_reservation'])) {
    $id = (int)$_GET['confirm_reservation'];
    mysqli_query($conn, "UPDATE reservations SET reservation_status = 'Confirmed' WHERE reservation_id = $id");
    $success = 'Reservation confirmed!';
}
if (isset($_GET['cancel_reservation'])) {
    $id = (int)$_GET['cancel_reservation'];
    mysqli_query($conn, "UPDATE reservations SET reservation_status = 'Cancelled' WHERE reservation_id = $id");
    $success = 'Reservation cancelled!';
}
if (isset($_GET['delete_reservation'])) {
    $id = (int)$_GET['delete_reservation'];
    mysqli_query($conn, "DELETE FROM reservations WHERE reservation_id = $id");
    $success = 'Reservation deleted!';
}

// Handle Accommodation Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_accommodation'])) {
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $capacity = (int)$_POST['capacity'];
    $price = (float)$_POST['price'];
    
    mysqli_query($conn, "INSERT INTO accommodations (type, name, category, capacity, price_per_night, status) 
                         VALUES ('$type', '$name', '$category', $capacity, $price, 'Available')");
    $success = 'Accommodation added!';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_accommodation'])) {
    $id = (int)$_POST['accommodation_id'];
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $capacity = (int)$_POST['capacity'];
    $price = (float)$_POST['price'];
    
    mysqli_query($conn, "UPDATE accommodations SET type='$type', name='$name', category='$category', 
                         capacity=$capacity, price_per_night=$price WHERE accommodation_id=$id");
    $success = 'Accommodation updated!';
}
if (isset($_GET['delete_accommodation'])) {
    $id = (int)$_GET['delete_accommodation'];
    mysqli_query($conn, "DELETE FROM accommodations WHERE accommodation_id = $id");
    $success = 'Accommodation deleted!';
}

// Handle User Actions
if (isset($_GET['delete_user'])) {
    $id = (int)$_GET['delete_user'];
    mysqli_query($conn, "DELETE FROM users WHERE user_id = $id");
    $success = 'User deleted!';
}

// Handle Payment Actions
if (isset($_GET['mark_paid'])) {
    $id = (int)$_GET['mark_paid'];
    mysqli_query($conn, "UPDATE payments SET payment_status = 'Paid' WHERE payment_id = $id");
    $success = 'Payment marked as paid!';
}

// Get statistics
$total_reservations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM reservations"))['count'];
$total_guests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM guests"))['count'];
$total_revenue = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_price) as total FROM reservations"))['total'] ?? 0;
$pending_reservations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM reservations WHERE reservation_status = 'Pending'"))['count'];
$confirmed_reservations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM reservations WHERE reservation_status = 'Confirmed'"))['count'];
$cancelled_reservations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM reservations WHERE reservation_status = 'Cancelled'"))['count'];
$paid_amount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount_paid) as total FROM payments WHERE payment_status = 'Paid'"))['total'] ?? 0;
$unpaid_amount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount_paid) as total FROM payments WHERE payment_status = 'Unpaid'"))['total'] ?? 0;

// Popular rooms
$popular_rooms = mysqli_query($conn, "SELECT a.name, COUNT(*) as bookings 
    FROM reservations r 
    JOIN accommodations a ON r.accommodation_id = a.accommodation_id 
    GROUP BY r.accommodation_id 
    ORDER BY bookings DESC LIMIT 5");

// Get data
$reservations = mysqli_query($conn, "SELECT r.*, a.name as room_name, g.full_name, g.email 
    FROM reservations r 
    JOIN accommodations a ON r.accommodation_id = a.accommodation_id 
    JOIN guests g ON r.guest_id = g.guest_id 
    ORDER BY r.date_reserved DESC");

$accommodations = mysqli_query($conn, "SELECT * FROM accommodations ORDER BY accommodation_id DESC");
$users = mysqli_query($conn, "SELECT * FROM users ORDER BY created_at DESC");
$payments = mysqli_query($conn, "SELECT p.*, r.reservation_id, a.name as room_name, g.full_name 
    FROM payments p 
    JOIN reservations r ON p.reservation_id = r.reservation_id 
    JOIN accommodations a ON r.accommodation_id = a.accommodation_id 
    JOIN guests g ON r.guest_id = g.guest_id 
    ORDER BY p.payment_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - The Grand Stay Resort</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="brand">The Grand Stay Resort - Admin</div>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="../index.php">View Site</a>
            <a href="../logout.php" class="btn-danger">Logout</a>
        </div>
    </nav>

    <div class="container">
        <h1>Admin Dashboard</h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'Admin'); ?>!</p>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 40px 0;">
            <div class="info-box">
                <h3>Total Reservations</h3>
                <p style="font-size: 32px; font-weight: bold; color: #4a7ba7;"><?php echo $total_reservations; ?></p>
            </div>
            <div class="info-box">
                <h3>Total Guests</h3>
                <p style="font-size: 32px; font-weight: bold; color: #4a7ba7;"><?php echo $total_guests; ?></p>
            </div>
            <div class="info-box">
                <h3>Total Revenue</h3>
                <p style="font-size: 32px; font-weight: bold; color: #4a7ba7;">₱<?php echo number_format($total_revenue, 2); ?></p>
            </div>
            <div class="info-box warning">
                <h3>Pending Reservations</h3>
                <p style="font-size: 32px; font-weight: bold; color: #ff9800;"><?php echo $pending_reservations; ?></p>
            </div>
        </div>

        <div class="dashboard-tabs">
            <button class="tab-btn active" data-tab="reservations">Reservations</button>
            <button class="tab-btn" data-tab="accommodations">Accommodations</button>
            <button class="tab-btn" data-tab="users">Users</button>
            <button class="tab-btn" data-tab="payments">Payments</button>
            <button class="tab-btn" data-tab="reports">Reports</button>
        </div>

        <!-- Reservations Section -->
        <div id="reservations-section" class="tab-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0;">Manage Reservations</h2>
                <a href="export_reservations.php" class="btn btn-primary">Export to CSV</a>
            </div>
            <input type="text" id="searchReservations" placeholder="Search reservations..." style="width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 5px;">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Guest</th>
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
                            <td><?php echo htmlspecialchars($res['room_name']); ?></td>
                            <td><?php echo date('M d, Y', strtotime($res['check_in_date'])); ?></td>
                            <td><?php echo date('M d, Y', strtotime($res['check_out_date'])); ?></td>
                            <td><?php echo $res['num_pax']; ?></td>
                            <td>₱<?php echo number_format($res['total_price'], 2); ?></td>
                            <td><span class="badge badge-<?php echo $res['reservation_status'] === 'Confirmed' ? 'success' : 'warning'; ?>"><?php echo $res['reservation_status']; ?></span></td>
                            <td>
                                <?php if ($res['reservation_status'] === 'Pending'): ?>
                                    <a href="?confirm_reservation=<?php echo $res['reservation_id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.9rem; display: inline-block; margin: 2px;">Confirm</a>
                                <?php endif; ?>
                                <a href="?cancel_reservation=<?php echo $res['reservation_id']; ?>" class="btn btn-secondary" style="padding: 5px 10px; font-size: 0.9rem; display: inline-block; margin: 2px;">Cancel</a>
                                <a href="?delete_reservation=<?php echo $res['reservation_id']; ?>" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.9rem; display: inline-block; margin: 2px;" onclick="return confirm('Delete this reservation?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Accommodations Section -->
        <div id="accommodations-section" class="tab-content" style="display: none;">
            <h2>Manage Accommodations</h2>
            
            <div class="info-box">
                <h3>Add New Accommodation</h3>
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
                    <button type="submit" name="add_accommodation" class="btn btn-primary">Add Accommodation</button>
                </form>
            </div>

            <h3 style="margin-top: 30px;">All Accommodations</h3>
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
                            <td>#<?php echo $acc['accommodation_id']; ?></td>
                            <td><?php echo $acc['type']; ?></td>
                            <td><?php echo htmlspecialchars($acc['name']); ?></td>
                            <td><?php echo $acc['category']; ?></td>
                            <td><?php echo $acc['capacity']; ?></td>
                            <td>₱<?php echo number_format($acc['price_per_night'], 2); ?></td>
                            <td><span class="badge badge-success"><?php echo $acc['status']; ?></span></td>
                            <td>
                                <a href="#" onclick="editAccommodation(<?php echo $acc['accommodation_id']; ?>, '<?php echo addslashes($acc['type']); ?>', '<?php echo addslashes($acc['name']); ?>', '<?php echo addslashes($acc['category']); ?>', <?php echo $acc['capacity']; ?>, <?php echo $acc['price_per_night']; ?>)" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.9rem; display: inline-block; margin: 2px;">Edit</a>
                                <a href="?delete_accommodation=<?php echo $acc['accommodation_id']; ?>" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.9rem; display: inline-block; margin: 2px;" onclick="return confirm('Delete this accommodation?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Users Section -->
        <div id="users-section" class="tab-content" style="display: none;">
            <h2>Manage Users</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Full Name</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = mysqli_fetch_assoc($users)): ?>
                        <tr>
                            <td>#<?php echo $user['user_id']; ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['full_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
                            <td><span class="badge badge-<?php echo $user['role'] === 'admin' ? 'danger' : 'primary'; ?>"><?php echo $user['role']; ?></span></td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <?php if ($user['user_id'] != $_SESSION['user_id']): ?>
                                    <a href="?delete_user=<?php echo $user['user_id']; ?>" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.9rem; display: inline-block; margin: 2px;" onclick="return confirm('Delete this user?')">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payments Section -->
        <div id="payments-section" class="tab-content" style="display: none;">
            <h2>Manage Payments</h2>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Reservation ID</th>
                            <th>Guest</th>
                            <th>Room</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Account Number</th>
                            <th>Reference Number</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($payment = mysqli_fetch_assoc($payments)): ?>
                        <tr>
                            <td>#<?php echo $payment['payment_id']; ?></td>
                            <td>#<?php echo $payment['reservation_id']; ?></td>
                            <td><?php echo htmlspecialchars($payment['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($payment['room_name']); ?></td>
                            <td>₱<?php echo number_format($payment['amount_paid'], 2); ?></td>
                            <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                            <td><?php echo htmlspecialchars($payment['account_number'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($payment['reference_number'] ?? 'N/A'); ?></td>
                            <td><?php echo date('M d, Y', strtotime($payment['payment_date'])); ?></td>
                            <td><span class="badge badge-<?php echo $payment['payment_status'] === 'Paid' ? 'success' : 'warning'; ?>"><?php echo $payment['payment_status']; ?></span></td>
                            <td>
                                <?php if ($payment['payment_status'] !== 'Paid'): ?>
                                    <a href="?mark_paid=<?php echo $payment['payment_id']; ?>" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.9rem; display: inline-block; margin: 2px;">Mark Paid</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Reports Section -->
        <div id="reports-section" class="tab-content" style="display: none;">
            <h2>Reports & Analytics</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 30px 0;">
                <div class="info-box">
                    <h3>Confirmed Bookings</h3>
                    <p style="font-size: 32px; font-weight: bold; color: #4caf50;"><?php echo $confirmed_reservations; ?></p>
                </div>
                <div class="info-box warning">
                    <h3>Cancelled Bookings</h3>
                    <p style="font-size: 32px; font-weight: bold; color: #f44336;"><?php echo $cancelled_reservations; ?></p>
                </div>
                <div class="info-box">
                    <h3>Paid Amount</h3>
                    <p style="font-size: 32px; font-weight: bold; color: #4caf50;">₱<?php echo number_format($paid_amount, 2); ?></p>
                </div>
                <div class="info-box warning">
                    <h3>Unpaid Amount</h3>
                    <p style="font-size: 32px; font-weight: bold; color: #ff9800;">₱<?php echo number_format($unpaid_amount, 2); ?></p>
                </div>
            </div>

            <h3>Most Popular Rooms/Cottages</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Room/Cottage Name</th>
                            <th>Total Bookings</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($room = mysqli_fetch_assoc($popular_rooms)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($room['name']); ?></td>
                            <td><?php echo $room['bookings']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Edit Accommodation Modal -->
    <div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="background: white; max-width: 600px; margin: 50px auto; padding: 30px; border-radius: 8px;">
            <h2>Edit Accommodation</h2>
            <form method="POST">
                <input type="hidden" name="accommodation_id" id="edit_id">
                <div class="form-group">
                    <label>Type</label>
                    <select name="type" id="edit_type" required>
                        <option value="Room">Room</option>
                        <option value="Cottage">Cottage</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="edit_name" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" id="edit_category" required>
                        <option value="Standard">Standard</option>
                        <option value="Deluxe">Deluxe</option>
                        <option value="VIP">VIP</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Capacity</label>
                    <input type="number" name="capacity" id="edit_capacity" min="1" required>
                </div>
                <div class="form-group">
                    <label>Price per Night</label>
                    <input type="number" name="price" id="edit_price" step="0.01" min="0" required>
                </div>
                <button type="submit" name="edit_accommodation" class="btn btn-primary">Update</button>
                <button type="button" onclick="closeModal()" class="btn btn-secondary">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function editAccommodation(id, type, name, category, capacity, price) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_type').value = type;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_category').value = category;
            document.getElementById('edit_capacity').value = capacity;
            document.getElementById('edit_price').value = price;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Search functionality
        document.getElementById('searchReservations')?.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll('#reservations-section tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            });
        });

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tab = this.getAttribute('data-tab');
                
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                document.querySelectorAll('.tab-content').forEach(section => section.style.display = 'none');
                document.getElementById(tab + '-section').style.display = 'block';
            });
        });
    </script>
</body>
</html>
