CREATE DATABASE IF NOT EXISTS mfgtest;

USE mfgtest;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT,
    `username` VARCHAR(30),
    `password` CHAR(40),
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT,
    `product` VARCHAR(30),
    `description` VARCHAR(80),
    `serial` INT,
    `user_id` INT DEFAULT 1,
    PRIMARY KEY (`id`)
);

INSERT INTO `users` (username) VALUES ('unknown');
INSERT INTO `users` (username, password) VALUES ('admin', SHA('admin'));
