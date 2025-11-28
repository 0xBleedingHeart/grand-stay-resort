-- Database: Apartment/Boarding House Reservation System
-- Created based on user specifications

CREATE DATABASE IF NOT EXISTS reservation_system;
USE reservation_system;

-- --------------------------------------------------------

--
-- Users Table (for authentication)
--
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- --------------------------------------------------------

--
-- 1. Guests Table
-- Stores information about the customers making the reservation.
--
CREATE TABLE guests (
    guest_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    contact_number VARCHAR(20) NOT NULL,
    email VARCHAR(100),
    address TEXT
);

-- --------------------------------------------------------

--
-- 2. Accommodation Table
-- Stores all available rooms and cottages.
--
CREATE TABLE accommodations (
    accommodation_id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(20) NOT NULL, -- Either 'Room' or 'Cottage'
    name VARCHAR(50) NOT NULL, -- Name or code of the room/cottage
    category VARCHAR(20),      -- Example: 'VIP', 'Standard', 'Deluxe'
    capacity INT NOT NULL,
    price_per_night DECIMAL(10, 2) NOT NULL,
    status VARCHAR(20) DEFAULT 'Available' -- Available, Reserved, Under Maintenance
);

-- --------------------------------------------------------

--
-- 3. Reservations Table
-- Stores details of each reservation.
-- Note: Combines fields from the top and bottom screenshots provided.
--
CREATE TABLE reservations (
    reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    guest_id INT NOT NULL,
    accommodation_id INT NOT NULL,
    check_in_date DATE NOT NULL,
    check_out_date DATE NOT NULL,
    num_pax INT NOT NULL,
    total_price DECIMAL(10, 2),
    reservation_status VARCHAR(20) DEFAULT 'Pending', -- Pending, Confirmed, Cancelled
    date_reserved DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign Key Constraints
    FOREIGN KEY (guest_id) REFERENCES guests(guest_id) ON DELETE CASCADE,
    FOREIGN KEY (accommodation_id) REFERENCES accommodations(accommodation_id) ON DELETE CASCADE
);

-- --------------------------------------------------------

--
-- 4. Add-Ons Table
-- Lists optional items guests can add.
--
CREATE TABLE addons (
    addon_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL, -- e.g., Extra Bed, Pillow
    price DECIMAL(10, 2) NOT NULL
);

-- --------------------------------------------------------

--
-- 5. Reservation_Addons Table
-- Connects reservations to the add-ons chosen by guests.
--
CREATE TABLE reservation_addons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    addon_id INT NOT NULL,
    quantity INT DEFAULT 1,
    subtotal DECIMAL(10, 2) NOT NULL,
    
    -- Foreign Key Constraints
    FOREIGN KEY (reservation_id) REFERENCES reservations(reservation_id) ON DELETE CASCADE,
    FOREIGN KEY (addon_id) REFERENCES addons(addon_id) ON DELETE CASCADE
);

-- --------------------------------------------------------

--
-- 6. Table_Reservation Table
-- Stores if the guest also reserves a dining table or function area.
--
CREATE TABLE table_reservations (
    table_reservation_id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    table_number VARCHAR(10) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    status VARCHAR(20) DEFAULT 'Reserved', -- Reserved, Available, Cancelled
    
    -- Foreign Key Constraint
    FOREIGN KEY (reservation_id) REFERENCES reservations(reservation_id) ON DELETE CASCADE
);

-- --------------------------------------------------------

--
-- 7. Payments Table
-- Keeps track of payments made for reservations.
--
CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    reservation_id INT NOT NULL,
    payment_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    amount_paid DECIMAL(10, 2) NOT NULL,
    payment_method VARCHAR(30) NOT NULL, -- Cash, GCash, Card, etc.
    payment_status VARCHAR(20) DEFAULT 'Unpaid', -- Paid, Unpaid, Partial
    
    -- Foreign Key Constraint
    FOREIGN KEY (reservation_id) REFERENCES reservations(reservation_id) ON DELETE CASCADE
);
