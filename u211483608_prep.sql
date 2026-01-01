-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 01, 2026 at 12:08 PM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u211483608_prep`
--

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `content` text NOT NULL,
  `status` enum('published','draft') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `slug`, `image`, `content`, `status`, `created_at`, `updated_at`) VALUES
(1, 'How', 'how', '', 'Like WOWgdsfggggggggggggggggggggggggggggggggggggggggggggggggssssssssssssssssssssssssssssgggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg', 'published', '2026-01-01 10:41:43', '2026-01-01 10:42:15');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `publisher` varchar(100) DEFAULT NULL,
  `edition` varchar(50) DEFAULT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL,
  `file_url` varchar(255) DEFAULT NULL,
  `file_size` varchar(20) DEFAULT NULL,
  `file_format` varchar(10) DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `language` varchar(50) DEFAULT 'English',
  `price` decimal(10,2) DEFAULT 0.00,
  `is_free` tinyint(1) DEFAULT 0,
  `is_independent` tinyint(1) DEFAULT 0,
  `download_count` int(11) DEFAULT 0,
  `view_count` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_recommended` tinyint(1) DEFAULT 0,
  `status` enum('published','draft','archived') DEFAULT 'draft',
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `subject_id`, `title`, `slug`, `author`, `publisher`, `edition`, `isbn`, `description`, `cover_image`, `file_url`, `file_size`, `file_format`, `pages`, `language`, `price`, `is_free`, `is_independent`, `download_count`, `view_count`, `is_featured`, `is_recommended`, `status`, `published_at`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Ilets Guide', 'ilets-guide', 'Aks', 'ARK', '5', '23453444', 'ddd', 'uploads/book_covers/1766822176_Capture.PNG', 'uploads/books/1766822176_sdgfs.txt', '88.77 KB', 'DOCS', 233, 'English', 0.00, 1, 0, 6, 0, 1, 0, 'published', NULL, '2025-12-27 07:56:16', '2026-01-01 07:29:12'),
(2, NULL, 'Speaking Templates', 'speaking-templates', 'Daljeet Singh', 'Daljeet Singh', '2025-26', 'PWD051125', 'Master the CELPIP Speaking test with ready-to-use templates designed for high CLB scores. Learn how to structure your answers, improve fluency, and speak confidently in every task. Perfect for PR applicants.', 'uploads/book_covers/1767189548_20251231_1926_CELPIP Study Guide Cover_simple_compose_01kdtb24hrekr9zj4g3xhzr6sf.png', 'uploads/books/1767189548_CELPIP_Speaking Template.pdf', '221.56 KB', '', 9, 'English', 100.00, 0, 1, 0, 0, 1, 1, 'published', NULL, '2025-12-31 13:59:08', '2026-01-01 09:29:55');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `short_description` varchar(200) DEFAULT NULL,
  `full_description` longtext DEFAULT NULL,
  `icon` varchar(50) DEFAULT 'fas fa-book',
  `cover_image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_popular` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive','coming_soon') DEFAULT 'active',
  `meta_title` varchar(150) DEFAULT NULL,
  `meta_description` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `slug`, `short_description`, `full_description`, `icon`, `cover_image`, `sort_order`, `is_featured`, `is_popular`, `status`, `meta_title`, `meta_description`, `created_at`, `updated_at`) VALUES
