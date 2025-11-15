-- Settings table to store all configuration options
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(255) NOT NULL,
  `setting_value` text,
  `setting_type` enum('string','boolean','integer','json') DEFAULT 'string',
  `setting_group` varchar(100) DEFAULT 'general',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_setting_key` (`setting_key`),
  KEY `setting_group` (`setting_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default settings
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_type`, `setting_group`) VALUES
('site_title', 'Natti Nation', 'string', 'general'),
('site_tagline', 'Just another WordPress site', 'string', 'general'),
('site_url', 'https://blogname.com', 'string', 'general'),
('admin_email', 'admin@blogname.com', 'string', 'general'),
('timezone', 'utc-8', 'string', 'general'),
('membership', '1', 'boolean', 'general'),
('default_category', '1', 'integer', 'writing'),
('default_post_format', 'standard', 'string', 'writing'),
('homepage_display', 'latest', 'string', 'reading'),
('posts_per_page', '10', 'integer', 'reading'),
('allow_comments', '1', 'boolean', 'discussion'),
('comment_moderation', '0', 'boolean', 'discussion');