CREATE TABLE resource_purchases (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `resource_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `amount` DECIMAL(10, 2) NOT NULL,
    `payment_status` ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    `payment_method` VARCHAR(50),
    `transaction_id` VARCHAR(100),
    `purchased_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    
    INDEX `idx_resource_id` (`resource_id`),
    INDEX `idx_user_id` (`user_id`),
    INDEX `idx_payment_status` (`payment_status`),
    INDEX `idx_purchased_at` (`purchased_at`),
    UNIQUE KEY `unique_transaction` (`transaction_id`),
    FOREIGN KEY (`resource_id`) REFERENCES resources(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES users(`id`) ON DELETE CASCADE
);