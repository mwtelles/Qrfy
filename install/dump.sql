CREATE TABLE `users` (
`user_id` int(11) NOT NULL AUTO_INCREMENT,
`email` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`password` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
`name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
`billing` text COLLATE utf8mb4_unicode_ci,
`api_key` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`token_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
`twofa_secret` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`pending_email` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`email_activation_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
`lost_password_code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
`facebook_id` bigint(20) DEFAULT NULL,
`type` int(11) NOT NULL DEFAULT '0',
`active` int(11) NOT NULL DEFAULT '0',
`plan_id` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
`plan_expiration_date` datetime DEFAULT NULL,
`plan_settings` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
`plan_trial_done` tinyint(4) DEFAULT '0',
`payment_subscription_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
`language` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'english',
`timezone` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT 'UTC',
`date` datetime DEFAULT NULL,
`ip` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
`country` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`last_activity` datetime DEFAULT NULL,
`last_user_agent` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
`total_logins` int(11) DEFAULT '0',
PRIMARY KEY (`user_id`),
KEY `plan_id` (`plan_id`),
KEY `api_key` (`api_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `users` (`user_id`, `email`, `password`, `api_key`, `name`, `token_code`, `email_activation_code`, `lost_password_code`, `facebook_id`, `type`, `active`, `plan_id`, `plan_expiration_date`, `plan_settings`, `plan_trial_done`, `payment_subscription_id`, `language`, `timezone`, `date`, `ip`, `last_activity`, `last_user_agent`, `total_logins`)
VALUES (1, 'admin', '$2y$10$uFNO0pQKEHSFcus1zSFlveiPCB3EvG9ZlES7XKgJFTAl5JbRGFCWy', md5(rand()), 'AltumCode', '', '', '', NULL, 1, 1, 'custom', '2030-01-11 13:23:42', '{"stores_limit":5,"menus_limit":50,"categories_limit":50,"items_limit":50,"no_ads":true,"ordering_is_enabled":true,"analytics_is_enabled":true,"removable_branding_is_enabled":true,"custom_url_is_enabled":true,"password_protection_is_enabled":true,"search_engine_block_is_enabled":true,"custom_css_is_enabled":true,"custom_js_is_enabled":true,"email_reports_is_enabled":true}', 0, '', 'english', 'UTC', '2020-01-20 12:20:20', '', '2020-01-20 12:20:20', '', 0);

-- SEPARATOR --

CREATE TABLE `users_logs` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`user_id` int(11) DEFAULT NULL,
`type` varchar(64) DEFAULT NULL,
`date` datetime DEFAULT NULL,
`ip` varchar(64) DEFAULT NULL,
`public` int(11) DEFAULT '1',
PRIMARY KEY (`id`),
KEY `users_logs_user_id` (`user_id`),
CONSTRAINT `users_logs_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `plans` (
`plan_id` int(11) NOT NULL AUTO_INCREMENT,
`name` varchar(256) NOT NULL DEFAULT '',
`monthly_price` float NULL,
`annual_price` float NULL,
`lifetime_price` float NULL,
`settings` text NOT NULL,
`taxes_ids` text,
`status` tinyint(4) NOT NULL,
`date` datetime NOT NULL,
PRIMARY KEY (`plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `pages_categories` (
`pages_category_id` int(11) NOT NULL AUTO_INCREMENT,
`url` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`description` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT '',
`icon` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`order` int(11) NOT NULL DEFAULT '0',
PRIMARY KEY (`pages_category_id`),
KEY `url` (`url`)
) ROW_FORMAT=DYNAMIC ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `pages` (
`page_id` int(11) NOT NULL AUTO_INCREMENT,
`pages_category_id` int(11) DEFAULT NULL,
`url` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
`title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`description` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`content` longtext COLLATE utf8mb4_unicode_ci,
`type` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT '',
`position` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
`order` int(11) DEFAULT '0',
`total_views` int(11) DEFAULT '0',
`date` datetime DEFAULT NULL,
`last_date` datetime DEFAULT NULL,
PRIMARY KEY (`page_id`),
KEY `pages_pages_category_id_index` (`pages_category_id`),
KEY `pages_url_index` (`url`),
CONSTRAINT `pages_pages_categories_pages_category_id_fk` FOREIGN KEY (`pages_category_id`) REFERENCES `pages_categories` (`pages_category_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `settings` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`key` varchar(64) NOT NULL DEFAULT '',
`value` longtext NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --
SET @cron_key = MD5(RAND());
-- SEPARATOR --
SET @cron_reset_date = NOW();

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`)
VALUES
('ads', '{\"header\":\"\",\"footer\":\"\"}'),
('captcha', '{\"recaptcha_is_enabled\":\"0\",\"recaptcha_public_key\":\"\",\"recaptcha_private_key\":\"\"}'),
('cron', concat('{\"key\":\"', @cron_key, '\",\"reset_date\":\"', @cron_reset_date, '\"}')),
('default_language', 'english'),
('email_confirmation', '0'),
('register_is_enabled', '1'),
('email_notifications', '{\"emails\":\"\",\"new_user\":\"\",\"new_payment\":\"\"}'),
('facebook', '{\"is_enabled\":\"0\",\"app_id\":\"\",\"app_secret\":\"\"}'),
('favicon', ''),
('logo', ''),
('plan_custom', '{\"plan_id\":\"custom\",\"name\":\"Custom\",\"status\":1}'),
('plan_free', '{\"plan_id\":\"free\",\"name\":\"Free\",\"days\":null,\"status\":1,\"settings\":{\"stores_limit\":1,\"menus_limit\":5,"categories_limit":50,"items_limit":50,\"no_ads\":true,\"analytics_is_enabled\":true,\"ordering_is_enabled\":true,\"removable_branding_is_enabled\":false,\"custom_url_is_enabled\":true,\"password_protection_is_enabled\":true,\"search_engine_block_is_enabled\":false,\"custom_css_is_enabled\":false,\"custom_js_is_enabled\":false,\"email_reports_is_enabled\":false}}'),
('plan_trial', '{\"plan_id\":\"trial\",\"name\":\"Trial\",\"days\":7,\"status\":0,\"settings\":{\"stores_limit\":10,\"menus_limit\":50,"categories_limit":50,"items_limit":50,\"no_ads\":true,\"analytics_is_enabled\":true,\"ordering_is_enabled\":true,\"removable_branding_is_enabled\":true,\"custom_url_is_enabled\":true,\"password_protection_is_enabled\":true,\"search_engine_block_is_enabled\":true,\"custom_css_is_enabled\":false,\"custom_js_is_enabled\":false}}'),
('payment', '{\"is_enabled\":\"0\",\"type\":\"both\",\"brand_name\":\":)\",\"currency\":\"USD\",\"codes_is_enabled\":\"1\"}'),
('paypal', '{\"is_enabled\":\"0\",\"mode\":\"sandbox\",\"client_id\":\"\",\"secret\":\"\"}'),
('stripe', '{\"is_enabled\":\"0\",\"publishable_key\":\"\",\"secret_key\":\"\",\"webhook_secret\":\"\"}'),
('offline_payment', '{\"is_enabled\":\"0\",\"instructions\":\"Your offline payment instructions go here..\"}'),
('smtp', '{\"host\":\"\",\"from\":\"\",\"encryption\":\"tls\",\"port\":\"587\",\"auth\":\"1\",\"username\":\"\",\"password\":\"\"}'),
('custom', '{\"head_js\":\"\",\"head_css\":\"\"}'),
('socials', '{\"facebook\":\"\",\"instagram\":\"\",\"twitter\":\"\",\"youtube\":\"\"}'),
('default_timezone', 'UTC'),
('title', 'EasyQR'),
('privacy_policy_url', ''),
('terms_and_conditions_url', ''),
('index_url', ''),
('business', '{\"invoice_is_enabled\":\"0\",\"name\":\"\",\"address\":\"\",\"city\":\"\",\"county\":\"\",\"zip\":\"\",\"country\":\"\",\"email\":\"\",\"phone\":\"\",\"tax_type\":\"\",\"tax_id\":\"\",\"custom_key_one\":\"\",\"custom_value_one\":\"\",\"custom_key_two\":\"\",\"custom_value_two\":\"\"}'),
('stores', '{"email_reports_is_enabled":"0","domains_is_enabled":"0","additional_domains_is_enabled":"0","main_domain_is_enabled":"1"}'),
('license', '{\"license\":\"\",\"type\":\"\"}'),
('product_info', '{\"version\":\"2.0.0\", \"code\":\"200\"}');

-- SEPARATOR --

CREATE TABLE `stores` (
  `store_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `url` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `name` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `socials` text COLLATE utf8mb4_unicode_ci,
  `currency` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `favicon` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `theme` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `timezone` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UTC',
  `custom_css` text COLLATE utf8mb4_unicode_ci,
  `custom_js` text COLLATE utf8mb4_unicode_ci,
  `pageviews` bigint(20) unsigned NOT NULL DEFAULT '0',
  `is_se_visible` tinyint(4) DEFAULT '1',
  `is_removed_branding` tinyint(4) DEFAULT '0',
  `email_reports_is_enabled` tinyint(4) NOT NULL DEFAULT '0',
  `email_reports_last_datetime` datetime DEFAULT NULL,
  `on_premise_ordering_is_enabled` tinyint(4) DEFAULT '0',
  `takeaway_ordering_is_enabled` tinyint(4) DEFAULT '0',
  `delivery_ordering_is_enabled` tinyint(4) DEFAULT '0',
  `is_enabled` tinyint(4) NOT NULL DEFAULT '1',
  `datetime` datetime NOT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`store_id`),
  KEY `user_id` (`user_id`),
  KEY `stores_url_idx` (`url`) USING BTREE,
  KEY `stores_ibfk_2` (`domain_id`),
  CONSTRAINT `stores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- SEPARATOR --

CREATE TABLE `menus` (
`menu_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`store_id` int(11) unsigned NOT NULL,
`user_id` int(11) DEFAULT NULL,
`url` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`name` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`description` text COLLATE utf8mb4_unicode_ci,
`image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`pageviews` bigint(20) unsigned NOT NULL DEFAULT '0',
`is_enabled` tinyint(4) DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`menu_id`),
KEY `user_id` (`user_id`),
KEY `store_id` (`store_id`) USING BTREE,
KEY `menus_url_idx` (`url`) USING BTREE,
CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `menus_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `categories` (
`category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`menu_id` int(11) unsigned NOT NULL,
`store_id` int(11) unsigned NOT NULL,
`user_id` int(11) DEFAULT NULL,
`url` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`name` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`description` text COLLATE utf8mb4_unicode_ci,
`pageviews` bigint(20) unsigned NOT NULL DEFAULT '0',
`is_enabled` tinyint(4) DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`category_id`),
KEY `user_id` (`user_id`),
KEY `menu_id` (`menu_id`),
KEY `store_id` (`store_id`) USING BTREE,
KEY `categories_url_idx` (`url`) USING BTREE,
CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `categories_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `categories_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `items` (
`item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`category_id` int(11) unsigned NOT NULL,
`menu_id` int(11) unsigned NOT NULL,
`store_id` int(11) unsigned NOT NULL,
`user_id` int(11) DEFAULT NULL,
`url` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`name` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`description` text COLLATE utf8mb4_unicode_ci,
`image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`price` float NOT NULL DEFAULT '0',
`variants_is_enabled` tinyint(4) NOT NULL DEFAULT '0',
`pageviews` bigint(20) unsigned NOT NULL DEFAULT '0',
`is_enabled` tinyint(4) DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`item_id`),
KEY `user_id` (`user_id`),
KEY `category_id` (`category_id`),
KEY `menu_id` (`menu_id`),
KEY `store_id` (`store_id`) USING BTREE,
KEY `url` (`url`) USING BTREE,
CONSTRAINT `items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_ibfk_3` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_ibfk_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `items_extras` (
`item_extra_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`item_id` int(11) unsigned NOT NULL,
`category_id` int(11) unsigned NOT NULL,
`menu_id` int(11) unsigned NOT NULL,
`store_id` int(11) unsigned NOT NULL,
`user_id` int(11) DEFAULT NULL,
`name` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`description` text COLLATE utf8mb4_unicode_ci,
`price` float NOT NULL DEFAULT '0',
`is_enabled` tinyint(4) DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`item_extra_id`),
KEY `user_id` (`user_id`),
KEY `item_id` (`item_id`),
KEY `category_id` (`category_id`),
KEY `menu_id` (`menu_id`),
KEY `store_id` (`store_id`) USING BTREE,
CONSTRAINT `items_extras_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_extras_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_extras_ibfk_3` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_extras_ibfk_4` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_extras_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `items_options` (
`item_option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`item_id` int(11) unsigned NOT NULL,
`category_id` int(11) unsigned NOT NULL,
`menu_id` int(11) unsigned NOT NULL,
`store_id` int(11) unsigned NOT NULL,
`user_id` int(11) DEFAULT NULL,
`name` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`options` text COLLATE utf8mb4_unicode_ci,
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`item_option_id`),
KEY `user_id` (`user_id`),
KEY `store_id` (`store_id`) USING BTREE,
KEY `menu_id` (`menu_id`),
KEY `category_id` (`category_id`),
KEY `item_id` (`item_id`),
CONSTRAINT `items_options_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_options_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_options_ibfk_3` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_options_ibfk_4` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_options_ibfk_5` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- SEPARATOR --

