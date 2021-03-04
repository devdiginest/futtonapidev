-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 08, 2021 at 05:00 PM
-- Server version: 8.0.22
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `diginjfq_myfuttonapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` varchar(24) NOT NULL,
  `name` varchar(30) NOT NULL,
  `class_teacher` varchar(24) DEFAULT NULL,
  `status` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `name`, `class_teacher`, `status`, `created_at`, `updated_at`) VALUES
('HHPElG7Bv65UiQQPtgzXdHhs', 'Class 001', 'T4XHeu7WX1crhQV2n3hMwIkv', 'Active', '2020-11-24 15:55:19', '2020-12-28 12:30:57');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` varchar(24) NOT NULL,
  `name` varchar(75) NOT NULL,
  `category` varchar(20) NOT NULL,
  `validity` varchar(9) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `price` int NOT NULL,
  `course_provider` varchar(75) DEFAULT NULL,
  `short_desc` varchar(255) DEFAULT NULL,
  `long_desc` varchar(1000) DEFAULT NULL,
  `level` varchar(15) DEFAULT NULL,
  `overview_provider` varchar(15) DEFAULT NULL,
  `overview_url` varchar(75) DEFAULT NULL,
  `thumbnail` varchar(50) DEFAULT NULL,
  `content_provider` varchar(15) DEFAULT NULL,
  `status` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `name`, `category`, `validity`, `start_date`, `end_date`, `price`, `course_provider`, `short_desc`, `long_desc`, `level`, `overview_provider`, `overview_url`, `thumbnail`, `content_provider`, `status`, `created_at`, `updated_at`) VALUES
('9mxAGo0bXzEHON78R359XLDR', 'thermodynamics', 'GATE Civil', 'Limited', '2021-01-06', '2021-01-31', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Draft', '2021-01-07 07:42:30', '2021-01-07 07:42:30'),
('BvWMhTO0LzRWkwtjXpbNWiny', 'Course 001', 'GATE Civil', 'Limited', '2020-12-01', '2021-01-31', 40000, 'Rubix Academy', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Active', '2020-11-28 12:43:02', '2020-12-21 13:14:44'),
('qIgzvXMBafgDUtzWfeznrTN8', 'sample course', 'NEET', 'Unlimited', NULL, NULL, 10000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Draft', '2021-01-07 07:25:23', '2021-01-07 07:25:23'),
('umznhMUKY7V504rgPJxqUz8b', 'test', 'GATE Civil', 'Limited', '2020-12-01', '2020-12-31', 20000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Draft', '2020-12-21 13:16:00', '2020-12-21 13:16:00'),
('VLG4XSZYUa4eFn5csXZxjj3f', 'test5', 'GATE Civil', 'Unlimited', NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Draft', '2021-01-07 08:05:16', '2021-01-07 08:05:16'),
('Yfko12m3qdLlP34xqPUmwjY0', 'english', 'GATE Civil', 'Limited', '2020-12-29', '2021-01-02', 10000, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Draft', '2020-12-28 12:48:43', '2020-12-28 12:48:43'),
('ZfLOohwWDBDZzwR6QRyxZYcT', 'test2', 'GATE Mechanical', 'Limited', '2020-12-03', '2020-12-26', 99, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Draft', '2020-12-23 10:15:53', '2020-12-23 10:15:53'),
('ZTvSsJyKcc3kOwgJJJdNXbEk', 'thermodynamics', 'GATE Civil', 'Limited', '2021-01-06', '2021-01-31', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Draft', '2021-01-07 07:42:30', '2021-01-07 07:42:30');

-- --------------------------------------------------------

--
-- Table structure for table `course_lessons`
--

CREATE TABLE `course_lessons` (
  `id` int NOT NULL,
  `course` varchar(24) NOT NULL,
  `subject` varchar(24) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(1000) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `resource_url` varchar(100) NOT NULL,
  `resource_type` varchar(10) NOT NULL,
  `resource_provider` varchar(10) NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `course_lessons`
--

INSERT INTO `course_lessons` (`id`, `course`, `subject`, `name`, `description`, `resource_url`, `resource_type`, `resource_provider`, `status`, `created_at`) VALUES
(1, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 'Unit, dimension & General Maths', 'A dimension is a measure of a physical variable (without numerical values), while a unit is a way to assign a number or measurement to that dimension. ', 'https://www.youtube.com/watch?v=oZeOeAwLdPw', 'video', 'youtube', 0, '2020-12-27 07:04:54'),
(2, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 'Kinematics', 'Kinematics is the branch of classical mechanics that describes the motion of points, objects and systems of groups of objects, without reference to the causes of motion (i.e., forces ). The study of kinematics is often referred to as the “geometry of motion.” Objects are in motion all around us.', 'https://www.youtube.com/watch?v=guqO7kTtYj4', 'video', 'youtube', 0, '2020-12-27 07:08:42'),
(3, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 'General Physics', 'Physics is the natural science that studies matter, its motion and behavior through space and time, and the related entities of energy and force. Physics is one of the most fundamental scientific disciplines, and its main goal is to understand how the universe behaves.', 'https://www.youtube.com/watch?v=WL5_T-g3Fxw&t=66s', 'video', 'youtube', 0, '2020-12-27 07:10:55'),
(4, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 'Newton Law of Motion', 'In classical mechanics, Newton\'s laws of motion are three laws that describe the relationship between the motion of an object and the forces acting on it. The first law states that an object either remains at rest or continues to move at a constant velocity, unless it is acted upon by an external force. The second law states that the rate of change of momentum of an object is directly proportional to the force applied, or, for an object with constant mass, that the net force on an object is equal to the mass of that object multiplied by the acceleration. The third law states that when one object exerts a force on a second object, that second object exerts a force that is equal in magnitude and opposite in direction on the first object.', 'https://www.youtube.com/watch?v=ZvPrn3aBQG8', 'video', 'youtube', 0, '2020-12-27 07:14:03'),
(5, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 'Rotational Dynamics', 'We have defined the angular displacement, angular speed and angular velocity, angular acceleration, and kinetic energy of an object rotating about an axis.  These definitions apply to objects spinning about an internal axis, such as a wheel spinning on its axle, or to objects revolving around a point external to the objects, such as the earth revolving around the sun. A spinning or revolving object has angular velocity ω.  Whenever the magnitude or direction of this angular velocity changes, the object has angular acceleration α.', 'https://www.youtube.com/watch?v=yYysCEZHCro', 'video', 'youtube', 0, '2020-12-27 07:17:35'),
(6, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 'Rotational Dynamics', '', 'https://www.youtube.com/watch?v=yYysCEZHCro', 'video', 'youtube', 0, '2021-01-06 04:25:18'),
(7, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 'Rotational Dyna', '', 'https://www.youtube.com/watch?v=yYysCEZHCro', 'video', 'youtube', 0, '2021-01-06 05:33:08'),
(8, 'ZfLOohwWDBDZzwR6QRyxZYcT', 'Jd3kyosci1sCSyeOo9sX1c9P', 'Eng Mathematics', 'TEst Description', 'https://www.youtube.com/watch?v=yYysCEZHCro', 'video', 'youtube', 1, '2021-01-08 08:06:01'),
(9, 'VLG4XSZYUa4eFn5csXZxjj3f', 'CsmZTBvKilx2BUD9fkpEPnxY', '4', '', 'https://www.youtube.com/watch?v=yYysCEZHCro', 'video', 'youtube', 0, '2021-01-08 09:36:35');

-- --------------------------------------------------------

--
-- Table structure for table `course_live_classes`
--

CREATE TABLE `course_live_classes` (
  `id` int NOT NULL,
  `course` varchar(24) NOT NULL,
  `subject` varchar(24) NOT NULL,
  `lesson` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `duration` varchar(5) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `course_live_classes`
--

INSERT INTO `course_live_classes` (`id`, `course`, `subject`, `lesson`, `name`, `date`, `time`, `duration`, `created_at`, `updated_at`) VALUES
(1, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 'Live Class 001', '2020-12-28', '16:30:00', '01:30', '2020-11-28 13:17:43', '2020-12-27 21:56:22'),
(2, 'VLG4XSZYUa4eFn5csXZxjj3f', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 'something', '2021-01-01', '15:18:00', '17:18', '2021-01-08 09:48:24', '2021-01-08 09:48:24'),
(3, 'VLG4XSZYUa4eFn5csXZxjj3f', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 'something', '2021-01-01', '15:18:00', '17:18', '2021-01-08 09:48:24', '2021-01-08 09:48:24');

-- --------------------------------------------------------

--
-- Table structure for table `course_reviews`
--

CREATE TABLE `course_reviews` (
  `id` int NOT NULL,
  `student` varchar(24) NOT NULL,
  `course` varchar(24) NOT NULL,
  `rating` decimal(2,1) NOT NULL,
  `review` varchar(500) DEFAULT '',
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `course_reviews`
--

INSERT INTO `course_reviews` (`id`, `student`, `course`, `rating`, `review`, `date`) VALUES
(1, 'T4XHeu7WX1crhQV2n3hMwIkv', 'BvWMhTO0LzRWkwtjXpbNWiny', 4.2, 'Excellent Course', '2020-12-04');

-- --------------------------------------------------------

--
-- Table structure for table `course_subjects`
--

CREATE TABLE `course_subjects` (
  `course_id` varchar(24) NOT NULL,
  `subject_id` varchar(24) NOT NULL,
  `status` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `course_subjects`
--

INSERT INTO `course_subjects` (`course_id`, `subject_id`, `status`) VALUES
('BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 0),
('BvWMhTO0LzRWkwtjXpbNWiny', 'Jd3kyosci1sCSyeOo9sX1c9P', 0),
('BvWMhTO0LzRWkwtjXpbNWiny', '2n7jvMl6JvhMGGSpdr4JR06g', 0),
('BvWMhTO0LzRWkwtjXpbNWiny', 'BT8ZA8h8Uynvyj1GK0mUnEW7', 1);

-- --------------------------------------------------------

--
-- Table structure for table `exams_1`
--

CREATE TABLE `exams_1` (
  `id` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `display_order` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `exams_1`
--

INSERT INTO `exams_1` (`id`, `name`, `display_order`, `created_at`) VALUES
(1, 'Certification Course', 4, '2020-12-26 15:02:26'),
(2, 'Engineering', 1, '2020-12-26 15:02:26'),
(3, 'Entrance', 3, '2020-12-26 15:02:26'),
(4, 'Medicine', 2, '2020-12-26 15:02:26');

-- --------------------------------------------------------

--
-- Table structure for table `exams_2`
--

CREATE TABLE `exams_2` (
  `id` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `display_order` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `exams_2`
--

INSERT INTO `exams_2` (`id`, `name`, `display_order`, `created_at`) VALUES
(1, 'GATE', 1, '2020-12-26 15:02:26'),
(2, 'Technical Exams', 2, '2020-12-26 15:02:26');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `display_order` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`, `display_order`, `created_at`) VALUES
(1, 'English', 1, '2020-12-26 15:02:26'),
(2, 'Hindi', 2, '2020-12-26 15:02:26');

