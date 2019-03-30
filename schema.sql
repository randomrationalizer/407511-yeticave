CREATE DATABASE `407511-yeticave`
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;
USE `407511-yeticave`;

CREATE TABLE `category` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(64) NOT NULL UNIQUE
);

CREATE TABLE `lot` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `start_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `name` VARCHAR(255) NOT NULL,
    `description` TEXT(500) NOT NULL,
    `img_path` VARCHAR(255) NOT NULL,
    `start_price` INT NOT NULL,
    `end_date` DATETIME NOT NULL,
    `step` INT NOT NULL,
    `author_id` INT NOT NULL,
    `winner_id` INT DEFAULT NULL,
    `category_id` INT NOT NULL
);

CREATE TABLE `bid` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `price` INT NOT NULL,
    `user_id` INT NOT NULL,
    `lot_id` INT NOT NULL
);

CREATE TABLE `user` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `registration_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `email` VARCHAR(128) NOT NULL UNIQUE,
    `username` VARCHAR(128) NOT NULL,
    `password` VARCHAR(64) NOT NULL,
    `avatar_path` VARCHAR(255),
    `contacts` VARCHAR(255) NOT NULL
);

CREATE FULLTEXT INDEX lot_ft_search 
ON `lot`(`name`, `description`);
