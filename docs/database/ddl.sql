CREATE TABLE `users` (
                         `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                         `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `email_verified_at` timestamp NULL DEFAULT NULL,
                         `first_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `last_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                         `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
                         `created_at` timestamp NULL DEFAULT NULL,
                         `updated_at` timestamp NULL DEFAULT NULL,
                         PRIMARY KEY (`id`),
                         UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `social_media_auths` (
                                      `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                      `user_id` bigint unsigned NOT NULL,
                                      `provider` enum('GOOGLE','FACEBOOK') COLLATE utf8mb4_unicode_ci NOT NULL,
                                      `created_at` timestamp NULL DEFAULT NULL,
                                      `updated_at` timestamp NULL DEFAULT NULL,
                                      PRIMARY KEY (`id`),
                                      KEY `social_media_auths_user_id_foreign` (`user_id`),
                                      CONSTRAINT `social_media_auths_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `products` (
                            `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                            `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                            `description` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
                            `price` decimal(6,2) NOT NULL,
                            `prepare_days` int NOT NULL,
                            `created_at` timestamp NULL DEFAULT NULL,
                            `updated_at` timestamp NULL DEFAULT NULL,
                            PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `product_images` (
                                  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                  `product_id` bigint unsigned NOT NULL,
                                  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
                                  `created_at` timestamp NULL DEFAULT NULL,
                                  `updated_at` timestamp NULL DEFAULT NULL,
                                  `feature_image` tinyint(1) NOT NULL,
                                  PRIMARY KEY (`id`),
                                  KEY `product_images_product_id_foreign` (`product_id`),
                                  CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_addresses` (
                                  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                  `user_id` bigint unsigned NOT NULL,
                                  `address_type` enum('BILLING','BILLING_AND_DELIVERY','DELIVERY','ONE_TIME') COLLATE utf8mb4_unicode_ci NOT NULL,
                                  `zip_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
                                  `city` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                                  `line1` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                                  `line2` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
                                  `building_number` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
                                  `additional_information` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
                                  `created_at` timestamp NULL DEFAULT NULL,
                                  `updated_at` timestamp NULL DEFAULT NULL,
                                  PRIMARY KEY (`id`),
                                  KEY `user_addresses_user_id_foreign` (`user_id`),
                                  CONSTRAINT `user_addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
                               `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                               `order_id` bigint unsigned NOT NULL,
                               `product_id` bigint unsigned NOT NULL,
                               `quantity` int NOT NULL,
                               `price_per_item` decimal(6,2) NOT NULL,
                               `created_at` timestamp NULL DEFAULT NULL,
                               `updated_at` timestamp NULL DEFAULT NULL,
                               PRIMARY KEY (`id`),
                               KEY `order_items_order_id_foreign` (`order_id`),
                               KEY `order_items_product_id_foreign` (`product_id`),
                               CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
                               CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `delivery_addresses` (
                                      `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                                      `order_id` bigint unsigned NOT NULL,
                                      `user_address_id` bigint unsigned NOT NULL,
                                      `created_at` timestamp NULL DEFAULT NULL,
                                      `updated_at` timestamp NULL DEFAULT NULL,
                                      PRIMARY KEY (`id`),
                                      KEY `delivery_addresses_order_id_foreign` (`order_id`),
                                      KEY `delivery_addresses_user_address_id_foreign` (`user_address_id`),
                                      CONSTRAINT `delivery_addresses_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
                                      CONSTRAINT `delivery_addresses_user_address_id_foreign` FOREIGN KEY (`user_address_id`) REFERENCES `user_addresses` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
                          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
                          `user_id` bigint unsigned DEFAULT NULL,
                          `delivery_type` enum('HOME','PICK_UP') COLLATE utf8mb4_unicode_ci NOT NULL,
                          `payment_type` enum('CASH','STRIPE','CARD') COLLATE utf8mb4_unicode_ci NOT NULL,
                          `payment_total` decimal(10,2) NOT NULL,
                          `delivery_date` date NOT NULL,
                          `order_status` enum('PENDING','CONFIRMED','REJECTED','DELETED','DELIVERED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
                          `created_at` timestamp NULL DEFAULT NULL,
                          `updated_at` timestamp NULL DEFAULT NULL,
                          PRIMARY KEY (`id`),
                          KEY `orders_user_id_foreign` (`user_id`),
                          CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
