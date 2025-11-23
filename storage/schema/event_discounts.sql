CREATE TABLE `event_discounts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `event_id` BIGINT UNSIGNED NOT NULL,
    `promo_code` VARCHAR(50) NOT NULL UNIQUE,
    `discount_type` ENUM('percentage', 'fixed') DEFAULT 'percentage',
    `discount_value` DECIMAL(10,2) NOT NULL,
    `promo_valid_until` DATE NULL,
    `promo_usage_limit` INT NULL,
    `used_count` INT DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;