USE test;

CREATE TABLE `item` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `price` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `order` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user`  INT NOT NULL,
  `status` VARCHAR(10) NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE `order_position` (
  `order` INT NOT NULL,
  `item`  INT NOT NULL,
  `quantity` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`order`, `item`),
  FOREIGN KEY (`order`) REFERENCES `order` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`item`) REFERENCES `item` (`id`) ON DELETE RESTRICT
);