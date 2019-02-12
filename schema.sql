CREATE DATABASE yeticave407511
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;
USE yeticave407511;

CREATE TABLE category (
    category_name CHAR(64) PRIMARY KEY NOT NULL
);

CREATE TABLE lot (
    id INT AUTO_INCREMENT PRIMARY KEY,
    add_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    lot_name CHAR(255) NOT NULL,
    lot_description TEXT(500) NOT NULL,
    img_path CHAR(255) NOT NULL,
    start_price INT(9) NOT NULL,
    ending_date TIMESTAMP NOT NULL,
    step INT(5) NOT NULL,
    autor_id INT(10) NOT NULL,
    winner_id INT(10),
    category CHAR(64) NOT NULL
);

CREATE TABLE bid (
    id INT AUTO_INCREMENT PRIMARY KEY,
    bid_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    bid_price INT(9) NOT NULL,
    user_id INT(10) NOT NULL,
    lot_id INT(20) NOT NULL
);

CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    registration_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    email CHAR(128) NOT NULL UNIQUE,
    username CHAR(128) NOT NULL,
    password CHAR(64) NOT NULL,
    avatar_path CHAR(255),
    contacts CHAR(255) NOT NULL
);
