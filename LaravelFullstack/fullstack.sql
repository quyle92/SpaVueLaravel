-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 27, 2021 at 11:00 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fullstack`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogcategories`
--

CREATE TABLE `blogcategories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogcategories`
--

INSERT INTO `blogcategories` (`id`, `category_id`, `blog_id`, `created_at`, `updated_at`) VALUES
(23, 22, 31, NULL, NULL),
(24, 20, 31, NULL, NULL),
(25, 20, 32, NULL, NULL),
(26, 22, 32, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_excerpt` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `featuredImage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `metaDescription` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `views` int(11) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `post`, `post_excerpt`, `slug`, `user_id`, `featuredImage`, `metaDescription`, `views`, `created_at`, `updated_at`) VALUES
(1, 'Laravel: Add data to the $request->all()', 'I wan to add the sector id to the request but', 'I wan to add the sector id to the request but', 'laravel-add-data-to-the-request-all', 20, 'laravel-add-data-to-the-request-all', 'laravel-add-data-to-the-request-all', 0, '2021-04-26 08:03:13', '2021-04-26 08:03:13'),
(31, 'Brief Introduction #', 'A dropdown select , alternative to the native select component.', 'A dropdown select , alternative to the native select component.', 'brief-introduction', 20, 'brief-introduction', 'brief-introduction', 0, '2021-04-26 21:32:09', '2021-04-26 21:32:09'),
(32, 'Brief Introduction #', 'View UI（iView） uses open source icon set  ionicons version 3.x', 'View UI（iView） uses open source icon set  ionicons version 3.x', 'brief-introduction-2', 20, 'brief-introduction-2', 'brief-introduction-2', 0, '2021-04-27 01:26:41', '2021-04-27 01:26:41');

-- --------------------------------------------------------

--
-- Table structure for table `blogtags`
--

CREATE TABLE `blogtags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` int(11) NOT NULL,
  `blog_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogtags`
--