-- --------------------------------------------------------

--
-- Table structure for table `mcq_options`
--

CREATE TABLE `mcq_options` (
  `id` int NOT NULL,
  `question` int NOT NULL,
  `option` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mcq_options`
--

INSERT INTO `mcq_options` (`id`, `question`, `option`, `created_at`, `updated_at`) VALUES
(1, 3, 'Option 01', '2020-11-29 04:26:33', '2020-11-29 04:26:33'),
(2, 3, 'Option 02', '2020-11-29 04:26:33', '2020-11-29 04:26:33'),
(3, 3, 'Option 03', '2020-11-29 04:26:33', '2020-11-29 04:26:33'),
(4, 3, 'Option 04', '2020-11-29 04:26:33', '2020-11-29 04:26:33'),
(5, 13, 'Option 01', '2020-11-29 18:31:03', '2020-12-26 01:10:03'),
(6, 13, 'Option 02', '2020-11-29 18:31:03', '2020-12-26 01:10:03'),
(7, 13, 'Option 03', '2020-11-29 18:31:03', '2020-12-26 01:10:03'),
(8, 13, 'Option 04', '2020-11-29 18:31:03', '2020-12-26 01:10:03'),
(13, 14, 'Option 01', '2020-12-18 17:19:55', '2020-12-26 01:11:47'),
(14, 14, 'Option 02', '2020-12-18 17:23:31', '2020-12-26 01:11:47'),
(15, 14, 'Option 03', '2020-12-18 17:23:31', '2020-12-26 01:11:47'),
(16, 14, 'Option 04', '2020-12-18 17:23:31', '2020-12-26 01:11:47'),
(19, 17, 'Option 01', '2020-12-21 16:06:25', '2020-12-26 01:12:57'),
(20, 17, 'Option 02', '2020-12-21 16:06:25', '2020-12-26 01:12:57'),
(21, 17, 'Option 03', '2020-12-21 16:06:25', '2020-12-26 01:12:57'),
(22, 17, 'Option 04', '2020-12-21 16:06:25', '2020-12-26 01:12:57'),
(30, 18, 'Option 01', '2020-12-21 16:06:25', '2020-12-26 01:13:54'),
(31, 18, 'Option 02', '2020-12-21 16:06:25', '2020-12-26 01:13:54'),
(32, 18, 'Option 03', '2020-12-22 17:25:06', '2020-12-26 01:13:54'),
(33, 18, 'Option 04', '2020-12-22 17:25:06', '2020-12-26 01:13:54'),
(34, 19, 'B', '2020-12-28 12:38:58', '2020-12-28 12:38:58'),
(35, 19, 'C', '2020-12-28 12:38:58', '2020-12-28 12:38:58'),
(36, 19, '', '2020-12-28 12:38:58', '2020-12-28 12:38:58'),
(39, 22, 'e', '2021-01-07 11:28:19', '2021-01-07 11:28:19'),
(40, 22, 'loge', '2021-01-07 11:28:19', '2021-01-07 11:28:19'),
(41, 23, '', '2021-01-07 11:49:57', '2021-01-07 11:49:57'),
(42, 23, '', '2021-01-07 11:49:57', '2021-01-07 11:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user` varchar(24) NOT NULL,
  `title` varchar(30) NOT NULL,
  `msg` varchar(255) NOT NULL,
  `received_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user`, `title`, `msg`, `received_date`) VALUES
