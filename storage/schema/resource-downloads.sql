CREATE TABLE `resource_downloads` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `resource_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `download_type` ENUM('free', 'paid') NOT NULL,
    `amount_paid` DECIMAL(10, 2) DEFAULT 0.00,
    `ip_address` VARCHAR(45),
    `user_agent` TEXT NOT NULL,
    `downloaded_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    INDEX `idx_resource_id` (`resource_id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_downloaded_at` (`downloaded_at`),
    INDEX `idx_download_type` (`download_type`),
    FOREIGN KEY (`resource_id`) REFERENCES resources(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES users(`id`) ON DELETE SET NULL
);