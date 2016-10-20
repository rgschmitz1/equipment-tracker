CREATE DATABASE IF NOT EXISTS `mfgtest`;

USE `mfgtest`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT,
    `username` VARCHAR(30),
    `status` TINYINT(1) DEFAULT '1',
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
    `serial` INT(8) UNSIGNED ZEROFILL,
    `product` VARCHAR(30),
    `description` VARCHAR(120),
    `cfgnum` VARCHAR(18),
    `revision` VARCHAR(3),
    `eco` TINYINT(2) UNSIGNED ZEROFILL,
    `last_claim_id` INT,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `claim_history` (
    `id` INT AUTO_INCREMENT,
    `product_id` INT,
    `user_id` INT DEFAULT '1',
    `claim_date` DATETIME,
    `approved` TINYINT(1),
    PRIMARY KEY (`id`)
);

INSERT INTO `users` (`id`, `username`)
    VALUES ('1', 'Unclaimed')
    ON DUPLICATE KEY UPDATE `id` = `id`;
INSERT INTO `adminusers` (`id`, `username`, `password`)
    VALUES ('1', 'admin', '$2y$10$U8/uwfOnU.jQGwGk7cqWf.t7KVZjE9C.IH9GGH4nRv3plFzLz6mWm')
    ON DUPLICATE KEY UPDATE `id` = `id`;