(1, 'T4XHeu7WX1crhQV2n3hMwIkv', 'Notification Title 01', 'Notification Message 01', '2020-12-04'),
(2, 'T4XHeu7WX1crhQV2n3hMwIkv', 'Notification Title 02', 'Notification Message 02', '2020-12-04'),
(3, 'rN7h2InJlYyBfENBR1svq9DW', 'Notification Title 01', 'Notification Message 01', '2020-12-04'),
(4, 'rN7h2InJlYyBfENBR1svq9DW', 'Notification Title 02', 'Notification Message 02', '2020-12-04'),
(5, 'rN7h2InJlYyBfENBR1svq9DW', 'Notification Title 03', 'Notification Message 03', '2020-12-04');

-- --------------------------------------------------------

--
-- Table structure for table `notifications_admin`
--

CREATE TABLE `notifications_admin` (
  `id` int NOT NULL,
  `user` varchar(24) NOT NULL,
  `title` varchar(30) NOT NULL,
  `msg` varchar(255) NOT NULL,
  `received_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `notifications_admin`
--

INSERT INTO `notifications_admin` (`id`, `user`, `title`, `msg`, `received_date`) VALUES
(1, 'T4XHeu7WX1crhQV2n3hMwIkv', 'Notification Title 01', 'Notification Message 01', '2020-12-04'),
(2, 'T4XHeu7WX1crhQV2n3hMwIkv', 'Notification Title 02', 'Notification Message 02', '2020-12-04');

-- --------------------------------------------------------

--
-- Table structure for table `notifications_student`
--

CREATE TABLE `notifications_student` (
  `id` int NOT NULL,
  `user` varchar(24) NOT NULL,
  `title` varchar(30) NOT NULL,
  `msg` varchar(255) NOT NULL,
  `received_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int NOT NULL,
  `student_id` varchar(24) NOT NULL,
  `course_id` varchar(24) NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `receipt` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `order_id` varchar(30) NOT NULL,
  `status` varchar(15) DEFAULT NULL,
  `payment_id` varchar(25) DEFAULT NULL,
  `signature` varchar(70) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `student_id`, `course_id`, `amount`, `receipt`, `order_id`, `status`, `payment_id`, `signature`, `created_at`) VALUES
(2, 'rN7h2InJlYyBfENBR1svq9DW', 'BvWMhTO0LzRWkwtjXpbNWiny', 40000.00, 'FREC-rN7h2InJlYyBfENBR1svq9DW-1608868755', 'order_GH8HugcaxAFNnt', 'paid', 'pay_GH8ItNosubJiRo', 'deff2d6de7595a7198658d63851054273b1e09e1ff68482d9d388333005f6223', '2020-12-25 03:59:17'),
(3, 'rN7h2InJlYyBfENBR1svq9DW', 'BvWMhTO0LzRWkwtjXpbNWiny', 40000.00, 'FREC-rN7h2InJlYyBfENBR1svq9DW-1608985437', 'order_GHfQ9XhVQzjSCQ', 'created', NULL, NULL, '2020-12-26 12:23:58'),
(4, 'rN7h2InJlYyBfENBR1svq9DW', 'BvWMhTO0LzRWkwtjXpbNWiny', 40000.00, 'FREC-rN7h2InJlYyBfENBR1svq9DW-1609070209', 'order_GI3Uccj4OlqlZp', 'created', NULL, NULL, '2020-12-27 11:56:51'),
(5, 'rN7h2InJlYyBfENBR1svq9DW', 'BvWMhTO0LzRWkwtjXpbNWiny', 40000.00, 'FREC-rN7h2InJlYyBfENBR1svq9DW-1609071761', 'order_GI3vwedZDaGVep', 'created', NULL, NULL, '2020-12-27 12:22:43'),
(6, 'rN7h2InJlYyBfENBR1svq9DW', 'BvWMhTO0LzRWkwtjXpbNWiny', 40000.00, 'FREC-rN7h2InJlYyBfENBR1svq9DW-1609071803', 'order_GI3wgPCr09BQzu', 'created', NULL, NULL, '2020-12-27 12:23:24'),
(7, 'wZvawpvrATuuw1U5dB6GPOkK', 'BvWMhTO0LzRWkwtjXpbNWiny', 40000.00, 'FREC-wZvawpvrATuuw1U5dB6GPOkK-1609154082', 'order_GIRJF4sYVXexqK', 'created', NULL, NULL, '2020-12-28 11:14:43'),
(8, 'wZvawpvrATuuw1U5dB6GPOkK', 'Yfko12m3qdLlP34xqPUmwjY0', 10000.00, 'FREC-wZvawpvrATuuw1U5dB6GPOkK-1609310428', 'order_GJ9ho52w4IbXXw', 'created', NULL, NULL, '2020-12-30 06:40:29');

-- --------------------------------------------------------

--
-- Table structure for table `preferences`
--

