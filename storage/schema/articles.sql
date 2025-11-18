-- Core articles table
CREATE TABLE `articles` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE,
    `content` LONGTEXT NOT NULL,
    `excerpt` TEXT DEFAULT NULL,
    `featured_image` VARCHAR(500) DEFAULT NULL,
    `status` ENUM('draft', 'published', 'archived') NOT NULL DEFAULT 'draft',
    `author_id` BIGINT UNSIGNED NOT NULL,
    `view_count` INT UNSIGNED NOT NULL DEFAULT 0,
    `comment_count` INT UNSIGNED NOT NULL DEFAULT 0,
    `categories` JSON DEFAULT NULL,
    `like_count` INT UNSIGNED NOT NULL DEFAULT 0,
    `seo_title` VARCHAR(60) NULL,
    `seo_description` VARCHAR(160) NULL,
    `seo_keywords` VARCHAR(255) NULL,
    `published_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX `idx_author_id` (`author_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_published_at` (`published_at`),
    INDEX `idx_slug` (`slug`),
    INDEX `idx_created_at` (`created_at`),
    FULLTEXT INDEX `idx_search` (`title`, `content`, `excerpt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;