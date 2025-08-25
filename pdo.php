<?php
//connect to the database without a database
$dbname = 'GalleryApp';
$host = 'shams-ThinkPad-E15-Gen-2';
$user = 'shams';
$pass = 'Seen@7122003';

// use backticks instead of single quotes
try {
    $pdo = new PDO("mysql:host=$host;port=3306;", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Create the database if it doesn't exist
$pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
$pdo->exec("USE `$dbname`");

// Create the users table if it doesn't exist
// we made the id to be auto & auto incremented
$pdo->exec("
CREATE TABLE IF NOT EXISTS Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    password_hash VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255) DEFAULT NULL,
    status VARCHAR(20) DEFAULT 'active' CHECK (status IN ('active','banned','suspended')),
    role ENUM('user', 'admin') DEFAULT 'user'
) ENGINE=InnoDB;
");

// Images table
$pdo->exec("
CREATE TABLE IF NOT EXISTS Images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    description TEXT,
    image_location VARCHAR(255) NOT NULL,
    image_filename VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB;
");


// Single quotes ' ' → means string literal
// Backticks ` ` → means identifier (database, table, or column name)
//to delete the DB-> $pdo->exec("DROP DATABASE `$dbname`");
