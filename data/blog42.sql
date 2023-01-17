-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: wolinski.pdx1-mysql-a7-6b.dreamhost.com
-- Generation Time: Jan 15, 2023 at 06:45 PM
-- Server version: 8.0.28-0ubuntu0.20.04.3
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog42`
--

-- --------------------------------------------------------

--
-- Table structure for table `fp_about_us`
--

CREATE TABLE `fp_about_us` (
  `about_us_id` int UNSIGNED NOT NULL,
  `about_us_title` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `about_us_article` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fp_about_us`
--

INSERT INTO `fp_about_us` (`about_us_id`, `about_us_title`, `about_us_article`) VALUES
(1, 'A long message about Blog42...', 'Blog42 started with one purpose: To blog about Life, the Universe, and Everything!  So, anyone can sign up and contribute.  Go on, tell us about your workday.  Tell us about trade routes.  Tell us about human behavior.  Or even speculate on the future.\r\n\r\nBlog42 is a blog where YOU are the author.  And the world is your audience!\r\n\r\n\r\n\r\n>>  NOTE FROM THE CREATOR:  <<\r\nHello, this is Brian.  I am the creator of Blog42.  As you may have seen, this is an early version of the Blog42 site.  I don\'t mind anyone signing up and contributing blog posts and/or testing out the features of the site.\r\n\r\n\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `fp_blog`
--

CREATE TABLE `fp_blog` (
  `article_id` int UNSIGNED NOT NULL,
  `image_id` int UNSIGNED DEFAULT NULL,
  `title` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `article` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fp_blog`
--

INSERT INTO `fp_blog` (`article_id`, `image_id`, `title`, `article`, `updated`, `created`) VALUES
(1, 1, 'Greetings!', 'Welcome to Blog42!  Please join us as we ponder the workings of the universe and wonders of the world.  Questions?  Answers? Rants?  Random musings?  We got it all!', '2020-05-02 13:10:02', '2020-05-02 04:57:09'),
(4, 4, 'TUTORIAL: Avoid getting lost', 'You know the best way to avoid getting lost?  Look at a map.  Or better yet, since we\'re in the 21st century, look at your phone!', '2020-05-02 11:09:54', '2020-05-02 10:55:58'),
(5, 6, 'Row row row your boat...', '...gently down the stream.  Merrily merrily merrily merrily life is but a dream.\r\n\r\nSeriously, has anyone actually heeded this advice?  I mean, has anyone actually gotten a boat and sailed down a stream.  Was the stream moving or stagnant?  What was the condition of the boat?  And what about the person navigating that boat?  What state of mind were they in?  Happy?  Nirvana?  Hopeless?  Lost?\r\n\r\nThese are the types of rhymes and sayings that can make you wonder how they came about and why we take them for granted?', '2020-05-03 00:17:10', '2020-05-02 11:12:04'),
(6, 5, 'Gloves', 'Gloves...they keep your hands warm!  And sometimes they keep your hands clean depending on the type of glove.  Aren\'t they just marvelous?', '2023-01-13 01:20:03', '2020-05-02 11:17:36'),
(7, 7, 'Kitchen Sink Tool', 'The KST is a multi-function Kitchen Sink Tool. The KST is a multi-function Kitchen Sink Tool. The KST is a multi-function Kitchen Sink Tool. The KST is a multi-function Kitchen Sink Tool. The KST is a multi-function Kitchen Sink Tool. The KST is a multi-function Kitchen Sink Tool. \r\n\r\n\r\nThe KST is a multi-function Kitchen Sink Tool. The KST is a multi-function Kitchen Sink Tool. The KST is a multi-function Kitchen Sink Tool. The KST is a multi-function Kitchen Sink Tool. The KST is a multi-function Kitchen Sink Tool. The KST is a multi-function Kitchen Sink Tool. The KST is a multi-function Kitchen Sink Tool. ', '2023-01-13 01:24:36', '2020-05-10 05:24:02');

-- --------------------------------------------------------

--
-- Table structure for table `fp_images`
--

CREATE TABLE `fp_images` (
  `image_id` int NOT NULL,
  `filename` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `caption` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fp_images`
--

INSERT INTO `fp_images` (`image_id`, `filename`, `caption`) VALUES
(1, 'hello.jpg', 'Hello!  ¡Hola!  Bonjour!'),
(2, 'php-code.jpg', 'PHP code...As far as the eye can see!'),
(4, 'world-map.jpg', 'A map shows you where you stand'),
(5, 'gloves.jpg', 'If you get cold, put these on'),
(6, 'rowboat.jpg', 'I wonder...has anyone tried to cross the Atlantic in a rowboat?'),
(7, 'kitchen-sink-tool.png', 'KST9000'),
(8, 'test-screen.jpg', 'test test test...'),
(9, 'test-screen2.jpg', 'test screen...NOOOOOOOOOOOOOOOOOOO!');

-- --------------------------------------------------------

--
-- Table structure for table `fp_users`
--

CREATE TABLE `fp_users` (
  `user_id` int UNSIGNED NOT NULL,
  `username` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pwd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fp_users`
--

INSERT INTO `fp_users` (`user_id`, `username`, `pwd`) VALUES
(1, 'Someone', '$2y$10$/2cz7l9O2yIigygu3z2WQ.BY8eN7X/2PHS1YVpOnFs1O738pZOyMO'),
(2, 'test_user', '$2y$10$3HkZtvnCs7S8yyks8mVj/.8hfXGMQJNWd6f3e2mgY2JNOScbIFXeW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fp_about_us`
--
ALTER TABLE `fp_about_us`
  ADD PRIMARY KEY (`about_us_id`);

--
-- Indexes for table `fp_blog`
--
ALTER TABLE `fp_blog`
  ADD PRIMARY KEY (`article_id`);

--
-- Indexes for table `fp_images`
--
ALTER TABLE `fp_images`
  ADD PRIMARY KEY (`image_id`);

--
-- Indexes for table `fp_users`
--
ALTER TABLE `fp_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fp_about_us`
--
ALTER TABLE `fp_about_us`
  MODIFY `about_us_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fp_blog`
--
ALTER TABLE `fp_blog`
  MODIFY `article_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `fp_images`
--
ALTER TABLE `fp_images`
  MODIFY `image_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `fp_users`
--
ALTER TABLE `fp_users`
  MODIFY `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