CREATE TABLE `preferences` (
  `id` int NOT NULL,
  `student_id` varchar(24) NOT NULL,
  `exam1_id` int NOT NULL,
  `exam2_id` int NOT NULL,
  `language_id` int NOT NULL,
  `stream_id` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `preferences`
--

INSERT INTO `preferences` (`id`, `student_id`, `exam1_id`, `exam2_id`, `language_id`, `stream_id`, `created_at`) VALUES
(1, 'rN7h2InJlYyBfENBR1svq9DW', 1, 1, 1, 1, '2020-12-26 17:30:47'),
(2, '48SXRn58mzRrYjA1RF2MhHLA', 2, 1, 1, 1, '2020-12-26 19:27:47'),
(3, 'MPKm2PTFpxd811uKitLs9PQK', 4, 2, 1, 2, '2020-12-28 09:40:02'),
(4, 'L1sI0hWunEYQyxzXd6N2ATuw', 1, 1, 2, 2, '2020-12-28 09:41:58'),
(5, 'XIA4uQCdyeZfVvha3uyh4gjW', 2, 1, 1, 1, '2020-12-28 10:41:36'),
(6, 'pbVjpUlEDwLJRN4fDub0c9wZ', 2, 1, 1, 2, '2020-12-28 11:37:49'),
(7, 'uNOGOeZ9myedc84JzY6HVx3N', 2, 1, 1, 1, '2020-12-29 09:55:43'),
(8, 'Otvr8O7rAuJIEzdDW1axvdjL', 2, 1, 1, 1, '2020-12-30 12:54:49');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` varchar(24) NOT NULL,
  `name` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `name`, `created_at`, `updated_at`) VALUES
('CsmZTBvKilx2BUD9fkpEPnxY', 'SuperAdmin', '2020-11-23 14:47:31', '2020-11-23 14:47:31'),
('eZaa8CJEnQYal9XWaqoCpVSF', 'Admin', '2020-11-23 14:47:31', '2020-11-23 14:47:31'),
('HpQ8T868WqcZnETcjUe54K2Z', 'Student', '2020-11-23 14:47:31', '2020-11-23 14:47:31'),
('Jd3kyosci1sCSyeOo9sX1c9P', 'Teacher', '2020-11-23 14:47:31', '2020-11-23 14:47:31');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int NOT NULL,
  `question` varchar(1000) NOT NULL,
  `type` varchar(1) NOT NULL,
  `subject` varchar(24) NOT NULL,
  `lesson` int NOT NULL,
  `correct_opt` int NOT NULL,
  `answer` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `question`, `type`, `subject`, `lesson`, `correct_opt`, `answer`, `created_at`, `updated_at`) VALUES
(3, 'Physics Question 001', '1', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 2, '', '2020-11-29 04:26:33', '2020-11-29 04:41:07'),
(4, 'Physics Question 002', '2', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 0, '', '2020-11-29 18:31:03', '2020-12-05 11:27:54'),
(8, 'Physics Question 003', '2', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 0, 'Qus 003 Answer', '2020-12-18 16:20:45', '2020-12-26 00:58:02'),
(9, 'Physics Question 004', '2', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 0, 'Qus 004 Answer', '2020-12-18 16:27:02', '2020-12-26 00:58:21'),
(10, 'Physics Question 005', '2', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 0, 'Qus 005 Answer', '2020-12-18 16:28:43', '2020-12-26 00:58:29'),
(11, 'Physics Question 006', '2', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 0, 'Qus 006 Answer', '2020-12-18 16:36:15', '2020-12-26 00:58:36'),
(12, 'Physics Question 007', '3', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 0, '2.7,4.3', '2020-12-18 16:43:46', '2020-12-26 00:58:56'),
(13, 'Physics Question 008', '1', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 1, '', '2020-12-18 17:11:29', '2020-12-26 00:56:22'),
(14, 'Physics Question 009', '1', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 1, '', '2020-12-18 17:19:55', '2020-12-26 00:56:28'),
(15, 'Physics Question 010', '3', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 0, '4.5,5.0', '2020-12-18 17:23:31', '2020-12-26 00:59:27'),
(17, 'Physics Question 011', '1', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 2, '', '2020-12-21 16:06:25', '2020-12-26 00:56:44'),
(18, 'What are the 3 states of matter?', '1', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 3, '', '2020-12-22 17:25:06', '2020-12-23 14:15:54'),
(19, 'What is A?', '1', 'CsmZTBvKilx2BUD9fkpEPnxY', 4, 2, '', '2020-12-28 12:38:58', '2021-01-07 10:24:06'),
(21, 'unit of power', '3', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 0, '', '2021-01-07 10:49:46', '2021-01-07 10:49:46'),
(22, 'ques', '1', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 1, '', '2021-01-07 11:28:19', '2021-01-07 11:28:19'),
(23, 'qqq', '0', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 0, '', '2021-01-07 11:49:57', '2021-01-07 11:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `settings_payg`
--

CREATE TABLE `settings_payg` (
  `provider` varchar(15) NOT NULL,
  `mode` varchar(10) NOT NULL,
  `api_key` varchar(64) NOT NULL,
  `auth_token` varchar(64) NOT NULL,
  `salt` varchar(64) NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_payg`
--

INSERT INTO `settings_payg` (`provider`, `mode`, `api_key`, `auth_token`, `salt`, `updated_at`) VALUES
('', '', '', '', '', '2020-11-29 09:55:49');

-- --------------------------------------------------------

--
-- Table structure for table `settings_smsg`
--

CREATE TABLE `settings_smsg` (
  `provider` varchar(15) NOT NULL,
  `mode` varchar(10) NOT NULL,
  `api_key` varchar(64) NOT NULL,
  `auth_token` varchar(64) NOT NULL,
  `salt` varchar(64) NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_smsg`
--

INSERT INTO `settings_smsg` (`provider`, `mode`, `api_key`, `auth_token`, `salt`, `updated_at`) VALUES
('Instamojo', 'Test', 'ajhgdf765asdhjagsjdhta7t', 'zkxhv6aebzmcxnjt8u4r7ter', 'cuyte7fr57123578', '2020-11-29 11:08:24');

-- --------------------------------------------------------

--
-- Table structure for table `settings_smtp`
--

CREATE TABLE `settings_smtp` (
  `protocol` varchar(5) NOT NULL,
  `port` varchar(5) NOT NULL,
  `host` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings_smtp`
--

INSERT INTO `settings_smtp` (`protocol`, `port`, `host`, `username`, `password`, `updated_at`) VALUES
('TLS', '547', 'smtp.gmail.com', 'username', 'password', '2020-11-29 11:03:11');

-- --------------------------------------------------------

--
-- Table structure for table `streams`
--

CREATE TABLE `streams` (
  `id` int NOT NULL,
  `name` varchar(30) NOT NULL,
  `display_order` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `streams`
--

INSERT INTO `streams` (`id`, `name`, `display_order`, `created_at`) VALUES
(1, 'Civil', 1, '2020-12-26 15:02:27'),
(2, 'Mechanical', 2, '2020-12-26 15:02:27');

-- --------------------------------------------------------

--
-- Table structure for table `student_courses`
--

CREATE TABLE `student_courses` (
  `student_id` varchar(24) NOT NULL,
  `course_id` varchar(24) NOT NULL,
  `course_progress` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `student_courses`
--

INSERT INTO `student_courses` (`student_id`, `course_id`, `course_progress`) VALUES
('NpEVbl1TDbeprIRh7vdLfQR8', 'BvWMhTO0LzRWkwtjXpbNWiny', 0),
('dy7q95b8G19KD0jx9V67YDtE', 'BvWMhTO0LzRWkwtjXpbNWiny', 0);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` varchar(24) NOT NULL,
  `name` varchar(50) NOT NULL,
  `status` varchar(15) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
('2n7jvMl6JvhMGGSpdr4JR06g', 'Engineering Mathematics', 'Active', '2021-01-04 04:49:46', '2021-01-04 04:49:46'),
('4Mq6caUL20hTBEPSY9e7k8Ph', 'Chemistry', 'Active', '2021-01-08 10:10:42', '2021-01-08 10:10:54'),
('BT8ZA8h8Uynvyj1GK0mUnEW7', 'Biology', 'Active', '2020-11-24 12:27:36', '2020-11-24 12:27:34'),
('CsmZTBvKilx2BUD9fkpEPnxY', 'Physics', 'Active', '2020-11-23 14:53:44', '2020-11-23 14:53:44'),
('Jd3kyosci1sCSyeOo9sX1c9P', 'Mathematics', 'Active', '2020-11-23 14:53:44', '2021-01-04 04:52:48'),
('O5R9xjLyUngFTYy4oUobfUya', 'English 01', 'Inactive', '2020-11-24 12:28:45', '2020-11-24 15:16:16'),
('w8K3Ixdj2OMlPpX1ClGKzDyS', 'Computer', 'Active', '2021-01-05 22:29:21', '2021-01-05 22:29:21');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_subjects`
--

CREATE TABLE `teacher_subjects` (
  `teacher_id` varchar(24) NOT NULL,
  `subject_id` varchar(24) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testorquiz_questions`
--

CREATE TABLE `testorquiz_questions` (
  `testorquiz_id` varchar(24) NOT NULL,
  `question_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `testorquiz_questions`
--

INSERT INTO `testorquiz_questions` (`testorquiz_id`, `question_id`) VALUES
('3GONDEjSbC3xJkzhX7ACrzig', 3),
('3GONDEjSbC3xJkzhX7ACrzig', 13),
('3GONDEjSbC3xJkzhX7ACrzig', 14),
('3GONDEjSbC3xJkzhX7ACrzig', 17),
('3GONDEjSbC3xJkzhX7ACrzig', 18);

-- --------------------------------------------------------

--
-- Table structure for table `tests_n_quizzes`
--

CREATE TABLE `tests_n_quizzes` (
  `id` varchar(24) NOT NULL,
  `type` varchar(1) NOT NULL,
  `title` varchar(100) NOT NULL,
  `exam_type` varchar(1) NOT NULL,
  `qus_count` int NOT NULL,
  `course` varchar(24) NOT NULL,
  `subject` varchar(24) DEFAULT '',
  `lesson` int DEFAULT '0',
  `validity` varchar(9) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tests_n_quizzes`
--

INSERT INTO `tests_n_quizzes` (`id`, `type`, `title`, `exam_type`, `qus_count`, `course`, `subject`, `lesson`, `validity`, `start_date`, `end_date`, `created_at`, `updated_at`) VALUES
('3GONDEjSbC3xJkzhX7ACrzig', '2', 'Quiz 26-12-2020', '1', 5, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 'Limited', '2020-12-16', '2020-12-25', '2020-12-23 13:30:59', '2020-12-26 01:03:47'),
('CZg0ceunmXEhdSS5jPwM2ZcR', '1', 'test5', '1', 2, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 'Limited', '2020-12-15', '2020-12-31', '2020-12-24 07:51:08', '2020-12-24 07:51:08'),
('HOsm3uJKLguwSwig1enCWNQ0', '1', 'Test 0001', '1', 50, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 'Limited', '2020-12-26', '2020-12-28', '2020-12-23 08:45:53', '2020-12-23 08:45:53'),
('hR4uIqEYGt3t42XpSry9bGgQ', '1', 'test4 ', '3', 2, 'ZfLOohwWDBDZzwR6QRyxZYcT', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 'Limited', '2020-12-08', '2020-12-25', '2020-12-24 07:14:52', '2020-12-24 07:14:52'),
('LntnZRgB3AwmczeD5tNhMgb6', '2', 'quiz test', '1', 2, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 'Limited', '2020-12-16', '2020-12-25', '2020-12-23 13:30:59', '2020-12-23 13:30:59'),
('mIQLCvs91DwjraKUAcUeUMpD', '1', 'test basil', '1', 5, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 'Limited', '2020-12-16', '2020-12-30', '2020-12-23 12:36:16', '2020-12-23 12:36:16'),
('PjD4S5st6uKMwKfmKoV2t8wn', '1', 'Biology', '1', 20, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 4, 'Limited', '2020-12-29', '2021-01-02', '2020-12-28 12:45:32', '2020-12-28 12:45:32'),
('qyYG59OhENmn3ip1zhS6z8h5', '2', 'new', '2', 29, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 2, 'Limited', '2020-12-30', '2020-12-31', '2020-12-28 12:46:46', '2020-12-28 12:46:46'),
('tbhEnbhLs9gyeSa1uxDeeq2L', '1', 'test 3', '1', 3, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 1, 'Limited', '2020-12-15', '2020-12-31', '2020-12-24 04:55:56', '2020-12-24 04:55:56'),
('UclME4lR5lQtaD1zv0V5iuMn', '2', 'new', '2', 29, 'BvWMhTO0LzRWkwtjXpbNWiny', 'CsmZTBvKilx2BUD9fkpEPnxY', 2, 'Limited', '2020-12-30', '2020-12-31', '2020-12-28 12:47:35', '2020-12-28 12:47:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(24) NOT NULL,
  `profile` varchar(24) NOT NULL,
  `name` varchar(30) NOT NULL,
  `mobile_no` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `joining_date` date DEFAULT NULL,
  `subject` varchar(24) DEFAULT NULL,
  `status` varchar(15) NOT NULL,
  `otp` varchar(4) DEFAULT '',
  `is_verified` tinyint NOT NULL DEFAULT '0',
  `token_resetpwd` varchar(24) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `profile`, `name`, `mobile_no`, `email`, `password`, `joining_date`, `subject`, `status`, `otp`, `is_verified`, `token_resetpwd`, `created_at`, `updated_at`) VALUES
('0Hg9xQ2T8htGgF6MtltaQIVU', 'HpQ8T868WqcZnETcjUe54K2Z', 'Jitheesh Kumar', '3456789016', 'jithu.k@gmail.com', '$2y$10$m/5Nxs8Jfxt6Z6sbcxsAY.3NU7kj4qvucn.jOSvX1uY3Wl3xyu25e', NULL, NULL, 'Active', '3689', 0, '', '2020-12-10 18:45:59', '2020-12-10 18:45:59'),
('48SXRn58mzRrYjA1RF2MhHLA', 'HpQ8T868WqcZnETcjUe54K2Z', 'Hari', '9495152925', 'hari@yahoo.co.in', '$2y$10$wgR6DtaweETrVw1E74Z7g.JdOxUkTTQu9zD5Q8IomCaubmjDJnqzK', NULL, NULL, 'Active', '', 1, '', '2020-12-26 19:27:47', '2020-12-26 19:28:40'),
('4yVdy1DToHejzhbISrJKHLHt', 'Jd3kyosci1sCSyeOo9sX1c9P', 'basil kurian', '7907338532', 'basillll@test.com', '$2y$10$vZz3QN/fX85SYDq8Z2OareFceKSQ3lFdky.HsqFGWGhZXKhmEyd/W', '2021-01-20', '4Mq6caUL20hTBEPSY9e7k8Ph', 'Active', '', 0, '', '2021-01-08 10:13:10', '2021-01-08 10:13:10'),
('DBxLR8K4Ac2YZE2FwECwJBi5', 'HpQ8T868WqcZnETcjUe54K2Z', 'Rajeesh V R', '3456789013', 'rajeesh.vr@gmail.com', '$2y$10$qdQ/mdkfEvR7wfSJklEifeXZMTkEjyUqsxdB20lwELwLEfo.vRP7O', NULL, NULL, 'Active', '7352', 0, '', '2020-12-10 18:40:50', '2020-12-10 18:40:50'),
('dy7q95b8G19KD0jx9V67YDtE', 'HpQ8T868WqcZnETcjUe54K2Z', 'Yedhu K', '3456789015', 'y.edhu@gmail.com', '$2y$10$yuGl.7xDJ/wJa0ceFNrUJObwqqyoZLdQ/EIo.vp.MK/2QZeyfBXCG', NULL, NULL, 'Active', '2482', 0, '', '2020-12-10 18:42:16', '2020-12-10 18:42:16'),
('HpQ8T868WqcZnETcjUe54K2Z', 'eZaa8CJEnQYal9XWaqoCpVSF', 'Futton Admin', '8888888888', 'futtonadmin@gmail.com', '$2y$10$V6C1RKmaEFuo.OqezJc2eOVtixUdFlDO4WRbVMWLr1lr6hLaiLOrm', NULL, NULL, 'Active', NULL, 1, NULL, '2020-11-23 15:03:56', '2020-12-08 15:40:53'),
('Jd3kyosci1sCSyeOo9sX1c9P', 'CsmZTBvKilx2BUD9fkpEPnxY', 'Futton SU', '9999999999', 'futtonsu@gmail.com', '$2y$10$V6C1RKmaEFuo.OqezJc2eOVtixUdFlDO4WRbVMWLr1lr6hLaiLOrm', NULL, NULL, 'Active', NULL, 1, NULL, '2020-11-23 15:03:56', '2020-11-23 15:08:02'),
('L1sI0hWunEYQyxzXd6N2ATuw', 'HpQ8T868WqcZnETcjUe54K2Z', 'arya', '9088888888', 'arya@got.com', '$2y$10$fV.cbHld4Qusb0SmzqrvNe0KxbuY27ixQ4A.YC08Ma2K8K2im8bXi', NULL, NULL, 'Active', '9470', 0, '', '2020-12-28 09:41:55', '2020-12-28 09:41:55'),
('MPKm2PTFpxd811uKitLs9PQK', 'HpQ8T868WqcZnETcjUe54K2Z', 'John Snow', '9800989889', 'john@gmail.com', '$2y$10$xvhpMZKobNThBg30dxymfeXaZme7TjKca4V2z3/somhlwnr4MwCLq', NULL, NULL, 'Active', '8692', 0, '', '2020-12-28 09:40:00', '2020-12-28 09:40:00'),
('NpEVbl1TDbeprIRh7vdLfQR8', 'HpQ8T868WqcZnETcjUe54K2Z', 'Abhilash Sony', '3456789014', 'abhilash.so@gmail.com', '$2y$10$GuJDJH9BEuWIWI.VbMhzBe9fqGaCfLtYvSnSX/j8PoI6zj.kALhf2', NULL, NULL, 'Inctive', '7711', 0, '', '2020-12-10 18:41:17', '2020-12-10 19:04:03'),
('Otvr8O7rAuJIEzdDW1axvdjL', 'HpQ8T868WqcZnETcjUe54K2Z', 'Basil', '7977658997', 'basilkurian.ka@gmail.com', '$2y$10$eccDrTs9iq5WstRgcqySVONToEfAVOIy7/pxu58i0semxhQeNWtoO', NULL, NULL, 'Active', '8642', 0, '', '2020-12-30 12:54:49', '2020-12-30 12:54:49'),
('pbVjpUlEDwLJRN4fDub0c9wZ', 'HpQ8T868WqcZnETcjUe54K2Z', 'Cleemis', '9447427512', 'cleemisjohnbabu17@gmail.com', '$2y$10$zK8WuD4O8j6sfLO9H/BPo.5x09h6kDYou7EgVzPB2vcMHDtGLjx7W', NULL, NULL, 'Active', '3441', 0, '', '2020-12-28 11:37:48', '2020-12-28 12:36:58'),
('rN7h2InJlYyBfENBR1svq9DW', 'HpQ8T868WqcZnETcjUe54K2Z', 'Some Student', '3456789022', 'some.student@yahoo.com', '$2y$10$W4BPardE6z23ezEK0DLgSOgEIBXXuiSJAhSaZhi.YV13GlePsB3YC', NULL, NULL, 'Active', '', 1, '', '2020-12-10 19:24:17', '2020-12-10 23:14:10'),
('T4XHeu7WX1crhQV2n3hMwIke', 'HpQ8T868WqcZnETcjUe54K2Z', 'Hari K', '9495045015', 'hari.hari1020@gmail.com', '$2y$10$W3fx/zUb72W1JySeVOofQuOMl7f8ByZl0ETTiPY53Gwum0oylHP5a', NULL, NULL, 'Active', '3699', 0, '', '2020-12-04 18:15:13', '2020-12-10 18:45:09'),
('T4XHeu7WX1crhQV2n3hMwIkv', 'Jd3kyosci1sCSyeOo9sX1c9P', 'Jayakrishnan A S', '9447328144', 'jkampadi@gmail.com', '$2y$10$zm16XVG09HLhdqj3sjwTAO4vaVXlIfaaMqi9/rqfG/MYm75LJ5ARe', '2020-11-02', 'CsmZTBvKilx2BUD9fkpEPnxY', 'Active', NULL, 1, NULL, '2020-12-04 17:14:52', '2021-01-07 10:37:24'),
('uNOGOeZ9myedc84JzY6HVx3N', 'HpQ8T868WqcZnETcjUe54K2Z', 'Rahul', '9633553574', 'rahulkrishna921@gmail.com', '$2y$10$bvTU1zuTERT4GDC6qogpLufsP01SM1erIZlDrYel7.v0kwSMthAim', NULL, NULL, 'Active', '3082', 0, '', '2020-12-29 09:55:43', '2020-12-29 09:55:43'),
('V0Tvf3T38Ox1uWpMdjJTlx6H', 'Jd3kyosci1sCSyeOo9sX1c9P', 'Harikrishnan A S', '9495045015', 'contrastinc2016@gmail.com', '$2y$10$NNpKmSJGQHO5sqFaAcsexOLjdFjjt5ElMvsOgNJMBbrClMFnSRVBe', '2020-11-01', 'CsmZTBvKilx2BUD9fkpEPnxY', 'Active', NULL, 1, NULL, '2020-11-23 16:27:37', '2021-01-07 09:29:41'),
('wZvawpvrATuuw1U5dB6GPOkK', 'HpQ8T868WqcZnETcjUe54K2Z', 'Aswin', '9400582772', 'aswin.tk12@gmail.com', '$2y$10$0w0WgrYZ.JrCpg49PaT.h.lrQSHKQ2rHHS3j2JnUJswuwVopou0nK', NULL, NULL, 'Active', '5524', 0, 'hvvcHswns2Zjixf2UXRyEUaO', '2020-12-17 12:03:58', '2020-12-22 12:11:25'),
('XIA4uQCdyeZfVvha3uyh4gjW', 'HpQ8T868WqcZnETcjUe54K2Z', 'Adarsh ajith', '9995661468', 'Adarsh20195@gmail.com', '$2y$10$MgZMewJz6YcWU//lLigvleiN51h9xp9PzHPALzjq2WAoqsc7.LYpi', NULL, NULL, 'Active', '4306', 0, '', '2020-12-28 10:41:35', '2020-12-28 10:41:35'),
('YCJa8dVNf3fYv71tZozQFnnZ', 'HpQ8T868WqcZnETcjUe54K2Z', 'Uday A V', '3456789012', 'uday.av@gmail.com', '$2y$10$crJgRAjZgad9HQq0D517LeENjf8OmS25WrPCSqXE2115/Ax0Sj/O2', NULL, NULL, 'Active', '8821', 0, '', '2020-12-10 18:40:01', '2020-12-10 18:40:00');

-- --------------------------------------------------------

--
-- Table structure for table `users_admin`
--

CREATE TABLE `users_admin` (
  `id` varchar(24) NOT NULL,
  `profile` varchar(24) NOT NULL,
  `name` varchar(30) NOT NULL,
  `mobile_no` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `joining_date` date DEFAULT NULL,
  `subject` varchar(24) DEFAULT NULL,
  `status` varchar(15) NOT NULL,
  `token_resetpwd` varchar(24) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_admin`
--

INSERT INTO `users_admin` (`id`, `profile`, `name`, `mobile_no`, `email`, `password`, `joining_date`, `subject`, `status`, `token_resetpwd`, `created_at`, `updated_at`) VALUES
('HpQ8T868WqcZnETcjUe54K2Z', 'eZaa8CJEnQYal9XWaqoCpVSF', 'Futton Admin', '8888888888', 'futtonadmin@gmail.com', '$2y$10$V6C1RKmaEFuo.OqezJc2eOVtixUdFlDO4WRbVMWLr1lr6hLaiLOrm', NULL, NULL, 'Active', '', '2020-11-23 15:03:56', '2020-12-08 15:40:53'),
('Jd3kyosci1sCSyeOo9sX1c9P', 'CsmZTBvKilx2BUD9fkpEPnxY', 'Futton SU', '9999999999', 'futtonsu@gmail.com', '$2y$10$V6C1RKmaEFuo.OqezJc2eOVtixUdFlDO4WRbVMWLr1lr6hLaiLOrm', NULL, NULL, 'Active', '', '2020-11-23 15:03:56', '2020-11-23 15:08:02'),
('T4XHeu7WX1crhQV2n3hMwIkv', 'Jd3kyosci1sCSyeOo9sX1c9P', 'Jayakrishnan A S', '9447328144', 'jkampadi@gmail.com', '$2y$10$zm16XVG09HLhdqj3sjwTAO4vaVXlIfaaMqi9/rqfG/MYm75LJ5ARe', '2020-11-02', 'CsmZTBvKilx2BUD9fkpEPnxY', 'Active', '', '2020-12-04 17:14:52', '2020-12-12 18:37:26'),
('V0Tvf3T38Ox1uWpMdjJTlx6H', 'Jd3kyosci1sCSyeOo9sX1c9P', 'Harikrishnan A S', '9495045015', 'contrastinc2016@gmail.com', '$2y$10$NNpKmSJGQHO5sqFaAcsexOLjdFjjt5ElMvsOgNJMBbrClMFnSRVBe', '2020-11-01', 'CsmZTBvKilx2BUD9fkpEPnxY', 'Inactive', 'tRIVVABQrtZvM2aRggU1U72V', '2020-11-23 16:27:37', '2020-12-13 10:55:54');

-- --------------------------------------------------------

--
-- Table structure for table `users_student`
--

CREATE TABLE `users_student` (
  `id` varchar(24) NOT NULL,
  `name` varchar(30) NOT NULL,
  `mobile_no` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(15) NOT NULL,
  `otp` varchar(4) DEFAULT '',
  `is_verified` tinyint NOT NULL DEFAULT '0',
  `token_resetpwd` varchar(24) DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_student`
--

INSERT INTO `users_student` (`id`, `name`, `mobile_no`, `email`, `password`, `status`, `otp`, `is_verified`, `token_resetpwd`, `created_at`, `updated_at`) VALUES
('0Hg9xQ2T8htGgF6MtltaQIVU', 'Jitheesh Kumar', '3456789016', 'jithu.k@gmail.com', '$2y$10$m/5Nxs8Jfxt6Z6sbcxsAY.3NU7kj4qvucn.jOSvX1uY3Wl3xyu25e', 'Active', '3689', 0, '', '2020-12-10 18:45:59', '2020-12-10 18:45:59'),
('40qz0J4apLvk49FW04gYUM2d', 'Rahul C', '3456789020', 'hulc@rediffmail.com', '$2y$10$ULLUSvUoyvXskmMTrE1EMOnUekCRTEM7BV7MoNyeVjOaQlDPzXPde', 'Active', '1529', 0, '', '2020-12-10 18:49:09', '2020-12-10 18:49:09'),
('DBxLR8K4Ac2YZE2FwECwJBi5', 'Rajeesh V R', '3456789013', 'rajeesh.vr@gmail.com', '$2y$10$qdQ/mdkfEvR7wfSJklEifeXZMTkEjyUqsxdB20lwELwLEfo.vRP7O', 'Active', '7352', 0, '', '2020-12-10 18:40:50', '2020-12-10 18:40:50'),
('dy7q95b8G19KD0jx9V67YDtE', 'Yedhu K', '3456789015', 'y.edhu@gmail.com', '$2y$10$yuGl.7xDJ/wJa0ceFNrUJObwqqyoZLdQ/EIo.vp.MK/2QZeyfBXCG', 'Active', '2482', 0, '', '2020-12-10 18:42:16', '2020-12-10 18:42:16'),
('eR6bw7r8q5HXRAxKHMGNlEoB', 'Harikrishnan A S', '9495152925', 'contrastinc2016@gmail.com', '$2y$10$VXmlFYtmHECfSok3CnlgPueskQsXs8WUYDiJi.sewxtEruZlBBZzm', 'Active', '', 1, '', '2020-12-20 13:14:00', '2020-12-20 13:27:42'),
('JJ38AWkE09Z6610D4khm1yye', 'Vijay Sunder', '3456789019', 'vsownsever@gmail.com', '$2y$10$hoAWiZ3S2UGX1AshU/c1DevtY/qvgyT.t7ZgPme1x51CPdUYHnZd.', 'Active', '6365', 0, '', '2020-12-10 18:48:28', '2020-12-10 18:48:28'),
('msAzzIWap0WzSwSUrejw4ey4', 'Shalini R', '3456789017', 'shalu456@gmail.com', '$2y$10$dezoyaCQdfnSxKoMBU.s7uqiBTmjt1LEteKLoif2gO3Z0O2waOyeW', 'Active', '8806', 0, '', '2020-12-10 18:46:28', '2020-12-10 18:46:28'),
('N8UUonyYFs4La4zxOpsTcLHi', 'Raji N', '3456789018', 'nrajiheart@gmail.com', '$2y$10$m4O4HSGLcOq6oK2ffbTbNOdQhW8tKmLSJc7bOV79s7LePaqIZotvq', 'Active', '4623', 0, '', '2020-12-10 18:46:56', '2020-12-10 18:46:56'),
('NpEVbl1TDbeprIRh7vdLfQR8', 'Abhilash Sony', '3456789014', 'abhilash.so@gmail.com', '$2y$10$GuJDJH9BEuWIWI.VbMhzBe9fqGaCfLtYvSnSX/j8PoI6zj.kALhf2', 'Inctive', '7711', 0, '', '2020-12-10 18:41:17', '2020-12-10 19:04:03'),
('rN7h2InJlYyBfENBR1svq9DW', 'Some Student', '3456789022', 'some.student@yahoo.com', '$2y$10$W4BPardE6z23ezEK0DLgSOgEIBXXuiSJAhSaZhi.YV13GlePsB3YC', 'Active', '', 1, '', '2020-12-10 19:24:17', '2020-12-10 23:14:10'),
('T4XHeu7WX1crhQV2n3hMwIkv', 'Hari K', '9495045015', 'hari.hari1020@gmail.com', '$2y$10$W3fx/zUb72W1JySeVOofQuOMl7f8ByZl0ETTiPY53Gwum0oylHP5a', 'Active', '3699', 0, '', '2020-12-04 18:15:13', '2020-12-10 18:45:09'),
('wZvawpvrATuuw1U5dB6GPOkK', 'Aswin', '9400582772', 'aswin.tk12@gmail.com', '$2y$10$0w0WgrYZ.JrCpg49PaT.h.lrQSHKQ2rHHS3j2JnUJswuwVopou0nK', 'Active', '5524', 0, 'hvvcHswns2Zjixf2UXRyEUaO', '2020-12-17 12:03:58', '2020-12-22 12:11:25'),
('YCJa8dVNf3fYv71tZozQFnnZ', 'Uday A V', '3456789012', 'uday.av@gmail.com', '$2y$10$crJgRAjZgad9HQq0D517LeENjf8OmS25WrPCSqXE2115/Ax0Sj/O2', 'Active', '8821', 0, '', '2020-12-10 18:40:01', '2020-12-10 18:40:00'),
('YICvg4oN5DqmbS0v5CIyxhFc', 'Vijayanand', '3456789021', 'anand.vijay@yahoo.com', '$2y$10$zdDHvZ121nrZ2IXEPqJj7eiKFMqEum80pwkiMLkJHD8oetQDdifNa', 'Active', '4531', 0, '', '2020-12-10 18:49:59', '2020-12-10 18:49:59');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_classes_classteacher` (`class_teacher`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_lessons`
--
ALTER TABLE `course_lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_courselessons_course` (`course`),
  ADD KEY `idx_courselessons_subject` (`subject`);

--
-- Indexes for table `course_live_classes`
--
ALTER TABLE `course_live_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_courselclasses_course` (`course`),
  ADD KEY `idx_courselclasses_subject` (`subject`),
  ADD KEY `idx_courselclasses_lesson` (`lesson`);

--
-- Indexes for table `course_reviews`
--
ALTER TABLE `course_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_coursereviews_student` (`student`);

--
-- Indexes for table `course_subjects`
--
ALTER TABLE `course_subjects`
  ADD KEY `idx_coursesubjects_cid` (`course_id`),
  ADD KEY `idx_coursesubjects_sid` (`subject_id`);

--
-- Indexes for table `exams_1`
--
ALTER TABLE `exams_1`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exams_2`
--
ALTER TABLE `exams_2`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mcq_options`
--
ALTER TABLE `mcq_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_mcqoptions_question` (`question`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notifications_user` (`user`);

--
-- Indexes for table `notifications_admin`
--
ALTER TABLE `notifications_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notificationsadmin_user` (`user`);

--
-- Indexes for table `notifications_student`
--
ALTER TABLE `notifications_student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notificationsstudent_user` (`user`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_orders_sid` (`student_id`),
  ADD KEY `idx_orders_cid` (`course_id`);

--
-- Indexes for table `preferences`
--
ALTER TABLE `preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pref_studid` (`student_id`),
  ADD KEY `idx_pref_exam1id` (`exam1_id`),
  ADD KEY `idx_pref_exam2id` (`exam2_id`),
  ADD KEY `idx_pref_langid` (`language_id`),
  ADD KEY `idx_pref_streamid` (`stream_id`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_questions_subject` (`subject`),
  ADD KEY `idx_questions_lesson` (`lesson`),
  ADD KEY `idx_questions_correctoption` (`correct_opt`);

--
-- Indexes for table `streams`
--
ALTER TABLE `streams`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD KEY `idx_studentcourses_sid` (`student_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testorquiz_questions`
--
ALTER TABLE `testorquiz_questions`
  ADD KEY `idx_tqq_questionid` (`question_id`);

--
-- Indexes for table `tests_n_quizzes`
--
ALTER TABLE `tests_n_quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_testsnquizzes_course` (`course`),
  ADD KEY `idx_testsnquizzes_subject` (`subject`),
  ADD KEY `idx_testsnquizzes_lesson` (`lesson`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_admin`
--
ALTER TABLE `users_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_usersadmin_subject` (`subject`),
  ADD KEY `idx_usersadmin_profile` (`profile`);

--
-- Indexes for table `users_student`
--
ALTER TABLE `users_student`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course_lessons`
--
ALTER TABLE `course_lessons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `course_live_classes`
--
ALTER TABLE `course_live_classes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `course_reviews`
--
ALTER TABLE `course_reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `exams_1`
--
ALTER TABLE `exams_1`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `exams_2`
--
ALTER TABLE `exams_2`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mcq_options`
--
ALTER TABLE `mcq_options`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications_admin`
--
ALTER TABLE `notifications_admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications_student`
--
ALTER TABLE `notifications_student`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `preferences`
--
ALTER TABLE `preferences`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `streams`
--
ALTER TABLE `streams`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `idx_classes_classteacher` FOREIGN KEY (`class_teacher`) REFERENCES `users_admin` (`id`);

--
-- Constraints for table `course_lessons`
--
ALTER TABLE `course_lessons`
  ADD CONSTRAINT `idx_courselessons_course` FOREIGN KEY (`course`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `idx_courselessons_subject` FOREIGN KEY (`subject`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `course_live_classes`
--
ALTER TABLE `course_live_classes`
  ADD CONSTRAINT `idx_courselclasses_course` FOREIGN KEY (`course`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `idx_courselclasses_lesson` FOREIGN KEY (`lesson`) REFERENCES `course_lessons` (`id`),
  ADD CONSTRAINT `idx_courselclasses_subject` FOREIGN KEY (`subject`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `course_reviews`
--
ALTER TABLE `course_reviews`
  ADD CONSTRAINT `idx_coursereviews_student` FOREIGN KEY (`student`) REFERENCES `users` (`id`);

--
-- Constraints for table `course_subjects`
--
ALTER TABLE `course_subjects`
  ADD CONSTRAINT `idx_coursesubjects_cid` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `idx_coursesubjects_sid` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `mcq_options`
--
ALTER TABLE `mcq_options`
  ADD CONSTRAINT `idx_mcqoptions_question` FOREIGN KEY (`question`) REFERENCES `questions` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `idx_notifications_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications_admin`
--
ALTER TABLE `notifications_admin`
  ADD CONSTRAINT `idx_notificationsadmin_user` FOREIGN KEY (`user`) REFERENCES `users_admin` (`id`);

--
-- Constraints for table `notifications_student`
--
ALTER TABLE `notifications_student`
  ADD CONSTRAINT `idx_notificationsstudent_user` FOREIGN KEY (`user`) REFERENCES `users_student` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `idx_orders_sid` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `preferences`
--
ALTER TABLE `preferences`
  ADD CONSTRAINT `idx_pref_exam1id` FOREIGN KEY (`exam1_id`) REFERENCES `exams_1` (`id`),
  ADD CONSTRAINT `idx_pref_exam2id` FOREIGN KEY (`exam2_id`) REFERENCES `exams_2` (`id`),
  ADD CONSTRAINT `idx_pref_langid` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`),
  ADD CONSTRAINT `idx_pref_streamid` FOREIGN KEY (`stream_id`) REFERENCES `streams` (`id`),
  ADD CONSTRAINT `idx_pref_studid` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `idx_questions_lesson` FOREIGN KEY (`lesson`) REFERENCES `course_lessons` (`id`),
  ADD CONSTRAINT `idx_questions_subject` FOREIGN KEY (`subject`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `student_courses`
--
ALTER TABLE `student_courses`
  ADD CONSTRAINT `idx_studentcourses_sid` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `testorquiz_questions`
--
ALTER TABLE `testorquiz_questions`
  ADD CONSTRAINT `idx_tqq_questionid` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`);

--
-- Constraints for table `tests_n_quizzes`
--
ALTER TABLE `tests_n_quizzes`
  ADD CONSTRAINT `idx_testsnquizzes_course` FOREIGN KEY (`course`) REFERENCES `courses` (`id`),
  ADD CONSTRAINT `idx_testsnquizzes_lesson` FOREIGN KEY (`lesson`) REFERENCES `course_lessons` (`id`),
  ADD CONSTRAINT `idx_testsnquizzes_subject` FOREIGN KEY (`subject`) REFERENCES `subjects` (`id`);

--
-- Constraints for table `users_admin`
--
ALTER TABLE `users_admin`
  ADD CONSTRAINT `idx_usersadmin_profile` FOREIGN KEY (`profile`) REFERENCES `profiles` (`id`),
  ADD CONSTRAINT `idx_usersadmin_subject` FOREIGN KEY (`subject`) REFERENCES `subjects` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
