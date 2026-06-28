-- Create database
CREATE DATABASE IF NOT EXISTS dailyneeds;
USE dailyneeds;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    icon VARCHAR(50) DEFAULT 'fa-shopping-bag'
);

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT 'default.jpg',
    stock INT DEFAULT 10,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Cart table
CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (user_id, product_id)
);

-- Insert Categories
INSERT INTO categories (name, icon) VALUES
('Vegetables', 'fa-carrot'),
('Fruits', 'fa-apple-alt'),
('Appliances', 'fa-blender'),
('Stationery', 'fa-pen');

-- Insert Products
INSERT INTO products (category_id, name, description, price, image) VALUES
-- Vegetables (category_id = 1)
(1, 'Carrot', 'Fresh organic carrots', 40.00, 'carrot.jpg'),
(1, 'Beetroot', 'Fresh beetroot', 35.00, 'beetroot.jpg'),
(1, 'Tomato', 'Ripe red tomatoes', 30.00, 'tomato.jpg'),
(1, 'Potato', 'Fresh potatoes', 25.00, 'potato.jpg'),
(1, 'Onion', 'Fresh onions', 20.00, 'onion.jpg'),

-- Fruits (category_id = 2)
(2, 'Mango', 'Sweet Alphonso mangoes', 120.00, 'mango.jpg'),
(2, 'Orange', 'Juicy oranges', 80.00, 'orange.jpg'),
(2, 'Pineapple', 'Fresh pineapple', 60.00, 'pineapple.jpg'),
(2, 'Apple', 'Crisp red apples', 90.00, 'apple.jpg'),
(2, 'Banana', 'Fresh bananas', 40.00, 'banana.jpg'),

-- Appliances (category_id = 3)
(3, 'Mixer Grinder', '500W mixer grinder', 2500.00, 'mixer.jpg'),
(3, 'Induction Stove', '2000W induction cooktop', 1800.00, 'induction.jpg'),
(3, 'Water Heater', '25L water heater', 3500.00, 'heater.jpg'),
(3, 'Electric Kettle', '1500W electric kettle', 1200.00, 'kettle.jpg'),
(3, 'Microwave Oven', '700W microwave oven', 4500.00, 'microwave.jpg'),

-- Stationery (category_id = 4)
(4, 'Pens', 'Pack of 10 ball pens', 50.00, 'pens.jpg'),
(4, 'Laptop Table', 'Foldable laptop table', 800.00, 'table.jpg'),
(4, 'Gum', 'White craft glue 100ml', 30.00, 'gum.jpg'),
(4, 'Notebook', '200 pages ruled notebook', 60.00, 'notebook.jpg'),
(4, 'Marker Set', 'Pack of 12 color markers', 120.00, 'marker.jpg');
