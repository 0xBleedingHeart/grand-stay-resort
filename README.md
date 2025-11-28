# The Grand Stay Resort - Reservation System

A comprehensive PHP-based resort booking and management system with user authentication, payment tracking, and admin dashboard.

## 🌟 Features

### User Features
- **User Registration & Login** - Secure authentication system
- **Browse Accommodations** - View available rooms and cottages with pricing
- **Real-time Booking** - Book accommodations with date validation and availability checking
- **Add-ons Selection** - Choose optional services (meals, spa, transfers, etc.)
- **Payment Methods** - Support for Cash, GCash, PayMaya, Credit/Debit Card, Bank Transfer
- **User Dashboard** - Manage profile, view bookings, payment history
- **Cancel Bookings** - Cancel pending or confirmed reservations
- **Print Receipts** - Generate printable booking confirmations
- **Gallery** - Browse resort photos
- **Contact Form** - Get in touch with resort staff

### Admin Features
- **Comprehensive Dashboard** - Overview of reservations, revenue, and statistics
- **Reservation Management** - Confirm, cancel, or delete bookings
- **Accommodation Management** - Add, edit, or delete rooms/cottages
- **User Management** - View and manage registered users
- **Payment Management** - Track payments and mark as paid
- **Reports & Analytics** - View booking statistics, revenue reports, popular rooms
- **Search & Filter** - Quick search across reservations
- **Export Data** - Export reservations to CSV

## 📋 Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- XAMPP/WAMP/LAMP (recommended for local development)

## 🚀 Installation

### 1. Clone/Download the Project
```bash
# Place the project in your web server directory
# For XAMPP: C:\xampp\htdocs\resort
# For WAMP: C:\wamp\www\resort
```

### 2. Database Setup

#### Option A: Using phpMyAdmin
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database named `reservation_system`
3. Import the `database.sql` file

#### Option B: Using MySQL Command Line
```bash
mysql -u root -p
CREATE DATABASE reservation_system;
USE reservation_system;
SOURCE /path/to/database.sql;
```

