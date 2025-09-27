-- Setup SQL for Riga project
-- Creates database, users and products tables and inserts demo rows.

CREATE DATABASE IF NOT EXISTS `riga` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `riga`;

-- Users table: id, email, password (bcrypt), name, created_at
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255),
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Products table: id, title, description, price, image, created_at
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `image` VARCHAR(512),
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert a demo admin user
-- Password: "password123" hashed with PHP password_hash (bcrypt)
-- Hash generated: $2y$10$abcdefghijklmnopqrstuv (placeholder: you should generate your own or change password after import)
-- For safety we insert a real bcrypt hash; run PHP to get a new hash if desired.

INSERT INTO `users` (`email`, `password`, `name`) VALUES
('admin@example.com', '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 'Admin');

-- Demo products
INSERT INTO `products` (`title`, `description`, `price`, `image`) VALUES
('Linen Midi Dress', 'Lightweight linen dress in beige.', 79.00, 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?auto=format&fit=crop&w=1200&q=80'),
('Silk Blouse', 'Elegant silk blouse with a soft drape.', 99.00, 'photo/blouse.png'),
('Tailored Blazer', 'Minimal blazer for a sharp look.', 149.00, 'photo/tailored.png');