(2, 'IELTS (A.C)', 'ilets', 'Boost your IELTS Academic score with Prepwithdaljeet’s Mastery Program—HD lessons, PDFs, practice tasks, weekly doubt sessions &amp; WhatsApp support. Learn at your pace with 1-year lifetime access.', 'Unlock your full potential in the IELTS Academic test with Prepwithdaljeet’s Complete Mastery Program.\r\nThis course includes video lectures, PDFs, practice assignments, and weekly doubt sessions designed to help you achieve your dream band score. With 1-year lifetime access and personal WhatsApp support, you can learn at your own pace and stay guided every step of the way.\r\n\r\n💰 Course Fee: ₹1049\r\n📅 Access: Lifetime (1 Year)\r\n🎯 Includes:\r\n\r\nHD Video Lessons (All 4 Modules)\r\n\r\nPractice PDFs &amp; Assignments\r\n\r\nWeekly Doubt-Clearing Session\r\n\r\nWhatsApp Support\r\n\r\nStart your IELTS Academic journey today and prepare smarter — not harder!', 'fas fa-books', 'uploads/courses/1767173450_20251028_1808_IELTS Course Card_simple_compose_01k8nd5n4pekpam1vbam6c7vhp.png', 1, 1, 1, 'active', NULL, NULL, '2025-12-27 07:00:22', '2025-12-31 09:30:50'),
(3, 'IELTS (G.T)', 'ielts-g-t', 'Excel in IELTS General Training with Prepwithdaljeet’s PR &amp; Work Abroad Program—HD lessons, practice PDFs, weekly doubt sessions &amp; WhatsApp support. 1-year lifetime access to boost your band s', 'Prepare strategically for IELTS General Training with Prepwithdaljeet’s PR &amp; Work Abroad Program.\r\nThis comprehensive course offers HD video lessons, practice PDFs, weekly assignments, and live doubt sessions, all crafted to help you excel in Listening, Reading, Writing, and Speaking.\r\nWith 1-year lifetime access and personal WhatsApp guidance, you’ll have all the support you need to achieve your desired band for immigration or job purposes.\r\n\r\n💰 Course Fee: ₹1021\r\n📅 Access: Lifetime (1 Year)\r\n🎯 Includes:\r\n\r\nFull Video Lessons (All 4 Modules)\r\n\r\nWriting Task Samples &amp; Practice PDFs\r\n\r\nWeekly Doubt-Clearing Session\r\n\r\nWhatsApp Support\r\n\r\nGet trained, get confident — and take your first step toward your dream country!', 'fas fa-book', 'uploads/courses/1767181558_20251028_1825_IELTS Course Card_simple_compose_01k8ne4k66fj9t7bzj6mjwhpv2.png', 2, 0, 0, 'active', NULL, NULL, '2025-12-31 11:45:58', '2025-12-31 11:45:58'),
(4, 'PTE (Core)', 'pte-core', 'Ace the PTE Core for Canada PR with Prepwithdaljeet’s Canada PR Success Program—HD lessons, practice PDFs, weekly doubt sessions &amp; WhatsApp support. Optional AI-evaluated mock tests available.', 'Prepare for PTE Core — the new English test accepted for Canada PR — with Prepwithdaljeet’s Canada PR Success Program.\r\nThis course is built for real results, featuring HD video lessons, practice PDFs, targeted assignments, weekly live doubt sessions, and personalized WhatsApp support.\r\n\r\nStudents can also access 20 full-length mock tests and 120 sectional tests with AI-based evaluation for deeper practice and score prediction.\r\n(Mock &amp; sectional test access available for an additional ₹1200.)\r\n\r\n💰 Course Fee: ₹1011\r\n📅 Access: Lifetime (1 Year)\r\n🎯 Includes:\r\n\r\nHD Video Lessons (All Modules)\r\n\r\nPractice PDFs + Weekly Doubt Sessions\r\n\r\nWhatsApp Mentorship\r\n\r\n(Optional) 20 Mock Tests + 120 Sectional Tests with AI Evaluation\r\n\r\nGet ready to achieve your CLB target and move one step closer to your Canadian dream!', 'fas fa-book', 'uploads/courses/1767181654_20251028_1843_PTE Course Card_simple_compose_01k8nf47hmfab86htn9v6s3qnv.png', 3, 1, 0, 'active', NULL, NULL, '2025-12-31 11:47:34', '2025-12-31 11:47:34'),
(5, 'PTE (Academic)', 'pte-academic', 'Boost your PTE Academic score with Prepwithdaljeet’s Smart Practice &amp; Strategy Course—HD lessons, PDFs, weekly doubt sessions &amp; WhatsApp support. Optional AI-evaluated mock tests available.', 'Achieve your dream PTE Academic score with Prepwithdaljeet’s Smart Practice &amp; Strategy Course.\r\nThis all-in-one program offers HD video lessons, detailed PDFs, assignments, weekly doubt-clearing sessions, and personalized WhatsApp support — everything you need for focused learning.\r\n\r\nYou’ll also have access to 20 full-length mock tests and 120 sectional tests powered by AI evaluation, giving you instant feedback and real exam simulation.\r\n(If you wish to include the mock and sectional test pack, an additional ₹1200 will apply.)\r\n\r\n💰 Course Fee: ₹1001\r\n📅 Access: Lifetime (1 Year)\r\n🎯 Includes:\r\n\r\nHD Video Lessons + Practice PDFs\r\n\r\nWeekly Doubt Sessions\r\n\r\nWhatsApp Mentorship\r\n\r\n(Optional) 20 Mock Tests + 120 Sectional Tests with AI Evaluation\r\n\r\nMaster every PTE module with precision, performance, and personal guidance!', 'fas fa-computer', 'uploads/courses/1767181794_20251028_1836_PTE Course Card Design_simple_compose_01k8neqnsyee6s9v6q229pgqtp.png', 4, 1, 1, 'active', NULL, NULL, '2025-12-31 11:49:54', '2025-12-31 11:49:54'),
(6, 'Duolingo English Test', 'duolingo-english-test', 'Boost your Duolingo English Test score with Prepwithdaljeet’s Smart Score Booster—HD lessons, practice PDFs, weekly doubt sessions &amp; WhatsApp support. Optional AI mock tests and discounted exam bo', 'Ace your Duolingo English Test with Prepwithdaljeet’s Smart Score Booster Program, designed for quick and result-oriented preparation.\r\nThe course offers HD video lessons, PDFs, assignments, weekly doubt sessions, and WhatsApp guidance to help you achieve your target score with confidence.\r\n\r\nPlus, get exclusive access to discounted exam booking — only $65 instead of $75!\r\n\r\n💰 Course Fee: ₹2250\r\n📅 Access: Lifetime (1 Year)\r\n🎯 Includes:\r\n\r\nHD Video Lessons + Practice PDFs\r\n\r\nWeekly Doubt Sessions\r\n\r\nWhatsApp Support\r\n\r\n(Optional) 12 Mock Tests + 40 Item-wise Tests (AI Evaluation)\r\n\r\n🎁 Discounted Exam Booking at $65 instead of $75\r\n\r\nPrepare fast, score high, and save more with Prepwithdaljeet’s Duolingo course!', 'fas fa-laptop', 'uploads/courses/1767182062_20251028_1918_Duolingo Test Prep Card_simple_compose_01k8nh4r3hfqssk7b27wnb734b.png', 4, 1, 0, 'active', NULL, NULL, '2025-12-31 11:54:22', '2025-12-31 11:54:22'),
(7, 'OET- Healthcare Professional', 'oet-healthcare-professional', 'Advance your medical career with Prepwithdaljeet’s OET Mastery Course—medical-focused lessons, practice tests, weekly doubt sessions &amp;amp;amp; WhatsApp support. Train confidently for your target s', 'Advance your medical career with Prepwithdaljeet’s OET Healthcare English Mastery Course, designed for doctors, nurses, and healthcare professionals seeking registration or migration abroad.\r\nThe course includes detailed video lessons, medical-context PDFs, practical writing &amp;amp;amp; speaking exercises, weekly doubt-clearing sessions, and personal WhatsApp support to help you master each subtest confidently.\r\n\r\nYou’ll also get 30 sectional tests (10 each for Reading, Writing, and Listening) and 8 full-length mock tests to build real-exam stamina and improve accuracy with expert feedback.\r\n\r\n💰 Course Fee: ₹1799\r\n📅 Access: Lifetime (1 Year)\r\n🎯 Includes:\r\n\r\nHD Video Lessons + Medical PDFs\r\n\r\n30 Sectional Tests (10 per module)\r\n\r\n8 Full-Length Mock Tests (1200 inr)\r\n\r\nWeekly Live Doubt Sessions\r\n\r\nWhatsApp Support\r\n\r\nBuild your confidence, refine your skills, and achieve your OET target score with professional training and real test practice!', 'fas fa-book', 'uploads/courses/1767182234_20251028_1926_OET Course Card Design_simple_compose_01k8nhk5bqfp9s5cjpar4mtkzk.png', 6, 1, 1, 'active', NULL, NULL, '2025-12-31 11:57:14', '2025-12-31 11:58:17'),
(8, 'French Coaching', 'french-coaching', 'Learn French fast with Prepwithdaljeet’s Complete French Training—HD lessons, grammar &amp; vocabulary practice, test series, weekly doubt sessions, and WhatsApp support. Build real speaking confidenc', 'Learn French quickly and effectively with Prepwithdaljeet’s Complete French Training Course, tailored for students, professionals, and global learners.\r\nThis course includes video lessons and test series focused on grammar, vocabulary, sentence structure, and reading, helping you build a strong foundation for real communication.\r\n\r\nYou’ll also receive weekly doubt-clearing sessions and WhatsApp mentorship to ensure personalized guidance and continuous improvement.\r\n\r\n💰 Course Fee: ₹1399\r\n📅 Access: Valid for 3 Months\r\n🎯 Includes:\r\n\r\nHD Video Lessons (All Levels)\r\n\r\nGrammar, Vocabulary &amp; Sentence Structure Practice\r\n\r\nReading &amp; Test Series\r\n\r\nWeekly Doubt Sessions\r\n\r\nWhatsApp Support\r\n\r\nBegin your French journey today — speak with confidence and embrace a global language in just 3 months!', 'fas fa-book', 'uploads/courses/1767183544_20251028_1939_French Training Card_simple_compose_01k8njatqsf92rr5wm2w4dzcsk.png', 7, 1, 1, 'active', NULL, NULL, '2025-12-31 12:19:04', '2025-12-31 12:19:04'),
(9, 'TOEFL Training', 'toefl-training', 'Get TOEFL-ready with Prepwithdaljeet’s Complete Prep Program—updated for the new TOEFL format from 6 Jan 2026. HD lessons, PDFs, weekly doubt sessions &amp; WhatsApp support, plus optional AI-evaluate', 'Complete TOEFL Test Preparation Program (Updated for 2026 Format)\r\n\r\nGet fully prepared for the new TOEFL format effective from 6 January 2026 with Prepwithdaljeet’s Complete Test Preparation Program — designed especially for students aiming for admission to top universities across the world.\r\n\r\nThis all-inclusive training program is structured to match the updated test style and scoring approach. You will learn through high-quality HD video lessons, detailed practice PDFs, assignments, and guided weekly doubt-clearing sessions, ensuring you build confidence across all four sections — Reading, Listening, Speaking, and Writing — exactly as tested in the new TOEFL pattern.\r\n\r\nOur course focuses on smart strategies, time-saving techniques, question-type mastery, and real-exam skill development, so you don’t just study — you learn to perform under actual exam conditions.\r\n\r\nYou will also have the option to access 15 full-length mock tests and 60 sectional tests created to simulate the 2026 TOEFL pattern, complete with AI-based scoring and feedback. This helps you track your progress, understand your strengths and weaknesses, and predict your final score accurately.\r\n(Mock &amp; sectional test pack available separately for ₹1700.)\r\n\r\nThroughout your learning journey, you’ll receive personal WhatsApp support, allowing you to clear doubts, stay motivated, and feel guided every step of the way. And with 1-year lifetime course access, you can learn at your pace — without stress.', 'fas fa-computer', 'uploads/courses/1767186734_20251028_1948_Modern TOEFL Course Card_simple_compose_01k8njvpmdfn4sws5f4yadprq6.png', 8, 1, 1, 'active', NULL, NULL, '2025-12-31 13:12:14', '2025-12-31 13:12:14'),
(10, 'CELPIP', 'celpip', 'Prepare for CELPIP with Prepwithdaljeet’s PR Preparation Program—HD lessons, PDFs, weekly doubt sessions &amp; WhatsApp support. Optional AI mock tests help you reach your CLB target with confidence.', 'Get fully prepared for CELPIP, the most trusted English test for Canada and Australia PR, with Prepwithdaljeet’s PR Preparation Program.\r\nThis course delivers everything you need — video lectures, practice PDFs, assignments, and weekly live doubt-clearing sessions — helping you build the confidence and strategies required to achieve your desired CLB score.\r\n\r\nStudents may also access 20 full-length mock tests and 120 sectional tests powered by AI evaluation to simulate the real exam experience.\r\n(Mock &amp; sectional test pack available for an additional ₹1200.)\r\n\r\n💰 Course Fee: ₹1299\r\n📅 Access: Lifetime (1 Year)\r\n🎯 Includes:\r\n\r\nHD Video Lessons (All Modules)\r\n\r\nPractice PDFs + Weekly Doubt Sessions\r\n\r\nWhatsApp Support\r\n\r\n(Optional) 20 Mock Tests + 120 Sectional Tests with AI Evaluation\r\n\r\nPrepare smarter, aim higher — and secure your PR dream for Canada or Australia!', 'fas fa-computer', 'uploads/courses/1767188393_20251028_1855_CELPIP Course Overview_simple_compose_01k8nft1wqek9tep1jb3we526n.png', 9, 0, 0, 'active', NULL, NULL, '2025-12-31 13:39:53', '2025-12-31 13:39:53');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(100) NOT NULL DEFAULT 'Prep with Daljeet',
  `tagline` varchar(200) DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `favicon_url` varchar(255) DEFAULT NULL,
  `primary_color` varchar(7) DEFAULT '#D4AF37',
  `secondary_color` varchar(7) DEFAULT '#000000',
  `accent_color` varchar(7) DEFAULT '#FFD700',
  `facebook_url` varchar(255) DEFAULT NULL,
  `instagram_url` varchar(255) DEFAULT NULL,
  `twitter_url` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `youtube_url` varchar(255) DEFAULT NULL,
  `show_facebook` tinyint(1) DEFAULT 1,
  `show_instagram` tinyint(1) DEFAULT 1,
  `show_twitter` tinyint(1) DEFAULT 1,
  `show_linkedin` tinyint(1) DEFAULT 1,
  `show_youtube` tinyint(1) DEFAULT 1,
  `primary_phone` varchar(20) NOT NULL,
  `secondary_phone` varchar(20) DEFAULT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `google_map_embed` text DEFAULT NULL,
  `enable_whatsapp_chat` tinyint(1) DEFAULT 1,
  `enable_newsletter` tinyint(1) DEFAULT 1,
  `admin_theme` enum('dark-gold','royal-purple','deep-blue','emerald-green') DEFAULT 'dark-gold',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `android_app_link` varchar(255) DEFAULT NULL,
  `ios_app_link` varchar(255) DEFAULT NULL,
  `ios_org_pass` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_name`, `tagline`, `logo_url`, `favicon_url`, `primary_color`, `secondary_color`, `accent_color`, `facebook_url`, `instagram_url`, `twitter_url`, `linkedin_url`, `youtube_url`, `show_facebook`, `show_instagram`, `show_twitter`, `show_linkedin`, `show_youtube`, `primary_phone`, `secondary_phone`, `whatsapp_number`, `email`, `address`, `google_map_embed`, `enable_whatsapp_chat`, `enable_newsletter`, `admin_theme`, `created_at`, `updated_at`, `android_app_link`, `ios_app_link`, `ios_org_pass`) VALUES
(1, 'Prep with Daljeet', 'Excellence', 'uploads/logos/1766835518_logo.png', 'uploads/favicons/1766836420_logo.png', '#fff5f5', '', '#ffe5e5', 'https://www.facebook.com/Prepwithdaljeet', 'https://www.instagram.com/prepwithdaljeet/', 'https://x.com/prepwithdaljeet', 'http://www.linkedin.com/in/Prepwithdaljeet', 'https://www.youtube.com/@PrepWithDaljeet', 1, 1, 1, 1, 1, '+91 9814927250', '', '+91 9814927250', 'director@prepwithdaljeet.com', 'PREPWITHDALJEET, Dilbagh Nagar, Jalandhar', '&lt;iframe src=&quot;https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13632.963622719488!2d75.53370014284597!3d31.324719368880587!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391a5ac6e632006b%3A0xad96747f7ed28ea!2sDilbagh%20Nagar%2C%20Jalandhar%2C%20Punjab%20144002!5e0!3m2!1sen!2sin!4v1766984289175!5m2!1sen!2sin&quot; width=&quot;600&quot; height=&quot;450&quot; style=&quot;border:0;&quot; allowfullscreen=&quot;&quot; loading=&quot;lazy&quot; referrerpolicy=&quot;no-referrer-when-downgrade&quot;&gt;&lt;/iframe&gt;', 1, 0, 'dark-gold', '2025-12-26 10:53:00', '2026-01-01 09:07:43', 'https://clphaward.page.link/xph7', 'https://apps.apple.com/lu/app/myinstitute/id1472483563', 'AHJCCT');

-- --------------------------------------------------------

--
-- Table structure for table `speaking_examples`
--

CREATE TABLE `speaking_examples` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `audio_file` varchar(255) DEFAULT NULL,
  `file_size` varchar(20) DEFAULT NULL,
  `duration` varchar(20) DEFAULT NULL,
  `transcript` text DEFAULT NULL,
  `level` enum('beginner','intermediate','advanced') DEFAULT 'intermediate',
  `tags` varchar(255) DEFAULT NULL,
  `download_count` int(11) DEFAULT 0,
  `view_count` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `status` enum('published','draft') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `duration_type` enum('hours','days','weeks','months') DEFAULT 'weeks',
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_price` decimal(12,2) DEFAULT NULL,
  `currency` varchar(3) DEFAULT 'INR',
  `description` text DEFAULT NULL,
  `highlights` text DEFAULT NULL,
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `what_you_learn` text DEFAULT NULL,
  `prerequisites` text DEFAULT NULL,
  `is_bestseller` tinyint(1) DEFAULT 0,
  `is_recommended` tinyint(1) DEFAULT 0,
  `display_order` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `course_id`, `subject_name`, `slug`, `duration`, `duration_type`, `price`, `discount_price`, `currency`, `description`, `highlights`, `features`, `what_you_learn`, `prerequisites`, `is_bestseller`, `is_recommended`, `display_order`, `status`, `created_at`, `updated_at`) VALUES
