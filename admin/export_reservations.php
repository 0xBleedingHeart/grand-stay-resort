<?php
require_once '../config/database.php';
require_once '../config/session.php';
requireAdmin();

$reservations = mysqli_query($conn, "SELECT r.*, a.name as room_name, g.full_name, g.email, g.contact_number 
    FROM reservations r 
    JOIN accommodations a ON r.accommodation_id = a.accommodation_id 
    JOIN guests g ON r.guest_id = g.guest_id 
    ORDER BY r.date_reserved DESC");

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="reservations_' . date('Y-m-d') . '.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Reservation ID', 'Guest Name', 'Email', 'Contact', 'Room', 'Check-in', 'Check-out', 'Guests', 'Total', 'Status', 'Date Reserved']);

while ($row = mysqli_fetch_assoc($reservations)) {
    fputcsv($output, [
        $row['reservation_id'],
        $row['full_name'],
        $row['email'],
        $row['contact_number'],
        $row['room_name'],
        $row['check_in_date'],
        $row['check_out_date'],
        $row['num_pax'],
        $row['total_price'],
        $row['reservation_status'],
        $row['date_reserved']
    ]);
}

fclose($output);
exit;
?>