CREATE TABLE `items_variants` (
`item_variant_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`item_id` int(11) unsigned NOT NULL,
`category_id` int(11) unsigned NOT NULL,
`menu_id` int(11) unsigned NOT NULL,
`store_id` int(11) unsigned NOT NULL,
`user_id` int(11) DEFAULT NULL,
`item_options_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
`price` float NOT NULL DEFAULT '0',
`is_enabled` tinyint(4) DEFAULT '1',
`datetime` datetime DEFAULT NULL,
`last_datetime` datetime DEFAULT NULL,
PRIMARY KEY (`item_variant_id`),
KEY `user_id` (`user_id`),
KEY `store_id` (`store_id`) USING BTREE,
KEY `item_id` (`item_id`),
KEY `category_id` (`category_id`),
KEY `menu_id` (`menu_id`),
CONSTRAINT `items_variants_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_variants_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_variants_ibfk_3` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_variants_ibfk_4` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `items_variants_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `orders` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '''on_premise'', ''takeaway'', ''delivery''',
  `details` text COLLATE utf8mb4_unicode_ci,
  `price` float NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`order_id`),
  KEY `store_id` (`store_id`) USING BTREE,
  KEY `orders_datetime_idx` (`datetime`) USING BTREE,
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_4` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `orders_ibfk_5` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `orders_items` (
`order_item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`order_id` int(11) unsigned NOT NULL,
`item_variant_id` int(11) unsigned DEFAULT NULL,
`item_id` int(11) unsigned NOT NULL,
`category_id` int(11) unsigned NOT NULL,
`menu_id` int(11) unsigned NOT NULL,
`store_id` int(11) unsigned NOT NULL,
`item_extras_ids` text COLLATE utf8mb4_unicode_ci,
`price` float NOT NULL DEFAULT '0',
`quantity` int(11) unsigned NOT NULL DEFAULT '1',
`datetime` datetime DEFAULT NULL,
PRIMARY KEY (`order_item_id`),
KEY `store_id` (`store_id`) USING BTREE,
KEY `order_id` (`order_id`),
KEY `item_variant_id` (`item_variant_id`),
KEY `item_id` (`item_id`),
KEY `category_id` (`category_id`),
KEY `menu_id` (`menu_id`),
KEY `orders_items_datetime_idx` (`datetime`) USING BTREE,
CONSTRAINT `orders_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `orders_items_ibfk_2` FOREIGN KEY (`item_variant_id`) REFERENCES `items_variants` (`item_variant_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `orders_items_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `orders_items_ibfk_4` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `orders_items_ibfk_5` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `orders_items_ibfk_6` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `statistics` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`store_id` int(11) unsigned DEFAULT NULL,
`menu_id` int(10) unsigned DEFAULT NULL,
`category_id` int(10) unsigned DEFAULT NULL,
`item_id` int(11) unsigned DEFAULT NULL,
`country_code` varchar(8) DEFAULT NULL,
`os_name` varchar(16) DEFAULT NULL,
`browser_name` varchar(32) DEFAULT NULL,
`referrer_host` varchar(256) DEFAULT NULL,
`referrer_path` varchar(1024) DEFAULT NULL,
`device_type` varchar(16) DEFAULT NULL,
`browser_language` varchar(16) DEFAULT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`id`),
KEY `store_id` (`store_id`),
KEY `menu_id` (`menu_id`),
KEY `category_id` (`category_id`),
KEY `item_id` (`item_id`),
CONSTRAINT `statistics_ibfk_1` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `statistics_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `statistics_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `statistics_ibfk_4` FOREIGN KEY (`item_id`) REFERENCES `items` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- SEPARATOR --

CREATE TABLE `domains` (
  `domain_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `store_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `scheme` varchar(8) NOT NULL DEFAULT '',
  `host` varchar(256) NOT NULL DEFAULT '',
  `custom_index_url` varchar(256) DEFAULT NULL,
  `type` tinyint(11) DEFAULT '1',
  `is_enabled` tinyint(4) DEFAULT '0',
  `datetime` datetime DEFAULT NULL,
  `last_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`domain_id`),
  KEY `user_id` (`user_id`),
  KEY `domains_host_index` (`host`),
  KEY `domains_type_index` (`type`),
  KEY `domains_ibfk_2` (`store_id`),
  CONSTRAINT `domains_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `domains_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- SEPARATOR --