(3, 2, 'package', 'package', '2', 'hours', 4500.00, 1049.00, 'INR', 'Unlock your full IELTS potential with Prepwithdaljeet’s Complete Mastery Program. This course includes HD video lessons, practice PDFs, assignments, and weekly doubt-clearing sessions to help you excel in Listening, Reading, Writing, and Speaking.\r\nWith 1-year access and personal WhatsApp guidance, you can learn at your own pace while staying fully supported throughout your preparation journey.', '• HD Video Lessons (All 4 IELTS Modules)\r\n• Practice PDFs &amp; Assignments\r\n• Weekly Doubt-Clearing Sessions\r\n• WhatsApp Support &amp; Mentorship\r\n• Strategy-Focused Training\r\n• Learn Anytime, Anywhere', '[]', '• IELTS Academic &amp; General Test Format &amp; Strategies\r\n• Advanced Writing Task 1 &amp; Task 2 Techniques\r\n• Speaking Structure, Fluency &amp; Confidence\r\n• Listening &amp; Reading Accuracy Improvement\r\n• Time Management &amp; Score-Boosting Tips', 'Basic English-speaking and understanding skills. No prior IELTS test experience required.', 0, 1, 3, 'active', '2025-12-27 12:57:47', '2025-12-31 13:44:01'),
(4, 10, 'Listening, Reading, Writing, Speaking', 'listening-reading-writing-speaking', '12', 'months', 6500.00, 1299.00, 'INR', 'Prepare confidently for the CELPIP exam with Prepwithdaljeet’s PR Preparation Program — designed for Canada &amp; Australia immigration aspirants. This course includes HD video lessons, practice PDFs, assignments, and weekly doubt-clearing sessions to help you master Listening, Reading, Writing, and Speaking with real-exam strategies. With 12-month access and dedicated WhatsApp support, you’ll stay guided, motivated, and fully exam-ready at every step.', '• HD Video Lessons (All CELPIP Modules)\r\n• Practice PDFs &amp; Assignments\r\n• Weekly Live Doubt Sessions\r\n• WhatsApp Support &amp; Mentorship\r\n• Strategy-Based Training\r\n• 12-Month Full Access', '[]', '• CELPIP exam format &amp; scoring system\r\n• Speaking structure &amp; fluency improvement\r\n• Writing task frameworks &amp; templates\r\n• Listening &amp; Reading accuracy techniques\r\n• Time management &amp; exam strategy\r\n• Tips to achieve higher CLB band scores', 'Basic English communication skills. Suitable for first-time &amp; repeat test-takers.', 1, 1, 4, 'active', '2025-12-31 13:49:29', '2025-12-31 13:49:29');

