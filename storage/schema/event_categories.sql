CREATE TABLE `event_categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `event_id` BIGINT UNSIGNED NOT NULL,
    `type_id` BIGINT UNSIGNED NOT NULL,
   `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    UNIQUE KEY `unique_event_category` (`event_id`, `type_id`),
    INDEX `idx_event_id` (`event_id`),
    INDEX `idx_type_id` (`type_id`),
    FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`type_id`) REFERENCES `event_types`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;