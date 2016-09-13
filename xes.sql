CREATE DATABASE IF NOT EXISTS manufacturing;

USE manufacturing;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT,
    `username` VARCHAR(30),
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `adminusers` (
    `id` INT AUTO_INCREMENT,
    `username` VARCHAR(30),
    `password` CHAR(255),
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `products` (
    `id` INT AUTO_INCREMENT,
    `product` VARCHAR(30),
    `description` VARCHAR(120),
    `serial` INT(8) UNSIGNED ZEROFILL,
    `last_claim_id` INT,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `claim_history` (
    `id` INT AUTO_INCREMENT,
    `product_id` INT,
    `user_id` INT DEFAULT 1,
    `claim_date` DATETIME,
    `approved` TINYINT(1),
    PRIMARY KEY (`id`)
);

INSERT INTO `users` (username) VALUES ('Unclaimed');
INSERT INTO `adminusers` (username, password) VALUES ('admin', '$2y$10$U8/uwfOnU.jQGwGk7cqWf.t7KVZjE9C.IH9GGH4nRv3plFzLz6mWm');
