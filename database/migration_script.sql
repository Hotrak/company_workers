-- ----------------------------------------------------------------------------
-- MySQL Workbench Migration
-- Migrated Schemata: workers
-- Source Schemata: company_workers
-- Created: Wed Jul  1 22:19:49 2020
-- Workbench Version: 8.0.20
-- ----------------------------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------------------------
-- Schema workers
-- ----------------------------------------------------------------------------
DROP SCHEMA IF EXISTS `workers` ;
CREATE SCHEMA IF NOT EXISTS `workers` ;

-- ----------------------------------------------------------------------------
-- Table workers.migrations
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `workers`.`migrations` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` VARCHAR(255) NOT NULL,
  `batch` INT(11) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 117
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table workers.oauth_access_tokens
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `workers`.`oauth_access_tokens` (
  `id` VARCHAR(100) NOT NULL,
  `user_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `client_id` BIGINT(20) UNSIGNED NOT NULL,
  `name` VARCHAR(255) NULL DEFAULT NULL,
  `scopes` TEXT NULL DEFAULT NULL,
  `revoked` TINYINT(1) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  `expires_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `oauth_access_tokens_user_id_index` (`user_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table workers.oauth_auth_codes
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `workers`.`oauth_auth_codes` (
  `id` VARCHAR(100) NOT NULL,
  `user_id` BIGINT(20) UNSIGNED NOT NULL,
  `client_id` BIGINT(20) UNSIGNED NOT NULL,
  `scopes` TEXT NULL DEFAULT NULL,
  `revoked` TINYINT(1) NOT NULL,
  `expires_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `oauth_auth_codes_user_id_index` (`user_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table workers.oauth_clients
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `workers`.`oauth_clients` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `secret` VARCHAR(100) NULL DEFAULT NULL,
  `provider` VARCHAR(255) NULL DEFAULT NULL,
  `redirect` TEXT NOT NULL,
  `personal_access_client` TINYINT(1) NOT NULL,
  `password_client` TINYINT(1) NOT NULL,
  `revoked` TINYINT(1) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `oauth_clients_user_id_index` (`user_id` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table workers.oauth_personal_access_clients
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `workers`.`oauth_personal_access_clients` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` BIGINT(20) UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table workers.oauth_refresh_tokens
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `workers`.`oauth_refresh_tokens` (
  `id` VARCHAR(100) NOT NULL,
  `access_token_id` VARCHAR(100) NOT NULL,
  `revoked` TINYINT(1) NOT NULL,
  `expires_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table workers.password_resets
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `workers`.`password_resets` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  INDEX `password_resets_email_index` (`email` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table workers.positions
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `workers`.`positions` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `lvl` INT(11) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `positions_name_unique` (`name` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table workers.users
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `workers`.`users` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(100) NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `users_email_unique` (`email` ASC))
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- Table workers.workers
-- ----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `workers`.`workers` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `surname` VARCHAR(255) NOT NULL,
  `patronymic` VARCHAR(255) NOT NULL,
  `position_id` BIGINT(20) UNSIGNED NULL DEFAULT NULL,
  `employment_date` DATE NOT NULL,
  `salary` DOUBLE(8,2) NOT NULL,
  `img` VARCHAR(255) NOT NULL,
  `parent_id` BIGINT(20) NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `workers_position_id_foreign` (`position_id` ASC),
  CONSTRAINT `workers_position_id_foreign`
    FOREIGN KEY (`position_id`)
    REFERENCES `workers`.`positions` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 49992
DEFAULT CHARACTER SET = utf8mb4
COLLATE = utf8mb4_unicode_ci;
SET FOREIGN_KEY_CHECKS = 1;