alter table stores add CONSTRAINT `stores_ibfk_2` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`domain_id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- SEPARATOR --

CREATE TABLE `email_reports` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`user_id` int(11) NOT NULL,
`store_id` int(11) unsigned NOT NULL,
`datetime` datetime NOT NULL,
PRIMARY KEY (`id`),
KEY `user_id` (`user_id`),
KEY `datetime` (`datetime`),
KEY `store_id` (`store_id`),
CONSTRAINT `email_reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
CONSTRAINT `email_reports_ibfk_2` FOREIGN KEY (`store_id`) REFERENCES `stores` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

INSERT INTO `stores` (`store_id`, `user_id`, `url`, `name`, `title`, `description`, `details`, `socials`, `currency`, `password`, `image`, `logo`, `favicon`, `theme`, `timezone`, `custom_css`, `custom_js`, `pageviews`, `is_se_visible`, `is_removed_branding`, `email_reports_is_enabled`, `email_reports_last_datetime`, `is_enabled`, `datetime`, `last_datetime`) VALUES
('1', '1', 'demo', 'Vintage Machine', 'Vintage Machine Cafe', 'great coffee, food & relaxing spot.', '{\"address\":\"Lorem ipsum dolor sit amet, number 200, street consectetur adipiscing.\",\"phone\":\"+100100100\",\"website\":\"https:\\/\\/example.com\",\"email\":\"example@example.com\",\"hours\":{\"1\":{\"is_enabled\":true,\"hours\":\"10AM - 9PM\"},\"2\":{\"is_enabled\":true,\"hours\":\"10AM - 9PM\"},\"3\":{\"is_enabled\":true,\"hours\":\"10AM - 9PM\"},\"4\":{\"is_enabled\":true,\"hours\":\"10AM - 9PM\"},\"5\":{\"is_enabled\":true,\"hours\":\"10AM - 9PM\"},\"6\":{\"is_enabled\":true,\"hours\":\"24\\/7\"},\"7\":{\"is_enabled\":true,\"hours\":\"24\\/7\"}}}', '{\"facebook\":\"example\",\"instagram\":\"example\",\"twitter\":\"example\"}', 'USD', NULL, '43b05d0c754dcdb7980dfaac6869ea67.jpg', 'fdf291dc2b8c9fa12a4b4ca05608fae1.png', '59e6f1e7ad6a86d2272fd74eca7e5405.png', 'new-york', 'UTC', '', '', '0', '1', '1', '0', '2020-10-20 15:54:52', '1', '2020-10-06 12:00:00', '2020-10-22 18:12:04');

