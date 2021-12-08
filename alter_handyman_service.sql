ALTER TABLE `app_settings`  ADD `inquriy_email` VARCHAR(255) NULL  AFTER `language_option`;  
ALTER TABLE `app_settings`  ADD `helpline_number` VARCHAR(255) NULL  AFTER `inquriy_email`;


INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES  (36,'2021_12_01_073610_alter_app_settings',4);
