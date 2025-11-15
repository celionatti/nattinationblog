CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    
    -- Core identity
    name VARCHAR(255) NOT NULL,
    username VARCHAR(100) UNIQUE,
    email VARCHAR(255) UNIQUE,
    email_verified_at DATETIME NULL,

    -- Local auth
    password VARCHAR(255) NULL,

    -- User role system
    role ENUM('admin', 'author', 'user') DEFAULT 'user',

    -- Profile fields
    bio TEXT NULL,
    avatar VARCHAR(255) NULL,

    -- Account status
    status ENUM('active', 'inactive', 'banned') DEFAULT 'active',

    -- Security & Analytics
    last_login_ip VARCHAR(100) NULL,
    last_login_at DATETIME NULL,
    two_factor_secret VARCHAR(255) NULL,

    -- Timestamps
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL
);

CREATE TABLE oauth_accounts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT UNSIGNED NOT NULL,

    provider VARCHAR(50) NOT NULL,        -- google, github, facebook
    provider_id VARCHAR(255) NOT NULL,    -- external provider unique ID

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY unique_provider_user (provider, provider_id),

    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,

    INDEX idx_user_id (user_id),
    INDEX idx_provider (provider, provider_id)
);

CREATE TABLE remember_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT UNSIGNED NOT NULL,

    token VARCHAR(255) NOT NULL,            -- store hashed token only
    user_agent VARCHAR(255) NULL,           -- device/browser info
    ip_address VARCHAR(100) NULL,           -- IP that created session
    expires_at DATETIME NOT NULL,           -- token validity period

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,

    UNIQUE KEY unique_token (token),
    INDEX idx_user_id (user_id)
);

CREATE TABLE password_resets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_id BIGINT UNSIGNED NOT NULL,

    reset_token VARCHAR(255) NOT NULL,      -- store hashed token only
    expires_at DATETIME NOT NULL,           -- token validity period

    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,

    UNIQUE KEY unique_reset_token (reset_token),
    INDEX idx_user_id (user_id)
);

-- CREATE TABLE two_factor_recovery_codes (
--     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

--     user_id BIGINT UNSIGNED NOT NULL,

--     recovery_code VARCHAR(255) NOT NULL,    -- store hashed code only
--     used BOOLEAN DEFAULT FALSE,

--     created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

--     FOREIGN KEY (user_id) REFERENCES users(id)
--         ON DELETE CASCADE,

--     UNIQUE KEY unique_recovery_code (recovery_code),
--     INDEX idx_user_id (user_id)
-- );


