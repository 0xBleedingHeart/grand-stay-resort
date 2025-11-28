<?php
require_once 'config/database.php';
require_once 'config/session.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name'] ?? '');
    
    if (empty($email) || empty($password)) {
        $error = 'Email and password are required';
    } else {
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = 'Email already exists';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (email, password, full_name) VALUES ('$email', '$hashed', '$full_name')";
            if (mysqli_query($conn, $sql)) {
                $success = 'Account created successfully! You can now login.';
            } else {
                $error = 'Registration failed';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - The Grand Stay Resort</title>
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

    <div class="auth-container">
        <div class="auth-header">
            <h1>Start your luxurious stay with us.</h1>
            <p>Sign up to view live availability or "Book Now" for a quick reservation.</p>
        </div>

        <div class="auth-card">
            <h2>Create Your Account</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <input type="text" name="full_name" placeholder="Full Name (Optional)">
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Choose Password" required>
                </div>
                <button type="submit" class="btn btn-primary">Sign Up</button>
            </form>
            
            <p class="text-center mt-20">Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>
</body>
</html>
