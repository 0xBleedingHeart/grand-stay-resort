# The Grand Stay Resort - Reservation System

A full-stack PHP web application for managing resort reservations with user authentication and admin portal.

## Features

### User Features
- User registration and login
- View available rooms and cottages
- Book accommodations
- View personal reservations
- Browse gallery
- Contact form

### Admin Features
- Admin dashboard with statistics
- Manage reservations (confirm/cancel)
- Manage accommodations (add/delete)
- View all guests
- Manage add-ons (add/delete)

## Installation

### 1. Database Setup
```bash
# Import the database schema
mysql -u root -p < database.sql
```

Or manually:
1. Open phpMyAdmin or MySQL client
2. Create database: `reservation_system`
3. Import `database.sql`

### 2. Configure Database Connection
Edit `config/database.php` if needed:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'reservation_system');
```

### 3. Create Admin Account
Run this SQL query to create an admin user:
```sql
INSERT INTO users (email, password, full_name, role) 
VALUES ('admin@grandstay.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin');
```
Default admin credentials:
- Email: `admin@grandstay.com`
- Password: `password`

### 4. Add Sample Data (Optional)
```sql
-- Sample Accommodations
INSERT INTO accommodations (type, name, category, capacity, price_per_night, status) VALUES
('Room', 'Ocean View Suite', 'VIP', 2, 5000.00, 'Available'),
('Room', 'Deluxe Room', 'Deluxe', 2, 3000.00, 'Available'),
('Cottage', 'Private Cottage', 'VIP', 4, 8000.00, 'Available'),
('Room', 'Standard Room', 'Standard', 2, 2000.00, 'Available');

-- Sample Add-ons
INSERT INTO addons (name, price) VALUES
('Extra Bed', 500.00),
('Extra Pillow', 100.00),
('Breakfast Buffet', 800.00),
('Airport Transfer', 1500.00);
```

## Usage

### Starting the Application
1. Start your web server (Apache/Nginx)
2. Navigate to `http://localhost/resort/`

### User Flow
1. Sign up for an account or login
2. Browse rooms and cottages
3. Book a stay
4. View reservations in user dashboard

### Admin Flow
1. Login with admin credentials
2. Access admin portal
3. Manage reservations, accommodations, guests, and add-ons

## File Structure
```
resort/
├── admin/
│   ├── dashboard.php
│   ├── reservations.php
│   ├── accommodations.php
│   ├── guests.php
│   └── addons.php
├── assets/
│   └── css/
│       └── style.css
├── config/
│   ├── database.php
│   └── session.php
├── user/
│   └── dashboard.php
├── index.php
├── login.php
├── signup.php
├── logout.php
├── book.php
├── rooms.php
├── dining.php
├── gallery.php
├── contact.php
└── database.sql
```

## Security Notes
- Change default admin password immediately
- Update database credentials in production
- Use HTTPS in production
- Implement CSRF protection for production use
- Add input validation and sanitization

## Technologies Used
- PHP 7.4+
- MySQL 5.7+
- HTML5
- CSS3
- mysqli extension

## License
MIT License