-- --------------------------------------------------------

--
-- Table structure for table `video_resources`
--

CREATE TABLE `video_resources` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `platform` enum('youtube','vimeo','custom') DEFAULT 'youtube',
  `duration` varchar(20) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `view_count` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `status` enum('published','draft') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `video_resources`
--

INSERT INTO `video_resources` (`id`, `title`, `description`, `video_url`, `thumbnail_url`, `platform`, `duration`, `category`, `tags`, `view_count`, `is_featured`, `status`, `created_at`, `updated_at`) VALUES
(2, 'CELPIP', 'CELPIP Speaking Task 1: The Ultimate Guide to Giving Advice\r\n\r\nMastering the first task of the CELPIP Speaking test is all about fluency, structure, and tone. In this comprehensive tutorial, Daljeet Singh from PrepWithDaljeet breaks down everything you need to know to score a CLB 9+ in Task 1.\r\n\r\nWhat You Will Learn:\r\nThe Format: Understand the timing (30s preparation / 90s speaking) and how the task appears on the screen.\r\n\r\nThe &quot;Advice Tone&quot;: Why you should avoid giving orders and how to use phrases like &quot;I recommend...&quot; or &quot;I suggest...&quot; to sound natural.\r\n\r\nTemplate for Success: A step-by-step structure including a professional introduction, body points, and a smooth conclusion.\r\n\r\nThe &quot;Magic Strategy&quot;: What to do if you get a topic you know nothing about (The Pro-Con strategy).\r\n\r\nReal-Life Samples: Full model answers for common topics like learning a language, moving to a new city, and financial planning.\r\n\r\nVideo Breakdown:\r\n0:00 – Introduction to Task 1\r\n\r\n2:32 – Understanding the Test Screen &amp; Timing\r\n\r\n4:02 – Patterns &amp; Phrases for Advice\r\n\r\n7:51 – The Speaking Template\r\n\r\n10:45 – Strategy for Difficult/Unknown Topics\r\n\r\n15:28 – Sample Answer 1: Learning a Language\r\n\r\n18:48 – Sample Answer 2: Moving to a New City\r\n\r\n21:40 – Sample Answer 3: Solving Financial Problems\r\n\r\nKey Tip for Test Takers:\r\nAlways address the person in the prompt by name. It shows the examiner you have understood the context of the situation and helps you maintain a conversational flow!', 'https://youtu.be/IG1PjBvaVVU?si=_hXshEsuakIQwKDN', 'uploads/thumbnails/1767190412_Gemini_Generated_Image_6zsnli6zsnli6zsn.png', 'youtube', '24:46', 'CELPIP', 'CELPIP Speaking Task 1, Giving Advice CELPIP, PrepWithDaljeet, CELPIP Speaking Template, CELPIP Speaking Tips, CELPIP Speaking Practice, CELPIP Task 1 Sample Answer, CELPIP Speaking CLB 9, CELPIP Speaking 90 seconds, How to give advice CELPIP, Canadian Im', 0, 1, 'published', '2025-12-31 14:13:32', '2025-12-31 14:13:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_slug` (`slug`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_subject_status` (`subject_id`,`status`),
  ADD KEY `idx_independent` (`is_independent`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_recommended` (`is_recommended`);
ALTER TABLE `books` ADD FULLTEXT KEY `idx_search` (`title`,`author`,`description`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_status_order` (`status`,`sort_order`),
  ADD KEY `idx_featured` (`is_featured`),
  ADD KEY `idx_popular` (`is_popular`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `speaking_examples`
--
ALTER TABLE `speaking_examples`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_level_status` (`level`,`status`);
ALTER TABLE `speaking_examples` ADD FULLTEXT KEY `idx_search` (`title`,`description`,`transcript`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_course_status` (`course_id`,`status`),
  ADD KEY `idx_bestseller` (`is_bestseller`),
  ADD KEY `idx_recommended` (`is_recommended`);

--
-- Indexes for table `video_resources`
--
ALTER TABLE `video_resources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category_status` (`category`,`status`);
ALTER TABLE `video_resources` ADD FULLTEXT KEY `idx_search` (`title`,`description`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `speaking_examples`
--
ALTER TABLE `speaking_examples`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `video_resources`
--
ALTER TABLE `video_resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
