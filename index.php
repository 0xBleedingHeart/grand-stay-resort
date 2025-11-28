<?php
require_once 'config/database.php';
require_once 'config/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Grand Stay Resort - Home</title>
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

    <div style="padding: 40px 20px; text-align: center; background: #f8f9fa;">
        <?php if (!isLoggedIn()): ?>
            <div style="display: flex; gap: 15px; justify-content: center;">
                <a href="login.php" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1rem; display: inline-block; width: 25%;">Login</a>
                <a href="signup.php" class="btn btn-secondary" style="padding: 15px 40px; font-size: 1.1rem; display: inline-block; width: 25%;">Sign Up</a>
            </div>
        <?php else: ?>
            <div style="display: flex; gap: 15px; justify-content: center;">
                <a href="user/dashboard.php" class="btn btn-primary" style="padding: 15px 40px; font-size: 1.1rem; display: inline-block; width: auto;">My Dashboard</a>
                <a href="book.php" class="btn btn-secondary" style="padding: 15px 40px; font-size: 1.1rem; display: inline-block; width: auto;">Book Now</a>
            </div>
        <?php endif; ?>
    </div>

    <div class="hero" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=1200'); background-size: cover; background-position: center; min-height: 500px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; text-align: center; padding: 60px 20px;">
        <h1 style="font-size: 3rem; margin-bottom: 20px; font-weight: 600;">Welcome to The Grand Stay Resort</h1>
        <p style="font-size: 1.3rem; margin-bottom: 0; max-width: 600px;">Relax and unwind in our luxury resort with top-notch amenities and services.</p>
    </div>

    <div class="container" style="padding: 60px 20px;">
        <div style="text-align: center; margin-bottom: 50px;">
            <h2 style="font-size: 2.5rem; margin-bottom: 15px; color: #333;">Experience Luxury & Comfort</h2>
            <p style="font-size: 1.1rem; color: #666; max-width: 800px; margin: 0 auto;">Discover the perfect blend of elegance and relaxation at The Grand Stay Resort. Our world-class facilities and exceptional service ensure an unforgettable experience.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-bottom: 50px;">
            <div style="text-align: center; padding: 30px; background: #f8f9fa; border-radius: 8px;">
                <div style="font-size: 3rem; margin-bottom: 15px;">🏨</div>
                <h3 style="margin-bottom: 10px; color: #333;">Premium Accommodations</h3>
                <p style="color: #666;">Choose from our selection of luxurious rooms and private cottages designed for your comfort.</p>
            </div>
            <div style="text-align: center; padding: 30px; background: #f8f9fa; border-radius: 8px;">
                <div style="font-size: 3rem; margin-bottom: 15px;">🍽️</div>
                <h3 style="margin-bottom: 10px; color: #333;">Fine Dining</h3>
                <p style="color: #666;">Savor exquisite cuisine at our signature restaurant and rooftop bar with stunning views.</p>
            </div>
            <div style="text-align: center; padding: 30px; background: #f8f9fa; border-radius: 8px;">
                <div style="font-size: 3rem; margin-bottom: 15px;">🏊</div>
                <h3 style="margin-bottom: 10px; color: #333;">World-Class Amenities</h3>
                <p style="color: #666;">Enjoy our infinity pool, grand spa, private beach, and tropical gardens.</p>
            </div>
        </div>
    </div>

    <footer style="background: #2c3e50; color: white; padding: 50px 20px 30px;">
        <div style="max-width: 1200px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; margin-bottom: 30px;">
            <div>
                <h3 style="margin-bottom: 15px;">Quick Links</h3>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="index.php" style="color: #ecf0f1; text-decoration: none;">Home</a>
                    <a href="rooms.php" style="color: #ecf0f1; text-decoration: none;">Rooms & Cottages</a>
                    <a href="dining.php" style="color: #ecf0f1; text-decoration: none;">Dining & Add-ons</a>
                    <a href="gallery.php" style="color: #ecf0f1; text-decoration: none;">Gallery</a>
                </div>
            </div>
            <div>
                <h3 style="margin-bottom: 15px;">Contact Us</h3>
                <div style="display: flex; flex-direction: column; gap: 10px; color: #ecf0f1;">
                    <p style="margin: 0;">📍 450 Grand Avenue, Paradise City</p>
                    <p style="margin: 0;">📞 +1 (800) 555-4422</p>
                    <p style="margin: 0;">✉️ reservations@grandstay.com</p>
                </div>
            </div>
            <div>
                <h3 style="margin-bottom: 15px;">Follow Us</h3>
                <div style="display: flex; gap: 15px;">
                    <a href="#" style="color: #ecf0f1; font-size: 1.5rem; text-decoration: none;">📘</a>
                    <a href="#" style="color: #ecf0f1; font-size: 1.5rem; text-decoration: none;">📷</a>
                    <a href="#" style="color: #ecf0f1; font-size: 1.5rem; text-decoration: none;">🐦</a>
                </div>
            </div>
        </div>
        <div style="text-align: center; padding-top: 20px; border-top: 1px solid #34495e; color: #95a5a6;">
            <p style="margin: 0;">&copy; 2025 The Grand Stay Resort. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