-- SEPARATOR --

INSERT INTO `menus` (`menu_id`, `store_id`, `user_id`, `url`, `name`, `description`, `image`, `pageviews`, `is_enabled`, `datetime`, `last_datetime`) VALUES
('1', '1', '1', 'coffee', 'Coffee', 'Just coffee.', '60d8f4dc75993a3753050e553c661294.jpg', '0', '1', '2020-10-22 18:15:44', NULL),
('2', '1', '1', 'breakfast', 'Breakfast', 'Breakfast menu is served from 10AM - 1PM.', '4095e4ca4860ed2aa22aafa341ae4122.jpg', '0', '1', '2020-10-22 18:16:45', NULL),
('3', '1', '1', 'lunch-dinner', 'Lunch & Dinner', 'Served from starting from 1PM.', '219952272d78d8b1c0bfa59d903e14b7.jpg', '0', '1', '2020-10-22 18:19:10', NULL);

-- SEPARATOR --

INSERT INTO `categories` (`category_id`, `menu_id`, `store_id`, `user_id`, `url`, `name`, `description`, `pageviews`, `is_enabled`, `datetime`, `last_datetime`) VALUES
('1', '1', '1', '1', 'cold', 'Cold coffee', '', '0', '1', '2020-10-22 21:00:05', NULL),
('2', '1', '1', '1', 'hot', 'Hot coffee', '', '0', '1', '2020-10-22 21:00:18', '2020-10-22 21:00:40'),
('3', '2', '1', '1', 'vegan', 'Vegan', '', '0', '1', '2020-10-22 21:21:38', NULL),
('4', '3', '1', '1', 'burgers', 'Burgers', '', '0', '1', '2020-10-22 21:24:47', NULL),
('5', '3', '1', '1', 'pasta', 'Pasta', '', '0', '1', '2020-10-22 21:30:56', NULL);

