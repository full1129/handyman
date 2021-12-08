CREATE TABLE `documents` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint DEFAULT '1',
  `is_required` tinyint DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (33, '2021_10_27_052244_create_documents_table', 3);
--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;


-- --------------------------------------------------------

--
-- Table structure for table `provider_documents`
--

CREATE TABLE `provider_documents` (
  `id` bigint UNSIGNED NOT NULL,
  `provider_id` bigint UNSIGNED NOT NULL,
  `document_id` bigint UNSIGNED NOT NULL,
  `is_verified` tinyint DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES  (34, '2021_10_27_090904_create_provider_documents_table', 3);

-- --------------------------------------------------------

--
-- Indexes for table `provider_documents`
--
ALTER TABLE `provider_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_documents_provider_id_foreign` (`provider_id`),
  ADD KEY `provider_documents_document_id_foreign` (`document_id`);

--
-- AUTO_INCREMENT for table `provider_documents`
--
ALTER TABLE `provider_documents`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for table `provider_documents`
--
ALTER TABLE `provider_documents`
  ADD CONSTRAINT `provider_documents_document_id_foreign` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `provider_documents_provider_id_foreign` FOREIGN KEY (`provider_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;



-- --------------------------------------------------------

--
-- Table structure for table `handyman_ratings`
--

CREATE TABLE `handyman_ratings` (
  `id` bigint UNSIGNED NOT NULL,
  `booking_id` bigint UNSIGNED NOT NULL,
  `service_id` bigint UNSIGNED NOT NULL,
  `handyman_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `rating` double DEFAULT NULL,
  `review` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES (35, '2021_10_27_115257_create_handyman_ratings_table', 3);

--
-- Indexes for table `handyman_ratings`
--
ALTER TABLE `handyman_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `handyman_ratings_handyman_id_foreign` (`handyman_id`),
  ADD KEY `handyman_ratings_customer_id_foreign` (`customer_id`),
  ADD KEY `handyman_ratings_service_id_foreign` (`service_id`),
  ADD KEY `handyman_ratings_booking_id_foreign` (`booking_id`);


--
-- AUTO_INCREMENT for table `handyman_ratings`
--
ALTER TABLE `handyman_ratings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for table `handyman_ratings`
--
ALTER TABLE `handyman_ratings`
  ADD CONSTRAINT `handyman_ratings_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `handyman_ratings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `handyman_ratings_handyman_id_foreign` FOREIGN KEY (`handyman_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `handyman_ratings_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

  

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `parent_id`, `created_at`, `updated_at`) VALUES
(NULL, 'document', 'web', NULL, '2021-10-27 06:00:09', '2021-10-27 06:00:09'), 
(NULL, 'document list', 'web', '63', '2021-10-27 06:00:24', '2021-10-27 06:00:24'), 
(NULL, 'document add', 'web', '63', '2021-10-27 06:00:38', '2021-10-27 06:00:38'), 
(NULL, 'document edit', 'web', '63', '2021-10-27 06:00:56', '2021-10-27 06:00:56'), 
(NULL, 'document delete', 'web', '63', '2021-10-27 06:01:11', '2021-10-27 06:01:11'), 
(NULL, 'provider document', 'web', NULL, '2021-10-27 10:32:48', '2021-10-27 10:32:48'), 
(NULL, 'providerdocument list', 'web', '68', '2021-10-27 10:33:05', '2021-10-27 10:33:05'), 
(NULL, 'providerdocument add', 'web', '68', '2021-10-27 10:33:20', '2021-10-27 10:33:20'), 
(NULL, 'providerdocument edit', 'web', '68', '2021-10-27 10:33:32', '2021-10-27 10:33:32'), 
(NULL, 'providerdocument delete', 'web', '68', '2021-10-27 10:33:51', '2021-10-27 10:33:51');

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
('63', '1'),
('64', '1'),
('65', '1'),
('66', '1'),
('67', '1'),
('68', '1'),
('69', '1'),
('69', '4'),
('70', '1'),
('70', '4'),
('71', '1'),
('71', '4'),
('72', '1'),
('72', '4');
