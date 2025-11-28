<?php
require_once 'config/database.php';
require_once 'config/session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - The Grand Stay Resort</title>
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
            <h1>Resort Photo Gallery</h1>
            <p>Explore our beautiful resort through categorized photos.</p>
        </div>

        <div class="text-center" style="margin: 30px 0;">
            <button class="btn btn-primary" style="max-width: 200px;">All Photos</button>
        </div>

        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=400" alt="Ocean View Suite">
                <div class="gallery-item-title">Ocean View Suite</div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1602002418082-a4443e081dd1?w=400" alt="Private Cottage">
                <div class="gallery-item-title">Private Cottage</div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=400" alt="Signature Restaurant">
                <div class="gallery-item-title">Signature Restaurant</div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1514933651103-005eec06c04b?w=400" alt="Rooftop Bar">
                <div class="gallery-item-title">Rooftop Bar</div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1575429198097-0414ec08e8cd?w=400" alt="Infinity Pool">
                <div class="gallery-item-title">Infinity Pool</div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=400" alt="The Grand Spa">
                <div class="gallery-item-title">The Grand Spa</div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=400" alt="Private Beach">
                <div class="gallery-item-title">Private Beach</div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1585320806297-9794b3e4eeae?w=400" alt="Tropical Garden">
                <div class="gallery-item-title">Tropical Garden</div>
            </div>
        </div>
    </div>
</body>
</html>