-- SEPARATOR --

INSERT INTO `items` (`item_id`, `category_id`, `menu_id`, `store_id`, `user_id`, `url`, `name`, `description`, `image`, `price`, `variants_is_enabled`, `pageviews`, `is_enabled`, `datetime`, `last_datetime`) VALUES
('1', '2', '1', '1', '1', 'caffe-late', 'Caffe Late', 'Simple & nice caffe late.', 'cd01696b79a25e10d11f6309c3a911a3.jpg', '2', '0', '0', '1', '2020-10-22 21:03:38', '2020-10-22 21:08:14'),
('2', '2', '1', '1', '1', 'americano', 'Americano', 'Caffe Americano is a type of coffee drink prepared by diluting an espresso with hot water, giving it a similar strength to, but different flavor from, traditionally brewed coffee. The strength of an Americano varies with the number of shots of espresso and the amount of water added.', '012e68300fca27f5459ab31595777282.jpg', '2.5', '0', '0', '1', '2020-10-22 21:09:01', '2020-10-22 21:11:13'),
('3', '2', '1', '1', '1', 'caffe-mocha', 'Caffe Mocha', 'A caffe mocha, also called mocaccino, is a chocolate-flavoured variant of a caffè latte. Other commonly used spellings are mochaccino and also mochachino. The name is derived from the city of Mocha, Yemen, which was one of the centers of early coffee trade.', '974541b7bb20f7565fafcca5aaf7fb1e.jpg', '3', '0', '0', '1', '2020-10-22 21:10:10', '2020-10-22 21:10:50'),
('4', '1', '1', '1', '1', 'cold-brew', 'Cold brew', 'Cold brew is really as simple as mixing ground coffee with cool water and steeping the mixture in the fridge overnight.', 'f5af9c710b8f9b8cbcc7a64e8b5048e8.jpg', '3', '0', '0', '1', '2020-10-22 21:16:02', NULL),
('5', '3', '2', '1', '1', 'french-toast', 'French Toast', '', '653f2d83e8ab5c36ea11907c80693f79.jpg', '5', '0', '0', '1', '2020-10-22 21:22:52', NULL),
('6', '3', '2', '1', '1', 'granola', 'Granola Bowl', '', '98e0d264361fcdddb6b36de97b35f1d1.jpg', '7.5', '0', '0', '1', '2020-10-22 21:23:29', NULL),
('7', '3', '2', '1', '1', 'avocado', 'Avocado Toast', '', 'f03daa3d679d859fe3d707590edac337.jpg', '5', '0', '0', '1', '2020-10-22 21:24:23', NULL),
('8', '4', '3', '1', '1', 'the-special-one', 'The special one', 'The legandary, the special one, limited edition.', '1e68a923c68c57da173fc659b013339f.jpg', '19.9', '1', '0', '1', '2020-10-22 21:25:12', '2020-10-22 21:48:39'),
('9', '4', '3', '1', '1', 'double', 'The Double', '', 'a5bda5476ac30603270a0f3f84e778fa.jpg', '15', '0', '0', '1', '2020-10-22 21:25:52', NULL),
('10', '4', '3', '1', '1', 'the-challenge', 'The Challenge', '', 'd25de049f97cfa391d1f14e49cd97aac.jpg', '30', '0', '0', '1', '2020-10-22 21:26:22', NULL),
('11', '5', '3', '1', '1', 'carbonara', 'Carbonara', 'Carbonara is an Italian pasta dish from Rome made with egg, hard cheese, cured pork, and black pepper.', 'cbf61cecb5f972ca36d29c881599750d.jpg', '9', '0', '0', '1', '2020-10-22 21:31:33', '2020-10-22 21:40:19');

