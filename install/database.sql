-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 19, 2025 at 12:14 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `username`, `email_verified_at`, `image`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@site.com', 'admin', NULL, '670ce58c687511728898444.png', '$2y$12$ecxM9ta/Mu9RTovy4/xAKebotQbkFcTwDEriRGnf3bwwJ2YBn//Ai', NULL, '2024-09-01 11:37:12', '2024-10-14 03:34:04');

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `click_url` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_resets`
--

CREATE TABLE `admin_password_resets` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agent_has_permissions`
--

CREATE TABLE `agent_has_permissions` (
  `id` bigint NOT NULL,
  `agent_permission_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `agent_id` bigint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `agent_permissions`
--

CREATE TABLE `agent_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `agent_permissions`
--

INSERT INTO `agent_permissions` (`id`, `name`, `guard_name`, `group_name`, `created_at`, `updated_at`) VALUES
(1, 'view contact', 'web', 'contact', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(2, 'add contact', 'web', 'contact', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(3, 'edit contact', 'web', 'contact', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(4, 'delete contact', 'web', 'contact', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(5, 'view contact list', 'web', 'contact list', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(6, 'add contact list', 'web', 'contact list', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(7, 'edit contact list', 'web', 'contact list', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(8, 'delete contact list', 'web', 'contact list', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(9, 'view list contact', 'web', 'contact list', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(10, 'add contact to list', 'web', 'contact list', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(11, 'remove contact from list', 'web', 'contact list', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(12, 'view contact tag', 'web', 'contact tag', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(13, 'add contact tag', 'web', 'contact tag', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(14, 'edit contact tag', 'web', 'contact tag', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(15, 'delete contact tag', 'web', 'contact tag', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(16, 'view inbox', 'web', 'whatsapp', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(17, 'send message', 'web', 'whatsapp', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(18, 'view customer', 'web', 'customer', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(19, 'add customer', 'web', 'customer', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(20, 'edit customer', 'web', 'customer', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(21, 'delete customer', 'web', 'customer', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(22, 'view template', 'web', 'template', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(23, 'edit template', 'web', 'template', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(24, 'add template', 'web', 'template', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(25, 'delete template', 'web', 'template', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(26, 'view campaign', 'web', 'campaign', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(27, 'add campaign', 'web', 'campaign', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(28, 'edit campaign', 'web', 'campaign', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(29, 'delete campaign', 'web', 'campaign', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(30, 'view chatbot', 'web', 'chatbot', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(31, 'add chatbot', 'web', 'chatbot', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(32, 'edit chatbot', 'web', 'chatbot', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(33, 'delete chatbot', 'web', 'chatbot', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(34, 'view welcome message', 'web', 'welcome message', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(35, 'add welcome message', 'web', 'welcome message', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(36, 'edit welcome message', 'web', 'welcome message', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(37, 'view agent', 'web', 'agent', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(38, 'add agent', 'web', 'agent', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(39, 'edit agent', 'web', 'agent', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(40, 'view permission', 'web', 'agent', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(41, 'assign permission', 'web', 'agent', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(42, 'delete agent', 'web', 'agent', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(43, 'view shortlink', 'web', 'shortlink', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(44, 'add shortlink', 'web', 'shortlink', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(45, 'edit shortlink', 'web', 'shortlink', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(46, 'delete shortlink', 'web', 'shortlink', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(47, 'view floater', 'web', 'floater', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(48, 'add floater', 'web', 'floater', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(49, 'delete floater', 'web', 'floater', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(50, 'view dashboard', 'web', 'other', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(51, 'view wallet', 'web', 'other', '2025-06-17 10:44:39', '2025-06-17 10:44:39'),
(52, 'view subscription', 'web', 'other', '2025-06-17 10:44:39', '2025-06-17 10:44:39');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` bigint NOT NULL,
  `whatsapp_account_id` bigint NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `template_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `template_header_params` text COLLATE utf8mb4_unicode_ci,
  `template_body_params` text COLLATE utf8mb4_unicode_ci,
  `send_at` timestamp NULL DEFAULT NULL,
  `et` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=init,1=completed,2=running,3=scheduled,9=failed',
  `total_message` int NOT NULL DEFAULT '0',
  `total_send` int NOT NULL DEFAULT '0',
  `total_success` int NOT NULL DEFAULT '0',
  `total_failed` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_contacts`
--

CREATE TABLE `campaign_contacts` (
  `id` bigint NOT NULL,
  `campaign_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `contact_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'not_sent= 0;is_sent = 2;is_success = 1;is_failed  = 9;',
  `send_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chatbots`
--

CREATE TABLE `chatbots` (
  `id` bigint NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_account_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `user_id` bigint NOT NULL DEFAULT '0',
  `keywords` text COLLATE utf8mb4_unicode_ci,
  `text` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=active,0=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `firstname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `details` text COLLATE utf8mb4_unicode_ci,
  `is_customer` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_lists`
--

CREATE TABLE `contact_lists` (
  `id` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_list_contacts`
--

CREATE TABLE `contact_list_contacts` (
  `id` bigint NOT NULL,
  `contact_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `contact_list_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_notes`
--

CREATE TABLE `contact_notes` (
  `id` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `conversation_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_tags`
--

CREATE TABLE `contact_tags` (
  `id` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_tag_contacts`
--

CREATE TABLE `contact_tag_contacts` (
  `id` bigint NOT NULL,
  `contact_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `contact_tag_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `id` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `contact_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=pending,2=important,3=done',
  `whatsapp_account_id` int NOT NULL DEFAULT '0',
  `last_message_at` timestamp NULL DEFAULT NULL COMMENT 'For ordering chatlist',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_jobs`
--

CREATE TABLE `cron_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` text COLLATE utf8mb4_unicode_ci,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cron_schedule_id` int NOT NULL DEFAULT '0',
  `next_run` datetime DEFAULT NULL,
  `last_run` datetime DEFAULT NULL,
  `is_running` tinyint(1) NOT NULL DEFAULT '1',
  `is_default` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cron_jobs`
--

INSERT INTO `cron_jobs` (`id`, `name`, `alias`, `action`, `url`, `cron_schedule_id`, `next_run`, `last_run`, `is_running`, `is_default`, `created_at`, `updated_at`) VALUES
(6, 'Subscription Expiration Notify', 'subscription_expired', '[\"\\\\App\\\\Http\\\\Controllers\\\\CronController\",\"subscriptionExpired\"]', '', 3, '2025-05-31 17:53:08', '2025-05-30 17:53:08', 1, 1, '2024-09-09 03:36:44', '2025-05-30 11:53:08'),
(7, 'Subscription notify ', 'subscription_notify', '[\"\\\\App\\\\Http\\\\Controllers\\\\CronController\",\"subscriptionNotify\"]', '', 3, '2025-05-31 17:55:26', '2025-05-30 17:55:26', 1, 1, '2024-09-09 03:36:44', '2025-05-30 11:55:26'),
(8, 'Send Campaign Message', 'campaign_message', '[\"\\\\App\\\\Http\\\\Controllers\\\\CronController\",\"campaignMessage\"]', '', 3, '2025-04-15 14:43:43', '2025-04-14 14:43:43', 1, 1, '2024-09-09 03:36:44', '2025-04-14 08:43:43');

-- --------------------------------------------------------

--
-- Table structure for table `cron_job_logs`
--

CREATE TABLE `cron_job_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `cron_job_id` int UNSIGNED NOT NULL DEFAULT '0',
  `start_at` datetime DEFAULT NULL,
  `end_at` datetime DEFAULT NULL,
  `duration` int UNSIGNED NOT NULL DEFAULT '0',
  `error` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cron_schedules`
--

CREATE TABLE `cron_schedules` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interval` int UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cron_schedules`
--

INSERT INTO `cron_schedules` (`id`, `name`, `interval`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Hourly', 3600, 1, '2024-03-13 23:34:09', '2025-02-27 05:54:21'),
(3, 'Daily', 86400, 1, '2024-05-06 04:46:39', '2024-05-06 04:46:39'),
(4, 'Yearly', 31622400, 1, '2024-09-09 02:52:56', '2025-02-27 05:55:15');

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `plan_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `plan_recurring_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=monthly,2=yearly',
  `method_code` int UNSIGNED NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `method_currency` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `detail` text COLLATE utf8mb4_unicode_ci,
  `btc_amount` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `btc_wallet` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_try` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>success, 2=>pending, 3=>cancel',
  `from_api` tinyint(1) NOT NULL DEFAULT '0',
  `admin_feedback` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `success_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `failed_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_cron` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `device_tokens`
--

CREATE TABLE `device_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `is_app` tinyint(1) NOT NULL DEFAULT '0',
  `token` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `extensions`
--

CREATE TABLE `extensions` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `info` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `script` text COLLATE utf8mb4_unicode_ci,
  `shortcode` text COLLATE utf8mb4_unicode_ci COMMENT 'object',
  `support` text COLLATE utf8mb4_unicode_ci COMMENT 'help section',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=>enable, 2=>disable',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `extensions`
--

INSERT INTO `extensions` (`id`, `act`, `name`, `description`, `info`, `image`, `script`, `shortcode`, `support`, `status`, `created_at`, `updated_at`) VALUES
(1, 'tawk-chat', 'Tawk.to', 'Key location is shown bellow', 'Tawk.to offers live chat support, helping you communicate with visitors and boost customer satisfaction', 'tawky_big.png', '<script>\r\n                        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();\r\n                        (function(){\r\n                        var s1=document.createElement(\"script\"),s0=document.getElementsByTagName(\"script\")[0];\r\n                        s1.async=true;\r\n                        s1.src=\"https://embed.tawk.to/{{app_key}}\";\r\n                        s1.charset=\"UTF-8\";\r\n                        s1.setAttribute(\"crossorigin\",\"*\");\r\n                        s0.parentNode.insertBefore(s1,s0);\r\n                        })();\r\n                    </script>', '{\"app_key\":{\"title\":\"App Key\",\"value\":\"121\"}}', 'twak.png', 0, '2019-10-18 11:16:05', '2025-05-07 10:35:27'),
(2, 'google-recaptcha2', 'Google Recaptcha 2', 'Key location is shown bellow', 'Google reCAPTCHA v2 blocks bots, reducing spam and enhancing website security', 'recaptcha3.png', '\n<script src=\"https://www.google.com/recaptcha/api.js\"></script>\n<div class=\"g-recaptcha\" data-sitekey=\"{{site_key}}\" data-callback=\"verifyCaptcha\"></div>\n<div id=\"g-recaptcha-error\"></div>', '{\"site_key\":{\"title\":\"Site Key\",\"value\":\"6LdPC88fAAAAADQlUf_DV6Hrvgm-pZuLJFSLDOWV\"},\"secret_key\":{\"title\":\"Secret Key\",\"value\":\"6LdPC88fAAAAAG5SVaRYDnV2NpCrptLg2XLYKRKB\"}}', 'recaptcha.png', 0, '2019-10-18 11:16:05', '2025-06-01 08:39:13'),
(3, 'custom-captcha', 'Custom Captcha', 'Just put any random string', 'Custom Captcha checks users with simple challenges, stopping spam and keeping your site safe', 'customcaptcha.png', NULL, '{\"random_key\":{\"title\":\"Random String\",\"value\":\"SecureString\"}}', 'na', 0, '2019-10-18 11:16:05', '2025-06-18 02:54:02'),
(4, 'google-analytics', 'Google Analytics', 'Key location is shown bellow', '\nGoogle Analytics tracks website traffic and user behavior, helping you improve performance and understand your audience', 'google_analytics.png', '<script async src=\"https://www.googletagmanager.com/gtag/js?id={{measurement_id}}\"></script>\n                <script>\n                  window.dataLayer = window.dataLayer || [];\n                  function gtag(){dataLayer.push(arguments);}\n                  gtag(\"js\", new Date());\n                \n                  gtag(\"config\", \"{{measurement_id}}\");\n                </script>', '{\"measurement_id\":{\"title\":\"Measurement ID\",\"value\":\"------\"}}', 'ganalytics.png', 0, NULL, '2021-05-03 22:19:12'),
(5, 'fb-comment', 'Facebook Comment ', 'Key location is shown bellow', 'Facebook Comment lets users share feedback on your site, increasing engagement and social interaction', 'Facebook.png', '<div id=\"fb-root\"></div><script async defer crossorigin=\"anonymous\" src=\"https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v4.0&appId={{app_key}}&autoLogAppEvents=1\"></script>', '{\"app_key\":{\"title\":\"App Key\",\"value\":\"----\"}}', 'fb_com.png', 0, NULL, '2022-03-21 17:18:36');

-- --------------------------------------------------------

--
-- Table structure for table `floaters`
--

CREATE TABLE `floaters` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `dial_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `color_code` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_data` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `act`, `form_data`, `created_at`, `updated_at`) VALUES
(7, 'kyc', '{\"father\'s_name\":{\"name\":\"Father\'s Name\",\"label\":\"father\'s_name\",\"is_required\":\"required\",\"instruction\":null,\"extensions\":null,\"options\":[],\"type\":\"text\",\"width\":\"12\"},\"mother\'s_name\":{\"name\":\"Mother\'s name\",\"label\":\"mother\'s_name\",\"is_required\":\"required\",\"instruction\":null,\"extensions\":null,\"options\":[],\"type\":\"text\",\"width\":\"12\"},\"gender\":{\"name\":\"Gender\",\"label\":\"gender\",\"is_required\":\"required\",\"instruction\":null,\"extensions\":\"\",\"options\":[\"Male\",\"Female\"],\"type\":\"radio\",\"width\":\"12\"},\"nationality\":{\"name\":\"Nationality\",\"label\":\"nationality\",\"is_required\":\"required\",\"instruction\":null,\"extensions\":null,\"options\":[],\"type\":\"text\",\"width\":\"12\"},\"nid_photo_both_side\":{\"name\":\"NID Photo Both Side\",\"label\":\"nid_photo_both_side\",\"is_required\":\"required\",\"instruction\":null,\"extensions\":\"jpg,jpeg,png\",\"options\":[],\"type\":\"file\",\"width\":\"12\"}}', '2022-03-17 02:56:14', '2025-04-09 09:24:53');

-- --------------------------------------------------------

--
-- Table structure for table `frontends`
--

CREATE TABLE `frontends` (
  `id` bigint UNSIGNED NOT NULL,
  `data_keys` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_values` longtext COLLATE utf8mb4_unicode_ci,
  `seo_content` longtext COLLATE utf8mb4_unicode_ci,
  `tempname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `frontends`
--

INSERT INTO `frontends` (`id`, `data_keys`, `data_values`, `seo_content`, `tempname`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'seo.data', '{\"seo_image\":\"1\",\"social_title\":\"OvoWpp \\u2013 Complete Cross Platform WhatsApp CRM and Marketing Tool | Web and Mobile App | SaaS\",\"keywords\":[\"ovowpp\",\"whatsapp crm\",\"whatsapp marketing tool\",\"whatsapp automation\",\"whatsapp campaign manager\",\"bulk whatsapp sender\",\"whatsapp chatbot\",\"whatsapp saas\",\"crm saas\",\"whatsapp message scheduler\",\"cross platform whatsapp app\",\"whatsapp marketing saas\",\"mobile whatsapp crm\",\"whatsapp widget integration\"],\"description\":\"OvoWpp is a complete cross-platform SaaS-based WhatsApp CRM and marketing solution designed to help businesses connect, engage, and convert effortlessly. With powerful web and mobile apps, and a centralized admin panel for full control, OvoWpp makes it easy to manage customer communication and automate campaigns. Whether you\'re handling customer, sending bulk messages, or building a CRM & Marketing Tool, OvoWpp offers everything in one subscription-based platform \\u2014 giving you all the tools to grow your brand through WhatsApp with the simplicity and scalability of SaaS.\",\"social_description\":\"OvoWpp is a complete cross-platform SaaS-based WhatsApp CRM and marketing solution designed to help businesses connect, engage, and convert effortlessly. With powerful web and mobile apps, and a centralized admin panel for full control, OvoWpp makes it easy to manage customer communication and automate campaigns. Whether you\'re handling customer, sending bulk messages, or building a CRM & Marketing Tool, OvoWpp offers everything in one subscription-based platform \\u2014 giving you all the tools to grow your brand through WhatsApp with the simplicity and scalability of SaaS.\",\"image\":\"6849880e4ed6c1749649422.png\"}', NULL, NULL, '', '2020-07-04 17:42:52', '2025-06-11 13:52:15'),
(24, 'about.content', '{\"has_image\":\"1\",\"heading\":\"Latest News\",\"subheading\":\"11\",\"description\":\"fdg sdfgsdf g ggg\",\"about_icon\":\"<i class=\\\"las la-address-card\\\"><\\/i>\",\"background_image\":\"60951a84abd141620384388.png\",\"about_image\":\"5f9914e907ace1603867881.jpg\"}', NULL, 'basic', '', '2020-10-27 18:51:20', '2024-09-10 01:37:23'),
(25, 'blog.content', '{\"heading\":\"Our Latest Blog\",\"subheading\":\"Explore our collection of articles, tips, and tutorials to help your business\"}', NULL, 'basic', '', '2020-10-27 18:51:34', '2025-06-01 09:20:21'),
(28, 'counter.content', '{\"heading\":\"Latest News\",\"subheading\":\"Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus necessitatibus repudiandae porro reprehenderit, beatae perferendis repellat quo ipsa omnis, vitae!\"}', NULL, 'basic', '', '2020-10-27 19:04:02', '2024-10-07 23:16:28'),
(31, 'social_icon.element', '{\"title\":\"Instagram\",\"social_icon\":\"<i class=\\\"fab fa-instagram\\\"><\\/i>\",\"url\":\"https:\\/\\/www.instagram.com\\/\"}', NULL, 'basic', '', '2020-11-11 22:07:30', '2025-03-27 07:16:28'),
(33, 'feature.content', '{\"heading\":\"Here\'s What You Get\",\"subheading\":\"Everything you need to grow, automate, and connect with your audience\"}', NULL, 'basic', '', '2021-01-03 17:40:54', '2025-05-31 14:59:29'),
(35, 'service.element', '{\"trx_type\":\"withdraw\",\"service_icon\":\"<i class=\\\"las la-highlighter\\\"><\\/i>\",\"title\":\"asdfasdf\",\"description\":\"asdfasdfasdfasdf\"}', NULL, 'basic', '', '2021-03-05 19:12:10', '2024-09-10 01:53:26'),
(36, 'service.content', '{\"trx_type\":\"deposit\",\"heading\":\"asdf fffff\",\"subheading\":\"555\"}', NULL, 'basic', '', '2021-03-05 19:27:34', '2024-09-10 01:39:12'),
(41, 'cookie.data', '{\"short_desc\":\"We may utilize cookies when you access our website, including any related media platforms or mobile applications. These technologies are employed to enhance site functionality and optimize your interactions with our services.\",\"description\":\"<div>\\r\\n    <h4>What Are Cookies?<\\/h4>\\r\\n    <p>Cookies are small data files that are placed on your computer or mobile device when you visit a website. These\\r\\n        files contain information that is transferred to your device\\u2019s hard drive. Cookies are widely used by website\\r\\n        owners for various purposes: they help websites function properly by enabling essential features such as page\\r\\n        navigation and access to secure areas; they improve efficiency by remembering your preferences and actions over\\r\\n        time, such as login details and language settings, so you don\\u2019t have to re-enter them each time you visit; they\\r\\n        provide reporting information that helps website owners understand how their site is being used, including data\\r\\n        on page visits, duration of visits, and any errors that occur, which is crucial for improving site performance\\r\\n        and user experience; they personalize your experience by remembering your preferences and tailoring content to\\r\\n        your interests, including showing relevant advertisements or recommendations based on your browsing history; and\\r\\n        they enhance security by detecting fraudulent activity and protecting your data from unauthorized access. By\\r\\n        using cookies, website owners can enhance the overall functionality and efficiency of their sites, providing a\\r\\n        better experience for their users.<\\/p>\\r\\n    <p><br><\\/p>\\r\\n<\\/div>\\r\\n\\r\\n<div>\\r\\n    <h4>Why Do We Use Cookies?<\\/h4>\\r\\n    <p>We use cookies for several reasons. Some cookies are required for technical reasons for our website to operate,\\r\\n        and we refer to these as \\u201cessential\\u201d or \\u201cstrictly necessary\\u201d cookies. These essential cookies are crucial for\\r\\n        enabling basic functions like page navigation, secure access to certain areas, and ensuring the overall\\r\\n        functionality of the site. Without these cookies, the website cannot perform properly.<\\/p>\\r\\n    <p>Other cookies enable us to track and target the interests of our users to enhance the experience on our website.\\r\\n        These cookies help us understand your preferences and behaviors, allowing us to tailor content and features to\\r\\n        better suit your needs. For example, they can remember your login details, language preferences, and other\\r\\n        customizable settings, providing a more personalized and efficient browsing experience.<br><\\/p>\\r\\n    <p><br><\\/p>\\r\\n<\\/div>\\r\\n<div>\\r\\n    <h4>Types of Cookies We Use<\\/h4>\\r\\n    <p>\\r\\n    <\\/p>\\r\\n    <ul style=\\\"margin-left:30px;list-style:circle;\\\"><li style=\\\"margin-bottom:10px;\\\">\\r\\n            <strong>Essential Cookies<\\/strong>\\r\\n            <span>These cookies are necessary for the website to function and cannot be switched off in our systems.\\r\\n                They are usually only set in response to actions made by you which amount to a request for services,\\r\\n                such as setting your privacy preferences, logging in, or filling in forms.<\\/span>\\r\\n        <\\/li><li style=\\\"margin-bottom:10px;\\\">\\r\\n            <strong>Performance and Functionality Cookies<\\/strong>\\r\\n            <span>These cookies are used to enhance the performance and functionality of our website but are\\r\\n                non-essential to its use. However, without these cookies, certain functionality may become\\r\\n                unavailable.<\\/span>\\r\\n        <\\/li><li style=\\\"margin-bottom:10px;\\\">\\r\\n            <strong>Analytics and Customization Cookies <\\/strong>\\r\\n            <span>These cookies collect information that is used either in aggregate form to help us understand how our\\r\\n                website is being used or how effective our marketing campaigns are, or to help us customize our website\\r\\n                for you.<\\/span>\\r\\n        <\\/li><li style=\\\"margin-bottom:10px;\\\">\\r\\n            <strong>Advertising Cookies<\\/strong>\\r\\n            <span>These cookies are used to make advertising messages more relevant to you. They perform functions like\\r\\n                preventing the same ad from continuously reappearing, ensuring that ads are properly displayed for\\r\\n                advertisers, and in some cases selecting advertisements that are based on your interests.<\\/span>\\r\\n        <\\/li><\\/ul>\\r\\n    <p><\\/p>\\r\\n<\\/div>\\r\\n<br>\\r\\n\\r\\n<div>\\r\\n    <h4>Your Choices Regarding Cookies<\\/h4>\\r\\n    <p>You have the right to decide whether to accept or reject cookies. You can exercise your cookie preferences by\\r\\n        clicking on the appropriate opt-out links provided in the cookie banner. This banner typically appears when you\\r\\n        first visit our website and allows you to choose which types of cookies you are comfortable with. You can also\\r\\n        set or amend your web browser controls to accept or refuse cookies. Most web browsers provide settings that\\r\\n        allow you to manage or delete cookies, and you can usually find these settings in the \\u201coptions\\u201d or \\u201cpreferences\\u201d\\r\\n        menu of your browser.<\\/p>\\r\\n    <p><br><\\/p>\\r\\n    <p>If you choose to reject cookies, you may still use our website, though your access to some functionality and\\r\\n        areas of our website may be restricted. For example, certain features that rely on cookies to remember your\\r\\n        preferences or login details may not work properly. Additionally, rejecting cookies may impact the\\r\\n        personalization of your experience, as we use cookies to tailor content and advertisements to your interests.\\r\\n        Despite these limitations, we respect your right to control your cookie preferences and strive to provide a\\r\\n        functional and enjoyable browsing experience regardless of your choices.<\\/p>\\r\\n<\\/div>\\r\\n<br>\\r\\n\\r\\n<div>\\r\\n    <h4>Contact Us\\r\\n    <\\/h4>\\r\\n    <p>\\r\\n        If you have any questions about our use of cookies or other technologies, please contact <a href=\\\"contact\\\"><strong> with us<\\/strong><\\/a>. Our team is available to assist you with any inquiries or concerns you may have\\r\\n        regarding our cookie policy. We value your privacy and are committed to ensuring that your experience on our\\r\\n        website is transparent and satisfactory.\\r\\n    <\\/p>\\r\\n<\\/div>\\r\\n<br><br>\",\"status\":1}', NULL, NULL, NULL, '2020-07-04 17:42:52', '2024-09-18 22:51:09'),
(42, 'policy_pages.element', '{\"title\":\"Privacy Policy\",\"details\":\"<div>\\r\\n    \\r\\n    <p>This Privacy Policy outlines how we collect, use, disclose, and protect your personal information when you visit\\r\\n        our website. By accessing or using our website, you agree to the terms of this Privacy Policy\\r\\n        and consent to the collection and use of your information as described herein.\\r\\n        We are committed to ensuring that your privacy is protected. Should we ask you to provide certain information by\\r\\n        which you can be identified when using this website, you can be assured that it will only be used in accordance\\r\\n        with this Privacy Policy. We regularly review our compliance with this policy and ensure that all data handling\\r\\n        practices are transparent and secure.\\r\\n    <\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n    <h4> Information We Collect<\\/h4>\\r\\n    <p>We collect personal information such as names, email addresses, \\r\\nand browsing data to enhance user experience and provide personalized \\r\\nservices. This data helps us understand user preferences and improve our\\r\\n offerings. Your privacy is important to us, and we ensure that all \\r\\ninformation is handled with strict confidentiality.<\\/p>\\r\\n    <ul style=\\\"margin-left:30px;list-style:circle;\\\"><li style=\\\"margin-bottom:10px;\\\">\\r\\n            <span>Personal Information:<\\/span>\\r\\n            <span>Name, email address, phone number, and other contact details.<\\/span>\\r\\n        <\\/li><li style=\\\"margin-bottom:10px;\\\">\\r\\n            <span>Usage Data:<\\/span>\\r\\n            <span>Information about how you use our website, including your IP address, browser type, and pages\\r\\n                visited.<\\/span>\\r\\n        <\\/li><li style=\\\"margin-bottom:10px;\\\">\\r\\n            <span>Cookies and Tracking technology:<\\/span>\\r\\n            <span> We use cookies to enhance your experience on our website. You can manage your cookie preferences\\r\\n                through your browser settings.<\\/span>\\r\\n        <\\/li><\\/ul>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n    <h4>How We Use Your Information<\\/h4>\\r\\n    <p>We use your information to provide and improve our services, \\r\\nensuring a personalized experience tailored to your needs. This includes\\r\\n processing transactions, communicating updates, and responding to \\r\\ninquiries. Additionally, we use your data for analytical purposes to \\r\\nenhance our offerings and for security measures to protect against \\r\\nfraud.<\\/p>\\r\\n    <ul style=\\\"margin-left:30px;list-style:circle;\\\"><li style=\\\"margin-bottom:10px;\\\">\\r\\n            <span>To provide and maintain our services.<\\/span>\\r\\n        <\\/li><li style=\\\"margin-bottom:10px;\\\">\\r\\n            <span>To improve and personalize your experience on our website.\\r\\n            <\\/span>\\r\\n        <\\/li><li style=\\\"margin-bottom:10px;\\\">\\r\\n            <span>To communicate with you, including sending updates and promotional materials.\\r\\n            <\\/span>\\r\\n        <\\/li><li style=\\\"margin-bottom:10px;\\\">\\r\\n            <span>\\r\\n                To analyze website usage and improve our services.\\r\\n            <\\/span>\\r\\n        <\\/li><\\/ul>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n\\r\\n<div>\\r\\n    <h4>Sharing Your Information<\\/h4>\\r\\n    <p>\\r\\n        We do not sell, trade, or otherwise transfer your personal information to outside parties except as described in\\r\\n        this Privacy Policy. We take reasonable steps to ensure that any third parties with whom we share your personal\\r\\n        information are bound by appropriate confidentiality and security obligations regarding your personal\\r\\n        information.\\r\\n\\r\\n        We understand the importance of maintaining the privacy and security of your personal information. Therefore, we\\r\\n        implement stringent measures to protect your data from unauthorized access, use, or disclosure. Our commitment\\r\\n        to safeguarding your privacy includes:\\r\\n\\r\\n    <\\/p><ul style=\\\"margin-left:30px;list-style:circle;\\\"><li style=\\\"margin-bottom:10px;\\\">\\r\\n            <strong>Data Encryption<\\/strong>\\r\\n            <span>We use advanced encryption technologies to protect your personal information during transmission and\\r\\n                storage. This ensures that your data is secure and inaccessible to unauthorized parties.<\\/span>\\r\\n        <\\/li><li style=\\\"margin-bottom:10px;\\\">\\r\\n            <strong>Access Controls<\\/strong>\\r\\n            <span>We restrict access to your personal information to only those employees, contractors, and agents who\\r\\n                need to know that information to process it on our behalf. These individuals are subject to strict\\r\\n                confidentiality obligations and may be disciplined or terminated if they fail to meet these\\r\\n                obligations.<\\/span>\\r\\n        <\\/li><li style=\\\"margin-bottom:10px;\\\">\\r\\n            <strong>Regular Audits<\\/strong>\\r\\n            <span>We conduct regular audits of our data handling practices and security measures to ensure compliance\\r\\n                with this Privacy Policy and applicable laws. This helps us identify and address any potential\\r\\n                vulnerabilities in our systems.<\\/span>\\r\\n        <\\/li><li style=\\\"margin-bottom:10px;\\\">\\r\\n            <strong>Incident Response<\\/strong>\\r\\n            <span>n the unlikely event of a data breach, we have established procedures to respond promptly and\\r\\n                effectively. We will notify you and any relevant authorities as required by law and take all necessary\\r\\n                steps to mitigate the impact of the breach.<\\/span>\\r\\n        <\\/li><\\/ul>\\r\\n\\r\\n<\\/div>\\r\\n\\r\\n<br \\/>\\r\\n\\r\\n<div>\\r\\n    <h4>Contact Us\\r\\n    <\\/h4>\\r\\n    <p>\\r\\n        If you have any questions about our privacy & policy, please contact\\u00a0<a href=\\\"\\/contact\\\"><strong>with us<\\/strong><\\/a>. Our team is available to assist you with any inquiries or\\r\\n        concerns you may have\\r\\n        regarding our privacy & policy. We value your privacy and are committed to ensuring that your experience on our\\r\\n        website is transparent and satisfactory.\\r\\n    <\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\"}', '{\"image\":null,\"description\":null,\"social_title\":null,\"social_description\":null,\"keywords\":null}', 'basic', 'privacy-policy', '2021-06-09 02:50:42', '2025-06-01 11:01:22'),
(43, 'policy_pages.element', '{\"title\":\"Terms of Service\",\"details\":\"<div>\\r\\n    \\r\\n    <p>By accessing this website, you agree to be bound by these Terms and Conditions of Use, along with all applicable laws and regulations. You are responsible for compliance with any local laws that may apply. If you do not agree with any of these terms, you are prohibited from using or accessing this site.<\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n    <h4>Customer Support<\\/h4>\\r\\n    <p>After purchasing or downloading our product, you can reach out for support via email. We will make every effort to resolve your issue and may provide smaller fixes through email correspondence, followed by updates to the core package. Verified users can access content support through our ticketing system, as well as via email and live chat.<\\/p>\\r\\n    <p>If your request requires additional modifications to the system, you have two options:<\\/p><p><br \\/><\\/p>\\r\\n    <ul>\\r\\n        <li>Wait for the next update release.<\\/li>\\r\\n        <li>Hire an expert (customizations are available for an additional fee).<\\/li>\\r\\n    <\\/ul>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n    <h4>Intellectual Property Ownership<\\/h4>\\r\\n    <p>You cannot claim intellectual or exclusive ownership of any of our products, whether modified or unmodified. All products remain the property of our organization. Our products are provided \\\"as-is\\\" without any warranty, express or implied. Under no circumstances shall we be liable for any damages, including but not limited to direct, indirect, incidental, or consequential damages arising from the use or inability to use our products.<\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n    <h4>Product Warranty Disclaimer<\\/h4>\\r\\n    <p>We do not offer any warranty or guarantee for our services. Once our services have been modified, we cannot guarantee compatibility with third-party plugins, modules, or web browsers. Browser compatibility should be tested using the demo templates. Please ensure the browsers you use are compatible, as we cannot guarantee functionality across all browser combinations.<\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n    <h4>Prohibited and Illegal Use<\\/h4>\\r\\n    <p>You may not use our products for any illegal or unauthorized purposes, nor may you violate any laws in your jurisdiction (including but not limited to copyright laws), as well as the laws of your country and international laws. The use of our platform for pages that promote violence, terrorism, explicit adult content, racism, offensive material, or illegal software is strictly prohibited.<\\/p>\\r\\n    <p>It is prohibited to reproduce, duplicate, copy, sell, trade, or exploit any part of our products without our express written permission or the product owner\'s consent.<\\/p>\\r\\n    <p>Our members are responsible for all content posted on forums and demos, as well as any activities that occur under their account. We reserve the right to block your account immediately if we detect any prohibited behavior.<\\/p>\\r\\n    <p>If you create an account on our site, you are responsible for maintaining the security of your account and all activities that occur under it. You must notify us immediately of any unauthorized use or security breaches.<\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n    <h4>Payment and Refund Policy<\\/h4>\\r\\n    <p>Refunds or cashback will not be issued. Once a deposit is made, it is non-reversible. You must use your balance to purchase our services, such as hosting or SEO campaigns. By making a deposit, you agree not to file a dispute or chargeback against us.<\\/p>\\r\\n    <p>If a dispute or chargeback is filed after making a deposit, we reserve the right to terminate all future orders and ban you from our site. Fraudulent activities, such as using unauthorized or stolen credit cards, will result in account termination without exceptions.<\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n    <h4>Free Balance and Coupon Policy<\\/h4>\\r\\n    <p>We offer multiple ways to earn free balance, coupons, and deposit offers, but we reserve the right to review and deduct these balances if we believe there is any form of misuse. If we deduct your free balance and your account balance becomes negative, your account will be suspended. To reactivate a suspended account, you must make a custom payment to settle your balance.<\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\\r\\n<div>\\r\\n    <h4>Contact Information<\\/h4>\\r\\n    <p>If you have any questions about our Terms of Service, please contact us through <a href=\\\"\\/contact\\\"><strong>this link<\\/strong><\\/a>. Our team is available to assist you with any inquiries or concerns regarding our Terms of Service. We are committed to ensuring that your experience on our platform is secure and satisfactory.<\\/p>\\r\\n<\\/div>\\r\\n<br \\/>\"}', '{\"image\":\"6635d5d9618e71714804185.png\",\"description\":null,\"social_title\":null,\"social_description\":null,\"keywords\":null}', 'basic', 'terms-of-service', '2021-06-09 02:51:18', '2025-06-01 11:03:52'),
(44, 'maintenance.data', '{\"description\":\"<div class=\\\"mb-5\\\" style=\\\"margin-bottom: 3rem !important;\\\">\\r\\n    <h3 class=\\\"mb-3\\\" style=\\\"text-align: center;\\\">\\r\\n        <font color=\\\"#ff0000\\\" face=\\\"Exo, sans-serif\\\"><span style=\\\"font-size: 24px;\\\">SITE UNDER MAINTENANCE<\\/span><\\/font>\\r\\n    <\\/h3>\\r\\n    <div class=\\\"mb-3\\\">\\r\\n        <p><font color=\\\"#ffffff\\\">Our site is currently undergoing scheduled maintenance to enhance performance and ensure a smoother\\r\\n            experience for you. During this time, some features may be temporarily unavailable. We are committed to\\r\\n            completing this update as quickly as possible. Thank you for your patience and understanding as we work to\\r\\n            improve your experience. Please check back oon for further updates.<\\/font><\\/p>\\r\\n    <\\/div>\\r\\n<\\/div>\",\"image\":\"66e188642ac371726056548.png\"}', NULL, NULL, NULL, '2020-07-04 17:42:52', '2025-05-14 12:01:16'),
(55, 'counter.content', '{\"heading\":\"Latest Newsss\",\"subheading\":\"Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus necessitatibus repudiandae porro reprehenderit, beatae perferendis repellat quo ipsa omnis, vitae!\"}', NULL, 'basic', '', '2024-04-20 19:13:50', '2024-04-20 19:13:50'),
(56, 'counter.content', '{\"heading\":\"Latest News\",\"subheading\":\"Lorem ipsum dolor sit, amet consectetur adipisicing elit. Doloribus necessitatibus repudiandae porro reprehenderit, beatae perferendis repellat quo ipsa omnis, vitae!\"}', NULL, 'basic', '', '2024-04-20 19:13:52', '2024-04-20 19:13:52'),
(60, 'kyc.content', '{\"required\":\"Complete KYC to unlock the full potential of our platform! KYC helps us verify your identity and keep things secure. It is quick and easy just follow the on-screen instructions. Get started with KYC verification now!\",\"pending\":\"Your KYC verification is being reviewed. We might need some additional information. You will get an email update soon. In the meantime, explore our platform with limited features.\"}', NULL, 'basic', '', '2024-04-25 00:35:35', '2024-04-25 00:35:35'),
(61, 'kyc.content', '{\"required\":\"Complete KYC to unlock the full potential of our platform! KYC helps us verify your identity and keep things secure. It is quick and easy just follow the on-screen instructions. Get started with KYC verification now!\",\"pending\":\"Your KYC verification is being reviewed. We might need some additional information. You will get an email update soon. In the meantime, explore our platform with limited features.\",\"reject\":\"We regret to inform you that the Know Your Customer (KYC) information provided has been reviewed and unfortunately, it has not met our verification standards.\"}', NULL, 'basic', '', '2024-04-25 00:40:29', '2024-04-25 00:40:29'),
(64, 'banner.content', '{\"has_image\":\"1\",\"heading\":\"Grow Your Business with WhatsApp CRM and Marketing Tools\",\"subheading\":\"Create campaigns, automate chats, manage contacts\\u2014 all from one powerful dashboard.\",\"button_text\":\"Get Started for Free\",\"button_url\":\"user\\/register\",\"image\":\"68517999039511750170009.png\"}', NULL, 'basic', '', '2024-04-30 18:06:45', '2025-06-18 03:20:09'),
(66, 'register_disable.content', '{\"has_image\":\"1\",\"heading\":\"Registration Currently Disabled\",\"subheading\":\"Page you are looking for doesn\'t exit or an other error occurred or temporarily unavailable.\",\"button_name\":\"Go to Home\",\"button_url\":\"#\",\"image\":\"68230bf6cdb771747127286.png\"}', NULL, 'basic', '', '2024-05-06 23:23:12', '2025-05-13 09:08:06'),
(68, 'faq.content', '{\"heading\":\"Answers to Common Questions\",\"subheading\":\"Before reaching out, browse through our FAQs for quick solutions to your concerns.\"}', NULL, 'basic', '', '2024-09-10 18:52:59', '2025-06-03 13:07:11'),
(69, 'counter.element', '{\"title\":\"Nesciunt excepteur\",\"counter_digit\":\"Excepturi atque solu\",\"sub_title\":\"Fugiat qui officia p\",\"counter_icon\":\"<i class=\\\"fab fa-accusoft\\\"><\\/i>\",\"counter_icon_2\":\"<i class=\\\"fab fa-accessible-icon\\\"><\\/i>\"}', NULL, 'basic', '', '2024-10-07 23:04:22', '2024-10-07 23:16:14'),
(70, 'how_it_work.content', '{\"heading\":\"How it Works?\",\"subheading\":\"Understand the process from signup to campaign launch in just a few steps\",\"button_text\":\"Get Started for Free\",\"button_url\":\"#\"}', NULL, 'basic', '', '2025-02-06 08:02:29', '2025-05-31 14:34:39'),
(71, 'how_it_work.element', '{\"step_icon\":\"<i class=\\\"fas fa-user-plus\\\"><\\/i>\",\"title\":\"Join to Platform\",\"subtitle\":\"Sign up for free and unlock a full-featured marketing and CRM platform within seconds\"}', NULL, 'basic', '', '2025-02-06 08:03:32', '2025-05-31 14:32:58'),
(72, 'how_it_work.element', '{\"step_icon\":\"<i class=\\\"las la-rocket\\\"><\\/i>\",\"title\":\"Explore our Feature\",\"subtitle\":\"Discover powerful tools like automation, campaign builders, and conversion boosters.\"}', NULL, 'basic', '', '2025-02-06 08:03:50', '2025-05-31 14:30:04'),
(73, 'how_it_work.element', '{\"step_icon\":\"<i class=\\\"far fa-address-book\\\"><\\/i>\",\"title\":\"Add or Import Your Contacts\",\"subtitle\":\"Add contacts manually or bulk import from your CRM tools, spreadsheets, or integrations.\"}', NULL, 'basic', '', '2025-02-06 08:04:20', '2025-05-31 14:32:25'),
(74, 'how_it_work.element', '{\"step_icon\":\"<i class=\\\"far fa-paper-plane\\\"><\\/i>\",\"title\":\"Send Message or Create Campaign\",\"subtitle\":\"Launch personalized messages or smart campaigns with real-time insights.\"}', NULL, 'basic', '', '2025-02-06 08:04:46', '2025-05-31 14:30:46'),
(75, 'feature.element', '{\"title\":\"Smart Contact Management\",\"feature_icon\":\"<i class=\\\"far fa-address-book\\\"><\\/i>\",\"description\":\"Easily organize, segment, and import contacts from files or integrations. Tag and filter contacts for better targeting in campaigns. Keep your WhatsApp marketing list clean and organized.\"}', NULL, 'basic', '', '2025-02-06 08:06:39', '2025-05-31 14:54:00'),
(76, 'feature.element', '{\"title\":\"Template Manager\",\"feature_icon\":\"<i class=\\\"far fa-clone\\\"><\\/i>\",\"description\":\"Create and save message templates for faster communication. Use dynamic variables to personalize each message. Supports header, body, footer, and buttons for a rich message experience.\"}', NULL, 'basic', '', '2025-02-06 08:06:46', '2025-05-31 14:50:18'),
(77, 'feature.element', '{\"title\":\"Manage Campaign\",\"feature_icon\":\"<i class=\\\"las la-bullhorn\\\"><\\/i>\",\"description\":\"Plan and launch marketing campaigns across your WhatsApp channels. Track message delivery, read status, and responses in real-time. Perfect for promotions, alerts, and customer engagement.\"}', NULL, 'basic', '', '2025-02-06 08:07:00', '2025-05-31 14:54:16'),
(78, 'feature.element', '{\"title\":\"Chatbot Builder\",\"feature_icon\":\"<i class=\\\"las la-robot\\\"><\\/i>\",\"description\":\"Automate conversations with intelligent chatbot flows. Provide instant replies, collect leads, and support customers 24\\/7. No coding required.\"}', NULL, 'basic', '', '2025-02-06 08:07:07', '2025-05-31 14:54:33'),
(79, 'feature.element', '{\"title\":\"Welcome Message Automation\",\"feature_icon\":\"<i class=\\\"far fa-hand-spock\\\"><\\/i>\",\"description\":\"Send automated welcome messages to new customers or leads. Make a great first impression with personalized greetings and quick action buttons.\"}', NULL, 'basic', '', '2025-02-06 08:07:14', '2025-05-31 14:54:51'),
(80, 'feature.element', '{\"title\":\"Short Link Generator\",\"feature_icon\":\"<i class=\\\"fas fa-external-link-alt\\\"><\\/i>\",\"description\":\"Create branded WhatsApp short links with pre-filled messages. Share across social media, email, or websites to drive instant conversations and leads.\"}', NULL, 'basic', '', '2025-02-06 08:07:21', '2025-05-31 14:55:14'),
(81, 'testimonial.content', '{\"heading\":\"Trusted Thousands of Businesses\",\"subheading\":\"Hear how OvoWpp is transforming customer engagement across industries.\"}', NULL, 'basic', '', '2025-02-06 08:07:57', '2025-06-03 12:47:22'),
(82, 'testimonial.element', '{\"has_image\":[\"1\"],\"author_name\":\"Emily Zhang\",\"author_designation\":\"Sales Manager, ClickRise Media\",\"review\":\"Thanks to OvoWpp\\u2019s WhatsApp automation and CRM pipeline features, we\\u2019ve streamlined our lead nurturing. Follow-ups are now timely and effective. Highly efficient and reliable.\",\"ratings\":\"4\",\"image\":\"681f3fca8de7b1746878410.png\"}', NULL, 'basic', '', '2025-02-06 08:09:08', '2025-06-01 08:50:48'),
(83, 'testimonial.element', '{\"has_image\":[\"1\"],\"author_name\":\"Carlos Mendes\",\"author_designation\":\"Founder & CEO, NovaFix Solutions\",\"review\":\"From auto-replies to campaign analytics, OvoWpp has helped us scale our communication without losing the personal touch. The support team is responsive and the onboarding was smooth.\",\"ratings\":\"5\",\"image\":\"67a4c29d4a0161738850973.png\"}', NULL, 'basic', '', '2025-02-06 08:09:33', '2025-06-01 08:49:12'),
(84, 'mobile_app.content', '{\"has_image\":\"1\",\"heading\":\"Take Your Business Anywhere\",\"subheading\":\"Manage conversions, deposit, and more \\u2014 with the our cross-platform mobile app\",\"benefit_title\":\"Awsome Benefits of the App\",\"bottom_text\":\"Join 10,000+ users who trust us to keep them connected on the go.\\\"\",\"download_title\":\"Download on the\",\"apple_store_link\":\"https:\\/\\/www.apple.com\\/app-store\\/\",\"google_store_link\":\"https:\\/\\/play.google.com\\/store\\/apps\",\"image\":\"68404040305c41749041216.png\"}', NULL, 'basic', '', '2025-02-06 08:11:41', '2025-06-05 01:46:56'),
(85, 'mobile_app.element', '{\"benefits\":\"Instant notifications for new chats\"}', NULL, 'basic', '', '2025-02-06 08:11:52', '2025-02-06 08:11:52'),
(86, 'mobile_app.element', '{\"benefits\":\"Instant access to all customer chats and data\"}', NULL, 'basic', '', '2025-02-06 08:12:00', '2025-02-06 08:12:00'),
(87, 'mobile_app.element', '{\"benefits\":\"Easy access to customer data\"}', NULL, 'basic', '', '2025-02-06 08:12:07', '2025-02-06 08:12:07'),
(88, 'mobile_app.element', '{\"benefits\":\"Seamless synchronization with the web platform\"}', NULL, 'basic', '', '2025-02-06 08:12:15', '2025-02-06 08:12:15'),
(89, 'mobile_app.element', '{\"benefits\":\"Secure and fast performance\"}', NULL, 'basic', '', '2025-02-06 08:12:25', '2025-02-06 08:12:25'),
(95, 'cta.content', '{\"has_image\":\"1\",\"heading\":\"Get Started with OvoWpp Today\",\"subheading\":\"Whether you\'re a startup or a growing business, OvoWpp gives you everything you need to scale your communication\",\"button_text\":\"Start My Free Trail\",\"button_url\":\"user\\/register\",\"background_image\":\"67a4c478dfc961738851448.png\",\"shape_image\":\"67a4c479208b51738851449.png\",\"wrapper_image\":\"6840405e50f831749041246.png\"}', NULL, 'basic', '', '2025-02-06 08:17:28', '2025-06-05 01:47:26'),
(96, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"How WhatsApp CRM Tools Can Skyrocket Your Customer Engagement\",\"description\":\"<p> In today\\u2019s fast-paced digital world, customer engagement is no longer a luxury \\u2014 it\'s a necessity. Businesses that interact with customers in real-time, on platforms they already use, create stronger relationships and drive more conversions. <strong>\\\"How WhatsApp CRM Tools Can Skyrocket Your Customer Engagement\\\"<\\/strong> explores the transformative impact of integrating WhatsApp-based CRM tools into your communication strategy. <\\/p> <br \\/> <h4>Why Choose WhatsApp as a CRM Channel?<\\/h4> <p> With over 2 billion users globally, WhatsApp is more than just a messaging app \\u2014 it\\u2019s where your customers already are. Using it as a CRM channel enables faster responses, personalized support, and a conversational approach that emails or traditional CRM tools often lack. WhatsApp bridges the communication gap between businesses and customers, helping you stay connected and relevant. <\\/p> <br \\/> <h4>Real-Time Communication Builds Trust<\\/h4> <p> One of the key advantages of WhatsApp CRM tools is the ability to respond to queries instantly. Whether it\'s support, sales inquiries, or feedback, real-time messaging fosters transparency and builds trust. Customers feel valued when their concerns are addressed promptly, increasing brand loyalty. <\\/p> <br \\/> <h4>Automated Chatbots for 24\\/7 Availability<\\/h4> <p> Chatbots powered by WhatsApp CRM ensure that you never miss a customer query \\u2014 even outside business hours. These smart bots can answer FAQs, collect data, book appointments, and guide users through sales funnels, making your business always-on and responsive. <\\/p> <br \\/> <h4>Personalization at Scale<\\/h4> <p> WhatsApp CRM platforms allow you to segment audiences and send personalized messages. From using customer names to tailoring content based on behavior, personalization improves engagement rates dramatically. Customers appreciate businesses that \\u2018know\\u2019 them, leading to higher retention. <\\/p> <br \\/> <h4>Campaigns That Convert<\\/h4> <p> Want to launch a sale, share a new product, or run promotions? With WhatsApp CRM, you can create targeted campaigns that go directly to your customer\\u2019s inbox. This direct line boosts open rates, drives instant action, and helps you measure results effectively. <\\/p> <br \\/> <h4>Analytics and Performance Insights<\\/h4> <p> Advanced CRM tools come with dashboards and analytics that track delivery rates, responses, and conversions. These insights help you understand what\\u2019s working and what\\u2019s not \\u2014 so you can optimize your messaging strategy continuously. <\\/p> <br \\/> <h4>Integrating WhatsApp CRM into Your Workflow<\\/h4> <p> Modern platforms like OvoWpp make it simple to integrate WhatsApp CRM into your existing systems. Whether you\'re using CRMs, eCommerce platforms, or helpdesks, seamless integration ensures that your team works smarter \\u2014 not harder. <\\/p> <br \\/> <p> WhatsApp CRM tools are revolutionizing how businesses interact with their customers. From instant replies to automated workflows and data-driven campaigns, they offer everything you need to enhance customer engagement. Explore tools like <strong>OvoWpp<\\/strong> and see how easily you can build stronger connections, improve customer satisfaction, and ultimately grow your business. <\\/p>\",\"image\":\"683c243f5f9921748771903.png\"}', NULL, 'basic', 'how-whatsapp-crm-tools-can-skyrocket-your-customer-engagement', '2025-02-06 08:27:03', '2025-06-01 10:20:35'),
(97, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"5 Powerful Ways to Automate Your Marketing with OvoWpp\",\"description\":\"<p> In today\\u2019s fast-paced business world, efficiency is everything. Automating your marketing efforts not only saves valuable time but also ensures your brand is always engaging with customers \\u2014 even when you\\u2019re offline. OvoWpp, a powerful WhatsApp CRM and marketing tool, makes it easy to streamline your communication, improve customer experience, and increase conversions through automation. In this blog, <strong>\\\"5 Powerful Ways to Automate Your Marketing with OvoWpp\\\"<\\/strong>, we\\u2019ll explore how to use automation smartly and effectively to grow your business. <\\/p> <br \\/> <h4>1. Schedule Campaigns Ahead of Time<\\/h4> <p> Never miss the perfect moment to connect. OvoWpp allows you to schedule your WhatsApp marketing campaigns in advance, so you can plan weeks or months ahead. Whether it\\u2019s a holiday offer, a product launch, or a recurring promotion, scheduled campaigns let you deliver messages exactly when they\\u2019ll have the most impact \\u2014 even while you\\u2019re sleeping or away from the office. This ensures consistency and helps you maintain a strong presence without manual effort. <\\/p> <br \\/> <h4>2. Trigger Messages Based on User Actions<\\/h4> <p> Personalized automation is a game-changer. With OvoWpp, you can set up workflows that send messages based on user behavior \\u2014 like joining your contact list, clicking on a link, or not responding within a certain time. This real-time engagement increases relevance and response rates. For example, you can automatically send a reminder to users who abandoned their cart or a thank-you message after a purchase \\u2014 all without lifting a finger. <\\/p> <br \\/> <h4>3. Use Pre-Built Templates to Save Time<\\/h4> <p> Creating messages from scratch for every campaign can be tedious and inconsistent. OvoWpp\\u2019s built-in template manager helps you organize your most-used messages \\u2014 from promotional content to customer service replies \\u2014 so you can reuse and personalize them quickly. Templates also help maintain your brand tone and comply with WhatsApp\\u2019s message policy. This is especially useful when managing a large audience or working with multiple agents. <\\/p> <br \\/> <h4>4. Automate Lead Nurturing Sequences<\\/h4> <p> Don\\u2019t let leads grow cold. With OvoWpp, you can create automated message sequences that educate and guide prospects over time. For example, new users can receive a welcome series, product tutorials, case studies, and special offers \\u2014 all delivered on a schedule. These nurturing flows help build trust, keep your audience engaged, and move them naturally toward conversion \\u2014 without constant manual follow-up. <\\/p> <br \\/> <h4>5. Set Up Auto-Replies with Smart Chatbots<\\/h4> <p> Customers expect quick responses, and with OvoWpp\\u2019s chatbot builder, you can deliver exactly that. Build intelligent chatbots that respond to common queries, qualify leads, collect information, and even guide users through your sales funnel. These chatbots work around the clock, improving your service and saving you hours of manual communication. Plus, you can customize the flow to match your unique business needs. <\\/p> <br \\/> <p> Automating your marketing with OvoWpp isn\\u2019t just about saving time \\u2014 it\\u2019s about creating a smarter, more personalized experience for your customers. From scheduled broadcasts to intelligent chatbots, you get the tools to run campaigns that convert \\u2014 even while you sleep. Start automating today and take your WhatsApp marketing to the next level with OvoWpp. <\\/p>\",\"image\":\"683c24c7a7c0c1748772039.png\"}', NULL, 'basic', '5-powerful-ways-to-automate-your-marketing-with-ovowpp', '2025-02-06 08:28:11', '2025-06-01 10:25:41'),
(108, 'contact.content', '{\"heading\":\"Have questions? Sent us an email\",\"subheading\":\"Whether you have a question, need support, or want to learn more abut service, our team is here to help.\",\"other_contact_title\":\"Other Ways to Reach Us\",\"other_contact_subtitle\":\"Connect through these alternative contact options.\",\"business_address_title\":\"Our Business Address\",\"business_address_subtitle\":\"Physical location for business-related inquiries.\",\"working_hours_title\":\"Our Working Hours\",\"working_hours_subtitle\":\"We\\u2019re open during these business hours.\",\"contact_email\":\"support@ovowpp.com\",\"contact_number\":\"423-67-7588\",\"contact_address\":\"123 Business Street, Suite 456, Hollywood United States\",\"working_days\":\"Monday - Friday\",\"working_hours\":\"10 AM to 4 PM\"}', NULL, 'basic', '', '2025-02-08 01:04:08', '2025-05-31 10:42:37'),
(109, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Why Every Business Needs a WhatsApp Campaign Manager\",\"description\":\"<p> In an era where instant messaging dominates communication, WhatsApp has emerged as a vital platform for customer interaction and marketing. But to truly unlock its potential, businesses need more than just the app \\u2014 they need a structured approach to campaigns. That\\u2019s where a WhatsApp Campaign Manager like OvoWpp becomes essential. In this blog, <strong>\\u201cWhy Every Business Needs a WhatsApp Campaign Manager,\\u201d<\\/strong> we\\u2019ll explore how managing your WhatsApp campaigns effectively can significantly boost your engagement, productivity, and sales. <\\/p> <br \\/> <h4>Centralized Campaign Planning<\\/h4> <p> A dedicated campaign manager brings all your WhatsApp marketing efforts under one roof. From setting goals to tracking performance, everything becomes easier and more organized. OvoWpp helps you create, manage, and optimize your campaigns \\u2014 all in one place. This level of structure allows you to launch professional campaigns that are consistent with your brand voice and objectives. <\\/p> <br \\/> <h4>Targeted Customer Segmentation<\\/h4> <p> Sending the same message to your entire audience rarely yields the best results. A WhatsApp Campaign Manager lets you segment your contacts based on demographics, purchase history, behavior, and more. With OvoWpp, you can send highly relevant messages to the right groups \\u2014 improving engagement, reducing opt-outs, and boosting conversions through precision targeting. <\\/p> <br \\/> <h4>Improved Response and Conversion Rates<\\/h4> <p> WhatsApp has a much higher open rate compared to emails. When you manage your campaigns strategically, your messages not only get seen but also acted upon. Whether it\'s a time-sensitive offer, a customer feedback request, or a follow-up message, a campaign manager helps you reach users at the right time with the right content \\u2014 resulting in better response and conversion rates. <\\/p> <br \\/> <h4>Automation and Smart Scheduling<\\/h4> <p> Managing campaigns manually can be overwhelming. A WhatsApp Campaign Manager allows you to schedule messages, automate responses, and trigger messages based on user behavior. OvoWpp takes care of the repetitive tasks, so your team can focus on crafting powerful messages and creative strategies instead of being bogged down with daily manual outreach. <\\/p> <br \\/> <h4>Data-Driven Decision Making<\\/h4> <p> Campaign success depends on insights. A campaign manager provides performance analytics that help you understand what\\u2019s working and what\\u2019s not. With OvoWpp, you can track open rates, click-throughs, responses, and more \\u2014 enabling you to refine your campaigns and continuously improve results using real data. <\\/p> <br \\/> <p> WhatsApp marketing without proper management is like running a race blindfolded. A WhatsApp Campaign Manager gives your business the clarity, control, and efficiency needed to make every campaign a success. With tools like OvoWpp, you can streamline outreach, boost engagement, and grow faster \\u2014 all from the convenience of a single platform. <\\/p>\",\"image\":\"683c252858f0d1748772136.png\"}', NULL, 'basic', 'why-every-business-needs-a-whatsapp-campaign-manager', '2025-02-08 02:34:59', '2025-06-01 10:27:10'),
(110, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Boost Conversions with WhatsApp Short Links and Smart Widgets\",\"description\":\"<p> In the competitive world of digital marketing, even a small delay or extra step can cause a potential customer to drop off. That\\u2019s why businesses are turning to smart, instant communication tools to remove friction and increase conversion rates. In this blog, <strong>\\u201cBoost Conversions with WhatsApp Short Links and Smart Widgets,\\u201d<\\/strong> we\\u2019ll explore how you can use OvoWpp\\u2019s simple yet strategic features to drive higher engagement, better customer experiences, and stronger results across your sales funnel. <\\/p> <br \\/> <h4>One-Click Conversations That Convert<\\/h4> <p> When a user clicks your WhatsApp short link, they\\u2019re instantly taken to a chat window with your business \\u2014 no need to save numbers or copy-paste details. OvoWpp allows you to create short links with predefined messages that guide the user toward a specific action, such as booking a service, asking for help, or redeeming a promo. This instant interaction builds trust and increases the chances of conversion dramatically. <\\/p> <br \\/> <h4>Custom, Trackable Links with Insights<\\/h4> <p> Not all visitors behave the same. With OvoWpp\\u2019s short link generator, you can customize your links to match each campaign\\u2019s goal and track their performance in real time. Know which platforms drive the most engagement, what messages trigger the most responses, and optimize your outreach based on data \\u2014 not guesswork. These insights help you fine-tune your strategy and achieve measurable ROI. <\\/p> <br \\/> <h4>Smart Widgets That Guide the User<\\/h4> <p> Whether you\\u2019re running an eCommerce store, a service platform, or a lead generation website, having a floating WhatsApp chat widget can change the game. OvoWpp\\u2019s smart widget can display available agents, route chats based on department, and even launch automated responses. This functionality creates a more interactive experience and reduces bounce rates by keeping visitors engaged at key touchpoints. <\\/p> <br \\/> <h4>Shorten the Path from Interest to Action<\\/h4> <p> Every second counts. Traditional contact forms, email responses, or third-party chats often take too long and create barriers. WhatsApp short links and widgets reduce that process to a single tap. This immediacy helps close deals faster, solve customer questions right away, and create a sense of responsiveness that users love. The fewer the steps, the higher the chance they\\u2019ll take action. <\\/p> <br \\/> <h4>Multi-Purpose Use Cases<\\/h4> <p> WhatsApp links and widgets can be used across a wide range of marketing scenarios \\u2014 from launching product campaigns and sending customer reminders to gathering feedback and providing instant support. Whether you\\u2019re a solopreneur, a startup, or a large enterprise, these tools scale with your needs and work across industries. <\\/p> <br \\/> <h4>Enhance Your Ad & Email Campaigns<\\/h4> <p> Pair your paid ads or email newsletters with a WhatsApp CTA using OvoWpp short links. Direct users to take immediate action, such as \\u201cChat with Sales,\\u201d \\u201cGet Your Coupon,\\u201d or \\u201cAsk a Question.\\u201d When combined with strong messaging and design, this can significantly increase the effectiveness of your outreach and drive more meaningful interactions. <\\/p> <br \\/> <h4>Elevate Customer Experience with Automation<\\/h4> <p> Short links can also trigger chatbot flows through OvoWpp, providing immediate answers or guiding users through an automated journey. Combine them with smart widgets that offer help proactively based on scroll behavior or page context \\u2014 giving users what they need before they even ask. <\\/p> <br \\/> <p> WhatsApp short links and smart widgets are more than tools \\u2014 they\\u2019re conversion accelerators. With OvoWpp, you gain access to an ecosystem that makes customer engagement effortless, responsive, and data-driven. Implement them today and watch how your business builds stronger relationships and increases its success rate with every click. <\\/p>\",\"image\":\"683c2714dfd2d1748772628.png\"}', NULL, 'basic', 'boost-conversions-with-whatsapp-short-links-and-smart-widgets', '2025-02-08 02:36:19', '2025-06-01 10:30:29'),
(111, 'pricing.content', '{\"heading\":\"Simple and Transparent Pricing\",\"subheading\":\"The subscription will automatically renew every year before you unsubscribe.\"}', NULL, 'basic', '', '2025-02-08 02:43:46', '2025-05-31 15:01:13');
INSERT INTO `frontends` (`id`, `data_keys`, `data_values`, `seo_content`, `tempname`, `slug`, `created_at`, `updated_at`) VALUES
(112, 'auth.content', '{\"login_title\":\"Access Your Dashboard\",\"login_subtitle\":\"Enter your credentials to continue managing your business effortlessly.\",\"register_title\":\"Get Started with OvoWpp\",\"register_subtitle\":\"Register now and start your free trial with all premium features.\",\"has_image\":\"1\",\"login_image\":\"683efc81a09f91748958337.png\",\"register_image\":\"683efc81d6a781748958337.png\"}', NULL, 'basic', '', '2025-02-08 02:54:38', '2025-06-03 13:45:38'),
(113, 'feature_page.content', '{\"heading\":\"Discover the Powerful Features\",\"subheading\":\"From seamless messaging to insightful analytics, our tools are designed to simplify customer interactions and help your business grow.\"}', NULL, 'basic', '', '2025-03-27 05:51:44', '2025-06-01 10:54:02'),
(114, 'feature_page.element', '{\"has_image\":[\"1\"],\"title\":\"Unified Inbox\",\"heading\":\"All your conversation in one place\",\"description\":\"Easily manage and organize customer chats from WhatsApp in a centralized inbox.\",\"benefits\":\"<ul class=\\\"text-list\\\">\\r\\n    <li class=\\\"text-list__item\\\">\\r\\n        <span class=\\\"text-list__icon\\\"> <i class=\\\"las la-check\\\"><\\/i> <\\/span>\\r\\n         Instant switch to multiple whatsapp account\\r\\n    <\\/li>\\r\\n    <li class=\\\"text-list__item\\\">\\r\\n        <span class=\\\"text-list__icon\\\"> <i class=\\\"las la-check\\\"><\\/i> <\\/span>\\r\\n        Instant access to all customer chats and data\\r\\n    <\\/li>\\r\\n    <li class=\\\"text-list__item\\\">\\r\\n        <span class=\\\"text-list__icon\\\"> <i class=\\\"las la-check\\\"><\\/i> <\\/span>\\r\\n        Conversation tag, note & more\\r\\n    <\\/li>\\r\\n    <li class=\\\"text-list__item\\\">\\r\\n        <span class=\\\"text-list__icon\\\"> <i class=\\\"las la-check\\\"><\\/i> <\\/span>\\r\\n        Seamless synchronization with the web platform\\r\\n    <\\/li>\\r\\n\\r\\n<\\/ul>\",\"image\":\"684eb7859db891749989253.png\"}', NULL, 'basic', '', '2025-03-27 06:02:44', '2025-06-15 12:07:36'),
(115, 'feature_page.element', '{\"has_image\":[\"1\"],\"title\":\"Smart Automation\",\"heading\":\"Save Time with Automation\",\"description\":\"Automate repetitive tasks like sending welcome messages, follow-ups, and reminders with customizable workflows. Focus on what matters most\\u2014your customers.\",\"benefits\":\"<ul class=\\\"text-list\\\">\\r\\n    <li class=\\\"text-list__item\\\">\\r\\n        <span class=\\\"text-list__icon\\\"> <i class=\\\"las la-check\\\"><\\/i> <\\/span>\\r\\n         Easily set the welcome message for the each whatsapp account\\r\\n    <\\/li>\\r\\n    <li class=\\\"text-list__item\\\">\\r\\n        <span class=\\\"text-list__icon\\\"> <i class=\\\"las la-check\\\"><\\/i> <\\/span>\\r\\n        Multiple chatbot support for the anytime response.\\r\\n    <\\/li>\\r\\n    <li class=\\\"text-list__item\\\">\\r\\n        <span class=\\\"text-list__icon\\\"> <i class=\\\"las la-check\\\"><\\/i> <\\/span>\\r\\n        Send the scheduled message via scheduled campaign\\r\\n    <\\/li>\\r\\n<\\/ul>\",\"image\":\"684eb373cfee11749988211.png\"}', NULL, 'basic', '', '2025-03-27 06:12:41', '2025-06-15 11:50:11'),
(116, 'feature_page.element', '{\"has_image\":[\"1\"],\"title\":\"Contact Segmentation\",\"heading\":\"Organize Your Contacts Like Never Before\",\"description\":\"Group your customers into segments with tags, labels, and filters. Tailor your communication to specific audiences for better results.\",\"benefits\":\"<ul class=\\\"text-list\\\">\\r\\n    <li class=\\\"text-list__item\\\">\\r\\n        <span class=\\\"text-list__icon\\\"> <i class=\\\"las la-check\\\"><\\/i> <\\/span>\\r\\n         Add the contact by manually\\r\\n    <\\/li>\\r\\n    <li class=\\\"text-list__item\\\">\\r\\n        <span class=\\\"text-list__icon\\\"> <i class=\\\"las la-check\\\"><\\/i> <\\/span>\\r\\n        Import contact from your spreadsheet\\r\\n    <\\/li>\\r\\n    <li class=\\\"text-list__item\\\">\\r\\n        <span class=\\\"text-list__icon\\\"> <i class=\\\"las la-check\\\"><\\/i> <\\/span>\\r\\n        Multiple contact supported\\r\\n    <\\/li>\\r\\n    <li class=\\\"text-list__item\\\">\\r\\n        <span class=\\\"text-list__icon\\\"> <i class=\\\"las la-check\\\"><\\/i> <\\/span>\\r\\n        Support multiple contact list\\r\\n    <\\/li>\\r\\n<\\/ul>\",\"image\":\"684eb38a69c7b1749988234.png\"}', NULL, 'basic', '', '2025-03-27 06:14:15', '2025-06-15 11:50:34'),
(117, 'feature_page.element', '{\"has_image\":[\"1\"],\"title\":\"Message Templates\",\"heading\":\"Manage Your Message Templates Easily\",\"description\":\"Submit your messages as a template and feel the intimate fun of a message template.\",\"benefits\":\"<ul class=\\\"text-list\\\">\\r\\n    <li class=\\\"text-list__item\\\">\\r\\n        <span class=\\\"text-list__icon\\\"> <i class=\\\"las la-check\\\"><\\/i> <\\/span>\\r\\n         Multiple message template support for each account\\r\\n    <\\/li>\\r\\n    <li class=\\\"text-list__item\\\">\\r\\n        <span class=\\\"text-list__icon\\\"> <i class=\\\"las la-check\\\"><\\/i> <\\/span>\\r\\n        Media supported to the template header.\\r\\n    <\\/li>\\r\\n    <li class=\\\"text-list__item\\\">\\r\\n        <span class=\\\"text-list__icon\\\"> <i class=\\\"las la-check\\\"><\\/i> <\\/span>\\r\\n        Image, Video, Document support to the template body\\r\\n    <\\/li>\\r\\n<\\/ul>\",\"image\":\"684eb3a655e031749988262.png\"}', NULL, 'basic', '', '2025-03-27 06:14:51', '2025-06-15 11:51:02'),
(118, 'footer.content', '{\"description\":\"OvoWpp turns WhatsApp into a powerful CRM and marketing hub \\u2014 manage messages, chatbots, automation and agents with ease across web and mobile.\",\"subscribe_title\":\"Stay up to date\",\"subscribe_subtitle\":\"Stay up to date with the latest news, announcements, and articles.\"}', NULL, 'basic', '', '2025-03-27 06:42:49', '2025-05-31 10:26:24'),
(119, 'social_icon.element', '{\"title\":\"Facebook\",\"social_icon\":\"<i class=\\\"fab fa-facebook-square\\\"><\\/i>\",\"url\":\"https:\\/\\/www.facebook.com\"}', NULL, 'basic', '', '2025-03-27 07:15:24', '2025-03-27 07:15:24'),
(120, 'social_icon.element', '{\"title\":\"X\",\"social_icon\":\"<i class=\\\"fa-brands fa-x-twitter\\\"><\\/i>\",\"url\":\"https:\\/\\/www.x.com\"}', NULL, 'basic', '', '2025-03-27 07:15:50', '2025-03-27 07:15:50'),
(121, 'social_icon.element', '{\"title\":\"Linkedin\",\"social_icon\":\"<i class=\\\"fab fa-linkedin\\\"><\\/i>\",\"url\":\"https:\\/\\/www.linkedin.com\\/\"}', NULL, 'basic', '', '2025-03-27 07:17:04', '2025-03-27 07:17:04'),
(124, 'feature.element', '{\"title\":\"Unified Inbox Manager\",\"feature_icon\":\"<i class=\\\"fas fa-inbox\\\"><\\/i>\",\"description\":\"Manage all your WhatsApp chats in one smart inbox. Assign conversations to agents, track responses, and resolve issues quickly. Ideal for customer support teams.\"}', NULL, 'basic', '', '2025-05-31 14:47:13', '2025-05-31 14:52:34'),
(125, 'feature.element', '{\"title\":\"Customer CRM Manager\",\"feature_icon\":\"<i class=\\\"las la-users\\\"><\\/i>\",\"description\":\"Convert contacts into loyal customers with detailed profiles, history, and interaction tracking. Use filters and custom fields to personalize your communication strategy.\"}', NULL, 'basic', '', '2025-05-31 14:47:24', '2025-05-31 14:55:27'),
(126, 'feature.element', '{\"title\":\"Agent Management System\",\"feature_icon\":\"<i class=\\\"las la-users-cog\\\"><\\/i>\",\"description\":\"Invite and manage team members with role-based access. Assign chats, track performance, and boost productivity with organized agent collaboration.\"}', NULL, 'basic', '', '2025-05-31 14:47:39', '2025-05-31 14:55:43'),
(127, 'faq.element', '{\"category\":\"general\",\"question\":\"What is OvoWpp?\",\"answer\":\"OvoWpp is a complete cross-platform WhatsApp CRM and marketing tool that helps businesses manage conversations, automate customer engagement, and run marketing campaigns through WhatsApp.\"}', NULL, 'basic', '', '2025-06-01 08:54:11', '2025-06-01 08:54:11'),
(128, 'faq.element', '{\"category\":\"general\",\"question\":\"Who can use OvoWpp?\",\"answer\":\"OvoWpp is designed for businesses of all sizes \\u2014 from freelancers and startups to large enterprises \\u2014 who want to scale customer communication on WhatsApp.\"}', NULL, 'basic', '', '2025-06-01 08:54:22', '2025-06-01 08:54:22'),
(129, 'faq.element', '{\"category\":\"general\",\"question\":\"Does OvoWpp require technical skills to use?\",\"answer\":\"Not at all. OvoWpp features an intuitive dashboard with drag-and-drop automation, easy campaign setup, and simple integration steps suitable for all users.\"}', NULL, 'basic', '', '2025-06-01 08:54:32', '2025-06-01 08:54:32'),
(130, 'faq.element', '{\"category\":\"general\",\"question\":\"What platforms is OvoWpp compatible with?\",\"answer\":\"OvoWpp is accessible on all modern web browsers and optimized for desktop, tablet, and mobile use.\"}', NULL, 'basic', '', '2025-06-01 08:54:47', '2025-06-01 08:54:47'),
(131, 'faq.element', '{\"category\":\"general\",\"question\":\"Is OvoWpp officially integrated with WhatsApp?\",\"answer\":\"Yes, OvoWpp uses the official WhatsApp Business API, ensuring reliability, security, and full compliance with WhatsApp\\u2019s policies.\"}', NULL, 'basic', '', '2025-06-01 08:55:02', '2025-06-01 08:55:02'),
(132, 'faq.element', '{\"category\":\"service\",\"question\":\"What services does OvoWpp provide?\",\"answer\":\"OvoWpp offers WhatsApp CRM tools, message automation, contact segmentation, multi-agent inbox, campaign analytics, and lead tracking.\"}', NULL, 'basic', '', '2025-06-01 08:56:15', '2025-06-01 08:56:15'),
(133, 'faq.element', '{\"category\":\"service\",\"question\":\"Can I manage multiple WhatsApp numbers?\",\"answer\":\"Yes. OvoWpp\\u2019s Pro and Enterprise plans allow you to connect and manage multiple numbers from one central dashboard.\"}', NULL, 'basic', '', '2025-06-01 08:56:27', '2025-06-01 08:56:27'),
(134, 'faq.element', '{\"category\":\"service\",\"question\":\"Is team collaboration supported?\",\"answer\":\"Yes. Your entire support or marketing team can collaborate using shared inboxes, internal notes, and role-based access controls.\"}', NULL, 'basic', '', '2025-06-01 08:56:41', '2025-06-01 08:56:41'),
(135, 'faq.element', '{\"category\":\"service\",\"question\":\"Do you provide customer support?\",\"answer\":\"Yes. Our support team is available via live chat, email, and knowledge base. Enterprise clients receive priority support and a dedicated account manager.\"}', NULL, 'basic', '', '2025-06-01 08:56:51', '2025-06-01 08:56:51'),
(136, 'faq.element', '{\"category\":\"service\",\"question\":\"Is customer data safe with OvoWpp?\",\"answer\":\"Absolutely. We use industry-standard encryption, regular audits, and secure cloud infrastructure to ensure your data is protected. OvoWpp is also fully GDPR-compliant.\"}', NULL, 'basic', '', '2025-06-01 08:57:07', '2025-06-01 08:57:07'),
(137, 'faq.element', '{\"category\":\"subscription\",\"question\":\"What subscription plans do you offer?\",\"answer\":\"OvoWpp offers three plans: Starter (basic features), Pro (advanced tools + multiple users), and Enterprise (custom solutions and dedicated support).\"}', NULL, 'basic', '', '2025-06-01 08:58:46', '2025-06-01 08:58:56'),
(138, 'faq.element', '{\"category\":\"subscription\",\"question\":\"How does billing work for OvoWpp subscriptions?\",\"answer\":\"Subscriptions are billed on a monthly or yearly basis, depending on your chosen plan. Yearly subscriptions come with a discounted rate.\"}', NULL, 'basic', '', '2025-06-01 09:00:56', '2025-06-01 09:00:56'),
(139, 'faq.element', '{\"category\":\"subscription\",\"question\":\"Can I upgrade my subscription if my business grows?\",\"answer\":\"Yes. You can upgrade your plan at any time from your dashboard. All changes are applied immediately and prorated based on your billing cycle.\"}', NULL, 'basic', '', '2025-06-01 09:01:06', '2025-06-01 09:01:06'),
(140, 'faq.element', '{\"category\":\"subscription\",\"question\":\"What happens if I exceed my usage limit?\",\"answer\":\"If you exceed your plan\\u2019s limit (such as number of messages, contacts, or users), you will receive a notification with options to upgrade or purchase additional usage packs.\"}', NULL, 'basic', '', '2025-06-01 09:01:17', '2025-06-01 09:01:17'),
(141, 'faq.element', '{\"category\":\"subscription\",\"question\":\"Are invoices and billing history available?\",\"answer\":\"Yes. You can access and download all invoices and billing history directly from your OvoWpp account under the \\u201cBilling\\u201d section.\"}', NULL, 'basic', '', '2025-06-01 09:01:28', '2025-06-01 09:01:28'),
(142, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"From Contact to Customer,  The WhatsApp Sales Funnel Explained\",\"description\":\"<p> WhatsApp is no longer just a messaging app \\u2014 it\\u2019s a high-conversion sales tool that helps businesses build personalized journeys from first contact to final purchase. In this blog, <strong>\\u201cFrom Contact to Customer: The WhatsApp Sales Funnel Explained,\\u201d<\\/strong> we\\u2019ll break down each stage of the funnel and show how OvoWpp empowers you to engage, nurture, and convert leads directly inside WhatsApp. <\\/p> <br \\/> <h4>Stage 1: Awareness with WhatsApp Entry Points<\\/h4> <p> The top of the funnel begins with awareness \\u2014 and WhatsApp is the perfect entry channel. Use OvoWpp\\u2019s smart short links in your ads, website banners, email campaigns, or social posts. These links direct prospects to a WhatsApp conversation with a pre-filled message, lowering friction and giving them a convenient way to reach out. Whether they\\u2019re interested in a product or have a question, this stage is all about opening the conversation. <\\/p> <br \\/> <h4>Stage 2: Engagement via Chatbots and Quick Replies<\\/h4> <p> Once the lead is in, OvoWpp\\u2019s chatbot and template manager take over. Automated greetings, product info, FAQs, or qualification questions help engage users instantly \\u2014 even outside business hours. This keeps the prospect warm, nurtures interest, and ensures they receive timely responses that maintain momentum. Personalized flows can be designed to guide each visitor based on their intent and behavior. <\\/p> <br \\/> <h4>Stage 3: Consideration through Smart Follow-Ups<\\/h4> <p> Not every lead is ready to buy immediately \\u2014 that\\u2019s where OvoWpp\\u2019s campaign manager shines. You can segment contacts based on actions or responses and send targeted follow-ups with rich content: demos, testimonials, limited-time offers, or educational resources. These thoughtful nudges help move leads through the consideration stage toward a confident buying decision. <\\/p> <br \\/> <h4>Stage 4: Conversion with Live Agent or Automation<\\/h4> <p> When the prospect is ready, it\\u2019s time to convert. OvoWpp lets you assign live agents or continue automated flows to close the sale. Whether it\\u2019s sharing a payment link, booking a service, or confirming an order, the platform makes the final steps fast and convenient. The seamless transition between bot and human ensures no opportunity is missed. <\\/p> <br \\/> <h4>Stage 5: Retention and Upselling<\\/h4> <p> The funnel doesn\\u2019t end at the sale \\u2014 it loops into retention. With OvoWpp, you can set up automated check-ins, customer satisfaction surveys, and special offers via WhatsApp. Keep the customer engaged and build loyalty by sending relevant messages based on their previous purchases or interests. A happy customer is your best advocate. <\\/p> <br \\/> <h4>Why WhatsApp Sales Funnels Outperform Traditional Methods<\\/h4> <p> Traditional sales funnels rely on email, forms, and long wait times \\u2014 which today\\u2019s users find frustrating. WhatsApp provides instant access, high open rates, and conversational selling that feels human. OvoWpp wraps all the tools you need into one platform to make the entire journey smoother and more profitable. <\\/p> <br \\/> <p> With OvoWpp, you can build and manage your full WhatsApp sales funnel \\u2014 from generating leads to closing deals \\u2014 all within a single, intuitive interface. Whether you\'re a small business or a scaling enterprise, this approach ensures you\'re always one step closer to turning contacts into loyal customers. <\\/p>\",\"image\":\"683c27f65e2051748772854.png\"}', NULL, 'basic', 'from-contact-to-customer-the-whatsapp-sales-funnel-explained', '2025-06-01 09:45:24', '2025-06-01 10:32:33'),
(143, 'blog.element', '{\"has_image\":[\"1\"],\"title\":\"Top Benefits of Using OvoWpp for Agencies and Startups\",\"description\":\"<p>In today\\u2019s hyper-competitive market, agencies and startups need smarter ways to connect with their audience, nurture relationships, and drive sales without overwhelming their teams. <strong>OvoWpp<\\/strong> is not just another WhatsApp marketing tool \\u2014 it\\u2019s a comprehensive solution designed to help businesses unlock the full potential of WhatsApp as a high-impact communication and sales platform. In this blog, <strong>\\u201cTop Benefits of Using OvoWpp for Agencies and Startups,\\u201d<\\/strong> we dive deep into how OvoWpp empowers organizations to streamline workflows, automate customer interactions, and accelerate growth by leveraging WhatsApp\\u2019s unparalleled reach and engagement capabilities.<\\/p> <br \\/> <h4>Benefit 1: Simplified and Centralized Communication Management<\\/h4> <p>Managing client conversations across multiple channels can be chaotic and time-consuming. OvoWpp brings all WhatsApp chats into a unified dashboard, allowing agencies and startups to effortlessly monitor, respond to, and track conversations in real-time. This centralized communication hub helps avoid missed messages, improves response times, and ensures every client receives consistent, professional attention\\u2014essential for building trust and credibility.<\\/p> <br \\/> <h4>Benefit 2: Powerful Automation to Capture and Nurture Leads<\\/h4> <p>Generating leads and keeping them engaged requires constant effort. OvoWpp\\u2019s intelligent automation features, such as smart short links, chatbots, and triggered campaigns, capture leads right at the moment of interest and nurture them through personalized messaging flows. Whether prospects come from social ads, website banners, or email campaigns, OvoWpp initiates conversations automatically, answers FAQs instantly, and qualifies leads \\u2014 freeing your team to focus on closing deals rather than chasing contacts.<\\/p> <br \\/> <h4>Benefit 3: Higher Conversion Rates with Seamless Sales Processes<\\/h4> <p>Closing sales quickly is critical for startups and agencies looking to scale. OvoWpp bridges the gap between automated messaging and human interaction by enabling a smooth handoff to live agents when prospects are ready to buy. The platform supports direct sharing of payment links, appointment bookings, or detailed proposals within WhatsApp, removing friction from the buyer\\u2019s journey. This seamless flow ensures no opportunity slips away and significantly boosts conversion rates.<\\/p> <br \\/> <h4>Benefit 4: Cost-Efficient Customer Support and Marketing<\\/h4> <p>Startups and agencies often operate under tight budgets and resource constraints. OvoWpp\\u2019s automation tools reduce the need for large support teams by handling common queries and follow-ups automatically, cutting down operational costs. At the same time, it enhances marketing ROI by delivering personalized, timely campaigns directly to customers\\u2019 most-used messaging app \\u2014 increasing open rates and engagement far beyond traditional email or SMS marketing.<\\/p> <br \\/> <h4>Benefit 5: Effective Customer Retention and Upselling Opportunities<\\/h4> <p>Winning a customer is just the beginning; retaining and growing that relationship is where sustainable business happens. OvoWpp makes it easy to run automated check-ins, satisfaction surveys, and tailored upsell campaigns via WhatsApp. By staying connected with customers and offering relevant recommendations based on their purchase history and preferences, agencies and startups can build loyalty, encourage repeat business, and turn satisfied clients into enthusiastic brand advocates.<\\/p> <br \\/> <h4>Benefit 6: Scalable Platform Built for Growth<\\/h4> <p>Whether you\\u2019re a small startup just starting out or an agency managing multiple clients, OvoWpp scales with your business needs. Its intuitive interface and robust integrations mean you can onboard new team members quickly, manage multiple WhatsApp accounts, and adapt messaging flows as your strategies evolve \\u2014 all without needing complex technical skills.<\\/p> <br \\/> <p>In summary, OvoWpp transforms WhatsApp from a simple messaging app into a full-fledged sales, marketing, and support powerhouse tailored for agencies and startups. By combining centralized communication, smart automation, seamless sales tools, and customer retention strategies within one platform, OvoWpp enables businesses to save time, reduce costs, and accelerate growth \\u2014 all while delivering a superior customer experience. If you\\u2019re ready to take your agency or startup to the next level, OvoWpp offers the tools and flexibility to make it happen with WhatsApp at the core of your customer engagement strategy.<\\/p>\",\"image\":\"683c288a70a551748773002.png\"}', NULL, 'basic', 'top-benefits-of-using-ovowpp-for-agencies-and-startups', '2025-06-01 09:45:39', '2025-06-01 10:33:59'),
(144, 'breadcrumb.content', '{\"has_image\":\"1\",\"background_image\":\"683c2ef4e2f5e1748774644.png\"}', NULL, 'basic', '', '2025-06-01 10:41:24', '2025-06-01 10:44:05');

-- --------------------------------------------------------

--
-- Table structure for table `gateways`
--

CREATE TABLE `gateways` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` int UNSIGNED NOT NULL DEFAULT '0',
  `code` int DEFAULT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alias` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NULL',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=>enable, 2=>disable',
  `gateway_parameters` text COLLATE utf8mb4_unicode_ci,
  `supported_currencies` text COLLATE utf8mb4_unicode_ci,
  `crypto` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: fiat currency, 1: crypto currency',
  `extra` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gateways`
--

INSERT INTO `gateways` (`id`, `form_id`, `code`, `name`, `alias`, `image`, `status`, `gateway_parameters`, `supported_currencies`, `crypto`, `extra`, `description`, `created_at`, `updated_at`) VALUES
(1, 0, 101, 'Paypal - Basic', 'Paypal', '66f93024b850f1727606820.png', 1, '{\"paypal_email\":{\"title\":\"PayPal Email\",\"global\":true,\"value\":\"sb-sie1e33346198@business.example.com\"}}', '{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-10-13 03:59:25'),
(2, 0, 102, 'PerfectMoney', 'PerfectMoney', '66f9305b163861727606875.png', 1, '{\"passphrase\":{\"title\":\"ALTERNATE PASSPHRASE\",\"global\":true,\"value\":\"h9Rz18d60KeErSFPUViTlTyUX\"},\"wallet_id\":{\"title\":\"PM Wallet\",\"global\":false,\"value\":\"\"}}', '{\"USD\":\"$\",\"EUR\":\"\\u20ac\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-10-13 05:05:22'),
(3, 0, 103, 'Stripe - Hosted', 'Stripe', '66f932d3898531727607507.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51Q6RC200ViU9uYNAreOGXIikLFE4VKrRNw92sFrDgqv1mMS7HKsrDsTOd9g6ug6mWVnhQGhAlfzwkzivhLgQJGWR00cmSSbqtf\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51Q6RC200ViU9uYNAdEkyqVKhIzbLzJci72Or96xppTkZgDkzOjRiZC6Pz6Nol5FqUraLUnu9Ug0Zt8K5TXrJ1g8H00qMlrHnMl\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-10-05 00:27:02'),
(5, 0, 105, 'PayTM', 'Paytm', '66f9305278b331727606866.png', 1, '{\"MID\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"-----------\"},\"merchant_key\":{\"title\":\"Merchant Key\",\"global\":true,\"value\":\"-----------\"},\"WEBSITE\":{\"title\":\"Paytm Website\",\"global\":true,\"value\":\"-----------\"},\"INDUSTRY_TYPE_ID\":{\"title\":\"Industry Type\",\"global\":true,\"value\":\"-----------\"},\"CHANNEL_ID\":{\"title\":\"CHANNEL ID\",\"global\":true,\"value\":\"-----------\"},\"transaction_url\":{\"title\":\"Transaction URL\",\"global\":true,\"value\":\"-----------\"},\"transaction_status_url\":{\"title\":\"Transaction STATUS URL\",\"global\":true,\"value\":\"-----------\"}}', '{\"AUD\":\"AUD\",\"ARS\":\"ARS\",\"BDT\":\"BDT\",\"BRL\":\"BRL\",\"BGN\":\"BGN\",\"CAD\":\"CAD\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"COP\":\"COP\",\"HRK\":\"HRK\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EGP\":\"EGP\",\"EUR\":\"EUR\",\"GEL\":\"GEL\",\"GHS\":\"GHS\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"KES\":\"KES\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"MAD\":\"MAD\",\"NPR\":\"NPR\",\"NZD\":\"NZD\",\"NGN\":\"NGN\",\"NOK\":\"NOK\",\"PKR\":\"PKR\",\"PEN\":\"PEN\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"RON\":\"RON\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"ZAR\":\"ZAR\",\"KRW\":\"KRW\",\"LKR\":\"LKR\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"TRY\":\"TRY\",\"UGX\":\"UGX\",\"UAH\":\"UAH\",\"AED\":\"AED\",\"GBP\":\"GBP\",\"USD\":\"USD\",\"VND\":\"VND\",\"XOF\":\"XOF\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-10-14 00:19:59'),
(6, 0, 106, 'Payeer', 'Payeer', '66f93018e4b7c1727606808.png', 1, '{\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"P1124379867\"},\"secret_key\":{\"title\":\"Secret key\",\"global\":true,\"value\":\"768336\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"RUB\":\"RUB\"}', 0, '{\"status\":{\"title\": \"Status URL\",\"value\":\"ipn.Payeer\"}}', NULL, '2019-09-14 13:14:22', '2024-10-13 03:41:46'),
(7, 0, 107, 'PayStack', 'Paystack', '66f9303d3ca031727606845.png', 1, '{\"public_key\":{\"title\":\"Public key\",\"global\":true,\"value\":\"pk_test_7a71410e62ae07cad950d94e4a3827b974937450\"},\"secret_key\":{\"title\":\"Secret key\",\"global\":true,\"value\":\"sk_test_e8cf00c8c7fc173b60f02199e2752e2f34e50494\"}}', '{\"USD\":\"USD\",\"NGN\":\"NGN\"}', 0, '{\"callback\":{\"title\": \"Callback URL\",\"value\":\"ipn.Paystack\"},\"webhook\":{\"title\": \"Webhook URL\",\"value\":\"ipn.Paystack\"}}\r\n', NULL, '2019-09-14 13:14:22', '2024-10-13 04:19:28'),
(9, 0, 109, 'Flutterwave', 'Flutterwave', '66f92fb282a3a1727606706.png', 1, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"FLWPUBK_TEST-0ee1835b2e1088d2a529356ec7dcdb30-X\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"FLWSECK_TEST-6c5417024ef775a0eabfb021d41369f8-X\"},\"encryption_key\":{\"title\":\"Encryption Key\",\"global\":true,\"value\":\"FLWSECK_TEST78b28d6fdf42\"}}', '{\"BIF\":\"BIF\",\"CAD\":\"CAD\",\"CDF\":\"CDF\",\"CVE\":\"CVE\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"GHS\":\"GHS\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"KES\":\"KES\",\"LRD\":\"LRD\",\"MWK\":\"MWK\",\"MZN\":\"MZN\",\"NGN\":\"NGN\",\"RWF\":\"RWF\",\"SLL\":\"SLL\",\"STD\":\"STD\",\"TZS\":\"TZS\",\"UGX\":\"UGX\",\"USD\":\"USD\",\"XAF\":\"XAF\",\"XOF\":\"XOF\",\"ZMK\":\"ZMK\",\"ZMW\":\"ZMW\",\"ZWD\":\"ZWD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-10-13 01:33:13'),
(10, 0, 110, 'RazorPay', 'Razorpay', '66f93067ae7661727606887.png', 1, '{\"key_id\":{\"title\":\"Key Id\",\"global\":true,\"value\":\"-------------\"},\"key_secret\":{\"title\":\"Key Secret \",\"global\":true,\"value\":\"------------\"}}', '{\"INR\":\"INR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-10-14 00:20:13'),
(12, 0, 112, 'Instamojo', 'Instamojo', '66f92fbe2ccbb1727606718.png', 1, '{\"api_key\":{\"title\":\"API KEY\",\"global\":true,\"value\":\"--------------\"},\"auth_token\":{\"title\":\"Auth Token\",\"global\":true,\"value\":\"----------------\"},\"salt\":{\"title\":\"Salt\",\"global\":true,\"value\":\"------------\"}}', '{\"INR\":\"INR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-10-14 00:19:23'),
(15, 0, 503, 'CoinPayments Crypto', 'Coinpayments', '66f92f90365d41727606672.png', 1, '{\"public_key\":{\"title\":\"Public Key\",\"global\":true,\"value\":\"222a8c8825477fbea80812a9d5d70057e4821e43198926daa075fdc08cc98cd6\"},\"private_key\":{\"title\":\"Private Key\",\"global\":true,\"value\":\"6d049b6B91a5eBe2053bb21eAa0DCb60f33790ec96B2342192804b0e9dfFf741\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"47818ed2d6962bcab1eba829e38ad0c4\"}}', '{\"BTC\":\"Bitcoin\",\"BTC.LN\":\"Bitcoin (Lightning Network)\",\"LTC\":\"Litecoin\",\"CPS\":\"CPS Coin\",\"VLX\":\"Velas\",\"APL\":\"Apollo\",\"AYA\":\"Aryacoin\",\"BAD\":\"Badcoin\",\"BCD\":\"Bitcoin Diamond\",\"BCH\":\"Bitcoin Cash\",\"BCN\":\"Bytecoin\",\"BEAM\":\"BEAM\",\"BITB\":\"Bean Cash\",\"BLK\":\"BlackCoin\",\"BSV\":\"Bitcoin SV\",\"BTAD\":\"Bitcoin Adult\",\"BTG\":\"Bitcoin Gold\",\"BTT\":\"BitTorrent\",\"CLOAK\":\"CloakCoin\",\"CLUB\":\"ClubCoin\",\"CRW\":\"Crown\",\"CRYP\":\"CrypticCoin\",\"CRYT\":\"CryTrExCoin\",\"CURE\":\"CureCoin\",\"DASH\":\"DASH\",\"DCR\":\"Decred\",\"DEV\":\"DeviantCoin\",\"DGB\":\"DigiByte\",\"DOGE\":\"Dogecoin\",\"EBST\":\"eBoost\",\"EOS\":\"EOS\",\"ETC\":\"Ether Classic\",\"ETH\":\"Ethereum\",\"ETN\":\"Electroneum\",\"EUNO\":\"EUNO\",\"EXP\":\"EXP\",\"Expanse\":\"Expanse\",\"FLASH\":\"FLASH\",\"GAME\":\"GameCredits\",\"GLC\":\"Goldcoin\",\"GRS\":\"Groestlcoin\",\"KMD\":\"Komodo\",\"LOKI\":\"LOKI\",\"LSK\":\"LSK\",\"MAID\":\"MaidSafeCoin\",\"MUE\":\"MonetaryUnit\",\"NAV\":\"NAV Coin\",\"NEO\":\"NEO\",\"NMC\":\"Namecoin\",\"NVST\":\"NVO Token\",\"NXT\":\"NXT\",\"OMNI\":\"OMNI\",\"PINK\":\"PinkCoin\",\"PIVX\":\"PIVX\",\"POT\":\"PotCoin\",\"PPC\":\"Peercoin\",\"PROC\":\"ProCurrency\",\"PURA\":\"PURA\",\"QTUM\":\"QTUM\",\"RES\":\"Resistance\",\"RVN\":\"Ravencoin\",\"RVR\":\"RevolutionVR\",\"SBD\":\"Steem Dollars\",\"SMART\":\"SmartCash\",\"SOXAX\":\"SOXAX\",\"STEEM\":\"STEEM\",\"STRAT\":\"STRAT\",\"SYS\":\"Syscoin\",\"TPAY\":\"TokenPay\",\"TRIGGERS\":\"Triggers\",\"TRX\":\" TRON\",\"UBQ\":\"Ubiq\",\"UNIT\":\"UniversalCurrency\",\"USDT\":\"Tether USD (Omni Layer)\",\"USDT.BEP20\":\"Tether USD (BSC Chain)\",\"USDT.ERC20\":\"Tether USD (ERC20)\",\"USDT.TRC20\":\"Tether USD (Tron/TRC20)\",\"VTC\":\"Vertcoin\",\"WAVES\":\"Waves\",\"XCP\":\"Counterparty\",\"XEM\":\"NEM\",\"XMR\":\"Monero\",\"XSN\":\"Stakenet\",\"XSR\":\"SucreCoin\",\"XVG\":\"VERGE\",\"XZC\":\"ZCoin\",\"ZEC\":\"ZCash\",\"ZEN\":\"Horizen\"}', 1, NULL, NULL, '2019-09-14 13:14:22', '2024-10-13 06:14:28'),
(16, 0, 504, 'CoinPayments Fiat', 'CoinpaymentsFiat', '66f92fa7d56851727606695.png', 1, '{\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"6515561\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CLP\":\"CLP\",\"CNY\":\"CNY\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"ISK\":\"ISK\",\"JPY\":\"JPY\",\"KRW\":\"KRW\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"RUB\":\"RUB\",\"SEK\":\"SEK\",\"SGD\":\"SGD\",\"THB\":\"THB\",\"TWD\":\"TWD\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-09-29 04:44:55'),
(17, 0, 505, 'Coingate', 'Coingate', '66f92f1dafc6e1727606557.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"------------------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-10-14 00:19:09'),
(18, 0, 506, 'CoinbaseCommerce', 'CoinbaseCommerce', '66f92e80485251727606400.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"------------------\"},\"secret\":{\"title\":\"Webhook Shared Secret\",\"global\":true,\"value\":\"-------------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"JPY\":\"JPY\",\"GBP\":\"GBP\",\"AUD\":\"AUD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"CNY\":\"CNY\",\"SEK\":\"SEK\",\"NZD\":\"NZD\",\"MXN\":\"MXN\",\"SGD\":\"SGD\",\"HKD\":\"HKD\",\"NOK\":\"NOK\",\"KRW\":\"KRW\",\"TRY\":\"TRY\",\"RUB\":\"RUB\",\"INR\":\"INR\",\"BRL\":\"BRL\",\"ZAR\":\"ZAR\",\"AED\":\"AED\",\"AFN\":\"AFN\",\"ALL\":\"ALL\",\"AMD\":\"AMD\",\"ANG\":\"ANG\",\"AOA\":\"AOA\",\"ARS\":\"ARS\",\"AWG\":\"AWG\",\"AZN\":\"AZN\",\"BAM\":\"BAM\",\"BBD\":\"BBD\",\"BDT\":\"BDT\",\"BGN\":\"BGN\",\"BHD\":\"BHD\",\"BIF\":\"BIF\",\"BMD\":\"BMD\",\"BND\":\"BND\",\"BOB\":\"BOB\",\"BSD\":\"BSD\",\"BTN\":\"BTN\",\"BWP\":\"BWP\",\"BYN\":\"BYN\",\"BZD\":\"BZD\",\"CDF\":\"CDF\",\"CLF\":\"CLF\",\"CLP\":\"CLP\",\"COP\":\"COP\",\"CRC\":\"CRC\",\"CUC\":\"CUC\",\"CUP\":\"CUP\",\"CVE\":\"CVE\",\"CZK\":\"CZK\",\"DJF\":\"DJF\",\"DKK\":\"DKK\",\"DOP\":\"DOP\",\"DZD\":\"DZD\",\"EGP\":\"EGP\",\"ERN\":\"ERN\",\"ETB\":\"ETB\",\"FJD\":\"FJD\",\"FKP\":\"FKP\",\"GEL\":\"GEL\",\"GGP\":\"GGP\",\"GHS\":\"GHS\",\"GIP\":\"GIP\",\"GMD\":\"GMD\",\"GNF\":\"GNF\",\"GTQ\":\"GTQ\",\"GYD\":\"GYD\",\"HNL\":\"HNL\",\"HRK\":\"HRK\",\"HTG\":\"HTG\",\"HUF\":\"HUF\",\"IDR\":\"IDR\",\"ILS\":\"ILS\",\"IMP\":\"IMP\",\"IQD\":\"IQD\",\"IRR\":\"IRR\",\"ISK\":\"ISK\",\"JEP\":\"JEP\",\"JMD\":\"JMD\",\"JOD\":\"JOD\",\"KES\":\"KES\",\"KGS\":\"KGS\",\"KHR\":\"KHR\",\"KMF\":\"KMF\",\"KPW\":\"KPW\",\"KWD\":\"KWD\",\"KYD\":\"KYD\",\"KZT\":\"KZT\",\"LAK\":\"LAK\",\"LBP\":\"LBP\",\"LKR\":\"LKR\",\"LRD\":\"LRD\",\"LSL\":\"LSL\",\"LYD\":\"LYD\",\"MAD\":\"MAD\",\"MDL\":\"MDL\",\"MGA\":\"MGA\",\"MKD\":\"MKD\",\"MMK\":\"MMK\",\"MNT\":\"MNT\",\"MOP\":\"MOP\",\"MRO\":\"MRO\",\"MUR\":\"MUR\",\"MVR\":\"MVR\",\"MWK\":\"MWK\",\"MYR\":\"MYR\",\"MZN\":\"MZN\",\"NAD\":\"NAD\",\"NGN\":\"NGN\",\"NIO\":\"NIO\",\"NPR\":\"NPR\",\"OMR\":\"OMR\",\"PAB\":\"PAB\",\"PEN\":\"PEN\",\"PGK\":\"PGK\",\"PHP\":\"PHP\",\"PKR\":\"PKR\",\"PLN\":\"PLN\",\"PYG\":\"PYG\",\"QAR\":\"QAR\",\"RON\":\"RON\",\"RSD\":\"RSD\",\"RWF\":\"RWF\",\"SAR\":\"SAR\",\"SBD\":\"SBD\",\"SCR\":\"SCR\",\"SDG\":\"SDG\",\"SHP\":\"SHP\",\"SLL\":\"SLL\",\"SOS\":\"SOS\",\"SRD\":\"SRD\",\"SSP\":\"SSP\",\"STD\":\"STD\",\"SVC\":\"SVC\",\"SYP\":\"SYP\",\"SZL\":\"SZL\",\"THB\":\"THB\",\"TJS\":\"TJS\",\"TMT\":\"TMT\",\"TND\":\"TND\",\"TOP\":\"TOP\",\"TTD\":\"TTD\",\"TWD\":\"TWD\",\"TZS\":\"TZS\",\"UAH\":\"UAH\",\"UGX\":\"UGX\",\"UYU\":\"UYU\",\"UZS\":\"UZS\",\"VEF\":\"VEF\",\"VND\":\"VND\",\"VUV\":\"VUV\",\"WST\":\"WST\",\"XAF\":\"XAF\",\"XAG\":\"XAG\",\"XAU\":\"XAU\",\"XCD\":\"XCD\",\"XDR\":\"XDR\",\"XOF\":\"XOF\",\"XPD\":\"XPD\",\"XPF\":\"XPF\",\"XPT\":\"XPT\",\"YER\":\"YER\",\"ZMW\":\"ZMW\",\"ZWL\":\"ZWL\"}\r\n\r\n', 0, '{\"endpoint\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.CoinbaseCommerce\"}}', NULL, '2019-09-14 13:14:22', '2024-10-14 00:18:55'),
(24, 0, 113, 'Paypal - Express', 'PaypalSdk', '66f954f3b28261727616243.png', 1, '{\"clientId\":{\"title\":\"Paypal Client ID\",\"global\":true,\"value\":\"AYq9c_gjnfFiLpWdotm-5XTw-RwtWtBrxIEW7IJGcjmq6cLDcTOjSSVlIqnIq4dYWnxrOEeK7s0UuuCy\"},\"clientSecret\":{\"title\":\"Client Secret\",\"global\":true,\"value\":\"ECXn_0gIPEdgVDiPfh-zR3KFm5WfmZe5UvhDrKNNa59i5bTSZ3K4S9QFb9uJNZ-vjBGEwcdKD0SRQsP5\"}}', '{\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CZK\":\"CZK\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"HKD\":\"HKD\",\"HUF\":\"HUF\",\"INR\":\"INR\",\"ILS\":\"ILS\",\"JPY\":\"JPY\",\"MYR\":\"MYR\",\"MXN\":\"MXN\",\"TWD\":\"TWD\",\"NZD\":\"NZD\",\"NOK\":\"NOK\",\"PHP\":\"PHP\",\"PLN\":\"PLN\",\"GBP\":\"GBP\",\"RUB\":\"RUB\",\"SGD\":\"SGD\",\"SEK\":\"SEK\",\"CHF\":\"CHF\",\"THB\":\"THB\",\"USD\":\"$\"}', 0, NULL, NULL, '2019-09-14 13:14:22', '2024-10-13 03:59:51'),
(25, 0, 114, 'Stripe - Checkout', 'StripeV3', '66f930941abc51727606932.png', 1, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"sk_test_51Q6RC200ViU9uYNAreOGXIikLFE4VKrRNw92sFrDgqv1mMS7HKsrDsTOd9g6ug6mWVnhQGhAlfzwkzivhLgQJGWR00cmSSbqtf\"},\"publishable_key\":{\"title\":\"PUBLISHABLE KEY\",\"global\":true,\"value\":\"pk_test_51Q6RC200ViU9uYNAdEkyqVKhIzbLzJci72Or96xppTkZgDkzOjRiZC6Pz6Nol5FqUraLUnu9Ug0Zt8K5TXrJ1g8H00qMlrHnMl\"},\"end_point\":{\"title\":\"End Point Secret\",\"global\":true,\"value\":\"whsec_VnTLcUcx5bMenhc6P0PZiTR0T6NGs5yF\"}}', '{\"USD\":\"USD\",\"AUD\":\"AUD\",\"BRL\":\"BRL\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"INR\":\"INR\",\"JPY\":\"JPY\",\"MXN\":\"MXN\",\"MYR\":\"MYR\",\"NOK\":\"NOK\",\"NZD\":\"NZD\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"SGD\":\"SGD\"}', 0, '{\"webhook\":{\"title\": \"Webhook Endpoint\",\"value\":\"ipn.StripeV3\"}}', NULL, '2019-09-14 13:14:22', '2024-10-05 00:25:34'),
(36, 0, 119, 'MercadoPago', 'MercadoPago', '66f92fcac0e111727606730.png', 1, '{\"access_token\":{\"title\":\"Access Token\",\"global\":true,\"value\":\"--------------\"}}', '{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\",\"BRL\":\"BRL\"}', 0, NULL, NULL, NULL, '2024-10-14 00:19:38'),
(37, 0, 120, 'Authorize.net', 'Authorize', '66f92de1ce5151727606241.png', 1, '{\"login_id\":{\"title\":\"Login ID\",\"global\":true,\"value\":\"59e4P9DBcZv\"},\"transaction_key\":{\"title\":\"Transaction Key\",\"global\":true,\"value\":\"47x47TJyLw2E7DbR\"}}', '{\"USD\":\"USD\",\"CAD\":\"CAD\",\"CHF\":\"CHF\",\"DKK\":\"DKK\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"NOK\":\"NOK\",\"PLN\":\"PLN\",\"SEK\":\"SEK\",\"AUD\":\"AUD\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2024-10-03 03:12:33'),
(50, 0, 507, 'BTCPay', 'BTCPay', '66f92dab2d0c81727606187.png', 1, '{\"store_id\":{\"title\":\"Store Id\",\"global\":true,\"value\":\"GLeYKqo2vM1jW9e2aFpGsLqokwTbfpQ3yZFQBRy2um58\"},\"api_key\":{\"title\":\"Api Key\",\"global\":true,\"value\":\"a60a2d61645cddd1f552212ca0f802121e47d08c\"},\"server_name\":{\"title\":\"Server Name\",\"global\":true,\"value\":\"https:\\/\\/testnet.demo.btcpayserver.org\"},\"secret_code\":{\"title\":\"Secret Code\",\"global\":true,\"value\":\"SUCdqPn9CDkY7RmJHfpQVHP2Lf2\"}}', '{\"BTC\":\"Bitcoin\",\"LTC\":\"Litecoin\"}', 1, '{\"webhook\":{\"title\": \"IPN URL\",\"value\":\"ipn.BTCPay\"}}', NULL, NULL, '2024-10-14 03:40:52'),
(51, 0, 508, 'NowPayments - Hosted', 'NowPaymentsHosted', '66f92ffed509e1727606782.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"MAFWEB2-X6146ZP-KJTB98H-QV2WW46\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"yr2n6OSV5tvb9h0YdXy+2Fmihp4LwSnq\"}}', '{\"BTG\":\"BTG\",\"ETH\":\"ETH\",\"XMR\":\"XMR\",\"ZEC\":\"ZEC\",\"XVG\":\"XVG\",\"ADA\":\"ADA\",\"LTC\":\"LTC\",\"BCH\":\"BCH\",\"QTUM\":\"QTUM\",\"DASH\":\"DASH\",\"XLM\":\"XLM\",\"XRP\":\"XRP\",\"XEM\":\"XEM\",\"DGB\":\"DGB\",\"LSK\":\"LSK\",\"DOGE\":\"DOGE\",\"TRX\":\"TRX\",\"KMD\":\"KMD\",\"REP\":\"REP\",\"BAT\":\"BAT\",\"ARK\":\"ARK\",\"WAVES\":\"WAVES\",\"BNB\":\"BNB\",\"XZC\":\"XZC\",\"NANO\":\"NANO\",\"TUSD\":\"TUSD\",\"VET\":\"VET\",\"ZEN\":\"ZEN\",\"GRS\":\"GRS\",\"FUN\":\"FUN\",\"NEO\":\"NEO\",\"GAS\":\"GAS\",\"PAX\":\"PAX\",\"USDC\":\"USDC\",\"ONT\":\"ONT\",\"XTZ\":\"XTZ\",\"LINK\":\"LINK\",\"RVN\":\"RVN\",\"BNBMAINNET\":\"BNBMAINNET\",\"ZIL\":\"ZIL\",\"BCD\":\"BCD\",\"USDT\":\"USDT\",\"USDTERC20\":\"USDTERC20\",\"CRO\":\"CRO\",\"DAI\":\"DAI\",\"HT\":\"HT\",\"WABI\":\"WABI\",\"BUSD\":\"BUSD\",\"ALGO\":\"ALGO\",\"USDTTRC20\":\"USDTTRC20\",\"GT\":\"GT\",\"STPT\":\"STPT\",\"AVA\":\"AVA\",\"SXP\":\"SXP\",\"UNI\":\"UNI\",\"OKB\":\"OKB\",\"BTC\":\"BTC\"}', 1, '', NULL, NULL, '2024-10-13 02:56:13'),
(52, 0, 509, 'NowPayments - Checkout', 'NowPaymentsCheckout', '66f92ff5897b41727606773.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"MAFWEB2-X6146ZP-KJTB98H-QV2WW46\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"yr2n6OSV5tvb9h0YdXy+2Fmihp4LwSnq\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\"}', 1, '', NULL, NULL, '2024-10-13 02:47:13'),
(53, 0, 122, '2Checkout', 'TwoCheckout', '66f93484853cf1727607940.png', 1, '{\"merchant_code\":{\"title\":\"Merchant Code\",\"global\":true,\"value\":\"255237318607\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"tNbET^O0mlJ4QHdAf6W#\"}}', '{\"AFN\": \"AFN\",\"ALL\": \"ALL\",\"DZD\": \"DZD\",\"ARS\": \"ARS\",\"AUD\": \"AUD\",\"AZN\": \"AZN\",\"BSD\": \"BSD\",\"BDT\": \"BDT\",\"BBD\": \"BBD\",\"BZD\": \"BZD\",\"BMD\": \"BMD\",\"BOB\": \"BOB\",\"BWP\": \"BWP\",\"BRL\": \"BRL\",\"GBP\": \"GBP\",\"BND\": \"BND\",\"BGN\": \"BGN\",\"CAD\": \"CAD\",\"CLP\": \"CLP\",\"CNY\": \"CNY\",\"COP\": \"COP\",\"CRC\": \"CRC\",\"HRK\": \"HRK\",\"CZK\": \"CZK\",\"DKK\": \"DKK\",\"DOP\": \"DOP\",\"XCD\": \"XCD\",\"EGP\": \"EGP\",\"EUR\": \"EUR\",\"FJD\": \"FJD\",\"GTQ\": \"GTQ\",\"HKD\": \"HKD\",\"HNL\": \"HNL\",\"HUF\": \"HUF\",\"INR\": \"INR\",\"IDR\": \"IDR\",\"ILS\": \"ILS\",\"JMD\": \"JMD\",\"JPY\": \"JPY\",\"KZT\": \"KZT\",\"KES\": \"KES\",\"LAK\": \"LAK\",\"MMK\": \"MMK\",\"LBP\": \"LBP\",\"LRD\": \"LRD\",\"MOP\": \"MOP\",\"MYR\": \"MYR\",\"MVR\": \"MVR\",\"MRO\": \"MRO\",\"MUR\": \"MUR\",\"MXN\": \"MXN\",\"MAD\": \"MAD\",\"NPR\": \"NPR\",\"TWD\": \"TWD\",\"NZD\": \"NZD\",\"NIO\": \"NIO\",\"NOK\": \"NOK\",\"PKR\": \"PKR\",\"PGK\": \"PGK\",\"PEN\": \"PEN\",\"PHP\": \"PHP\",\"PLN\": \"PLN\",\"QAR\": \"QAR\",\"RON\": \"RON\",\"RUB\": \"RUB\",\"WST\": \"WST\",\"SAR\": \"SAR\",\"SCR\": \"SCR\",\"SGD\": \"SGD\",\"SBD\": \"SBD\",\"ZAR\": \"ZAR\",\"KRW\": \"KRW\",\"LKR\": \"LKR\",\"SEK\": \"SEK\",\"CHF\": \"CHF\",\"SYP\": \"SYP\",\"THB\": \"THB\",\"TOP\": \"TOP\",\"TTD\": \"TTD\",\"TRY\": \"TRY\",\"UAH\": \"UAH\",\"AED\": \"AED\",\"USD\": \"USD\",\"VUV\": \"VUV\",\"VND\": \"VND\",\"XOF\": \"XOF\",\"YER\": \"YER\"}', 0, '{\"approved_url\":{\"title\": \"Approved URL\",\"value\":\"ipn.TwoCheckout\"}}', NULL, NULL, '2024-10-13 05:40:07'),
(54, 0, 123, 'Checkout', 'Checkout', '66f92e6bd08e01727606379.png', 0, '{\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"------\"},\"public_key\":{\"title\":\"PUBLIC KEY\",\"global\":true,\"value\":\"------\"},\"processing_channel_id\":{\"title\":\"PROCESSING CHANNEL\",\"global\":true,\"value\":\"------\"}}', '{\"USD\":\"USD\",\"EUR\":\"EUR\",\"GBP\":\"GBP\",\"HKD\":\"HKD\",\"AUD\":\"AUD\",\"CAN\":\"CAN\",\"CHF\":\"CHF\",\"SGD\":\"SGD\",\"JPY\":\"JPY\",\"NZD\":\"NZD\"}', 0, NULL, NULL, NULL, '2024-09-30 01:55:03'),
(55, 19, 1000, 'Bank Transfer', 'bank_transfer', '66f95525bfa571727616293.png', 1, '[]', '[]', 0, NULL, '<div style=\"border-left: 3px solid #b5b0b0;\r\n    padding: 12px;\r\n    font-style: italic;\r\n    margin: 30px 0px;\r\n    background: #f9f9f9;\r\n    border-radius: 3px;\"><p style=\"\r\n    margin-bottom: 10px;\r\n    font-weight: bold;\r\n    font-size: 17px;\r\n\">Please send the funds to the information provided below. We cannot be held responsible for any errors if the amount is sent to incorrect details. Kindly complete the form after transferring the funds<br><br>Bank information</p><p style=\"\r\n    margin-bottom: 0;\r\n\">\r\n</p><p style=\"\r\nmargin-bottom: 0;\r\n\"><span style=\"font-weight:500\">Bank Name:</span>&nbsp;Demo Bank</p>\r\n<p style=\"\r\nmargin-bottom: 0;\r\n\"><span style=\"font-weight:500\">Branch:</span>&nbsp;Demo Branch</p>\r\n<p style=\"\r\nmargin-bottom: 0;\r\n\"><span style=\"font-weight:500\">Routing:</span> 1234</p>\r\n<p style=\"\r\n    margin-bottom: 0;\r\n\"><span style=\"font-weight:500\">Account Number:</span> xxx-xxx-<span style=\"color: rgb(67, 64, 79); font-weight: var(--bs-body-font-weight); text-align: var(--bs-body-text-align); display: inline !important;\">xxx-xxx-xxx</span></p></div>', '2024-03-13 23:11:21', '2024-10-05 03:08:54'),
(56, 0, 510, 'Binance', 'Binance', '66f92d4ae66161727606090.png', 1, '{\"api_key\":{\"title\":\"API Key\",\"global\":true,\"value\":\"--------------------\"},\"secret_key\":{\"title\":\"Secret Key\",\"global\":true,\"value\":\"--------------------\"},\"merchant_id\":{\"title\":\"Merchant ID\",\"global\":true,\"value\":\"-------------\"}}', '{\"BTC\":\"Bitcoin\",\"USD\":\"USD\",\"BNB\":\"BNB\"}', 1, '{\"cron\":{\"title\": \"Cron Job URL\",\"value\":\"ipn.Binance\"}}', NULL, NULL, '2024-10-14 00:18:37'),
(57, 0, 124, 'SslCommerz', 'SslCommerz', '66f93471b7b9b1727607921.png', 1, '{\"store_id\":{\"title\":\"Store ID\",\"global\":true,\"value\":\"---------\"},\"store_password\":{\"title\":\"Store Password\",\"global\":true,\"value\":\"----------\"}}', '{\"BDT\":\"BDT\",\"USD\":\"USD\",\"EUR\":\"EUR\",\"SGD\":\"SGD\",\"INR\":\"INR\",\"MYR\":\"MYR\"}', 0, NULL, NULL, NULL, '2024-09-29 05:05:21'),
(58, 0, 125, 'Aamarpay', 'Aamarpay', '66f933390c5201727607609.png', 0, '{\"store_id\":{\"title\":\"Store ID\",\"global\":true,\"value\":\"---------\"},\"signature_key\":{\"title\":\"Signature Key\",\"global\":true,\"value\":\"----------\"}}', '{\"BDT\":\"BDT\"}', 0, NULL, NULL, NULL, '2024-10-14 06:00:24'),
(60, 22, 1001, 'Mobile Money Transfer', 'mobile_money_transfer', '670e115c4d5251728975196.png', 1, '[]', '[]', 0, NULL, '<p style=\"margin-bottom: 10px; font-size: 17px; font-weight: bold; font-style: italic;\">Please send the funds to the information provided below. We cannot be held responsible for any errors if the amount is sent to incorrect details. Kindly complete the form after transferring the funds<br><br>Bank information</p><p style=\"font-style: italic;\"></p><p style=\"font-style: italic;\"><span style=\"color: hsl(var(--body-color)); font-size: 0.875rem; background-color: hsl(var(--white)); text-align: var(--bs-body-text-align); display: inline !important;\">Mobile Number:&nbsp;xxx-xxx-</span><span style=\"font-size: 0.875rem; font-weight: var(--bs-body-font-weight); background-color: hsl(var(--white)); text-align: var(--bs-body-text-align); color: rgb(67, 64, 79); display: inline !important;\">xxx-xxx-xxx</span><br></p>', '2024-09-25 08:22:40', '2024-10-15 00:53:33');

-- --------------------------------------------------------

--
-- Table structure for table `gateway_currencies`
--

CREATE TABLE `gateway_currencies` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `symbol` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `method_code` int DEFAULT NULL,
  `gateway_alias` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `max_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) NOT NULL DEFAULT '0.00',
  `fixed_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `gateway_parameter` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gateway_currencies`
--

INSERT INTO `gateway_currencies` (`id`, `name`, `currency`, `symbol`, `method_code`, `gateway_alias`, `min_amount`, `max_amount`, `percent_charge`, `fixed_charge`, `rate`, `gateway_parameter`, `created_at`, `updated_at`) VALUES
(147, 'Bank Wire', 'USD', '', 1001, 'bank_wire', 10.00000000, 100000.00000000, 1.00, 5.00000000, 1.00000000, NULL, '2022-03-30 09:16:43', '2022-07-26 05:57:22'),
(202, 'Bank Transfer', 'USD', '', 1000, 'bank_transfer', 10.00000000, 1000.00000000, 1.00, 1.00000000, 1.00000000, NULL, '2024-03-13 23:11:21', '2025-04-10 11:04:12'),
(269, 'BTCPay - BTC', 'BTC', '₿', 507, 'BTCPay', 1.00000000, 100.00000000, 1.00, 1.00000000, 1.00000000, '{\"store_id\":\"GLeYKqo2vM1jW9e2aFpGsLqokwTbfpQ3yZFQBRy2um58\",\"api_key\":\"a60a2d61645cddd1f552212ca0f802121e47d08c\",\"server_name\":\"https:\\/\\/testnet.demo.btcpayserver.org\",\"secret_code\":\"SUCdqPn9CDkY7RmJHfpQVHP2Lf2\"}', '2024-05-07 08:08:13', '2024-10-12 02:37:53'),
(273, 'Checkout - USD', 'USD', '$', 123, 'Checkout', 1.00000000, 100.00000000, 1.00, 1.00000000, 1.00000000, '{\"secret_key\":\"------\",\"public_key\":\"------\",\"processing_channel_id\":\"------\"}', '2024-05-07 08:09:44', '2024-09-29 04:39:39'),
(276, 'Coingate - USD', 'USD', '$', 505, 'Coingate', 1.00000000, 100.00000000, 1.00, 1.00000000, 1.00000000, '{\"api_key\":\"------------------\"}', '2024-05-07 08:11:37', '2024-10-14 00:19:09'),
(280, 'CoinPayments Fiat - USD', 'USD', '$', 504, 'CoinpaymentsFiat', 1.00000000, 10000.00000000, 10.00, 1.00000000, 10.00000000, '{\"merchant_id\":\"6515561\"}', '2024-05-07 08:12:07', '2024-09-29 04:44:55'),
(281, 'CoinPayments Fiat - AUD', 'AUD', '$', 504, 'CoinpaymentsFiat', 1.00000000, 10000.00000000, 0.00, 1.00000000, 1.00000000, '{\"merchant_id\":\"6515561\"}', '2024-05-07 08:12:07', '2024-09-29 04:44:55'),
(282, 'Flutterwave - USD', 'USD', 'USD', 109, 'Flutterwave', 1.00000000, 2000.00000000, 0.00, 1.00000000, 1.00000000, '{\"public_key\":\"FLWPUBK_TEST-0ee1835b2e1088d2a529356ec7dcdb30-X\",\"secret_key\":\"FLWSECK_TEST-6c5417024ef775a0eabfb021d41369f8-X\",\"encryption_key\":\"FLWSECK_TEST78b28d6fdf42\"}', '2024-05-07 08:12:18', '2024-10-13 01:33:13'),
(284, 'Mercado Pago - USD', 'USD', '$', 119, 'MercadoPago', 1.00000000, 10.00000000, 1.00, 1.00000000, 1.00000000, '{\"access_token\":\"--------------\"}', '2024-05-07 08:19:24', '2024-10-14 00:19:38'),
(287, 'Now payments checkout - USD', 'USD', '$', 509, 'NowPaymentsCheckout', 1.00000000, 100.00000000, 1.00, 1.00000000, 1.00000000, '{\"api_key\":\"MAFWEB2-X6146ZP-KJTB98H-QV2WW46\",\"secret_key\":\"yr2n6OSV5tvb9h0YdXy+2Fmihp4LwSnq\"}', '2024-05-07 08:20:21', '2024-10-13 02:47:13'),
(288, 'Payeer - USD', 'USD', '$', 106, 'Payeer', 1.00000000, 10000.00000000, 1.00, 1.00000000, 1.00000000, '{\"merchant_id\":\"P1124379867\",\"secret_key\":\"768336\"}', '2024-05-07 08:20:58', '2024-10-13 03:41:46'),
(289, 'Paypal - USD', 'USD', '$', 101, 'Paypal', 1.00000000, 10000.00000000, 1.00, 1.00000000, 1.00000000, '{\"paypal_email\":\"sb-sie1e33346198@business.example.com\"}', '2024-05-07 08:21:11', '2024-10-13 03:59:25'),
(290, 'Paypal Express - USD', 'USD', '$', 113, 'PaypalSdk', 1.00000000, 1000000.00000000, 1.00, 1.00000000, 1.00000000, '{\"clientId\":\"AYq9c_gjnfFiLpWdotm-5XTw-RwtWtBrxIEW7IJGcjmq6cLDcTOjSSVlIqnIq4dYWnxrOEeK7s0UuuCy\",\"clientSecret\":\"ECXn_0gIPEdgVDiPfh-zR3KFm5WfmZe5UvhDrKNNa59i5bTSZ3K4S9QFb9uJNZ-vjBGEwcdKD0SRQsP5\"}', '2024-05-07 08:21:33', '2024-10-13 03:59:51'),
(292, 'PayTM - AUD', 'AUD', '$', 105, 'Paytm', 1.00000000, 10000.00000000, 1.00, 1.00000000, 1.00000000, '{\"MID\":\"-----------\",\"merchant_key\":\"-----------\",\"WEBSITE\":\"-----------\",\"INDUSTRY_TYPE_ID\":\"-----------\",\"CHANNEL_ID\":\"-----------\",\"transaction_url\":\"-----------\",\"transaction_status_url\":\"-----------\"}', '2024-05-07 08:22:07', '2024-10-14 00:19:59'),
(293, 'PayTM - USD', 'USD', '$', 105, 'Paytm', 1.00000000, 10000.00000000, 1.00, 1.00000000, 2.00000000, '{\"MID\":\"-----------\",\"merchant_key\":\"-----------\",\"WEBSITE\":\"-----------\",\"INDUSTRY_TYPE_ID\":\"-----------\",\"CHANNEL_ID\":\"-----------\",\"transaction_url\":\"-----------\",\"transaction_status_url\":\"-----------\"}', '2024-05-07 08:22:07', '2024-10-14 00:19:59'),
(294, 'Perfect Money - USD', 'USD', 'usd', 102, 'PerfectMoney', 1.00000000, 10000.00000000, 1.00, 1.00000000, 1.00000000, '{\"passphrase\":\"h9Rz18d60KeErSFPUViTlTyUX\",\"wallet_id\":\"100\"}', '2024-05-07 08:22:25', '2024-10-13 05:05:23'),
(295, 'RazorPay - INR', 'INR', '$', 110, 'Razorpay', 1.00000000, 10000.00000000, 1.00, 1.00000000, 1.00000000, '{\"key_id\":\"-------------\",\"key_secret\":\"------------\"}', '2024-05-07 08:22:50', '2024-10-14 00:21:41'),
(299, 'Stripe Hosted - USD', 'USD', '$', 103, 'Stripe', 1.00000000, 10000.00000000, 1.00, 1.00000000, 1.00000000, '{\"secret_key\":\"sk_test_51Q6RC200ViU9uYNAreOGXIikLFE4VKrRNw92sFrDgqv1mMS7HKsrDsTOd9g6ug6mWVnhQGhAlfzwkzivhLgQJGWR00cmSSbqtf\",\"publishable_key\":\"pk_test_51Q6RC200ViU9uYNAdEkyqVKhIzbLzJci72Or96xppTkZgDkzOjRiZC6Pz6Nol5FqUraLUnu9Ug0Zt8K5TXrJ1g8H00qMlrHnMl\"}', '2024-05-07 08:24:06', '2024-10-09 06:06:36'),
(301, 'Stripe Checkout - USD', 'USD', 'USD', 114, 'StripeV3', 10.00000000, 1000.00000000, 0.00, 1.00000000, 1.00000000, '{\"secret_key\":\"sk_test_51Q6RC200ViU9uYNAreOGXIikLFE4VKrRNw92sFrDgqv1mMS7HKsrDsTOd9g6ug6mWVnhQGhAlfzwkzivhLgQJGWR00cmSSbqtf\",\"publishable_key\":\"pk_test_51Q6RC200ViU9uYNAdEkyqVKhIzbLzJci72Or96xppTkZgDkzOjRiZC6Pz6Nol5FqUraLUnu9Ug0Zt8K5TXrJ1g8H00qMlrHnMl\",\"end_point\":\"whsec_VnTLcUcx5bMenhc6P0PZiTR0T6NGs5yF\"}', '2024-05-07 08:24:47', '2024-10-05 00:25:34'),
(302, '2Checkout - USD', 'USD', '$', 122, 'TwoCheckout', 1.00000000, 10000.00000000, 1.00, 1.00000000, 1.00000000, '{\"merchant_code\":\"255237318607\",\"secret_key\":\"tNbET^O0mlJ4QHdAf6W#\"}', '2024-05-07 08:24:57', '2024-10-13 05:40:07'),
(304, 'SslCommerz - BDT', 'BDT', '৳', 124, 'SslCommerz', 1.00000000, 100.00000000, 1.00, 1.00000000, 115.00000000, '{\"store_id\":\"---------\",\"store_password\":\"----------\"}', '2024-05-08 07:34:12', '2024-09-29 05:05:21'),
(309, 'CoinPayments - BTC', 'BTC', '₿', 503, 'Coinpayments', 1.00000000, 10000.00000000, 10.00, 1.00000000, 1.00000000, '{\"public_key\":\"222a8c8825477fbea80812a9d5d70057e4821e43198926daa075fdc08cc98cd6\",\"private_key\":\"6d049b6B91a5eBe2053bb21eAa0DCb60f33790ec96B2342192804b0e9dfFf741\",\"merchant_id\":\"47818ed2d6962bcab1eba829e38ad0c4\"}', '2024-05-08 07:35:24', '2024-10-13 06:14:28'),
(312, 'Binance - BTC', 'BTC', '₿', 510, 'Binance', 1.00000000, 100.00000000, 1.00, 1.00000000, 1.00000000, '{\"api_key\":\"--------------------\",\"secret_key\":\"--------------------\",\"merchant_id\":\"-------------\"}', '2024-05-08 07:36:01', '2024-10-14 00:18:37'),
(314, 'Coinbase Commerce - USD', 'USD', '$', 506, 'CoinbaseCommerce', 1.00000000, 10000.00000000, 10.00, 1.00000000, 1.00000000, '{\"api_key\":\"------------------\",\"secret\":\"-------------\"}', '2024-05-08 07:41:51', '2024-10-14 00:18:55'),
(315, 'Instamojo - INR', 'INR', '₹', 112, 'Instamojo', 1.00000000, 10000.00000000, 1.00, 1.00000000, 85.00000000, '{\"api_key\":\"--------------\",\"auth_token\":\"----------------\",\"salt\":\"------------\"}', '2024-05-08 07:42:57', '2024-10-14 00:19:23'),
(316, 'Now payments hosted - BTC', 'BTC', '₿', 508, 'NowPaymentsHosted', 1.00000000, 1000.00000000, 1.00, 1.00000000, 1.00000000, '{\"api_key\":\"MAFWEB2-X6146ZP-KJTB98H-QV2WW46\",\"secret_key\":\"yr2n6OSV5tvb9h0YdXy+2Fmihp4LwSnq\"}', '2024-05-08 07:43:55', '2024-10-13 02:56:13'),
(318, 'PayStack - NGN', 'NGN', '₦', 107, 'Paystack', 1.00000000, 10000.00000000, 1.00, 1.00000000, 1420.00000000, '{\"public_key\":\"pk_test_7a71410e62ae07cad950d94e4a3827b974937450\",\"secret_key\":\"sk_test_e8cf00c8c7fc173b60f02199e2752e2f34e50494\"}', '2024-05-08 07:44:50', '2025-04-10 12:26:55'),
(327, 'Authorize.net - USD', 'USD', '$', 120, 'Authorize', 1.00000000, 10.00000000, 1.00, 1.00000000, 1.00000000, '{\"login_id\":\"59e4P9DBcZv\",\"transaction_key\":\"47x47TJyLw2E7DbR\"}', '2024-09-25 04:57:07', '2024-09-29 04:37:21'),
(330, 'Perfect Money - EUR', 'EUR', '$', 102, 'PerfectMoney', 1.00000000, 100.00000000, 1.00, 1.00000000, 1.00000000, '{\"passphrase\":\"h9Rz18d60KeErSFPUViTlTyUX\",\"wallet_id\":\"200\"}', '2024-09-25 07:23:23', '2024-10-13 05:05:23'),
(331, 'Mobile Money Transfer', 'USD', '', 1001, 'mobile_money_transfer', 10.00000000, 10000.00000000, 2.00, 2.00000000, 1.00000000, NULL, '2024-09-25 08:22:40', '2025-03-19 11:25:28'),
(333, 'Binance - BNB', 'BNB', 'BNB', 510, 'Binance', 1.00000000, 100.00000000, 1.00, 1.00000000, 0.00170000, '{\"api_key\":\"--------------------\",\"secret_key\":\"--------------------\",\"merchant_id\":\"-------------\"}', '2024-10-09 06:01:52', '2024-10-14 00:18:37'),
(334, 'Stripe Hosted - JPY', 'JPY', 'JPY', 103, 'Stripe', 1.00000000, 1000000.00000000, 1.00, 1.00000000, 148.71000000, '{\"secret_key\":\"sk_test_51Q6RC200ViU9uYNAreOGXIikLFE4VKrRNw92sFrDgqv1mMS7HKsrDsTOd9g6ug6mWVnhQGhAlfzwkzivhLgQJGWR00cmSSbqtf\",\"publishable_key\":\"pk_test_51Q6RC200ViU9uYNAdEkyqVKhIzbLzJci72Or96xppTkZgDkzOjRiZC6Pz6Nol5FqUraLUnu9Ug0Zt8K5TXrJ1g8H00qMlrHnMl\"}', '2024-10-09 06:06:36', '2024-10-09 06:06:36'),
(336, 'PayStack - USD', 'USD', '$', 107, 'Paystack', 10.00000000, 1000.00000000, 2.00, 10.00000000, 500.00000000, '{\"public_key\":\"pk_test_7a71410e62ae07cad950d94e4a3827b974937450\",\"secret_key\":\"sk_test_e8cf00c8c7fc173b60f02199e2752e2f34e50494\"}', '2025-04-10 12:26:55', '2025-04-10 12:26:55');

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `site_name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cur_text` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'currency text',
  `cur_sym` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'currency symbol',
  `email_from` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_from_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_template` text COLLATE utf8mb4_unicode_ci,
  `sms_template` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral_amount_percentage` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '% of pricing plan price',
  `subscription_notify_before` int NOT NULL DEFAULT '7' COMMENT 'How many days before the notification will send to user for subscription',
  `webhook_verify_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_template` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_color` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail_config` text COLLATE utf8mb4_unicode_ci COMMENT 'email configuration',
  `sms_config` text COLLATE utf8mb4_unicode_ci,
  `firebase_config` text COLLATE utf8mb4_unicode_ci,
  `global_shortcodes` text COLLATE utf8mb4_unicode_ci,
  `kv` tinyint(1) NOT NULL DEFAULT '0',
  `ev` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'email verification, 0 - dont check, 1 - check',
  `en` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'email notification, 0 - dont send, 1 - send',
  `sv` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'mobile verication, 0 - dont check, 1 - check',
  `sn` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'sms notification, 0 - dont send, 1 - send',
  `pn` tinyint(1) NOT NULL DEFAULT '1',
  `force_ssl` tinyint(1) NOT NULL DEFAULT '0',
  `in_app_payment` tinyint(1) NOT NULL DEFAULT '1',
  `maintenance_mode` tinyint(1) NOT NULL DEFAULT '0',
  `secure_password` tinyint(1) NOT NULL DEFAULT '0',
  `agree` tinyint(1) NOT NULL DEFAULT '0',
  `multi_language` tinyint(1) NOT NULL DEFAULT '1',
  `registration` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Off	, 1: On',
  `active_template` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `socialite_credentials` text COLLATE utf8mb4_unicode_ci,
  `last_cron` datetime DEFAULT NULL,
  `available_version` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `system_customized` tinyint(1) NOT NULL DEFAULT '0',
  `paginate_number` int NOT NULL DEFAULT '0',
  `currency_format` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>Both\r\n2=>Text Only\r\n3=>Symbol Only',
  `time_format` text COLLATE utf8mb4_unicode_ci,
  `date_format` text COLLATE utf8mb4_unicode_ci,
  `allow_precision` int NOT NULL DEFAULT '2',
  `thousand_separator` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preloader_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pusher_config` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `site_name`, `cur_text`, `cur_sym`, `email_from`, `email_from_name`, `email_template`, `sms_template`, `referral_amount_percentage`, `subscription_notify_before`, `webhook_verify_token`, `sms_from`, `push_title`, `push_template`, `base_color`, `mail_config`, `sms_config`, `firebase_config`, `global_shortcodes`, `kv`, `ev`, `en`, `sv`, `sn`, `pn`, `force_ssl`, `in_app_payment`, `maintenance_mode`, `secure_password`, `agree`, `multi_language`, `registration`, `active_template`, `socialite_credentials`, `last_cron`, `available_version`, `system_customized`, `paginate_number`, `currency_format`, `time_format`, `date_format`, `allow_precision`, `thousand_separator`, `preloader_image`, `pusher_config`, `created_at`, `updated_at`) VALUES
(1, 'OvoWpp', 'USD', '$', 'info@ovosolution.com', '{{site_name}}', '<!DOCTYPE html>\r\n<html lang=\"en\">\r\n\r\n<head>\r\n    <meta charset=\"UTF-8\">\r\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\r\n    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\r\n    <title>Email Notification</title>\r\n    <style>\r\n        /* General Styles */\r\n        body {\r\n            margin: 0;\r\n            padding: 0;\r\n            font-family: \'Open Sans\', Arial, sans-serif;\r\n            background-color: #f4f4f4;\r\n            -webkit-text-size-adjust: 100%;\r\n            -ms-text-size-adjust: 100%;\r\n        }\r\n\r\n        table {\r\n            border-spacing: 0;\r\n            border-collapse: collapse;\r\n            width: 100%;\r\n        }\r\n\r\n        img {\r\n            display: block;\r\n            border: 0;\r\n            line-height: 0;\r\n        }\r\n\r\n        a {\r\n            color: #ff600036;\r\n            text-decoration: none;\r\n        }\r\n\r\n        .email-wrapper {\r\n            width: 100%;\r\n            background-color: #f4f4f4;\r\n            padding: 30px 0;\r\n        }\r\n\r\n        .email-container {\r\n            width: 100%;\r\n            max-width: 600px;\r\n            margin: 0 auto;\r\n            background-color: #ffffff;\r\n            border-radius: 8px;\r\n            overflow: hidden;\r\n            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);\r\n        }\r\n\r\n        /* Header */\r\n        .email-header {\r\n            background-color: #ff600036;\r\n            color: #000;\r\n            text-align: center;\r\n            padding: 20px;\r\n            font-size: 16px;\r\n            font-weight: 600;\r\n        }\r\n\r\n        /* Logo */\r\n        .email-logo {\r\n            text-align: center;\r\n            padding: 40px 0;\r\n        }\r\n\r\n        .email-logo img {\r\n            max-width: 180px;\r\n            margin: 0 auto;\r\n        }\r\n\r\n        /* Content */\r\n        .email-content {\r\n            padding: 0 30px 30px 30px;\r\n            text-align: left;\r\n        }\r\n\r\n        .email-content h1 {\r\n            font-size: 22px;\r\n            color: #414a51;\r\n            font-weight: bold;\r\n            margin-bottom: 10px;\r\n        }\r\n\r\n        .email-content p {\r\n            font-size: 16px;\r\n            color: #7f8c8d;\r\n            line-height: 1.6;\r\n            margin: 20px 0;\r\n        }\r\n\r\n        .email-divider {\r\n            margin: 20px auto;\r\n            width: 60px;\r\n            border-bottom: 3px solid #ff600036;\r\n        }\r\n\r\n        /* Footer */\r\n        .email-footer {\r\n            background-color: #ff600036;\r\n            color: #000;\r\n            text-align: center;\r\n            font-size: 16px;\r\n            font-weight: 600;\r\n            padding: 20px;\r\n        }\r\n\r\n\r\n        /* Responsive Design */\r\n        @media only screen and (max-width: 480px) {\r\n            .email-content {\r\n                padding: 20px;\r\n            }\r\n\r\n            .email-header,\r\n            .email-footer {\r\n                padding: 15px;\r\n            }\r\n        }\r\n    </style>\r\n</head>\r\n\r\n<body>\r\n    <div class=\"email-wrapper\">\r\n        <table class=\"email-container\" cellpadding=\"0\" cellspacing=\"0\">\r\n            <tbody style=\"border: 1px solid #ffddc9\">\r\n                <tr>\r\n                    <td>\r\n                        <!-- Header -->\r\n                        <div class=\"email-header\">\r\n                            System Generated Email\r\n                        </div>\r\n\r\n                        \r\n                        <!-- Logo -->\r\n                        <div class=\"email-logo\">\r\n                            <a href=\"#\">\r\n                                <img src=\"https://i.ibb.co.com/dLYyDXX/OVO-logo-for-Light-BG.png\" alt=\"Company Logo\">\r\n                            </a>\r\n                        </div>\r\n                        <!-- Content -->\r\n                        <div class=\"email-content\">\r\n                            <h1>Hello {{username}}</h1>\r\n                            <p>{{message}}</p>\r\n                        </div>\r\n\r\n                        <!-- Footer -->\r\n                        <div class=\"email-footer\">\r\n                            &copy; 2024 <a href=\"#\" style=\"color: #0087ff;\">{{site_name}}</a>. All Rights Reserved.\r\n                        </div>\r\n                    </td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</body>\r\n\r\n</html>', 'hi {{fullname}} ({{username}}), {{message}}', 5.00, 2, 'ovowpp', '{{site_name}}', '{{site_name}}', 'hi {{fullname}} ({{username}}), {{message}}', '25d466', '{\"name\":\"php\"}', '{\"name\":\"infobip\",\"clickatell\":{\"api_key\":\"----------------\"},\"infobip\":{\"username\":\"------------8888888\",\"password\":\"-----------------\"},\"message_bird\":{\"api_key\":\"-------------------\"},\"nexmo\":{\"api_key\":\"----------------------\",\"api_secret\":\"----------------------\"},\"sms_broadcast\":{\"username\":\"----------------------\",\"password\":\"-----------------------------\"},\"twilio\":{\"account_sid\":\"-----------------------\",\"auth_token\":\"---------------------------\",\"from\":\"----------------------\"},\"text_magic\":{\"username\":\"-----------------------\",\"apiv2_key\":\"-------------------------------\"},\"custom\":{\"method\":\"get\",\"url\":\"https:\\/\\/hostname.com\\/demo-api-v1\",\"headers\":{\"name\":[\"api_key\"],\"value\":[\"test_api 555\"]},\"body\":{\"name\":[\"from_number\"],\"value\":[\"5657545757\"]}}}', '{\"apiKey\":\"AIzaSyCb6zm7_8kdStXjZMgLZpwjGDuTUg0e_qM\",\"authDomain\":\"flutter-prime-df1c5.firebaseapp.com\",\"projectId\":\"flutter-prime-df1c5\",\"storageBucket\":\"flutter-prime-df1c5.appspot.com\",\"messagingSenderId\":\"274514992002\",\"appId\":\"1:274514992002:web:4d77660766f4797500cd9b\",\"measurementId\":\"G-KFPM07RXRC\"}', '{\n    \"site_name\":\"Name of your site\",\n    \"site_currency\":\"Currency of your site\",\n    \"currency_symbol\":\"Symbol of currency\"\n}', 0, 1, 1, 1, 1, 0, 0, 1, 0, 0, 1, 1, 1, 'basic', '{\"google\":{\"client_id\":\"609720830799-cmdk7assb6j5i6l436ro72qdg05p080a.apps.googleusercontent.com\",\"client_secret\":\"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\",\"status\":1,\"info\":\"Quickly set up Google Login for easy and secure access to your website for all users\"},\"facebook\":{\"client_id\":\"------\",\"client_secret\":\"sdfsdf\",\"status\":1,\"info\":\"Set up Facebook Login for fast, secure user access and seamless integration with your website.\"},\"linkedin\":{\"client_id\":\"78l4zid62xp3yb\",\"client_secret\":\"WPL_AP1.kz1krlM9SsuZlWMS.XkPh9A==\",\"status\":1,\"info\":\"Set up LinkedIn Login for professional, secure access and easy user authentication on your website.\"}}', '2025-05-30 18:27:03', '1.0', 0, 20, 2, 'h:i A', 'd/m/Y', 2, ',', '683ecdc7f35db1748946375.gif', '{\"pusher_app_id\":\"------------\",\"pusher_app_key\":\"------------\",\"pusher_app_secret\":\"------------\",\"pusher_app_cluster\":\"------------\"}', NULL, '2025-06-19 11:57:48');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: not default language, 1: default language',
  `image` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `code`, `is_default`, `image`, `info`, `created_at`, `updated_at`) VALUES
(1, 'English', 'en', 1, '66dd7636311b31725789750.png', 'English is a global language with rich vocabulary, bridging international communication and culture.', '2020-07-06 03:47:55', '2024-10-03 04:11:19'),
(12, 'bangla', 'bn', 0, '66dd762f478701725789743.png', 'Bangla is a rich, expressive language spoken by millions, known for its cultural depth and literary heritage.', '2024-09-08 01:34:54', '2024-10-02 08:10:11'),
(13, 'Turkish', 'tr', 0, '66dd763ce41bd1725789756.png', 'Turkish is a vibrant language with deep historical roots, known for its unique structure and cultural significance.', '2024-09-08 01:35:12', '2024-09-10 05:19:32'),
(14, 'Spanish', 'es', 0, '66dd764462e2f1725789764.png', 'Spanish is a widely spoken language, celebrated for its melodic flow and rich cultural heritage.', '2024-09-08 01:35:22', '2024-10-03 04:11:19'),
(15, 'French', 'fr', 0, '66dd7652c06061725789778.png', 'French is a romantic language, renowned for its elegance, rich literature, and global influence.', '2024-09-08 01:35:28', '2024-10-02 08:10:07'),
(17, 'Russian', 'ru', 0, '66dd7a31f25a01725790769.png', 'Russian is a powerful language, known for its complex grammar and rich literary tradition.', '2024-09-08 04:19:30', '2024-09-10 05:20:29'),
(19, 'Portuguese', 'pt', 0, '66e6c31120d4c1726399249.png', 'Portuguese is a dynamic language with a rich cultural history, known for its expressiveness and global influence.', '2024-09-15 05:20:49', '2024-09-15 05:25:42'),
(23, 'Italy', 'it', 0, '670781623fe0d1728545122.png', 'Italian is a romantic and melodic language, celebrated for its rich history, artistic influence, and cultural significance in music.', '2024-10-10 01:25:22', '2024-10-10 01:27:28'),
(24, 'Japanese', 'jr', 0, '670cd7835eb281728894851.png', 'Japanese is a unique and nuanced language, known for its complex writing and deep cultural significance.', '2024-10-14 02:34:12', '2024-10-14 02:34:12');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL DEFAULT '0',
  `whatsapp_message_id` text COLLATE utf8mb4_unicode_ci,
  `whatsapp_account_id` int NOT NULL DEFAULT '0',
  `campaign_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `chatbot_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `template_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `conversation_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `message` longtext COLLATE utf8mb4_unicode_ci,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=sent,2=received',
  `message_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=text,2=image,3=video,4=document',
  `media_id` text COLLATE utf8mb4_unicode_ci,
  `media_url` text COLLATE utf8mb4_unicode_ci,
  `media_type` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mime_type` text COLLATE utf8mb4_unicode_ci,
  `media_caption` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `media_filename` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `media_path` text COLLATE utf8mb4_unicode_ci,
  `send_at` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=sent,2=delivered,3=read,9=failed',
  `ordering` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_logs`
--

CREATE TABLE `notification_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `sender` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_from` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sent_to` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `notification_type` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_read` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `id` bigint UNSIGNED NOT NULL,
  `act` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_body` text COLLATE utf8mb4_unicode_ci,
  `sms_body` text COLLATE utf8mb4_unicode_ci,
  `push_body` text COLLATE utf8mb4_unicode_ci,
  `shortcodes` text COLLATE utf8mb4_unicode_ci,
  `email_status` tinyint(1) NOT NULL DEFAULT '1',
  `email_sent_from_name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_sent_from_address` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sms_status` tinyint(1) NOT NULL DEFAULT '1',
  `sms_sent_from` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `push_status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_templates`
--

INSERT INTO `notification_templates` (`id`, `act`, `name`, `subject`, `push_title`, `email_body`, `sms_body`, `push_body`, `shortcodes`, `email_status`, `email_sent_from_name`, `email_sent_from_address`, `sms_status`, `sms_sent_from`, `push_status`, `created_at`, `updated_at`) VALUES
(1, 'BAL_ADD', 'Balance - Added', 'Your Account has been Credited', '{{site_name}} - Balance Added', '<div>We\'re writing to inform you that an amount of {{amount}} {{site_currency}} has been successfully added to your account.</div><div><br></div><div>Here are the details of the transaction:</div><div><br></div><div><b>Transaction Number: </b>{{trx}}</div><div><b>Current Balance:</b> {{post_balance}} {{site_currency}}</div><div><b>Admin Note:</b> {{remark}}</div><div><br></div><div>If you have any questions or require further assistance, please don\'t hesitate to contact us. We\'re here to assist you.</div>', 'We\'re writing to inform you that an amount of {{amount}} {{site_currency}} has been successfully added to your account.', '{{amount}} {{site_currency}} has been successfully added to your account.', '{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}', 0, '{{site_name}} Finance', NULL, 1, NULL, 1, '2021-11-03 12:00:00', '2024-10-03 04:16:09'),
(2, 'BAL_SUB', 'Balance - Subtracted', 'Your Account has been Debited', '{{site_name}} - Balance Subtracted', '<div>We wish to inform you that an amount of {{amount}} {{site_currency}} has been successfully deducted from your account.</div><div><br></div><div>Below are the details of the transaction:</div><div><br></div><div><b>Transaction Number:</b> {{trx}}</div><div><b>Current Balance: </b>{{post_balance}} {{site_currency}}</div><div><b>Admin Note:</b> {{remark}}</div><div><br></div><div>Should you require any further clarification or assistance, please do not hesitate to reach out to us. We are here to assist you in any way we can.</div><div><br></div><div>Thank you for your continued trust in {{site_name}}.</div>', 'We wish to inform you that an amount of {{amount}} {{site_currency}} has been successfully deducted from your account.', '{{amount}} {{site_currency}} debited from your account.', '{\"trx\":\"Transaction number for the action\",\"amount\":\"Amount inserted by the admin\",\"remark\":\"Remark inserted by the admin\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, '{{site_name}} Finance', NULL, 1, NULL, 1, '2021-11-03 12:00:00', '2024-10-03 04:15:37'),
(3, 'DEPOSIT_COMPLETE', 'Deposit - Automated - Successful', 'Deposit Completed Successfully', '{{site_name}} - Deposit successful', '<div>We\'re delighted to inform you that your deposit of {{amount}} {{site_currency}} via {{method_name}} has been completed.</div><div><br></div><div>Below, you\'ll find the details of your deposit:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge: </b>{{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Received:</b> {{method_amount}} {{method_currency}}</div><div><b>Paid via:</b> {{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><div>Your current balance stands at {{post_balance}} {{site_currency}}.</div><div><br></div><div>If you have any questions or need further assistance, feel free to reach out to our support team. We\'re here to assist you in any way we can.</div>', 'We\'re delighted to inform you that your deposit of {{amount}} {{site_currency}} via {{method_name}} has been completed.', 'Deposit Completed Successfully', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, '{{site_name}} Billing', NULL, 1, NULL, 1, '2021-11-03 12:00:00', '2024-05-08 07:20:34'),
(4, 'DEPOSIT_APPROVE', 'Deposit - Manual - Approved', 'Deposit Request Approved', '{{site_name}} - Deposit Request Approved', '<div>We are pleased to inform you that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been approved.</div><div><br></div><div>Here are the details of your deposit:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge: </b>{{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Received: </b>{{method_amount}} {{method_currency}}</div><div><b>Paid via: </b>{{method_name}}</div><div><b>Transaction Number: </b>{{trx}}</div><div><br></div><div>Your current balance now stands at {{post_balance}} {{site_currency}}.</div><div><br></div><div>Should you have any questions or require further assistance, please feel free to contact our support team. We\'re here to help.</div>', 'We are pleased to inform you that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been approved.', 'Deposit of {{amount}} {{site_currency}} via {{method_name}} has been approved.', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after this transaction\"}', 1, '{{site_name}} Billing', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:19:49'),
(5, 'DEPOSIT_REJECT', 'Deposit - Manual - Rejected', 'Deposit Request Rejected', '{{site_name}} - Deposit Request Rejected', '<div>We regret to inform you that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.</div><div><br></div><div>Here are the details of the rejected deposit:</div><div><br></div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Received:</b> {{method_amount}} {{method_currency}}</div><div><b>Paid via:</b> {{method_name}}</div><div><b>Charge:</b> {{charge}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><div>If you have any questions or need further clarification, please don\'t hesitate to contact us. We\'re here to assist you.</div><div><br></div><div>Rejection Reason:</div><div>{{rejection_message}}</div><div><br></div><div>Thank you for your understanding.</div>', 'We regret to inform you that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.', 'Your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"rejection_message\":\"Rejection message by the admin\"}', 1, '{{site_name}} Billing', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:20:13'),
(6, 'DEPOSIT_REQUEST', 'Deposit - Manual - Requested', 'Deposit Request Submitted Successfully', NULL, '<div>We are pleased to confirm that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.</div><div><br></div><div>Below are the details of your deposit:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge:</b> {{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Payable:</b> {{method_amount}} {{method_currency}}</div><div><b>Pay via: </b>{{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><div>Should you have any questions or require further assistance, please feel free to reach out to our support team. We\'re here to assist you.</div>', 'We are pleased to confirm that your deposit request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.', 'Your deposit request of {{amount}} {{site_currency}} via {{method_name}} submitted successfully.', '{\"trx\":\"Transaction number for the deposit\",\"amount\":\"Amount inserted by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the deposit method\",\"method_currency\":\"Currency of the deposit method\",\"method_amount\":\"Amount after conversion between base currency and method currency\"}', 1, '{{site_name}} Billing', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-04-25 03:27:42'),
(7, 'PASS_RESET_CODE', 'Password - Reset - Code', 'Password Reset', '{{site_name}} Password Reset Code', '<div>We\'ve received a request to reset the password for your account on <b>{{time}}</b>. The request originated from\r\n            the following IP address: <b>{{ip}}</b>, using <b>{{browser}}</b> on <b>{{operating_system}}</b>.\r\n    </div><br>\r\n    <div><span>To proceed with the password reset, please use the following account recovery code</span>: <span><b><font size=\"6\">{{code}}</font></b></span></div><br>\r\n    <div><span>If you did not initiate this password reset request, please disregard this message. Your account security\r\n            remains our top priority, and we advise you to take appropriate action if you suspect any unauthorized\r\n            access to your account.</span></div>', 'To proceed with the password reset, please use the following account recovery code: {{code}}', 'To proceed with the password reset, please use the following account recovery code: {{code}}', '{\"code\":\"Verification code for password reset\",\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}', 1, '{{site_name}} Authentication Center', NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:24:57'),
(8, 'PASS_RESET_DONE', 'Password - Reset - Confirmation', 'Password Reset Successful', NULL, '<div><div><span>We are writing to inform you that the password reset for your account was successful. This action was completed at {{time}} from the following browser</span>: <span>{{browser}}</span><span>on {{operating_system}}, with the IP address</span>: <span>{{ip}}</span>.</div><br><div><span>Your account security is our utmost priority, and we are committed to ensuring the safety of your information. If you did not initiate this password reset or notice any suspicious activity on your account, please contact our support team immediately for further assistance.</span></div></div>', 'We are writing to inform you that the password reset for your account was successful.', 'We are writing to inform you that the password reset for your account was successful.', '{\"ip\":\"IP address of the user\",\"browser\":\"Browser of the user\",\"operating_system\":\"Operating system of the user\",\"time\":\"Time of the request\"}', 1, '{{site_name}} Authentication Center', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-04-25 03:27:24'),
(9, 'ADMIN_SUPPORT_REPLY', 'Support - Reply', 'Re: {{ticket_subject}} - Ticket #{{ticket_id}}', '{{site_name}} - Support Ticket Replied', '<div>\r\n    <div><span>Thank you for reaching out to us regarding your support ticket with the subject</span>:\r\n        <span>\"{{ticket_subject}}\"&nbsp;</span><span>and ticket ID</span>: {{ticket_id}}.</div><br>\r\n    <div><span>We have carefully reviewed your inquiry, and we are pleased to provide you with the following\r\n            response</span><span>:</span></div><br>\r\n    <div>{{reply}}</div><br>\r\n    <div><span>If you have any further questions or need additional assistance, please feel free to reply by clicking on\r\n            the following link</span>: <a href=\"{{link}}\" title=\"\" target=\"_blank\">{{link}}</a><span>. This link will take you to\r\n            the ticket thread where you can provide further information or ask for clarification.</span></div><br>\r\n    <div><span>Thank you for your patience and cooperation as we worked to address your concerns.</span></div>\r\n</div>', 'Thank you for reaching out to us regarding your support ticket with the subject: \"{{ticket_subject}}\" and ticket ID: {{ticket_id}}. We have carefully reviewed your inquiry. To check the response, please go to the following link: {{link}}', 'Re: {{ticket_subject}} - Ticket #{{ticket_id}}', '{\"ticket_id\":\"ID of the support ticket\",\"ticket_subject\":\"Subject  of the support ticket\",\"reply\":\"Reply made by the admin\",\"link\":\"URL to view the support ticket\"}', 1, '{{site_name}} Support Team', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:26:06'),
(10, 'EVER_CODE', 'Verification - Email', 'Email Verification Code', NULL, '<div>\r\n    <div><span>Thank you for taking the time to verify your email address with us. Your email verification code\r\n            is</span>: <b><font size=\"6\">{{code}}</font></b></div><br>\r\n    <div><span>Please enter this code in the designated field on our platform to complete the verification\r\n            process.</span></div><br>\r\n    <div><span>If you did not request this verification code, please disregard this email. Your account security is our\r\n            top priority, and we advise you to take appropriate measures if you suspect any unauthorized access.</span>\r\n    </div><br>\r\n    <div><span>If you have any questions or encounter any issues during the verification process, please don\'t hesitate\r\n            to contact our support team for assistance.</span></div><br>\r\n    <div><span>Thank you for choosing us.</span></div>\r\n</div>', '---', '---', '{\"code\":\"Email verification code\"}', 1, '{{site_name}} Verification Center', NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2024-04-25 03:27:12'),
(11, 'SVER_CODE', 'Verification - SMS', 'Verify Your Mobile Number', NULL, '---', 'Your mobile verification code is {{code}}. Please enter this code in the appropriate field to verify your mobile number. If you did not request this code, please ignore this message.', '---', '{\"code\":\"SMS Verification Code\"}', 0, '{{site_name}} Verification Center', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-04-25 03:27:03'),
(12, 'WITHDRAW_APPROVE', 'Withdraw - Approved', 'Withdrawal Confirmation: Your Request Processed Successfully', '{{site_name}} - Withdrawal Request Approved', '<div>We are writing to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been processed successfully.</div><div><br></div><div>Below are the details of your withdrawal:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge:</b> {{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>You will receive:</b> {{method_amount}} {{method_currency}}</div><div><b>Via:</b> {{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><hr><div><br></div><div><b>Details of Processed Payment:</b></div><div>{{admin_details}}</div><div><br></div><div>Should you have any questions or require further assistance, feel free to reach out to our support team. We\'re here to help.</div>', 'We are writing to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been processed successfully.', 'Withdrawal Confirmation: Your Request Processed Successfully', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"admin_details\":\"Details provided by the admin\"}', 1, '{{site_name}} Finance', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:26:37'),
(13, 'WITHDRAW_REJECT', 'Withdraw - Rejected', 'Withdrawal Request Rejected', '{{site_name}} - Withdrawal Request Rejected', '<div>We regret to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.</div><div><br></div><div>Here are the details of your withdrawal:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge:</b> {{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Expected Amount:</b> {{method_amount}} {{method_currency}}</div><div><b>Via:</b> {{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><hr><div><br></div><div><b>Refund Details:</b></div><div>{{amount}} {{site_currency}} has been refunded to your account, and your current balance is {{post_balance}} {{site_currency}}.</div><div><br></div><hr><div><br></div><div><b>Reason for Rejection:</b></div><div>{{admin_details}}</div><div><br></div><div>If you have any questions or concerns regarding this rejection or need further assistance, please do not hesitate to contact our support team. We apologize for any inconvenience this may have caused.</div>', 'We regret to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been rejected.', 'Withdrawal Request Rejected', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this action\",\"admin_details\":\"Rejection message by the admin\"}', 1, '{{site_name}} Finance', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:26:55'),
(14, 'WITHDRAW_REQUEST', 'Withdraw - Requested', 'Withdrawal Request Confirmation', '{{site_name}} - Requested for withdrawal', '<div>We are pleased to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.</div><div><br></div><div>Here are the details of your withdrawal:</div><div><br></div><div><b>Amount:</b> {{amount}} {{site_currency}}</div><div><b>Charge:</b> {{charge}} {{site_currency}}</div><div><b>Conversion Rate:</b> 1 {{site_currency}} = {{rate}} {{method_currency}}</div><div><b>Expected Amount:</b> {{method_amount}} {{method_currency}}</div><div><b>Via:</b> {{method_name}}</div><div><b>Transaction Number:</b> {{trx}}</div><div><br></div><div>Your current balance is {{post_balance}} {{site_currency}}.</div><div><br></div><div>Should you have any questions or require further assistance, feel free to reach out to our support team. We\'re here to help.</div>', 'We are pleased to inform you that your withdrawal request of {{amount}} {{site_currency}} via {{method_name}} has been submitted successfully.', 'Withdrawal request submitted successfully', '{\"trx\":\"Transaction number for the withdraw\",\"amount\":\"Amount requested by the user\",\"charge\":\"Gateway charge set by the admin\",\"rate\":\"Conversion rate between base currency and method currency\",\"method_name\":\"Name of the withdraw method\",\"method_currency\":\"Currency of the withdraw method\",\"method_amount\":\"Amount after conversion between base currency and method currency\",\"post_balance\":\"Balance of the user after fter this transaction\"}', 1, '{{site_name}} Finance', NULL, 1, NULL, 0, '2021-11-03 12:00:00', '2024-05-08 07:27:20'),
(15, 'DEFAULT', 'Default Template', '{{subject}}', '{{subject}}', '{{message}}', '{{message}}', '{{message}}', '{\"subject\":\"Subject\",\"message\":\"Message\"}', 1, NULL, NULL, 1, NULL, 1, '2019-09-14 13:14:22', '2024-05-16 01:32:53'),
(16, 'KYC_APPROVE', 'KYC Approved', 'KYC Details has been approved', '{{site_name}} - KYC Approved', '<div><div><span>We are pleased to inform you that your Know Your Customer (KYC) information has been successfully reviewed and approved. This means that you are now eligible to conduct any payout operations within our system.</span></div><br><div><span>Your commitment to completing the KYC process promptly is greatly appreciated, as it helps us ensure the security and integrity of our platform for all users.</span></div><br><div><span>With your KYC verification now complete, you can proceed with confidence to carry out any payout transactions you require. Should you encounter any issues or have any questions along the way, please don\'t hesitate to reach out to our support team. We\'re here to assist you every step of the way.</span></div><br><div><span>Thank you once again for choosing {{site_name}} and for your cooperation in this matter.</span></div></div>', 'We are pleased to inform you that your Know Your Customer (KYC) information has been successfully reviewed and approved. This means that you are now eligible to conduct any payout operations within our system.', 'Your  Know Your Customer (KYC) information has been approved successfully', '[]', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2024-05-08 07:23:57'),
(17, 'KYC_REJECT', 'KYC Rejected', 'KYC has been rejected', '{{site_name}} - KYC Rejected', '<div><div><span>We regret to inform you that the Know Your Customer (KYC) information provided has been reviewed and unfortunately, it has not met our verification standards. As a result, we are unable to approve your KYC submission at this time.</span></div><br><div><span>We understand that this news may be disappointing, and we want to assure you that we take these matters seriously to maintain the security and integrity of our platform.</span></div><br><div><span>Reasons for rejection may include discrepancies or incomplete information in the documentation provided. If you believe there has been a misunderstanding or if you would like further clarification on why your KYC was rejected, please don\'t hesitate to contact our support team.</span></div><br><div><span>We encourage you to review your submitted information and ensure that all details are accurate and up-to-date. Once any necessary adjustments have been made, you are welcome to resubmit your KYC information for review.</span></div><br><div><span>We apologize for any inconvenience this may cause and appreciate your understanding and cooperation in this matter.</span></div><br><div>Rejection Reason:</div><div>{{reason}}</div><div><br></div><div><span>Thank you for your continued support and patience.</span></div></div>', 'We regret to inform you that the Know Your Customer (KYC) information provided has been reviewed and unfortunately, it has not met our verification standards. As a result, we are unable to approve your KYC submission at this time. We encourage you to review your submitted information and ensure that all details are accurate and up-to-date. Once any necessary adjustments have been made, you are welcome to resubmit your KYC information for review.', 'Your  Know Your Customer (KYC) information has been rejected', '{\"reason\":\"Rejection Reason\"}', 1, '{{site_name}} Verification Center', NULL, 1, NULL, 0, NULL, '2024-05-08 07:24:13'),
(18, 'SUBSCRIPTION_PAYMENT', 'Subscription Payment', 'Subscription payment has been successful', '{{site_name}} - Subscription payment', '<div>We wish to inform you that an amount of <strong data-start=\"186\" data-end=\"218\">{{amount}} {{site_currency}}</strong> has been successfully deducted from your account for your subscription to the <strong data-start=\"297\" data-end=\"314\">{{plan_name}}</strong> plan.</div><div><br></div><div>Below are the details of the transaction:</div><div><b>Transaction Number:</b> {{trx}}</div><div><b>Subscription Plan</b> <b>:</b> {{plan_name}}</div><div><b>Billing Cycle :</b>&nbsp;{{duration}}</div><div><b>Amount Paid : </b>{{amount}}</div><div><b>Next Billing Date :</b>&nbsp;{{next_billing}}</div><div><b>Current Balance: </b>{{post_balance}} {{site_currency}}</div><div><b>Admin Note:</b> {{remark}}</div><div><br></div><div>Should you require any further clarification or assistance, please do not hesitate to reach out to us. We are here to assist you in any way we can.</div><div><br></div><div>Thank you for your continued trust in {{site_name}}.</div>', 'Congratulations! We wish to inform you that an amount of {{amount}} {{site_currency}} has been successfully deducted for subscription payment.', '{{amount}} {{site_currency}} payment done for subscription payment.', '{\n    \"trx\": \"Transaction number for the action\",\n    \"amount\": \"Amount of the subscription payment\",\n    \"plan_name\": \"The name of the subscription plan\",\n    \"duration\" : \"The duration of the subscription plan\",\n    \"next_billing\" : \"The next billing date\",\n    \"remark\": \"Remark inserted by the admin\",\n    \"post_balance\": \"Balance of the user after this transaction\"\n}', 1, '{{site_name}} Finance', NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2025-05-28 15:22:22'),
(19, 'SUBSCRIPTION_EXPIRED', 'Subscription expired', 'Subscription has been expired.', '{{site_name}} - Subscription expired', '<div>We wish to inform you that your&nbsp;&nbsp;<strong data-start=\"186\" data-end=\"218\">{{subscription_type}}&nbsp; </strong><span data-start=\"186\" data-end=\"218\">subscription has&nbsp;&nbsp;</span>been expired.</div><div><br></div><div>Below are the details of the subscription :</div><div><b>Subscription Plan</b> <b>:</b> {{plan_name}}</div><div><b>Billing Cycle :</b>&nbsp;{{subscription_type}}</div><div><b>Expired Date :</b>&nbsp;{{expired_at}}</div><div><span style=\"font-weight: bolder;\">Billing Price :&nbsp;</span>{{amount}}&nbsp;{{site_currency}}</div><div><b>Current Balance: </b>{{post_balance}} {{site_currency}}</div><div><br></div><div>Thank you for your continued trust in {{site_name}}.</div>', 'Test', '{{amount}} {{site_currency}} payment done for subscription payment.', '{\n    \"trx\": \"Transaction number for the action\",\n    \"plan_name\": \"The name of the subscription plan\",\n    \"expired_at\": \"The expired datetime\",\n    \"subscription_url\": \"Redirect URL\",\n    \"subscription_type\": \"Yearly or Monthly\",\n    \"remark\": \"Remark inserted by the admin\",\n    \"post_balance\": \"Balance of the user after this transaction\"\n}', 1, '{{site_name}} Maintenance', NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2025-05-30 11:36:32'),
(20, 'UPCOMING_EXPIRED_SUBSCRIPTION', 'Subscription expiring notification', 'Subscription expiration notification.', '{{site_name}} - Subscription expiration', '<div>\r\n  We wish to inform you that your&nbsp;&nbsp;<strong data-start=\"186\" data-end=\"218\">{{subscription_type}}&nbsp; </strong><span data-start=\"186\" data-end=\"218\">subscription will be expired on&nbsp;</span><span style=\"\r\n      font-weight: bolder;\r\n      background-color: hsl(var(--white));\r\n      font-size: var(--bs-body-font-size);\r\n      text-align: var(--bs-body-text-align);\r\n      display: inline !important;\r\n    \">{{next_billing}}</span><span style=\"\r\n      background-color: hsl(var(--white));\r\n      font-size: var(--bs-body-font-size);\r\n      text-align: var(--bs-body-text-align);\r\n      display: inline !important;\r\n    \">. Kindly renew your subscription to continue enjoying our services.</span>\r\n</div>\r\n<div><br></div>\r\n<div>Below are the details of the subscription :</div>\r\n<div><b>Subscription Plan</b> <b>:</b> {{plan_name}}</div>\r\n<div>\r\n  <span style=\"font-weight: bolder\">Plan Price</span><span style=\"display: inline !important\">&nbsp;</span><span style=\"font-weight: bolder\">:</span><span style=\"display: inline !important\">&nbsp;{{plan_price}}&nbsp;{{site_currency}}</span>\r\n</div>\r\n<div><b>Billing Cycle :</b>&nbsp;{{subscription_type}}</div>\r\n<div><b>Billing Date :</b>&nbsp;{{next_billing}}</div>\r\n<div><b>Current Balance: </b>{{post_balance}} {{site_currency}}</div>\r\n<div>\r\n  <b>For renew &nbsp; :&nbsp;&nbsp;</b><a href=\"{{subscription_url}}\" style=\"display: inline !important\">Click here</a>\r\n</div>\r\n<div><br></div>\r\n<div>Thank you for your continued trust in {{site_name}}.</div>', 'Test', '{{amount}} {{site_currency}} payment done for subscription payment.', '{\n    \"trx\": \"Transaction number for the action\",\n    \"plan_name\": \"The name of the subscription plan\",\n    \"plan_price\" : \"Price of the plan\",\n    \"next_billing\": \"The next billing date\",\n    \"subscription_url\": \"Redirect URL\",\n    \"expiration_days\": \"Expiration days of the subscription\",\n    \"subscription_type\": \"Yearly or Monthly\",\n    \"remark\": \"Remark inserted by the admin\",\n    \"post_balance\": \"Balance of the user after this transaction\"\n}', 1, '{{site_name}} Maintenance', NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2025-05-13 08:47:09'),
(21, 'REFERRAL_COMMISSION', 'Referral commision ', 'Referral commission received', '{{site_name}} - Referral Commission', '<div>We\'re writing to inform you that an amount of {{amount}} {{site_currency}} has been successfully added to your account as a referral commission for referring {{user}}.</div><div><br></div><div>Here are the details of the transaction:</div><div><br></div><div><b>Transaction Number: </b>{{trx}}</div><div><b>Current Balance:</b> {{post_balance}} {{site_currency}}</div><div><b>Admin Note:</b> {{remark}}</div><div><br></div><div>If you have any questions or require further assistance, please don\'t hesitate to contact us. We\'re here to assist you.</div>', 'We\'re writing to inform you that an amount of {{amount}} {{site_currency}} has been successfully added to your account as a referral commission for referring {{user}}.', '{{amount}} {{site_currency}} commission amount has been received.', '{ \"trx\": \"Transaction number for the action\", \"amount\": \"Amount inserted by the admin\", \"remark\": \"Remark inserted by the admin\", \"post_balance\": \"Balance of the user after this transaction\", \"user\": \"Username of the user\" }', 1, '{{site_name}} Finance', NULL, 1, NULL, 1, '2021-11-03 12:00:00', '2025-03-20 13:29:51'),
(22, 'SUBSCRIPTION_INVOICE', 'Subscription Invoice ', 'Subscription invoice for user', '{{site_name}} - Subscription Invoice', '<div>\r\n  Hello {{user}},\r\n</div>\r\n\r\n<div><br></div>\r\n\r\n<div>\r\n  An invoice has been generated for your recent transaction. You can view the details and complete the payment using the link below.\r\n</div>\r\n\r\n<div><br></div>\r\n\r\n<div><b>Invoice ID:</b> {{invoice_id}}</div>\r\n<div><b>Invoice Date:</b> {{invoice_date}}</div>\r\n<div><b>Amount Due:</b> {{amount}}</div>\r\n<div><b>Admin Note:</b> {{remark}}</div>\r\n<div><b>Your Balance After Transaction:</b> {{post_balance}}</div>\r\n\r\n<div><br></div>\r\n\r\n<div>\r\n  👉 <a href=\"{{invoice_url}}\" target=\"_blank\">Click here to view and pay your invoice</a>\r\n</div>\r\n\r\n<div><br></div>\r\n\r\n<div>\r\n  If you have any questions or need assistance, feel free to reach out. We\'re here to help.\r\n</div>\r\n\r\n<div><br></div>\r\n\r\n<div>\r\n  Best regards,<br>\r\n  The {{site_name}} Team\r\n</div>', '', '', '{ \"invoice_id\": \"Invoice id\", \"invoice_date\": \"Invoice date\", \"invoice_url\" : \"URL of the invoice\", \"amount\": \"Amount inserted by the admin\", \"remark\": \"Remark inserted by the admin\", \"post_balance\": \"Balance of the user after this transaction\", \"user\": \"Username of the user\" }', 1, '{{site_name}} Finance', NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2025-04-10 06:07:26'),
(23, 'AGENT_REGISTERED', 'Agent - Registered', 'Agent registraiton', '{{site_name}} - Agent Registered', '<div><br></div>\r\n\r\n<div>\r\n  Congratulations! You have been successfully registered on <b>{{site_name}}</b> as an agent by <b>{{parent_user}}</b>.\r\n</div>\r\n\r\n<div><br></div>\r\n\r\n<div>Below are your login credentials:</div>\r\n<div><b>Username:</b> {{username}}</div>\r\n<div><b>Email:</b> {{email}}</div>\r\n<div><b>Password:</b> {{password}} <i>(One-time password)</i></div>\r\n\r\n<div><br></div>\r\n\r\n<div>\r\n  👉 <a href=\"{{login_url}}\" target=\"_blank\">Click here to log in to your account</a>\r\n</div>\r\n\r\n<div><br></div>\r\n\r\n<div>\r\n  <b>Important:</b> For security reasons, you are required to change your password after your first login. This one-time password will expire once you update it.\r\n</div>\r\n\r\n<div><br></div>\r\n\r\n<div>\r\n  If you have any questions or need assistance, feel free to reach out to our support team.\r\n</div>\r\n\r\n<div><br></div>\r\n\r\n<div>\r\n  Best regards,<br>\r\n  The {{site_name}} Team\r\n</div>', '', '', '{ \"user\": \"Username of the newly created user(agent)\", \"parent_user\": \"Username of the user who created the agent\", \"username\": \"Username of the agent\", \"email\": \"Email address of the agent\", \"password\": \"One time password for agent\", \"login_url\" : \"Login url for agent\" }', 1, '{{site_name}} Management', NULL, 0, NULL, 0, '2021-11-03 12:00:00', '2025-06-18 22:41:16');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tempname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'template name',
  `secs` text COLLATE utf8mb4_unicode_ci,
  `seo_content` text COLLATE utf8mb4_unicode_ci,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `slug`, `tempname`, `secs`, `seo_content`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 'HOME', '/', 'templates.basic.', '[\"how_it_work\",\"feature\",\"pricing\",\"testimonial\",\"mobile_app\",\"faq\",\"cta\",\"blog\"]', '{\"image\":\"670d1fed046621728913389.png\",\"description\":\"Et recusandae Minus\",\"social_title\":\"test\",\"social_description\":\"Odit magna eos cons\",\"keywords\":null}', 1, '2020-07-11 06:23:58', '2025-06-03 13:12:25'),
(4, 'Blog', 'blog', 'templates.basic.', NULL, NULL, 1, '2020-10-22 01:14:43', '2025-05-31 15:56:08'),
(5, 'Contact', 'contact', 'templates.basic.', '[\"faq\"]', NULL, 1, '2020-10-22 01:14:53', '2025-05-31 10:29:33'),
(28, 'Features', 'feature', 'templates.basic.', '[\"feature\",\"cta\"]', NULL, 1, '2020-10-22 01:14:53', '2025-05-31 15:53:43'),
(29, 'Pricing', 'pricing', 'templates.basic.', '[\"faq\",\"cta\"]', NULL, 1, '2020-10-22 01:14:53', '2025-02-08 00:37:31');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plan_purchases`
--

CREATE TABLE `plan_purchases` (
  `id` bigint NOT NULL,
  `plan_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `recurring_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=monthly,2=yearly',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `payment_method` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=wallet,2=gateway',
  `gateway_method_code` int NOT NULL DEFAULT '0',
  `auto_renewal` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `expired_at` datetime DEFAULT NULL,
  `is_sent_expired_notify` tinyint(1) NOT NULL DEFAULT '0',
  `is_sent_reminder_notify` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pricing_plans`
--

CREATE TABLE `pricing_plans` (
  `id` bigint NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `monthly_price` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `yearly_price` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `account_limit` int NOT NULL DEFAULT '0' COMMENT 'how many waba can be added',
  `contact_limit` int NOT NULL DEFAULT '0',
  `template_limit` int NOT NULL DEFAULT '0',
  `welcome_message` tinyint(1) NOT NULL DEFAULT '0',
  `chatbot_limit` int NOT NULL DEFAULT '0',
  `campaign_limit` tinyint NOT NULL DEFAULT '0',
  `short_link_limit` int NOT NULL DEFAULT '0',
  `floater_limit` int NOT NULL DEFAULT '0',
  `agent_limit` int NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '1',
  `is_popular` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `short_links`
--

CREATE TABLE `short_links` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dial_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qr_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `click` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_attachments`
--

CREATE TABLE `support_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `support_message_id` int UNSIGNED NOT NULL DEFAULT '0',
  `attachment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_messages`
--

CREATE TABLE `support_messages` (
  `id` bigint UNSIGNED NOT NULL,
  `support_ticket_id` int UNSIGNED NOT NULL DEFAULT '0',
  `admin_id` int UNSIGNED NOT NULL DEFAULT '0',
  `message` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int DEFAULT '0',
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ticket` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Open, 1: Answered, 2: Replied, 3: Closed',
  `priority` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1 = Low, 2 = medium, 3 = heigh',
  `last_reply` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `templates`
--

CREATE TABLE `templates` (
  `id` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `whatsapp_template_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_account_id` bigint NOT NULL DEFAULT '0',
  `name` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Meta API allow maximum 512 char',
  `header` text COLLATE utf8mb4_unicode_ci,
  `header_format` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `header_media` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `buttons` text COLLATE utf8mb4_unicode_ci,
  `footer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `add_security_recommendation` tinyint(1) NOT NULL DEFAULT '0',
  `code_expiration_minutes` int DEFAULT NULL,
  `category_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `language_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=pending,1=approved,2=rejected,3=disabled\r\n',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `template_categories`
--

CREATE TABLE `template_categories` (
  `id` bigint NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `label` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `template_categories`
--

INSERT INTO `template_categories` (`id`, `name`, `label`) VALUES
(1, 'MARKETING', 'Marketing'),
(2, 'UTILITY', 'Utility'),
(3, 'AUTHENTICATION', 'Authentication');

-- --------------------------------------------------------

--
-- Table structure for table `template_languages`
--

CREATE TABLE `template_languages` (
  `id` bigint NOT NULL,
  `code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `template_languages`
--

INSERT INTO `template_languages` (`id`, `code`, `country`, `created_at`, `updated_at`) VALUES
(1, 'af', 'Afrikaans', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(2, 'sq', 'Albanian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(3, 'ar', 'Arabic', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(4, 'ar_EG', 'Arabic (EGY)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(5, 'ar_AE', 'Arabic (UAE)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(6, 'ar_LB', 'Arabic (LBN)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(7, 'ar_MA', 'Arabic (MAR)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(8, 'ar_QA', 'Arabic (QAT)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(9, 'az', 'Azerbaijani', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(10, 'be_BY', 'Belarusian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(11, 'bn', 'Bengali', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(12, 'bn_IN', 'Bengali (IND)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(13, 'bg', 'Bulgarian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(14, 'ca', 'Catalan', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(15, 'zh_CN', 'Chinese (CHN)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(16, 'zh_HK', 'Chinese (HKG)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(17, 'zh_TW', 'Chinese (TAI)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(18, 'hr', 'Croatian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(19, 'cs', 'Czech', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(20, 'da', 'Danish', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(21, 'prs_AF', 'Dari', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(22, 'nl', 'Dutch', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(23, 'nl_BE', 'Dutch (BEL)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(24, 'en', 'English', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(25, 'en_GB', 'English (UK)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(26, 'en_US', 'English (US)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(27, 'en_AE', 'English (UAE)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(28, 'en_AU', 'English (AUS)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(29, 'en_CA', 'English (CAN)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(30, 'en_GH', 'English (GHA)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(31, 'en_IE', 'English (IRL)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(32, 'en_IN', 'English (IND)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(33, 'en_JM', 'English (JAM)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(34, 'en_MY', 'English (MYS)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(35, 'en_NZ', 'English (NZL)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(36, 'en_QA', 'English (QAT)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(37, 'en_SG', 'English (SGP)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(38, 'en_UG', 'English (UGA)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(39, 'en_ZA', 'English (ZAF)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(40, 'et', 'Estonian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(41, 'fil', 'Filipino', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(42, 'fi', 'Finnish', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(43, 'fr', 'French', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(44, 'fr_BE', 'French (BEL)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(45, 'fr_CA', 'French (CAN)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(46, 'fr_CH', 'French (CHE)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(47, 'fr_CI', 'French (CIV)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(48, 'fr_MA', 'French (MAR)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(49, 'ka', 'Georgian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(50, 'de', 'German', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(51, 'de_AT', 'German (AUT)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(52, 'de_CH', 'German (CHE)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(53, 'el', 'Greek', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(54, 'gu', 'Gujarati', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(55, 'ha', 'Hausa', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(56, 'he', 'Hebrew', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(57, 'hi', 'Hindi', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(58, 'hu', 'Hungarian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(59, 'id', 'Indonesian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(60, 'ga', 'Irish', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(61, 'it', 'Italian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(62, 'ja', 'Japanese', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(63, 'kn', 'Kannada', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(64, 'kk', 'Kazakh', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(65, 'rw_RW', 'Kinyarwanda', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(66, 'ko', 'Korean', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(67, 'ky_KG', 'Kyrgyz (Kyrgyzstan)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(68, 'lo', 'Lao', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(69, 'lv', 'Latvian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(70, 'lt', 'Lithuanian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(71, 'mk', 'Macedonian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(72, 'ms', 'Malay', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(73, 'ml', 'Malayalam', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(74, 'mr', 'Marathi', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(75, 'nb', 'Norwegian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(76, 'ps_AF', 'Pashto', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(77, 'fa', 'Persian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(78, 'pl', 'Polish', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(79, 'pt_BR', 'Portuguese (BR)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(80, 'pt_PT', 'Portuguese (POR)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(81, 'pa', 'Punjabi', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(82, 'ro', 'Romanian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(83, 'ru', 'Russian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(84, 'sr', 'Serbian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(85, 'si_LK', 'Sinhala', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(86, 'sk', 'Slovak', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(87, 'sl', 'Slovenian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(88, 'es', 'Spanish', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(89, 'es_AR', 'Spanish (ARG)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(90, 'es_CL', 'Spanish (CHL)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(91, 'es_CO', 'Spanish (COL)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(92, 'es_CR', 'Spanish (CRI)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(93, 'es_DO', 'Spanish (DOM)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(94, 'es_EC', 'Spanish (ECU)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(95, 'es_HN', 'Spanish (HND)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(96, 'es_MX', 'Spanish (MEX)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(97, 'es_PA', 'Spanish (PAN)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(98, 'es_PE', 'Spanish (PER)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(99, 'es_ES', 'Spanish (SPA)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(100, 'es_UY', 'Spanish (URY)', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(101, 'sw', 'Swahili', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(102, 'sv', 'Swedish', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(103, 'ta', 'Tamil', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(104, 'te', 'Telugu', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(105, 'th', 'Thai', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(106, 'tr', 'Turkish', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(107, 'uk', 'Ukrainian', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(108, 'ur', 'Urdu', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(109, 'uz', 'Uzbek', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(110, 'vi', 'Vietnamese', '2025-05-14 07:33:20', '2025-05-14 07:33:20'),
(111, 'zu', 'Zulu', '2025-05-14 07:33:20', '2025-05-14 07:33:20');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `post_balance` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `trx_type` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remark` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `firstname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dial_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_by` int UNSIGNED NOT NULL DEFAULT '0',
  `balance` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: banned, 1: active',
  `kyc_data` text COLLATE utf8mb4_unicode_ci,
  `kyc_rejection_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kv` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: KYC Unverified, 2: KYC pending, 1: KYC verified',
  `ev` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: email unverified, 1: email verified',
  `sv` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: mobile unverified, 1: mobile verified',
  `profile_complete` tinyint(1) NOT NULL DEFAULT '0',
  `ver_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'stores verification code',
  `ver_code_send_at` datetime DEFAULT NULL COMMENT 'verification send time',
  `ts` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: 2fa off, 1: 2fa on',
  `tv` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0: 2fa unverified, 1: 2fa verified',
  `tsc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `en` tinyint(1) NOT NULL DEFAULT '1',
  `sn` tinyint(1) NOT NULL DEFAULT '1',
  `pn` tinyint(1) NOT NULL DEFAULT '1',
  `ban_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int NOT NULL DEFAULT '0',
  `is_agent` tinyint(1) NOT NULL DEFAULT '0',
  `plan_id` int NOT NULL DEFAULT '0',
  `account_limit` int NOT NULL DEFAULT '0',
  `contact_limit` int NOT NULL DEFAULT '0',
  `template_limit` int NOT NULL DEFAULT '0',
  `welcome_message` tinyint(1) NOT NULL DEFAULT '0',
  `chatbot_limit` int NOT NULL DEFAULT '0',
  `campaign_limit` int NOT NULL DEFAULT '0',
  `short_link_limit` int NOT NULL DEFAULT '0',
  `floater_limit` int NOT NULL DEFAULT '0',
  `agent_limit` int NOT NULL DEFAULT '0',
  `plan_expired_at` datetime DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_logins`
--

CREATE TABLE `user_logins` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `user_ip` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `latitude` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `os` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `welcome_messages`
--

CREATE TABLE `welcome_messages` (
  `id` bigint NOT NULL,
  `whatsapp_account_id` bigint NOT NULL DEFAULT '0',
  `message` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '\r\n',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whatsapp_accounts`
--

CREATE TABLE `whatsapp_accounts` (
  `id` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `business_name` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp_business_account_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token` longtext COLLATE utf8mb4_unicode_ci,
  `code_verification_status` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NOT_VERIFIED',
  `meta_app_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=NO,1=YES',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` bigint UNSIGNED NOT NULL,
  `method_id` int UNSIGNED NOT NULL DEFAULT '0',
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `currency` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `trx` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `after_charge` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `withdraw_information` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>success, 2=>pending, 3=>cancel,  ',
  `admin_feedback` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_methods`
--

CREATE TABLE `withdraw_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `form_id` int UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `min_limit` decimal(28,8) DEFAULT '0.00000000',
  `max_limit` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `fixed_charge` decimal(28,8) DEFAULT '0.00000000',
  `rate` decimal(28,8) DEFAULT '0.00000000',
  `percent_charge` decimal(5,2) DEFAULT NULL,
  `currency` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`,`username`);

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agent_has_permissions`
--
ALTER TABLE `agent_has_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agent_permissions`
--
ALTER TABLE `agent_permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaign_contacts`
--
ALTER TABLE `campaign_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chatbots`
--
ALTER TABLE `chatbots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_lists`
--
ALTER TABLE `contact_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_list_contacts`
--
ALTER TABLE `contact_list_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_notes`
--
ALTER TABLE `contact_notes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_tags`
--
ALTER TABLE `contact_tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_tag_contacts`
--
ALTER TABLE `contact_tag_contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_job_logs`
--
ALTER TABLE `cron_job_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cron_schedules`
--
ALTER TABLE `cron_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_tokens`
--
ALTER TABLE `device_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `extensions`
--
ALTER TABLE `extensions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `floaters`
--
ALTER TABLE `floaters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `frontends`
--
ALTER TABLE `frontends`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gateways`
--
ALTER TABLE `gateways`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `gateway_currencies`
--
ALTER TABLE `gateway_currencies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_logs`
--
ALTER TABLE `notification_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification_templates`
--
ALTER TABLE `notification_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `plan_purchases`
--
ALTER TABLE `plan_purchases`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pricing_plans`
--
ALTER TABLE `pricing_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `short_links`
--
ALTER TABLE `short_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_attachments`
--
ALTER TABLE `support_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_messages`
--
ALTER TABLE `support_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `templates`
--
ALTER TABLE `templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `template_categories`
--
ALTER TABLE `template_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `template_languages`
--
ALTER TABLE `template_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`,`email`);

--
-- Indexes for table `user_logins`
--
ALTER TABLE `user_logins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `welcome_messages`
--
ALTER TABLE `welcome_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `whatsapp_accounts`
--
ALTER TABLE `whatsapp_accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agent_has_permissions`
--
ALTER TABLE `agent_has_permissions`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `agent_permissions`
--
ALTER TABLE `agent_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `campaign_contacts`
--
ALTER TABLE `campaign_contacts`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chatbots`
--
ALTER TABLE `chatbots`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_lists`
--
ALTER TABLE `contact_lists`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_list_contacts`
--
ALTER TABLE `contact_list_contacts`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_notes`
--
ALTER TABLE `contact_notes`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_tags`
--
ALTER TABLE `contact_tags`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_tag_contacts`
--
ALTER TABLE `contact_tag_contacts`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_jobs`
--
ALTER TABLE `cron_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `cron_job_logs`
--
ALTER TABLE `cron_job_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cron_schedules`
--
ALTER TABLE `cron_schedules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `device_tokens`
--
ALTER TABLE `device_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `extensions`
--
ALTER TABLE `extensions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `floaters`
--
ALTER TABLE `floaters`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `frontends`
--
ALTER TABLE `frontends`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `gateways`
--
ALTER TABLE `gateways`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `gateway_currencies`
--
ALTER TABLE `gateway_currencies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=337;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_logs`
--
ALTER TABLE `notification_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_templates`
--
ALTER TABLE `notification_templates`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plan_purchases`
--
ALTER TABLE `plan_purchases`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pricing_plans`
--
ALTER TABLE `pricing_plans`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `short_links`
--
ALTER TABLE `short_links`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_attachments`
--
ALTER TABLE `support_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_messages`
--
ALTER TABLE `support_messages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `templates`
--
ALTER TABLE `templates`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `template_categories`
--
ALTER TABLE `template_categories`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `template_languages`
--
ALTER TABLE `template_languages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_logins`
--
ALTER TABLE `user_logins`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `welcome_messages`
--
ALTER TABLE `welcome_messages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whatsapp_accounts`
--
ALTER TABLE `whatsapp_accounts`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw_methods`
--
ALTER TABLE `withdraw_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



-- ================== version 1.1 ==================
CREATE TABLE `coupons` (
  `id` bigint NOT NULL,
  `code` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=fixed,2=percent',
  `amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `min_purchase_amount` decimal(28,8) NOT NULL DEFAULT '0.00000000',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `use_limit` int NOT NULL DEFAULT '0',
  `per_user_limit` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=available,0=disable',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `coupons`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `plan_purchases` ADD `coupon_id` BIGINT UNSIGNED NOT NULL DEFAULT '0' AFTER `user_id`;
ALTER TABLE `deposits` ADD `coupon_id` BIGINT UNSIGNED NOT NULL DEFAULT '0' AFTER `plan_id`;

ALTER TABLE `general_settings` 
  ADD `meta_app_id` VARCHAR(255) NULL DEFAULT NULL AFTER `webhook_verify_token`, 
  ADD `meta_app_secret` TEXT NULL DEFAULT NULL AFTER `meta_app_id`,
  Add `meta_configuration_id` TEXT NULL DEFAULT NULL AFTER `meta_app_secret`,
  ADD `whatsapp_embedded_signup` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0=disable,1=enable' AFTER `webhook_verify_token`;


ALTER TABLE `admins` ADD `status` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1=active,0=disable' AFTER `remember_token`;


CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`id`, `name`, `guard_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin', 1, NOW(), NOW());

ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;




CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `group_name`, `created_at`, `updated_at`) VALUES
(1, 'view users', 'admin', 'manage user', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(2, 'view user agents', 'admin', 'manage user', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(3, 'send user notification', 'admin', 'manage user', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(4, 'view user notifications', 'admin', 'manage user', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(5, 'update user balance', 'admin', 'manage user', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(6, 'ban user', 'admin', 'manage user', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(7, 'login as user', 'admin', 'manage user', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(8, 'update user', 'admin', 'manage user', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(9, 'view pricing plans', 'admin', 'pricing plan', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(10, 'add pricing plan', 'admin', 'pricing plan', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(11, 'edit pricing plan', 'admin', 'pricing plan', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(12, 'view contact', 'admin', 'system data', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(13, 'view contact list', 'admin', 'system data', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(14, 'view contact tag', 'admin', 'system data', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(15, 'view campaign', 'admin', 'system data', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(16, 'view chatbot', 'admin', 'system data', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(17, 'view short link', 'admin', 'system data', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(18, 'view deposit', 'admin', 'deposit', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(19, 'approve deposit', 'admin', 'deposit', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(20, 'reject deposit', 'admin', 'deposit', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(21, 'view withdraw', 'admin', 'withdraw', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(22, 'approve withdraw', 'admin', 'withdraw', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(23, 'reject withdraw', 'admin', 'withdraw', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(24, 'view admin', 'admin', 'admin', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(25, 'add admin', 'admin', 'admin', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(26, 'edit admin', 'admin', 'admin', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(27, 'view roles', 'admin', 'role', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(28, 'add role', 'admin', 'role', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(29, 'edit role', 'admin', 'role', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(30, 'assign permissions', 'admin', 'role', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(31, 'manage gateways', 'admin', 'gateway', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(32, 'manage withdraw methods', 'admin', 'gateway', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(33, 'update general settings', 'admin', 'setting', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(34, 'update brand settings', 'admin', 'setting', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(35, 'system configuration', 'admin', 'setting', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(36, 'pusher configuration', 'admin', 'setting', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(37, 'notification settings', 'admin', 'setting', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(38, 'kyc settings', 'admin', 'setting', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(39, 'update maintenance mode', 'admin', 'setting', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(40, 'social login settings', 'admin', 'setting', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(41, 'seo settings', 'admin', 'setting', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(42, 'in app payment settings', 'admin', 'setting', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(43, 'view all transactions', 'admin', 'report', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(44, 'view user transactions', 'admin', 'report', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(45, 'view login history', 'admin', 'report', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(46, 'view subscription history', 'admin', 'report', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(47, 'view all notifications', 'admin', 'report', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(48, 'view tickets', 'admin', 'support ticket', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(49, 'answer tickets', 'admin', 'support ticket', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(50, 'close tickets', 'admin', 'support ticket', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(51, 'manage pages', 'admin', 'manage content', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(52, 'manage sections', 'admin', 'manage content', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(53, 'view dashboard', 'admin', 'other', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(54, 'manage extensions', 'admin', 'other', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(55, 'manage languages', 'admin', 'other', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(56, 'manage subscribers', 'admin', 'other', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(57, 'view application info', 'admin', 'other', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(58, 'custom css', 'admin', 'other', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(59, 'manage cron job', 'admin', 'other', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(60, 'sitemap xml', 'admin', 'other', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(61, 'robots txt', 'admin', 'other', '2025-08-18 14:14:01', '2025-08-18 14:14:01'),
(65, 'add coupon', 'admin', 'coupon', '2025-08-24 07:37:14', '2025-08-24 07:37:14'),
(66, 'edit coupon', 'admin', 'coupon', '2025-08-24 07:37:14', '2025-08-24 07:37:14'),
(67, 'cookie settings', 'admin', 'other', '2025-08-24 07:37:14', '2025-08-24 07:37:14'),
(68, 'view coupon', 'admin', 'coupon', '2025-08-24 07:37:46', '2025-08-24 07:37:46');

ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;
COMMIT;




CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;
COMMIT;


CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` 
    FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `roles` (`name`, `guard_name`, `status`, `created_at`, `updated_at`)
SELECT 'Super Admin', 'admin', 1, NOW(), NOW()
WHERE NOT EXISTS (
    SELECT 1 FROM `roles` WHERE `name` = 'Super Admin' AND `guard_name` = 'admin'
);

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`)
SELECT `id`, 'App\\Models\\Admin', 1
FROM `roles`
WHERE `name` = 'Super Admin' AND `guard_name` = 'admin'
ON DUPLICATE KEY UPDATE role_id = role_id;

COMMIT;



CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(25, 1),
(26, 1),
(27, 1),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1),
(34, 1),
(35, 1),
(36, 1),
(37, 1),
(38, 1),
(39, 1),
(40, 1),
(41, 1),
(42, 1),
(43, 1),
(44, 1),
(45, 1),
(46, 1),
(47, 1),
(48, 1),
(49, 1),
(50, 1),
(51, 1),
(52, 1),
(53, 1),
(54, 1),
(55, 1),
(56, 1),
(57, 1),
(58, 1),
(59, 1),
(60, 1),
(61, 1),
(65, 1),
(66, 1),
(67, 1),
(68, 1);
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;


ALTER TABLE `pricing_plans` CHANGE `campaign_limit` `campaign_limit` INT NOT NULL DEFAULT '0'; 
INSERT INTO `cron_jobs` (`name`, `alias`, `action`, `url`, `cron_schedule_id`, `next_run`, `last_run`, `is_running`, `is_default`, `created_at`, `updated_at`) VALUES
('Coupon Expiration', 'coupon_expiration', '[\"\\\\App\\\\Http\\\\Controllers\\\\CronController\",\"couponExpiration\"]', '', 3, '2025-04-15 14:43:43', '2025-04-14 14:43:43', 1, 1, '2024-09-09 03:36:44', '2025-04-14 08:43:43');





-- ==================== Version 1.3 ======================
CREATE TABLE `cta_urls` (
  `id` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cta_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `header_format` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `header` text COLLATE utf8mb4_unicode_ci,
  `body` text COLLATE utf8mb4_unicode_ci,
  `action` text COLLATE utf8mb4_unicode_ci,
  `footer` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `cta_urls`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `cta_urls`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;
COMMIT;

ALTER TABLE `messages` ADD `agent_id` BIGINT UNSIGNED NOT NULL DEFAULT '0' AFTER `conversation_id`, ADD `cta_url_id` BIGINT UNSIGNED NOT NULL DEFAULT '0' AFTER `agent_id`;

INSERT INTO `agent_permissions` (`id`, `name`, `guard_name`, `group_name`, `created_at`, `updated_at`) 
VALUES
    (NULL, 'view contact name', 'web', 'whatsapp', NULL, NULL),
    (NULL, 'view contact mobile', 'web', 'whatsapp', NULL, NULL),
    (NULL, 'view contact profile', 'web', 'whatsapp', NULL, NULL),
    (NULL, 'view cta url', 'web', 'cta url', NULL, NULL),
    (NULL, 'add cta url', 'web', 'cta url', NULL, NULL),
    (NULL, 'delete cta url', 'web', 'cta url', NULL, NULL);


ALTER TABLE `messages` CHANGE `media_caption` `media_caption` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

CREATE TABLE `template_cards` (
  `id` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `template_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `header_format` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT 'IMAGE' COMMENT 'IMAGE or VIDEO',
  `header` text COLLATE utf8mb4_unicode_ci,
  `buttons` text COLLATE utf8mb4_unicode_ci,
  `media_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `template_cards`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `template_cards`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;
COMMIT;

INSERT INTO `notification_templates` (`id`, `act`, `name`, `subject`, `push_title`, `email_body`, `sms_body`, `push_body`, `shortcodes`, `email_status`, `email_sent_from_name`, `email_sent_from_address`, `sms_status`, `sms_sent_from`, `push_status`, `created_at`, `updated_at`) VALUES
(NULL, 'TEMPLATE_APPROVE', 'Whatsapp Template - Approved', 'WhatsApp message template approved', '{{site_name}} - WhatsApp Template Approved', '<div>We are writing to inform you that your WhatsApp message template has been approved by Meta. Now you can send this template to your customers.</div><div><br></div><div>Below are the details of your teamplate:</div><div><br></div><div><b>Name:</b> {{name}}</div><div><b>Template ID: </b>{{template_id}}</div><div><b>Approve Date: </b>{{time}}</div><div><br></div><div>Should you have any questions or require further assistance, feel free to reach out to our support team. We\'re here to help.</div>', 'Your submission for a WhatsApp message template has been approved.', 'Your WhatsApp message template has been approved', '{ \"name\" : \"Name of the whatsapp template\", \"template_id\" : \"Template ID\", \"time\" : \"Time\" }', '1', '{{site_name}} Verification Center', NULL, '1', NULL, '0', '2021-11-03 18:00:00', '2025-09-20 13:26:26'),
(NULL, 'TEMPLATE_REJECTED', 'Whatsapp Template - Rejected', 'WhatsApp message template rejected', '{{site_name}} - WhatsApp Template Rejected', '<div>We are writing to inform you that your WhatsApp message template has been rejected by Meta.</div><div><br></div><div>Below are the details of your teamplate:</div><div><br></div><div><b>Name:</b> {{name}}</div><div><b>Template ID: </b>{{template_id}}</div><div><b>Reason:&nbsp;</b><span style=\"display: inline !important;\">{{reason}}</span></div><div><b>Rejected Date: </b>{{time}}</div><div><br></div><div>Should you have any questions or require further assistance, feel free to reach out to our support team. We\'re here to help.</div>', 'Your submission for a WhatsApp message template has been rejected.', 'Your WhatsApp message template has been rejected', '{ \"name\": \"Name of the whatsapp template\", \"template_id\": \"Template ID\", \"time\": \"Time\", \"reason\": \"Reason provided by meta\" }', '1', '{{site_name}} Verification Center', NULL, '1', NULL, '0', '2021-11-03 18:00:00', '2025-09-22 01:39:23');

CREATE TABLE `ai_assistants` (
  `id` bigint NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `info` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `config` text COLLATE utf8mb4_unicode_ci,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=enable,0=disable',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `ai_assistants` (`id`, `name`, `info`, `provider`, `config`, `url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Open AI', 'OpenAI provides advanced AI models capable of generating human-like text. It powers chatbots, content creation.', 'openai', '{\"api_key\":\"------------------\",\"model\":\"-------\",\"temperature\":\"0.7\"}', 'https://platform.openai.com/api-keys', 0, NULL, '2025-09-30 13:49:07'),
(2, 'Google Gemini', 'Google Gemini delivers powerful AI models designed for reasoning. It supports chat, content generation, and automation.', 'gemini', '{\"api_key\":\"-------------\",\"temperature\":\"0.7\",\"model\":\"----------\",\"max_output_tokens\":\"512\"}', 'https://aistudio.google.com/app/api-keys', 0, NULL, '2025-09-30 13:49:07');

ALTER TABLE `ai_assistants`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ai_assistants`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

ALTER TABLE `templates` ADD `rejected_reason` VARCHAR(255) NULL DEFAULT NULL AFTER `status`;
ALTER TABLE `plan_purchases` ADD `discount_amount` DECIMAL(28,8) NOT NULL DEFAULT '0' AFTER `amount`;

ALTER TABLE `pricing_plans` ADD `ai_assistance` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=yes,0=no' AFTER `welcome_message`;
ALTER TABLE `users` ADD `ai_assistance` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=yes,0=no' AFTER `welcome_message`;

ALTER TABLE `pricing_plans` ADD `cta_url_message` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=yes,0=no' AFTER `ai_assistance`;
ALTER TABLE `users` ADD `cta_url_message` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=available,0=not available' AFTER `ai_assistance`; 

ALTER TABLE `conversations` ADD `needs_human_reply` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=yes,0=no' AFTER `last_message_at`;

ALTER TABLE `messages` ADD `ai_reply` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=yes,0=no' AFTER `ordering`;

INSERT INTO `agent_permissions` (`id`, `name`, `guard_name`, `group_name`, `created_at`, `updated_at`) VALUES (NULL, 'ai assistant settings', 'web', 'ai assistant', NULL, NULL);


CREATE TABLE `ai_user_settings` (
  `id` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `system_prompt` longtext COLLATE utf8mb4_unicode_ci,
  `fallback_response` longtext COLLATE utf8mb4_unicode_ci,
  `max_length` int NOT NULL DEFAULT '512' COMMENT 'Max length of reply',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=on,0=off',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `ai_user_settings`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ai_user_settings`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `group_name`, `created_at`, `updated_at`) VALUES (69, 'ai assistant settings', 'admin', 'setting', NULL, NULL);
INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES ('69', '1');

UPDATE `ai_assistants` SET `config` = '{\"api_key\":\"------------------\",\"model\":\"gpt-4o-mini\",\"temperature\":\"0.7\"}' WHERE `ai_assistants`.`id` = 1; 
UPDATE `ai_assistants` SET `config` = '{\"api_key\":\"-------------\",\"temperature\":\"0.7\",\"model\":\"gemini-2.5-flash\",\"max_output_tokens\":\"512\"}' WHERE `ai_assistants`.`id` = 2; 


-- ================= version 1.4 ===================
ALTER TABLE `template_cards` ADD `media_path` TEXT NULL DEFAULT NULL AFTER `media_id`;
ALTER TABLE `contacts` ADD `is_blocked` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=yes,0=no' AFTER `is_customer`, ADD `blocked_by` BIGINT NOT NULL DEFAULT '0' COMMENT 'who blocked the contact' AFTER `is_blocked`;

INSERT INTO `agent_permissions` (`id`, `name`, `guard_name`, `group_name`, `created_at`, `updated_at`) VALUES (NULL, 'block contact', 'web', 'whatsapp', '2025-10-20 16:48:50', '2025-10-20 16:48:50'), (NULL, 'unblock contact', 'web', 'whatsapp', '2025-10-20 16:48:50', '2025-10-20 16:48:50');
ALTER TABLE `general_settings` ADD `google_maps_api` TEXT NULL DEFAULT NULL AFTER `meta_configuration_id`;
ALTER TABLE `messages` ADD `location` TEXT NULL DEFAULT NULL AFTER `media_path`;


-- ============== VERSION 1.5 ==================
--
-- Flows
--
CREATE TABLE `flows` (
  `id` bigint NOT NULL,
  `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `trigger_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=new message,2=keyword',
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=active,0=inactive',
  `nodes_json` json DEFAULT NULL,
  `edges_json` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `flows`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `flows`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;


--
-- Flow Edges 
--
CREATE TABLE `flow_edges` (
  `id` bigint UNSIGNED NOT NULL,
  `flow_id` bigint UNSIGNED DEFAULT '0',
  `source_node_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_node_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_index` int DEFAULT NULL,
  `edge_json` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `flow_edges`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `flow_edges`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;


-- 
-- Flow Nodes 
--
CREATE TABLE `flow_nodes` (
  `id` bigint NOT NULL,
  `flow_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `node_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cta_url_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `interactive_list_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `flow_node_media_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=text,1=image,2=video,',
  `position_x` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position_y` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `text` text COLLATE utf8mb4_unicode_ci,
  `location` text COLLATE utf8mb4_unicode_ci,
  `source_node_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_node_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nodes_json` json DEFAULT NULL,
  `buttons_json` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `flow_nodes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `flow_nodes`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--   
-- Flow Node Media
-- 
CREATE TABLE `flow_node_media` (
  `id` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `flow_node_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `media_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=image,2=video,3=audio,4=document',
  `media_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `flow_node_media`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `flow_node_media`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

-- 
-- Contact flow states
-- 
CREATE TABLE `contact_flow_states` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `conversation_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `flow_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `current_node_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'UUID or ID of the node where the conversation currently is',
  `last_button_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Identifier or index of last clicked button',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=waiting,1=sent',
  `last_interacted_at` timestamp NULL DEFAULT NULL COMMENT 'Last time contact interacted with this flow',
  `button_index` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


ALTER TABLE `contact_flow_states`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `contact_flow_states`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;


-- 
-- Interactive Lists
-- 
CREATE TABLE `interactive_lists` (
  `id` bigint NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `button_text` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `header` text COLLATE utf8mb4_unicode_ci,
  `body` text COLLATE utf8mb4_unicode_ci,
  `footer` text COLLATE utf8mb4_unicode_ci,
  `sections` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `interactive_lists`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `interactive_lists`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

-- 
-- Update Log
-- 
ALTER TABLE `users` ADD `flow_limit` INT NOT NULL DEFAULT '0' AFTER `campaign_limit`;
ALTER TABLE `pricing_plans` ADD `flow_limit` INT NOT NULL DEFAULT '0' AFTER `campaign_limit`;
ALTER TABLE `messages` ADD `flow_id` BIGINT UNSIGNED NOT NULL DEFAULT '0' AFTER `ai_reply`, ADD `flow_node_id` BIGINT UNSIGNED NOT NULL DEFAULT '0' AFTER `flow_id`;

ALTER TABLE `template_cards` ADD `body` VARCHAR(255) NULL DEFAULT NULL AFTER `header`;

ALTER TABLE `users` CHANGE `cta_url_message` `interactive_message` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=available,0=not available';
ALTER TABLE `pricing_plans` CHANGE `cta_url_message` `interactive_message` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=available,0=not available';
ALTER TABLE `messages` ADD `interactive_list_id` BIGINT UNSIGNED NOT NULL DEFAULT '0' AFTER `cta_url_id`;

DELETE FROM agent_permissions WHERE group_name = 'chatbot';

INSERT INTO agent_permissions (name, guard_name, group_name)
VALUES
  ('view flow builder', 'web', 'flow builder'),
  ('edit flow builder', 'web', 'flow builder'),
  ('add flow builder', 'web', 'flow builder'),
  ('delete flow builder', 'web', 'flow builder'),
  ('view interactive list', 'web', 'interactive list'),
  ('add interactive list', 'web', 'interactive list'),
  ('delete interactive list', 'web', 'interactive list');

ALTER TABLE `messages` ADD `list_reply` TEXT NULL DEFAULT NULL AFTER `location`;

-- 
-- Cron
-- 

INSERT INTO `cron_jobs` ( `name`, `alias`, `action`, `url`, `cron_schedule_id`, `next_run`, `last_run`, `is_running`, `is_default`, `created_at`, `updated_at`) VALUES ('Clear Trash Media', 'clear_trash_media', '[\"\\\\App\\\\Http\\\\Controllers\\\\CronController\",\"clearTrashMedia\"]', '', '3', '2025-11-09 13:00:34', '2025-11-08 13:00:34', '1', '1', '2024-09-09 09:36:44', '2025-11-08 13:00:34');




-- =================== v1.6===========================
ALTER TABLE `flow_nodes` CHANGE `type` `type` TINYINT NOT NULL DEFAULT '0';
ALTER TABLE `flow_nodes` ADD `template_id` BIGINT UNSIGNED NOT NULL DEFAULT '1' AFTER `node_id`;
ALTER TABLE `flow_nodes` ADD `header_params` TEXT NULL DEFAULT NULL AFTER `nodes_json`, ADD `body_params` TEXT NULL DEFAULT NULL AFTER `header_params`;

-- =================== v1.7===========================
INSERT INTO `languages` 
(`name`, `code`, `is_default`, `image`, `info`, `created_at`, `updated_at`)
SELECT 'Arabic', 'ar', 0, '692af23d8a2291764422205.png', NULL, '2025-11-29 13:16:45', '2025-11-29 13:16:45'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 
    FROM `languages` 
    WHERE `code` = 'ar'
);

-- =================== v1.8===========================
CREATE TABLE `ecommerce_configurations` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `config` text COLLATE utf8mb4_general_ci,
  `provider` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `ecommerce_configurations`
  ADD PRIMARY KEY (`id`);

  ALTER TABLE `ecommerce_configurations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `contacts` ADD `address` TEXT NULL DEFAULT NULL AFTER `image`;

ALTER TABLE `messages` ADD `product_data` TEXT NULL DEFAULT NULL AFTER `location`;

ALTER TABLE `pricing_plans` ADD `ecommerce_config` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 = off, 1 = on' AFTER `interactive_message`;

ALTER TABLE `users` ADD `ecommerce_config` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 = no, 1 = yes' AFTER `ai_assistance`;
ALTER TABLE `users` CHANGE `ecommerce_config` `ecommerce_available` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 = no, 1 = yes';

INSERT INTO `agent_permissions` ( `name`, `guard_name`, `group_name`, `created_at`, `updated_at`) VALUES
('show ecommerce products', 'web', 'ecommerce', '2025-12-21 09:50:41', '2025-12-21 09:50:41'),
('update ecommerce configuration', 'web', 'ecommerce', '2025-12-21 09:50:41', '2025-12-21 09:50:41');


CREATE TABLE `user_api_credentials` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL DEFAULT '0',
  `client_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_secret` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `user_api_credentials` ADD PRIMARY KEY (`id`);
ALTER TABLE `user_api_credentials` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

CREATE TABLE `external_api_ip_white_lists` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `version` tinyint UNSIGNED NOT NULL DEFAULT '6',
  `ip` varchar(240) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=active, 1=inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `external_api_ip_white_lists` ADD PRIMARY KEY (`id`);
ALTER TABLE `external_api_ip_white_lists` MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `external_api_ip_white_lists` DROP `version`;
ALTER TABLE `external_api_ip_white_lists` DROP `status`;

  
ALTER TABLE `pricing_plans` CHANGE `ecommerce_config` `ecommerce_available` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 = off, 1 = on'; 

ALTER TABLE `pricing_plans` ADD `api_available` INT NOT NULL DEFAULT '0' AFTER `is_popular`; 
ALTER TABLE `users` ADD `api_available` TINYINT(1) NOT NULL DEFAULT '0' AFTER `plan_expired_at`; 
ALTER TABLE `pricing_plans` CHANGE `api_available` `api_available` TINYINT(1) NOT NULL DEFAULT '0'; 


-- =================== v1.9=========================== 

ALTER TABLE `flows` ADD `whatsapp_account_id` INT NOT NULL DEFAULT '0' AFTER `user_id`;
ALTER TABLE `messages` ADD `error_message` LONGTEXT NULL DEFAULT NULL AFTER `flow_node_id`; 
ALTER TABLE `campaign_contacts` CHANGE `status` `is_faild` TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'not_sent= 0;is_sent = 2;is_success = 1;is_failed = 9;';
ALTER TABLE `campaign_contacts` CHANGE `is_faild` `is_faild` TINYINT(1) NOT NULL DEFAULT '0';
ALTER TABLE `campaign_contacts` CHANGE `is_faild` `is_failed` TINYINT(1) NOT NULL DEFAULT '0';

ALTER TABLE `campaigns`
  DROP `total_send`,
  DROP `total_failed`; 

  ALTER TABLE `campaigns` DROP `et`;

SET @sql := (
    SELECT IF(
        EXISTS (
            SELECT 1
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'campaign_contacts'
              AND COLUMN_NAME = 'status'
        ),
        'ALTER TABLE campaign_contacts DROP COLUMN status;',
        'SELECT 1;'
    )
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

ALTER TABLE `campaign_contacts` ADD `is_trigger` TINYINT NOT NULL DEFAULT '1' AFTER `send_at`; 

ALTER TABLE `campaign_contacts` ADD `error_message` LONGTEXT NULL DEFAULT NULL AFTER `is_trigger`; 
INSERT IGNORE INTO `cron_schedules` 
(`id`, `name`, `interval`, `status`, `created_at`, `updated_at`) 
VALUES (4, '5 Minute', '300', '1', NULL, NULL);

INSERT INTO `cron_jobs` (`id`, `name`, `alias`, `action`, `url`, `cron_schedule_id`, `next_run`, `last_run`, `is_running`, `is_default`, `created_at`, `updated_at`) VALUES (NULL, 'Change Campaign STtaus', 'change_campaign_status', '[\"\\\\App\\\\Http\\\\Controllers\\\\CronController\",\"checkCampaignStatus\"]', '', '4', '2025-12-22 14:04:03', '2025-12-21 14:04:03', '1', '1', '2024-09-09 09:36:44', '2025-12-21 20:04:03'); 

ALTER TABLE `conversations` CHANGE `needs_human_reply` `ai_reply` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '1=yes,0=no'; 


INSERT INTO `cron_jobs` (`id`, `name`, `alias`, `action`, `url`, `cron_schedule_id`, `next_run`, `last_run`, `is_running`, `is_default`, `created_at`, `updated_at`) VALUES (NULL, 'Update Campaign Message', 'update_campaign_message', '[\"\\\\App\\\\Http\\\\Controllers\\\\CronController\",\"campaignMessageUpdate\"]', '', '3', '2026-02-14 19:39:03', '2026-02-13 19:39:03', '1', '1', '2024-09-09 09:36:44', '2026-02-14 01:39:03'); 
ALTER TABLE `whatsapp_accounts` ADD `test_template` LONGTEXT NULL DEFAULT NULL AFTER `is_default`; 

ALTER TABLE `whatsapp_accounts` ADD `phone_number_status` TEXT NULL DEFAULT NULL AFTER `test_template`; 

INSERT INTO `notification_templates` ( `act`, `name`, `subject`, `push_title`, `email_body`, `sms_body`, `push_body`, `shortcodes`, `email_status`, `email_sent_from_name`, `email_sent_from_address`, `sms_status`, `sms_sent_from`, `push_status`, `created_at`, `updated_at`) VALUES
('MESSAGE_RECEIVED', 'New Message Received', 'You have received a new Message', 'New Message Received', '-------------', '-----------', 'New message from {{message_sender}}: \"{{message_content}}\". Take a look in your inbox!', '{ \"message_sender\": \"Message Sender Name or Mobile Number\", \"message_receiver\": \"Message Receiver name or mobile number\",\"message_content\": \"Message  Short Content\" }', 0, '{{site_name}} - Message Received', NULL, 1, NULL, 1, '2021-11-04 01:00:00', '2026-03-10 12:06:53');

UPDATE `general_settings` SET `sv` = '0' WHERE `general_settings`.`id` = 1; 
UPDATE `general_settings` SET `ev` = '0' WHERE `general_settings`.`id` = 1; 


-- =========================== version 2.1 ===========================
ALTER TABLE `templates` CHANGE `header_media` `header_media` LONGTEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL; 
ALTER TABLE `conversations` ADD `agent_id` INT NOT NULL DEFAULT '0' AFTER `ai_reply`; 


-- ========================== version 2.2 ===========================
ALTER TABLE `campaign_contacts` CHANGE `is_trigger` `is_trigger` TINYINT NOT NULL DEFAULT '0'; 


INSERT INTO `notification_templates` (`act`, `name`, `subject`, `push_title`, `email_body`, `sms_body`, `push_body`, `shortcodes`, `email_status`, `email_sent_from_name`, `email_sent_from_address`, `sms_status`, `sms_sent_from`, `push_status`, `created_at`, `updated_at`) VALUES
('SUBSCRIPTION_EXTENDED', 'Subscription extended', 'Subscription extended notification.', '{{site_name}} - {{plan_name}} Subscription extended', '<div>\r\n      Hello <strong>{{user}}</strong>,\r\n    </div>\r\n\r\n    <div><br></div>\r\n\r\n    <div>\r\n      Great news! Your subscription plan has been successfully extended.<br><br></div>\r\n    <div>\r\n\r\n    <b>New Expiry Date :</b> <strong>{{expired_date}} </strong>\r\n    <br><b>New Agent Limit :</b><strong>{{agent_limit}}</strong>\r\n    <br>\r\n    \r\n    <b>New Contact Limit:</b> <strong> {{contact_limit}} </strong>\r\n\r\n    <br>\r\n    <b>New Template Limit:</b> <strong> {{template_limit}}</strong></div><div><span style=\"font-weight: bolder; color: rgb(191, 191, 191);\">Automation Flow Limit:</span><span style=\"color: rgb(191, 191, 191); display: inline !important;\">&nbsp;</span><span style=\"font-weight: bolder; color: rgb(191, 191, 191);\">{{flow_limit}}</span></div><div><span style=\"font-weight: bolder; color: rgb(191, 191, 191);\">Campaign Limit:</span><span style=\"color: rgb(191, 191, 191); display: inline !important;\">&nbsp;</span><span style=\"font-weight: bolder; color: rgb(191, 191, 191);\">{{campaign_limit}}</span></div><div><div style=\"font-weight: bold;\"><br><div class=\"relative w-full overflow-visible\"><section class=\"text-token-text-primary w-full focus:outline-none [--shadow-height:45px] has-data-writing-block:pointer-events-none has-data-writing-block:-mt-(--shadow-height) has-data-writing-block:pt-(--shadow-height) [&amp;:has([data-writing-block])&gt;*]:pointer-events-auto [content-visibility:auto] supports-[content-visibility:auto]:[contain-intrinsic-size:auto_100lvh] R6Vx5W_threadScrollVars scroll-mb-[calc(var(--scroll-root-safe-area-inset-bottom,0px)+var(--thread-response-height))] scroll-mt-[calc(var(--header-height)+min(200px,max(70px,20svh)))]\" dir=\"auto\" data-turn-id=\"request-69e718d4-67f8-8322-850a-89947387b7c0-6\" data-turn-id-container=\"request-69e718d4-67f8-8322-850a-89947387b7c0-6\" data-testid=\"conversation-turn-372\" data-scroll-anchor=\"false\" data-turn=\"assistant\"><div class=\"text-base my-auto mx-auto pb-10 [--thread-content-margin:var(--thread-content-margin-xs,calc(var(--spacing)*4))] @w-sm/main:[--thread-content-margin:var(--thread-content-margin-sm,calc(var(--spacing)*6))] @w-lg/main:[--thread-content-margin:var(--thread-content-margin-lg,calc(var(--spacing)*16))] px-(--thread-content-margin)\"><div class=\"[--thread-content-max-width:40rem] @w-lg/main:[--thread-content-max-width:48rem] mx-auto max-w-(--thread-content-max-width) flex-1 group/turn-messages focus-visible:outline-hidden relative flex w-full min-w-0 flex-col agent-turn\"><div class=\"flex max-w-full flex-col gap-4 grow\"><div data-message-author-role=\"assistant\" data-message-id=\"b91b65b0-0dc2-4344-975e-a19247aa9e0a\" dir=\"auto\" data-message-model-slug=\"gpt-5-3-mini\" class=\"min-h-8 text-message relative flex w-full flex-col items-end gap-2 text-start break-words whitespace-normal outline-none keyboard-focused:focus-ring [.text-message+&amp;]:mt-1\" data-turn-start-message=\"true\" tabindex=\"0\"><div class=\"flex w-full flex-col gap-1 empty:hidden\"><div class=\"markdown prose dark:prose-invert wrap-break-word w-full dark markdown-new-styling\"><h5><font color=\"#b56308\">Please view all your subscription information from the <strong data-start=\"55\" data-end=\"88\">Dashboard → Subscription Info</strong> menu.</font></h5><h5><font color=\"#b56308\"><br></font><span style=\"color: rgb(191, 191, 191); font-family: sans-serif; font-size: 16px; font-weight: 400; display: inline !important;\">Your subscription remains active, and you can continue enjoying all the features included in your plan without interruption.</span></h5></div></div></div></div></div></div></section></div></div>\r\n\r\n    <div style=\"font-weight: bold;\"><br></div>\r\n\r\n    <div style=\"font-weight: bold;\">\r\n      Thank you for continuing with <strong>{{site_name}}</strong>.\r\n    </div>\r\n</div>', '', 'Your {{plan_name}} subscription has been extended. New expiry date: {{expired_date}}.', '{\n    \"user\": \"Full Name Of The User\",\n    \"expired_date\": \"New Expiry Date Of The Plan\",\n    \"agent_limit\": \"New Agent Limit\",\n    \"contact_limit\": \"New Contact Limit\",\n    \"template_limit\": \"New Template Limit\",\n    \"flow_limit\": \"New Automation Flow Limit\",\n    \"campaign_limit\": \"New Campaign Limit\"\n}', 1, '{{site_name}} Maintenance', NULL, 0, NULL, 0, NULL, '2026-05-09 07:21:33');


INSERT INTO `permissions` (`id`, `name`, `guard_name`, `group_name`, `created_at`, `updated_at`) VALUES
(70, 'pwa settings', 'admin', '', NULL, NULL);

UPDATE `permissions` SET `group_name` = 'setting' WHERE `permissions`.`id` = 70; 

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(70, 1);