### 3. Configure Database Connection
Edit `config/database.php` if needed (default settings work for XAMPP):
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'reservation_system');
```
### 4. Access the Application
```
http://localhost/resort/
```

## 🔐 Default Credentials

### Admin Account
- **Email:** admin@grandstay.com
- **Password:** password

### Test User Account
Create your own by signing up at: http://localhost/resort/signup.php

## 📖 User Workflow

### For Guests/Users

1. **Registration**
   - Navigate to http://localhost/resort/signup.php
   - Fill in email, password, and optional full name
   - Click "Sign Up"

2. **Login**
   - Go to http://localhost/resort/login.php
   - Enter email and password
   - Click "Login"

3. **Browse Accommodations**
   - Click "Rooms & Cottages" in navigation
   - View available rooms with prices and details
   - Check "Dining & Add-ons" for additional services

4. **Make a Booking**
   - Click "Book Now" button
   - Your profile information is auto-filled
   - Select room/cottage from dropdown
   - Choose check-in and check-out dates
   - Enter number of guests
   - Select optional add-ons (quantity)
   - Choose payment method
   - Enter account number and reference number
   - Click "Complete Booking"

5. **Manage Bookings**
   - Go to "My Dashboard"
   - View all your reservations in "Active Bookings" tab
   - Print receipts by clicking "Print" button
   - Cancel bookings if needed (Pending/Confirmed only)

6. **Update Profile**
   - In dashboard, go to "Status & Profile" tab
   - Update full name, phone, and address
   - Click "Update Profile"

7. **View Payment History**
   - In dashboard, click "Payment Method" tab
   - View all payment records with status

### For Administrators

1. **Login as Admin**
   - Go to http://localhost/resort/login.php
   - Use admin credentials
   - Automatically redirected to admin dashboard

2. **Dashboard Overview**
   - View key statistics: Total Reservations, Guests, Revenue, Pending Bookings
   - Access different management sections via tabs

3. **Manage Reservations**
   - **Reservations Tab:**
     - View all bookings with guest details
     - Search reservations using search bar
     - Confirm pending reservations
     - Cancel reservations
     - Delete reservations
     - Export to CSV

4. **Manage Accommodations**
   - **Accommodations Tab:**
     - Add new rooms/cottages with form
     - Edit existing accommodations (click Edit button)
     - Delete accommodations
     - View capacity, pricing, and status

5. **Manage Users**
   - **Users Tab:**
     - View all registered users
     - See user details (email, name, phone, role)
     - Delete users (except yourself)

6. **Manage Payments**
   - **Payments Tab:**
     - View all payment records
     - Check account numbers and reference numbers
     - Mark payments as "Paid" after verification
     - Track payment methods and amounts

7. **View Reports**
   - **Reports Tab:**
     - View confirmed vs cancelled bookings
     - Check paid vs unpaid amounts
     - See most popular rooms/cottages
     - Analyze booking trends

## 📁 Project Structure

```
resort/
├── admin/
│   ├── dashboard.php          # Admin dashboard with all management features
│   └── export_reservations.php # CSV export functionality
├── assets/
│   └── css/
│       └── style.css          # Main stylesheet
├── config/
│   ├── database.php           # Database configuration
│   └── session.php            # Session management & authentication
├── user/
│   ├── dashboard.php          # User dashboard
│   └── print_receipt.php      # Printable receipt page
├── index.php                  # Homepage
├── login.php                  # Login page
├── signup.php                 # Registration page
├── logout.php                 # Logout handler
├── book.php                   # Booking form
├── rooms.php                  # Rooms & cottages listing
├── dining.php                 # Add-ons listing
├── gallery.php                # Photo gallery
├── contact.php                # Contact page
├── database.sql               # Database schema
└── README.md                  # This file
```

## 🗄️ Database Schema

### Main Tables
- **users** - User accounts (email, password, role, profile info)
- **guests** - Guest information for reservations
- **accommodations** - Rooms and cottages (type, name, price, capacity)
- **reservations** - Booking records with dates and status
- **addons** - Available add-on services
- **reservation_addons** - Link between reservations and add-ons
- **payments** - Payment records with method and status

## 🎨 Key Features Explained

### Date Validation
- Prevents booking dates in the past
- Ensures check-out is after check-in
- Real-time validation on form submission

### Availability Checking
- Checks for overlapping reservations
- Prevents double-booking of accommodations
- Shows error if dates are unavailable

### Payment Tracking
- Records payment method and account details
- Tracks reference numbers for verification
- Admin can mark payments as paid after verification

### Add-ons System
- Users can select multiple add-ons with quantities
- Automatically calculated in total price
- Stored separately for detailed tracking

### Print Receipt
- Professional formatted receipt
- Includes all booking and payment details
- Print-friendly CSS (hides buttons when printing)

## 🔒 Security Notes

**For Production Deployment:**
1. Change default admin password immediately
2. Update database credentials
3. Use HTTPS (SSL certificate)
4. Implement CSRF protection
5. Add input validation and sanitization
6. Use prepared statements (upgrade from mysqli_real_escape_string)
7. Set proper file permissions
8. Enable error logging (disable display_errors)
9. Regular database backups

## 🐛 Troubleshooting

### Common Issues

**1. Database Connection Error**
- Check if MySQL is running
- Verify database credentials in `config/database.php`
- Ensure database `reservation_system` exists

**2. Admin Redirect Issues**
- Clear browser cache and cookies
- Check if admin account exists in database
- Verify session is working (check php.ini session settings)

**3. Booking Form Not Showing Rooms**
- Run `insert_sample_data.php` to populate accommodations
- Check if accommodations table has data
- Verify accommodation status is 'Available'

**4. Payment/Profile Fields Missing**
- Run all setup scripts in order
- Check if columns exist in database tables
- Re-import database.sql if needed

**5. Images Not Loading**
- Check internet connection (uses Unsplash CDN)
- Verify image URLs are accessible
- Check browser console for errors

## 📞 Support

For issues or questions:
- Email: reservations@grandstay.com
- Phone: +1 (800) 555-4422

## 📝 License

MIT License - Free to use and modify

## 🎯 Future Enhancements

Potential features to add:
- Email notifications for bookings
- SMS notifications
- Online payment gateway integration
- Calendar view for availability
- Multi-language support
- Customer reviews and ratings
- Promo code system
- Partial payment support
- Image upload for accommodations
- Advanced reporting with charts

## 👨‍💻 Development

Built with:
- PHP 7.4+
- MySQL 5.7+
- HTML5
- CSS3
- JavaScript (Vanilla)
- mysqli extension

---

**Version:** 1.0.0  
**Last Updated:** November 28, 2025  
**Developed by:** The Grand Stay Resort Development Team
