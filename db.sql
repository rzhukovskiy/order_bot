CREATE TABLE `command` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `chat_id` INT NOT NULL,
    `class` VARCHAR(255),
    `method` VARCHAR(255),
    `created_at` INT NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `social_id` (`social_id` ASC));