-- SEPARATOR --

INSERT INTO `items_options` (`item_option_id`, `item_id`, `category_id`, `menu_id`, `store_id`, `user_id`, `name`, `options`, `datetime`, `last_datetime`) VALUES
('1', '8', '4', '3', '1', '1', 'Size', '[\"Small\",\"Medium\",\"Large\"]', '2020-10-24 17:41:45', NULL),
('2', '8', '4', '3', '1', '1', 'Patties', '[\"1\",\"2\",\"3\"]', '2020-10-24 17:41:55', NULL);

-- SEPARATOR --

INSERT INTO `items_variants` (`item_variant_id`, `item_id`, `category_id`, `menu_id`, `store_id`, `user_id`, `item_options_ids`, `price`, `is_enabled`, `datetime`, `last_datetime`) VALUES
('1', '8', '4', '3', '1', '1', '[{\"item_option_id\":1,\"option\":0},{\"item_option_id\":2,\"option\":0}]', '15', '1', '2020-10-24 17:46:56', NULL),
('2', '8', '4', '3', '1', '1', '[{\"item_option_id\":1,\"option\":0},{\"item_option_id\":2,\"option\":1}]', '16', '1', '2020-10-24 17:47:13', NULL),
('3', '8', '4', '3', '1', '1', '[{\"item_option_id\":1,\"option\":1},{\"item_option_id\":2,\"option\":0}]', '17', '1', '2020-10-24 17:47:27', NULL),
('4', '8', '4', '3', '1', '1', '[{\"item_option_id\":1,\"option\":1},{\"item_option_id\":2,\"option\":1}]', '18', '1', '2020-10-24 17:47:35', NULL),
('5', '8', '4', '3', '1', '1', '[{\"item_option_id\":1,\"option\":2},{\"item_option_id\":2,\"option\":1}]', '19', '1', '2020-10-24 17:48:06', NULL),
('6', '8', '4', '3', '1', '1', '[{\"item_option_id\":1,\"option\":2},{\"item_option_id\":2,\"option\":2}]', '25', '1', '2020-10-24 17:48:15', NULL);

-- SEPARATOR --

CREATE TABLE `codes` (
  `code_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `days` int(10) UNSIGNED DEFAULT NULL,
  `plan_id` int(10) UNSIGNED DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount` int(10) UNSIGNED DEFAULT NULL,
  `quantity` int(10) UNSIGNED DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `redeemed` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`code_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SEPARATOR --

CREATE TABLE `payments` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `plan_id` int(10) UNSIGNED DEFAULT NULL,
  `processor` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `frequency` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `discount_amount` decimal(20,2) UNSIGNED DEFAULT NULL,
  `base_amount` decimal(20,2) UNSIGNED DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `payment_id` varchar(255) DEFAULT NULL,
  `subscription_id` varchar(255) DEFAULT NULL,
  `payer_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `billing` text DEFAULT NULL,
  `taxes_ids` text DEFAULT NULL,
  `total_amount` decimal(20,2) UNSIGNED DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `payment_proof` text DEFAULT NULL,
  `status` char(1) NOT NULL,
  `date` datetime DEFAULT NULL,
  `formatted_date` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- SEPARATOR --

CREATE TABLE `redeemed_codes` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code_id` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- SEPARATOR --

CREATE TABLE `taxes` (
  `tax_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `internal_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `countries` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`tax_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

