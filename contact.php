<?php
require_once 'config/database.php';
require_once 'config/session.php';

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = 'Message sent successfully! We will get back to you soon.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - The Grand Stay Resort</title>
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
            <h1>Contact Us</h1>
            <p>Get in touch with our team</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <div class="contact-grid">
            <div class="contact-info">
                <h2>Important Contact Information</h2>
                
                <div class="contact-item">
                    <div class="contact-item-icon">📍</div>
                    <div>
                        <strong>Address</strong><br>
                        450 Grand Avenue, Coastal District<br>
                        Paradise City, PC 90210
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-item-icon">📞</div>
                    <div>
                        <strong>Phone</strong><br>
                        +1 (800) 555-4422 (Reservations)
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-item-icon">✉️</div>
                    <div>
                        <strong>Email</strong><br>
                        reservations@grandstay.com
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-item-icon">🕐</div>
                    <div>
                        <strong>Front Desk Hours</strong><br>
                        24/7 (Always Available)
                    </div>
                </div>
            </div>

            <div class="contact-info">
                <h2>Send us a Message</h2>
                <form method="POST">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="full_name" placeholder="Enter your full name" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="Enter your email address" required>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="subject" placeholder="Brief subject line" required>
                    </div>
                    <div class="form-group">
                        <label>Message</label>
                        <textarea name="message" rows="5" placeholder="Type your message here..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