INSERT INTO `blogtags` (`id`, `tag_id`, `blog_id`, `created_at`, `updated_at`) VALUES
(9, 18, 31, NULL, NULL),
(10, 25, 31, NULL, NULL),
(11, 26, 32, NULL, NULL),
(12, 25, 32, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `categoryName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iconImage` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `categorySlug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `categoryName`, `iconImage`, `created_at`, `updated_at`, `categorySlug`) VALUES
(20, 'vuejs', 'SmartPSS-questions.png.png', '2021-04-23 20:17:00', '2021-04-26 19:27:15', 'vuejs'),
(22, 'laravel', 'Screenshot (12).png.png', '2021-04-26 03:43:59', '2021-04-26 19:27:22', 'laravel'),
(23, 'php', 'Screenshot (17).png.png', '2021-04-26 19:37:15', '2021-04-26 19:37:15', 'php'),
(24, 'symfony', 'Screenshot (14).png.png', '2021-04-26 19:37:30', '2021-04-26 19:37:30', 'symfony');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2021_04_20_030626_create_categories_table', 1),
(4, '2021_04_20_030741_create_tags_table', 1),
(5, '2021_04_20_030801_create_blogs_table', 1),
(6, '2021_04_20_030824_create_blogtags_table', 1),
(7, '2021_04_20_030847_create_blogcategories_table', 1),
(8, '2021_04_24_024422_create_roles_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `roleName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `isAdmin` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `roleName`, `permission`, `created_at`, `updated_at`, `isAdmin`) VALUES
(1, 'admin', '[{\"resourceName\":\"home\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"path\":\"/\"},{\"resourceName\":\"tags\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"path\":\"tags\"},{\"resourceName\":\"blog\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"path\":\"blog\"},{\"resourceName\":\"category\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"path\":\"category\"},{\"resourceName\":\"admin-users\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"path\":\"admin-users\"},{\"resourceName\":\"role\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"path\":\"role\"},{\"resourceName\":\"asign-role\",\"read\":true,\"write\":true,\"update\":true,\"delete\":true,\"path\":\"asign-role\"}]', '2021-04-23 20:26:28', '2021-04-26 18:53:32', 1),
(2, 'moderator', '[{\"resourceName\":\"home\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"/\"},{\"resourceName\":\"tags\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"tags\"},{\"resourceName\":\"blog\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"blog\"},{\"resourceName\":\"category\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"category\"},{\"resourceName\":\"admin-users\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"admin-users\"},{\"resourceName\":\"role\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"role\"},{\"resourceName\":\"asign-role\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"asign-role\"}]', '2021-04-23 20:27:58', '2021-04-26 18:52:54', 1),
(3, 'editor', '[{\"resourceName\":\"home\",\"read\":true,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"/\"},{\"resourceName\":\"tags\",\"read\":true,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"tags\"},{\"resourceName\":\"blog\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"blog\"},{\"resourceName\":\"category\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"category\"},{\"resourceName\":\"admin-users\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"admin-users\"},{\"resourceName\":\"role\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"role\"},{\"resourceName\":\"asign-role\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"asign-role\"}]', '2021-04-23 20:38:54', '2021-04-27 01:40:33', 1),
(4, 'user', '[{\"resourceName\":\"home\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"/\"},{\"resourceName\":\"tags\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"tags\"},{\"resourceName\":\"blog\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"blog\"},{\"resourceName\":\"category\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"category\"},{\"resourceName\":\"admin-users\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"admin-users\"},{\"resourceName\":\"role\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"role\"},{\"resourceName\":\"asign-role\",\"read\":false,\"write\":false,\"update\":false,\"delete\":false,\"path\":\"asign-role\"}]', '2021-04-23 20:38:58', '2021-04-27 01:40:43', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tagName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `tagName`, `created_at`, `updated_at`) VALUES
(18, 'css', '2021-04-22 02:07:01', '2021-04-27 01:25:33'),
(25, 'html', '2021-04-26 00:27:50', '2021-04-27 01:25:37'),
(26, 'javascript', '2021-04-26 19:37:49', '2021-04-27 01:25:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fullName` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userType` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `isActivated` tinyint(1) NOT NULL DEFAULT 0,
  `passwordResetCode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activationCode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `socialType` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullName`, `email`, `password`, `userType`, `isActivated`, `passwordResetCode`, `activationCode`, `socialType`, `created_at`, `updated_at`, `role_id`) VALUES
(20, 'quy', 'quy@gmail.com', '$2y$10$5sPCQJYyGSkrE6u.btn1e.qrZdF6TERZY6rph.gznXm.qYOtX4Nr2', 'admin', 0, NULL, NULL, NULL, '2021-04-23 01:59:33', '2021-04-23 20:39:32', 1),
(21, 'nam', 'nam@gmail.com', '$2y$10$yww9M38yTcr.wQAn0HZh2uV292rND0oYanmbBSCfP/ufY2XbiVply', 'editor', 0, NULL, NULL, NULL, '2021-04-23 02:06:08', '2021-04-23 20:39:37', 2),
(22, 'long', 'long@gmail.com', '$2y$10$69ANb/AGAV1ZNUabAxRHsurnUNpL/t/zO7svwT51PmaW7PNgrM.Qq', 'moderator', 0, NULL, NULL, NULL, '2021-04-23 20:39:15', '2021-04-24 01:02:19', 3),
(23, 'minh', 'minh@gmail.com', '$2y$10$bOeErqPZcPp38wgb16YNx./ZJkUX7ff1Lg0vqY8yPXbQ3qPXNSqTW', 'user', 0, NULL, NULL, NULL, '2021-04-23 20:39:25', '2021-04-23 20:39:25', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogcategories`
--
ALTER TABLE `blogcategories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `blogs_slug_unique` (`slug`);

--
-- Indexes for table `blogtags`
--
ALTER TABLE `blogtags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogcategories`
--
ALTER TABLE `blogcategories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `blogtags`
--
ALTER TABLE `blogtags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
