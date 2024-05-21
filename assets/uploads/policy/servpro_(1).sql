-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2019 at 10:08 AM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.1.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `servpro`
--

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(10) UNSIGNED NOT NULL,
  `chat_from` int(11) NOT NULL,
  `chat_to` int(11) NOT NULL,
  `content` mediumtext CHARACTER SET utf8 NOT NULL,
  `chat_utc_time` datetime NOT NULL,
  `from_delete_sts` tinyint(4) NOT NULL DEFAULT '0',
  `to_delete_sts` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(2) NOT NULL COMMENT '0-Unread,1-Read'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `colour_settings`
--

CREATE TABLE `colour_settings` (
  `id` int(11) NOT NULL,
  `morning` varchar(10) NOT NULL,
  `afternoon` varchar(10) NOT NULL,
  `evening` varchar(10) NOT NULL,
  `night` varchar(10) NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `colour_settings`
--

INSERT INTO `colour_settings` (`id`, `morning`, `afternoon`, `evening`, `night`, `updated_at`) VALUES
(1, '#d42129', '#ff9c00', '#e5632c', '#981e7d', '2018-12-10 10:34:09');

-- --------------------------------------------------------

--
-- Table structure for table `device_details`
--

CREATE TABLE `device_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `device_type` varbinary(10) NOT NULL,
  `device_id` tinytext NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `language_management`
--

CREATE TABLE `language_management` (
  `sno` int(11) NOT NULL,
  `page_key` varchar(255) NOT NULL,
  `lang_key` varchar(250) CHARACTER SET utf8 NOT NULL,
  `lang_value` varchar(250) CHARACTER SET utf8 NOT NULL,
  `language` varchar(20) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `language_management`
--

INSERT INTO `language_management` (`sno`, `page_key`, `lang_key`, `lang_value`, `language`) VALUES
(1, 'login', 'lg2_email', 'Email', 'en'),
(2, 'login', 'lg2_password', 'Password', 'en'),
(3, 'login', 'lg2_optional', 'optional', 'en'),
(4, 'sign_up', 'lg1_signup_with_fac', 'SIGNUP WITH FACEBOOK', 'en'),
(5, 'sign_up', 'lg1_signup_with_goo', 'SIGNUP WITH GOOGLE', 'en'),
(6, 'sign_up', 'lg1_sign_up', 'SIGN UP', 'en'),
(7, 'sign_up', 'lg1_confirm_passwor', 'Confirm Password', 'en'),
(8, 'sign_up', 'lg1_upload_ic_card', 'Upload IC Card', 'en'),
(9, 'sign_up', 'lg1_enter_username', 'Enter Username', 'en'),
(10, 'sign_up', 'lg1_enter_email', 'Enter Email', 'en'),
(11, 'sign_up', 'lg1_enter_password', 'Enter Password', 'en'),
(12, 'sign_up', 'lg1_enter_confirm_p', 'Enter Confirm Password', 'en'),
(13, 'sign_up', 'lg1_enter_phone_num', 'Enter Phone Number', 'en'),
(14, 'sign_up', 'lg1_password_and_co', 'New Password and Confirm Password does not match', 'en'),
(15, 'sign_up', 'lg1_maximum_15_numb', 'Maximum 15 numbers are allowed', 'en'),
(16, 'sign_up', 'lg1_enter_valid_ema', 'Enter Valid Email', 'en'),
(17, 'sign_up', 'lg1_already_have_an', 'Already have an account ?', 'en'),
(18, 'sign_up', 'lg1_login', 'Login', 'en'),
(19, 'sign_up', 'lg1_ic_file', 'IC File', 'en'),
(20, 'sign_up', 'lg1_profile_picture', 'Profile Picture', 'en'),
(21, 'sign_up', 'lg1_signup_now', 'Signup Now', 'en'),
(22, 'sign_up', 'lg1_gallery', 'Gallery', 'en'),
(23, 'sign_up', 'lg1_camera', 'Camera', 'en'),
(24, 'sign_up', 'lg1_cancel', 'Cancel', 'en'),
(25, 'sign_up', 'lg1_picture', 'Picture', 'en'),
(26, 'sign_up', 'lg1_please_install_', 'Please install camera app', 'en'),
(27, 'sign_up', 'lg1_please_install_', 'Please install file manager', 'en'),
(28, 'sign_up', 'lg1_invalid_file_pa', 'Invalid file path', 'en'),
(29, 'sign_up', 'lg1_there_is_no_net', 'There is no network connection', 'en'),
(30, 'sign_up', 'lg1_choose_picture', 'Choose a photo via', 'en'),
(31, 'login', 'lg2_sign_in_or_regi', 'Sign in or register', 'en'),
(32, 'login', 'lg2_sign_in', 'Sign in', 'en'),
(33, 'login', 'lg2_this_email_addr', 'This email address is invalid', 'en'),
(34, 'login', 'lg2_this_password_i', 'This password is too short', 'en'),
(35, 'login', 'lg2_this_password_i', 'This password is incorrect', 'en'),
(36, 'login', 'lg2_this_field_is_r', 'This field is required', 'en'),
(37, 'login', 'lg2_contacts_permis', 'Contacts permissions are needed for providing email completions', 'en'),
(38, 'login', 'lg2_username', 'Username / Email id', 'en'),
(39, 'login', 'lg2_ic_number', 'IC Number', 'en'),
(40, 'login', 'lg2_phone', 'Phone', 'en'),
(41, 'login', 'lg2_login_with_face', 'LOGIN WITH FACEBOOK', 'en'),
(42, 'login', 'lg2_login_with_goog', 'LOGIN WITH GOOGLE', 'en'),
(43, 'login', 'lg2_forgot_password', 'Forgot Password?', 'en'),
(44, 'login', 'lg2_please_enter_us', 'Please enter username', 'en'),
(45, 'login', 'lg2_please_enter_pa', 'Please enter password', 'en'),
(46, 'login', 'lg2_don_t_have_an_a', 'Don\'t have an account?', 'en'),
(47, 'navigation', 'lg3_dashboard', 'Dashboard', 'en'),
(48, 'navigation', 'lg3_my_profile', 'My Profile', 'en'),
(49, 'navigation', 'lg3_my_requests', 'My Requests', 'en'),
(50, 'navigation', 'lg3_my_services', 'My Services', 'en'),
(51, 'navigation', 'lg3_history', 'History', 'en'),
(52, 'navigation', 'lg3_chat_history', 'Chat History', 'en'),
(53, 'navigation', 'lg3_logout', 'Logout', 'en'),
(54, 'common_used_texts', 'lg7_servrep', 'ServRep', 'en'),
(55, 'common_used_texts', 'lg7_please_enable_i', 'Please Enable Internet', 'en'),
(59, 'common_used_texts', 'lg7_please_wait', 'Please wait...', 'en'),
(60, 'common_used_texts', 'lg7_yes', 'Yes', 'en'),
(61, 'common_used_texts', 'lg7_no', 'No', 'en'),
(62, 'common_used_texts', 'lg7_ok', 'OK', 'en'),
(63, 'common_used_texts', 'lg7_save', 'Save', 'en'),
(64, 'common_used_texts', 'lg7_done', 'Done', 'en'),
(65, 'dashboard', 'lg8_requests', 'Requests', 'en'),
(66, 'dashboard', 'lg8_providers', 'Providers', 'en'),
(67, 'dashboard', 'lg8_no_data_were_fo', 'No data were found', 'en'),
(68, 'logout', 'lg10_are_you_sure_yo', 'Are you sure you want to Logout?', 'en'),
(69, 'subscription', 'lg9_subscription', 'Subscription', 'en'),
(70, 'subscription', 'lg9_skip_now', 'Skip Now', 'en'),
(71, 'subscription', 'lg9_buy_now', 'Buy Now', 'en'),
(72, 'subscription', 'lg9_accept_request', 'Accept Request', 'en'),
(73, 'profile', 'lg4_please_select_p', 'Please Select Profile Image', 'en'),
(74, 'profile', 'lg4_please_select_c', 'Please Select Card Image', 'en'),
(75, 'profile', 'lg4_valid_till', 'Valid till', 'en'),
(76, 'request_and_provider_list', 'lg6_contact_number', 'Contact Number', 'en'),
(77, 'request_and_provider_list', 'lg6_chat', 'CHAT', 'en'),
(78, 'request_and_provider_list', 'lg6_appointment', 'Appointment date', 'en'),
(79, 'request_and_provider_list', 'lg6_title', 'Title', 'en'),
(80, 'request_and_provider_list', 'lg6_desc_point', 'Desc point', 'en'),
(81, 'request_and_provider_list', 'lg6_point', 'Point', 'en'),
(82, 'request_and_provider_list', 'lg6_location', 'Location', 'en'),
(83, 'request_and_provider_list', 'lg6_date', 'Date', 'en'),
(84, 'request_and_provider_list', 'lg6_time', 'Time', 'en'),
(85, 'request_and_provider_list', 'lg6_proposed_fee', 'Proposed Fee', 'en'),
(86, 'request_and_provider_list', 'lg6_next', 'Next', 'en'),
(87, 'request_and_provider_list', 'lg6_submit', 'Submit', 'en'),
(88, 'request_and_provider_list', 'lg6_availability', 'Availability', 'en'),
(89, 'request_and_provider_list', 'lg6_from', 'From', 'en'),
(90, 'request_and_provider_list', 'lg6_to', 'To', 'en'),
(91, 'request_and_provider_list', 'lg6_expecting_fee', 'Expecting Fee :', 'en'),
(92, 'request_and_provider_list', 'lg6_title_cannot_be', 'Title cannot be empty', 'en'),
(93, 'request_and_provider_list', 'lg6_date_cannot_be_', 'Date cannot be empty', 'en'),
(94, 'request_and_provider_list', 'lg6_time_cannot_be_', 'Time cannot be empty', 'en'),
(95, 'request_and_provider_list', 'lg6_proposed_fee_ca', 'Proposed fee cannot be empty', 'en'),
(96, 'request_and_provider_list', 'lg6_location_addres', 'Location address cannot be empty', 'en'),
(97, 'request_and_provider_list', 'lg6_please_try_agai', 'Please try again', 'en'),
(98, 'request_and_provider_list', 'lg6_error_in_locati', 'Error in location', 'en'),
(99, 'request_and_provider_list', 'lg6_contact_number_', 'Contact number cannot be empty', 'en'),
(100, 'request_and_provider_list', 'lg6_info', 'Info', 'en'),
(101, 'request_and_provider_list', 'lg6_all_days', 'All Days', 'en'),
(102, 'request_and_provider_list', 'lg6_sunday', 'Sunday', 'en'),
(103, 'request_and_provider_list', 'lg6_monday', 'Monday', 'en'),
(104, 'request_and_provider_list', 'lg6_tuesday', 'Tuesday', 'en'),
(105, 'request_and_provider_list', 'lg6_wednesday', 'Wednesday', 'en'),
(106, 'request_and_provider_list', 'lg6_thursday', 'Thursday', 'en'),
(107, 'request_and_provider_list', 'lg6_friday', 'Friday', 'en'),
(108, 'request_and_provider_list', 'lg6_saturday', 'Saturday', 'en'),
(109, 'request_and_provider_list', 'lg6_please_select_a', 'Please select atleast a day', 'en'),
(110, 'request_and_provider_list', 'lg6_choose_location', 'Choose Location', 'en'),
(111, 'request_and_provider_list', 'lg6_please_enter_de', 'Please enter description', 'en'),
(112, 'request_and_provider_list', 'lg6_please_enter_at', 'Please enter atleast one description', 'en'),
(113, 'request_and_provider_list', 'lg6_closed', 'Closed', 'en'),
(114, 'request_and_provider_list', 'lg6_permission_deni', 'Permission Denied', 'en'),
(115, 'request_and_provider_list', 'lg6_location_permis', 'Location Permission Needed', 'en'),
(116, 'request_and_provider_list', 'lg6_this_app_needs_', 'This app needs the Location permission', 'en'),
(117, 'request_and_provider_list', 'lg6_please_accept_t', 'please accept to use location functionality', 'en'),
(118, 'login', 'lg2_don_t_have_an_a', 'Don\'t have an account?', 'en'),
(119, 'login', 'lg2_please_enter_pa', 'Sila masukkan kata laluan', 'ma'),
(120, 'sign_up', 'lg1_choose_picture', 'Pilih foto melalui', 'ma'),
(121, 'sign_up', 'lg1_there_is_no_net', 'Tiada sambungan rangkaian', 'ma'),
(122, 'sign_up', 'lg1_invalid_file_pa', 'Laluan fail tidak sah', 'ma'),
(123, 'sign_up', 'lg1_please_install_', 'Sila pasang pengurus fail', 'ma'),
(124, 'sign_up', 'lg1_picture', 'Gambar', 'ma'),
(125, 'sign_up', 'lg1_cancel', 'Batalkan', 'ma'),
(126, 'sign_up', 'lg1_camera', 'Kamera', 'ma'),
(127, 'sign_up', 'lg1_gallery', 'Galeri', 'ma'),
(128, 'sign_up', 'lg1_signup_now', 'Daftarlah sekarang', 'ma'),
(129, 'sign_up', 'lg1_profile_picture', 'Gambar profil', 'ma'),
(130, 'sign_up', 'lg1_ic_file', 'Fail IC', 'ma'),
(131, 'sign_up', 'lg1_login', 'Log masuk', 'ma'),
(132, 'sign_up', 'lg1_already_have_an', 'Sudah mempunyai akaun ?', 'ma'),
(133, 'sign_up', 'lg1_enter_valid_ema', 'Masukkan E-mel Sah', 'ma'),
(134, 'sign_up', 'lg1_maximum_15_numb', 'Maksimum 15 nombor dibenarkan', 'ma'),
(135, 'sign_up', 'lg1_password_and_co', ' Kata Laluan Baru dan Sahkan Kata Laluan tidak sepadan', 'ma'),
(136, 'sign_up', 'lg1_enter_phone_num', 'Masukkan Nombor Telefon', 'ma'),
(137, 'sign_up', 'lg1_enter_confirm_p', 'Masukkan Sahkan Kata Laluan', 'ma'),
(138, 'sign_up', 'lg1_enter_password', 'Masukkan kata laluan', 'ma'),
(139, 'sign_up', 'lg1_enter_email', 'Masukkan email', 'ma'),
(140, 'sign_up', 'lg1_enter_username', 'Masukkan Nama Pengguna', 'ma'),
(141, 'sign_up', 'lg1_upload_ic_card', 'Muat naik Kad IC', 'ma'),
(142, 'sign_up', 'lg1_confirm_passwor', 'Sahkan Kata Laluan', 'ma'),
(143, 'sign_up', 'lg1_sign_up', 'Daftar', 'ma'),
(144, 'sign_up', 'lg1_signup_with_goo', 'TANDAKAN DENGAN GOOGLE', 'ma'),
(145, 'sign_up', 'lg1_signup_with_fac', 'TANDA DENGAN FACEBOOK', 'ma'),
(146, 'login', 'lg2_please_enter_us', ' Sila masukkan nama pengguna', 'ma'),
(147, 'login', 'lg2_forgot_password', 'Lupa kata laluan?', 'ma'),
(148, 'login', 'lg2_login_with_goog', ' LOGIN DENGAN GOOGLE', 'ma'),
(149, 'login', 'lg2_login_with_face', ' LOG MASUK MELALUI FACEBOOK', 'ma'),
(150, 'login', 'lg2_phone', ' Telefon', 'ma'),
(151, 'login', 'lg2_ic_number', ' Nombor IC', 'ma'),
(152, 'login', 'lg2_username', 'Nama pengguna / E-mel id', 'ma'),
(153, 'login', 'lg2_contacts_permis', ' Kebenaran kenalan diperlukan untuk menyediakan penyelesaian e-mel', 'ma'),
(154, 'login', 'lg2_this_field_is_r', ' Bidang ini diperlukan', 'ma'),
(155, 'login', 'lg2_this_password_i', ' Kata laluan ini salah', 'ma'),
(156, 'login', 'lg2_this_email_addr', ' Alamat e-mel ini tidak sah', 'ma'),
(157, 'login', 'lg2_sign_in', ' Masuk', 'ma'),
(158, 'login', 'lg2_sign_in_or_regi', ' Masuk atau mendaftar', 'ma'),
(159, 'login', 'lg2_optional', ' pilihan', 'ma'),
(160, 'login', 'lg2_password', ' Kata laluan', 'ma'),
(161, 'login', 'lg2_email', ' E-mel', 'ma'),
(162, 'navigation', 'lg3_logout', ' Log keluar', 'ma'),
(163, 'navigation', 'lg3_chat_history', ' Sejarah Sembang', 'ma'),
(164, 'navigation', 'lg3_history', 'Sejarah', 'ma'),
(165, 'navigation', 'lg3_my_services', ' Perkhidmatan Saya', 'ma'),
(166, 'navigation', 'lg3_my_requests', ' Permintaan saya', 'ma'),
(167, 'navigation', 'lg3_my_profile', ' Profil saya', 'ma'),
(168, 'navigation', 'lg3_dashboard', ' Papan Pemuka', 'ma'),
(169, 'profile', 'lg4_valid_till', 'Sah sehingga', 'ma'),
(170, 'profile', 'lg4_please_select_c', 'Sila Pilih Imej Kad', 'ma'),
(171, 'profile', 'lg4_please_select_p', ' Sila Pilih Imej Profil', 'ma'),
(172, 'dashboard', 'lg8_no_data_were_fo', ' Tiada data dijumpai', 'ma'),
(173, 'dashboard', 'lg8_providers', ' Penyedia', 'ma'),
(174, 'dashboard', 'lg8_requests', ' Permintaan', 'ma'),
(175, 'subscription', 'lg9_accept_request', ' Terima Permintaan', 'ma'),
(176, 'subscription', 'lg9_buy_now', ' Beli sekarang', 'ma'),
(177, 'subscription', 'lg9_skip_now', 'Langkau Sekarang', 'ma'),
(178, 'subscription', 'lg9_subscription', ' Langganan', 'ma'),
(179, 'logout', 'lg10_are_you_sure_yo', ' Adakah anda pasti mahu Logout?', 'ma'),
(180, 'common_used_texts', 'lg7_done', ' Selesai', 'ma'),
(181, 'common_used_texts', 'lg7_save', ' Simpan', 'ma'),
(182, 'common_used_texts', 'lg7_ok', 'okey', 'ma'),
(183, 'common_used_texts', 'lg7_no', 'Tidak', 'ma'),
(184, 'common_used_texts', 'lg7_yes', 'Ya', 'ma'),
(185, 'common_used_texts', 'lg7_please_wait', ' Sila tunggu...', 'ma'),
(189, 'common_used_texts', 'lg7_please_enable_i', ' Sila Aktifkan Internet', 'ma'),
(190, 'request_and_provider_list', 'lg6_please_accept_t', ' sila terima menggunakan fungsi lokasi', 'ma'),
(191, 'request_and_provider_list', 'lg6_this_app_needs_', ' Aplikasi ini memerlukan kebenaran Lokasi', 'ma'),
(192, 'request_and_provider_list', 'lg6_location_permis', 'Kebenaran Lokasi Diperlukan', 'ma'),
(193, 'request_and_provider_list', 'lg6_permission_deni', 'Kebenaran Dihentikan', 'ma'),
(194, 'request_and_provider_list', 'lg6_closed', 'Tertutup', 'ma'),
(195, 'request_and_provider_list', 'lg6_please_enter_at', ' Sila masukkan atlit satu perihalan', 'ma'),
(196, 'request_and_provider_list', 'lg6_please_enter_de', 'Sila masukkan keterangan', 'ma'),
(197, 'request_and_provider_list', 'lg6_choose_location', ' Pilih Lokasi', 'ma'),
(198, 'request_and_provider_list', 'lg6_please_select_a', 'Sila pilih satu hari', 'ma'),
(199, 'request_and_provider_list', 'lg6_saturday', ' Sabtu', 'ma'),
(200, 'request_and_provider_list', 'lg6_friday', ' Jumaat', 'ma'),
(201, 'request_and_provider_list', 'lg6_thursday', 'Khamis', 'ma'),
(202, 'request_and_provider_list', 'lg6_wednesday', 'Rabu', 'ma'),
(203, 'request_and_provider_list', 'lg6_tuesday', ' Selasa', 'ma'),
(204, 'request_and_provider_list', 'lg6_monday', ' Isnin', 'ma'),
(205, 'request_and_provider_list', 'lg6_sunday', ' Ahad', 'ma'),
(206, 'request_and_provider_list', 'lg6_all_days', 'Semua Hari', 'ma'),
(207, 'request_and_provider_list', 'lg6_info', 'Maklumat', 'ma'),
(208, 'request_and_provider_list', 'lg6_contact_number_', ' Nombor hubungan tidak boleh kosong', 'ma'),
(209, 'request_and_provider_list', 'lg6_error_in_locati', ' Ralat di lokasi', 'ma'),
(210, 'request_and_provider_list', 'lg6_please_try_agai', ' Sila cuba lagi', 'ma'),
(211, 'request_and_provider_list', 'lg6_location_addres', ' Alamat lokasi tidak boleh kosong', 'ma'),
(212, 'request_and_provider_list', 'lg6_proposed_fee_ca', ' Yuran yang dicadangkan tidak boleh kosong', 'ma'),
(213, 'request_and_provider_list', 'lg6_time_cannot_be_', ' Masa tidak boleh kosong', 'ma'),
(214, 'request_and_provider_list', 'lg6_date_cannot_be_', ' Tarikh tidak boleh kosong', 'ma'),
(215, 'request_and_provider_list', 'lg6_title_cannot_be', 'Tajuk tidak boleh kosong', 'ma'),
(216, 'request_and_provider_list', 'lg6_expecting_fee', ' Mengharapkan Yuran  : ', 'ma'),
(217, 'request_and_provider_list', 'lg6_to', ' Untuk', 'ma'),
(218, 'request_and_provider_list', 'lg6_from', ' Dari', 'ma'),
(219, 'request_and_provider_list', 'lg6_availability', ' Ketersediaan', 'ma'),
(220, 'request_and_provider_list', 'lg6_submit', 'Hantar', 'ma'),
(221, 'request_and_provider_list', 'lg6_next', ' Seterusnya', 'ma'),
(222, 'request_and_provider_list', 'lg6_proposed_fee', ' Cadangan Yuran', 'ma'),
(223, 'request_and_provider_list', 'lg6_time', ' Masa', 'ma'),
(224, 'request_and_provider_list', 'lg6_date', ' Tarikh', 'ma'),
(225, 'request_and_provider_list', 'lg6_location', ' Lokasi', 'ma'),
(226, 'request_and_provider_list', 'lg6_point', 'Titik', 'ma'),
(227, 'request_and_provider_list', 'lg6_desc_point', 'Desc', 'ma'),
(228, 'request_and_provider_list', 'lg6_title', 'Tajuk', 'ma'),
(229, 'request_and_provider_list', 'lg6_appointment', 'Tarikh pelantikan', 'ma'),
(230, 'request_and_provider_list', 'lg6_chat', 'Sembang', 'ma'),
(231, 'request_and_provider_list', 'lg6_contact_number', ' Nombor telefon', 'ma'),
(232, 'login', 'lg2_signup_now', 'Signup Now', 'en'),
(233, 'sign_up', 'lg1_username', 'Username', 'en'),
(234, 'sign_up', 'lg1_password', 'Password', 'en'),
(235, 'sign_up', 'lg1_email', 'Email', 'en'),
(236, 'sign_up', 'lg1_phone', 'Phone', 'en'),
(237, 'sign_up', 'lg1_username', 'Nama pengguna', 'ma'),
(238, 'sign_up', 'lg1_password', 'Kata laluan', 'ma'),
(239, 'sign_up', 'lg1_email', 'E-mel', 'ma'),
(240, 'sign_up', 'lg1_phone', 'Telefon', 'ma'),
(241, 'login', 'lg2_signup_now', 'Daftarlah sekarang', 'ma'),
(242, 'login', 'lg2_dont_have_an_ac', ' Tidak mempunyai akaun?', 'ma'),
(243, 'common_used_texts', 'lg7_please_install_', 'Please install Google Play services.', 'en'),
(244, 'common_used_texts', 'lg7_please_install_', 'Sila pasang perkhidmatan Google Play.', 'ma'),
(245, 'common_used_texts', 'lg7_enable_permissi', ' Dayakan Kebenaran', 'ma'),
(247, 'common_used_texts', 'lg7_enable_permissi', 'Enable permissions', 'en'),
(248, 'common_used_texts', 'lg7_loading', 'Memuatkan...', 'ma'),
(249, 'common_used_texts', 'lg7_these_permissio', 'These permissions are mandatory for the application. Please allow access.', 'en'),
(250, 'common_used_texts', 'lg7_these_permissio', ' Kebenaran ini adalah wajib untuk permohonan itu. Sila benarkan akses.', 'ma'),
(253, 'common_used_texts', 'lg7_servrep', 'ServRep', 'ma'),
(254, 'request_and_provider_list', 'lg6_pending', 'Pending', 'en'),
(255, 'request_and_provider_list', 'lg6_completed', 'Completed', 'en'),
(256, 'request_and_provider_list', 'lg6_requested', 'Requested', 'en'),
(257, 'request_and_provider_list', 'lg6_accepted', 'Accepted', 'en'),
(258, 'request_and_provider_list', 'lg6_all', 'All', 'en'),
(259, 'request_and_provider_list', 'lg6_complete_reques', 'Complete Request', 'en'),
(260, 'request_and_provider_list', 'lg6_acceptor', 'Acceptor', 'en'),
(261, 'request_and_provider_list', 'lg6_requester', 'Requester', 'en'),
(262, 'request_and_provider_list', 'lg6_pending', 'Yang belum selesai', 'ma'),
(263, 'request_and_provider_list', 'lg6_completed', 'Selesai', 'ma'),
(264, 'request_and_provider_list', 'lg6_requested', 'Diminta', 'ma'),
(265, 'request_and_provider_list', 'lg6_accepted', 'Diterima', 'ma'),
(266, 'request_and_provider_list', 'lg6_all', 'Semua', 'ma'),
(267, 'request_and_provider_list', 'lg6_complete_reques', 'Permintaan Lengkap', 'ma'),
(268, 'request_and_provider_list', 'lg6_acceptor', 'Penerimaan', 'ma'),
(269, 'request_and_provider_list', 'lg6_requester', 'Requester', 'ma'),
(270, 'common_used_texts', 'lg7_loading', 'Loading...', 'en'),
(271, 'request_and_provider_list', 'lg6_request', 'Request', 'en'),
(272, 'request_and_provider_list', 'lg6_provide', 'Provide', 'en'),
(273, 'request_and_provider_list', 'lg6_request', 'Permintaan', 'ma'),
(274, 'request_and_provider_list', 'lg6_provide', 'Menyediakan', 'ma'),
(275, 'common_used_texts', 'lg7_problem_occurre', 'Please enable permissions', 'en'),
(276, 'common_used_texts', 'lg7_problem_occurre', ' Sila aktifkan kebenaran', 'ma'),
(277, 'common_used_texts', 'lg7_oops__problem_o', 'Oops! problem occurred while connecting to server...', 'en'),
(278, 'common_used_texts', 'lg7_no_data_were_fo', 'No data were found!', 'en'),
(279, 'common_used_texts', 'lg7_oops__problem_o', 'Oops! masalah berlaku semasa menyambung ke pelayan ...', 'ma'),
(280, 'common_used_texts', 'lg7_no_data_were_fo', 'Tiada data dijumpai!', 'ma'),
(281, 'common_used_texts', 'lg7_pleaeenable_per', ' Sila aktifkan kebenaran', 'ma'),
(282, 'request_and_provider_list', 'lg6_desc', 'Desc. point', 'en'),
(283, 'request_and_provider_list', 'lg6_desc', 'Desc. titik', 'ma'),
(284, 'profile', 'lg4_ok', 'Ok', 'en'),
(285, 'profile', 'lg4_cancel', 'Cancel', 'en'),
(286, 'profile', 'lg4_these_permissio', 'These permissions are mandatory for the application. Please allow access.', 'en'),
(287, 'profile', 'lg4_maximum_15_numb', 'Maximum 15 numbers are allowed', 'en'),
(288, 'profile', 'lg4_enter_phone_num', 'Enter Phone Number', 'en'),
(289, 'profile', 'lg4_enter_valid_ema', 'Enter Valid Email', 'en'),
(290, 'profile', 'lg4_enter_email', 'Enter Email', 'en'),
(291, 'profile', 'lg4_enter_username', 'Enter Username', 'en'),
(292, 'request_and_provider_list', 'lg6_accept_request', 'Accept Request', 'en'),
(293, 'request_and_provider_list', 'lg6_accept_request', 'Terima Permintaan', 'ma'),
(294, 'sign_up', 'lg1_please_select_c', 'Please Select Card Image', 'en'),
(295, 'sign_up', 'lg1_please_select_p', 'Please Select Profile Image', 'en'),
(296, 'sign_up', 'lg1_please_select_p', ' Sila Pilih Imej Profil', 'ma'),
(297, 'sign_up', 'lg1_please_select_c', ' Sila Pilih Imej Kad', 'ma'),
(298, 'common_used_texts', 'lg7_cancel', 'Cancel', 'en'),
(299, 'common_used_texts', 'lg7_cancel', 'Batalkan', 'ma'),
(300, 'common_used_texts', 'lg7_gallery', 'Gallery', 'en'),
(301, 'common_used_texts', 'lg7_camera', 'Camera', 'en'),
(302, 'common_used_texts', 'lg7_gallery', 'Galeri', 'ma'),
(303, 'common_used_texts', 'lg7_camera', 'Kamera', 'ma'),
(304, 'common_used_texts', 'lg7_permission_nece', 'Permission necessary', 'en'),
(305, 'common_used_texts', 'lg7_external_storag', 'External storage permission is necessary', 'en'),
(306, 'common_used_texts', 'lg7_external_storag', 'Kebenaran storan luaran diperlukan', 'ma'),
(307, 'common_used_texts', 'lg7_permission_nece', 'Kebenaran diperlukan', 'ma'),
(309, 'common_used_texts', 'lg7_please_install_', 'Please install file manager', 'en'),
(310, 'common_used_texts', 'lg7_invalid_file_pa', 'Invalid file path', 'en'),
(311, 'common_used_texts', 'lg7_choose_picture', 'Choose picture', 'en'),
(312, 'common_used_texts', 'lg7_pleaeenable_per', 'gh', 'en'),
(313, 'common_used_texts', 'lg7_invalid_file_pa', ' Laluan fail tidak sah', 'ma'),
(314, 'common_used_texts', 'lg7_choose_picture', ' Pilih gambar', 'ma'),
(315, 'profile', 'lg4_subscription', 'Subscription', 'en'),
(316, 'profile', 'lg4_username', 'Username', 'en'),
(317, 'profile', 'lg4_email', 'Email', 'en'),
(318, 'profile', 'lg4_phone', 'Phone', 'en'),
(319, 'profile', 'lg4_ic_card', 'IC Card', 'en'),
(320, 'request_and_provider_list', 'lg6_call', 'Call', 'en'),
(321, 'common_used_texts', 'lg7_english', 'English', 'en'),
(322, 'common_used_texts', 'lg7_malay', 'Malay', 'en'),
(323, 'common_used_texts', 'lg7_choose_language', 'Choose Language', 'en'),
(324, 'common_used_texts', 'lg7_location', 'Location', 'en'),
(325, 'login', 'lg2_don_t_have_an_a', 'Tidak mempunyai akaun?', 'ma'),
(326, 'login', 'lg2_dont_have_an_ac', 'Don\'t have an account?', 'en'),
(327, 'profile', 'lg4_ic_card', 'Kad IC', 'ma'),
(328, 'profile', 'lg4_phone', 'Telefon', 'ma'),
(329, 'profile', 'lg4_email', 'E-mel', 'ma'),
(330, 'profile', 'lg4_username', 'Nama pengguna', 'ma'),
(331, 'profile', 'lg4_subscription', 'Langganan', 'ma'),
(332, 'profile', 'lg4_enter_username', 'Masukkan Nama Pengguna', 'ma'),
(333, 'profile', 'lg4_enter_email', 'Masukkan email', 'ma'),
(334, 'profile', 'lg4_enter_valid_ema', 'Masukkan E-mel Sah', 'ma'),
(335, 'profile', 'lg4_enter_phone_num', 'Masukkan Nombor Telefon', 'ma'),
(336, 'profile', 'lg4_maximum_15_numb', 'Maksimum 15 nombor dibenarkan', 'ma'),
(337, 'profile', 'lg4_ok', 'Okey', 'ma'),
(338, 'profile', 'lg4_cancel', 'Batalkan', 'ma'),
(339, 'profile', 'lg4_these_permissio', 'Kebenaran ini adalah wajib untuk permohonan itu. Sila benarkan akses.', 'ma'),
(340, 'request_and_provider_list', 'lg6_call', 'Panggilan', 'ma'),
(341, 'common_used_texts', 'lg7_location', ' Lokasi', 'ma'),
(342, 'common_used_texts', 'lg7_choose_language', 'Pilih Bahasa', 'ma'),
(343, 'common_used_texts', 'lg7_malay', 'Melayu', 'ma'),
(344, 'common_used_texts', 'lg7_english', ' Bahasa Inggeris', 'ma'),
(345, 'profile', 'lg4_lg4_update_prof', 'update', 'en'),
(346, 'profile', 'lg4_lg4_update_prof', 'Kemas kini', 'ma'),
(347, 'profile', 'lg4_choose_a_photo_', 'Choose a photo via', 'en'),
(348, 'profile', 'lg4_choose_a_photo_', 'Pilih foto melalui', 'ma'),
(349, 'profile', 'lg4_upload_ic_card', 'Upload ID Card', 'en'),
(350, 'profile', 'lg4_upload_ic_card', 'Muat naik Kad ID', 'ma'),
(351, 'request_and_provider_list', 'lg6_add_provide', 'Add Provide', 'en'),
(352, 'request_and_provider_list', 'lg6_add_provide', 'Tambah Menyediakan', 'ma'),
(353, 'request_and_provider_list', 'lg6_add_request', 'Add Request', 'en'),
(354, 'request_and_provider_list', 'lg6_add_request', 'Tambah Permintaan', 'ma'),
(355, 'request_and_provider_list', 'lg6_description_can', 'Description cannot be empty', 'en'),
(356, 'request_and_provider_list', 'lg6_description_can', ' Penerangan tidak boleh kosong', 'ma'),
(357, 'request_and_provider_list', 'lg6_days', 'Days', 'en'),
(358, 'request_and_provider_list', 'lg6_days', 'Hari', 'ma'),
(359, 'common_used_texts', 'lg7_maximum_20_char', 'Maximum 20 characters are allowed', 'en'),
(360, 'common_used_texts', 'lg7_maximum_20_char', 'Maksimum 20 aksara dibenarkan', 'ma'),
(361, 'common_used_texts', 'lg7_password_must_b', 'Password must be alphanumeric and 8-15 characters long', 'en'),
(362, 'common_used_texts', 'lg7_password_must_b', 'Kata laluan mesti 8 - 15 aksara panjang. Untuk menjadikannya lebih kuat menggunakan nombor dan huruf huruf besar dan huruf.', 'ma'),
(363, 'request_and_provider_list', 'lg6_history_details', 'History details', 'en'),
(364, 'request_and_provider_list', 'lg6_history_details', ' Butiran sejarah', 'ma'),
(365, 'common_used_texts', 'lg7_contact_number', 'Contact number', 'en'),
(366, 'common_used_texts', 'lg7_contact_number', 'Nombor telefon', 'ma'),
(367, 'common_used_texts', 'lg7_chat', 'Chat', 'en'),
(368, 'common_used_texts', 'lg7_chat', 'Sembang', 'ma'),
(369, 'common_used_texts', 'lg7_oops_itseems_li', 'Oops!! Itseems like you are not connected to internet', 'en'),
(370, 'common_used_texts', 'lg7_oops_itseems_li', ' Oops !! Nampaknya anda tidak disambungkan ke internet', 'ma'),
(371, 'common_used_texts', 'lg7_no_records_foun', 'No records found', 'en'),
(372, 'common_used_texts', 'lg7_no_records_foun', 'Tiada rekod dijumpai', 'ma'),
(373, 'request_and_provider_list', 'lg6_dialing_not_sup', 'Dialing not supported', 'en'),
(374, 'request_and_provider_list', 'lg6_dialing_tidak_d', 'Dialing tidak disokong', 'en'),
(375, 'request_and_provider_list', 'lg6_dialing_not_sup', 'Dialing tidak disokong', 'ma'),
(376, 'navigation', 'lg3_settings', 'Settings', 'en'),
(377, 'navigation', 'lg3_settings', 'Tetapan', 'ma'),
(378, 'common_used_texts', 'lg7_please_install_2', 'Please install camera app', 'en'),
(379, 'common_used_texts', 'lg7_please_install_2', 'Sila pasang apl kamera', 'ma'),
(380, 'login', 'lg2_enter_your_deta', 'Enter your details below', 'en'),
(381, 'login', 'lg2_sign_in_to', 'Sign in to', 'en'),
(382, 'login', 'lg2_signin_with', 'Signin with', 'en'),
(383, 'login', 'lg2_enter_your_deta', 'Masukkan butiran anda di bawah', 'ma'),
(384, 'login', 'lg2_signin_with', 'Daftar masuk dengan', 'ma'),
(385, 'login', 'lg2_sign_in_to', 'Masuk ke', 'ma'),
(386, 'request_and_provider_list', 'lg6_please_select_d', 'Please select date range', 'en'),
(387, 'request_and_provider_list', 'lg6_please_select_d', 'Sila pilih julat tarikh', 'ma'),
(388, 'common_used_texts', 'lg7_upload_your_pic', 'UPLOAD YOUR PICTURE', 'en'),
(389, 'common_used_texts', 'lg7_upload_your_id', 'UPLOAD YOUR ID', 'en'),
(390, 'common_used_texts', 'lg7_upload_your_id', 'UPLOAD ID ANDA', 'ma'),
(391, 'common_used_texts', 'lg7_upload_your_pic', 'UPLOAD GAMBAR ANDA', 'ma'),
(392, 'navigation', 'lg3_change_password', 'Change Password', 'en'),
(393, 'navigation', 'lg3_change_password', ' Tukar kata laluan', 'ma'),
(394, 'common_used_texts', 'lg7_please_provide_', 'Please provide the required fields', 'en'),
(395, 'common_used_texts', 'lg7_please_provide_', ' Sila berikan medan yang diperlukan', 'ma'),
(396, 'sign_up', 'lg1_enter_current_p', 'Enter current password', 'en'),
(397, 'sign_up', 'lg1_enter_current_p', ' Masukkan kata laluan semasa', 'ma'),
(398, 'sign_up', 'lg1_enter_new_passw', 'Enter new password', 'en'),
(399, 'sign_up', 'lg1_enter_new_passw', ' Masukkan kata laluan baru', 'ma'),
(400, 'profile', 'lg4_change_password', 'Change password', 'en'),
(401, 'profile', 'lg4_change_password', 'TUKAR KATA LALUAN', 'ma'),
(402, 'sign_up', 'lg1_current_passwor', 'Current Password', 'en'),
(403, 'sign_up', 'lg1_current_passwor', 'Kata Laluan Semasa', 'ma'),
(404, 'sign_up', 'lg1_new_password', 'New Password', 'en'),
(405, 'sign_up', 'lg1_new_password', 'Kata laluan baharu', 'ma'),
(406, 'sign_up', 'lg1_confirm_new_pas', 'Confirm New Password', 'en'),
(407, 'sign_up', 'lg1_confirm_new_pas', 'Sahkan Kata Laluan Baru', 'ma'),
(408, 'common_used_texts', 'lg7_submit', 'Submit', 'en'),
(409, 'common_used_texts', 'lg7_submit', 'Hantar', 'ma'),
(410, 'api', 'lg11_validation_erro', 'validation error', 'en'),
(411, 'api', 'lg11_records_found', 'Records found', 'en'),
(412, 'api', 'lg11_invalid_login_d', 'Invalid login details', 'en'),
(413, 'api', 'lg11_login_username_', 'Login username or password is missing.', 'en'),
(414, 'api', 'lg11_invalid_toeknid', 'Invalid toeknid', 'en'),
(415, 'api', 'lg11_login_tokenid_i', 'Login tokenid is missing.', 'en'),
(416, 'api', 'lg11_login_type_is_n', 'Login type is not valid', 'en'),
(417, 'api', 'lg11_email_address_n', 'Email address not yet registered', 'en'),
(418, 'api', 'lg11_password_reset_', 'Password reset link send to your registered email address', 'en'),
(419, 'api', 'lg11_email_address_n1', 'Email address not yet register', 'en'),
(420, 'api', 'lg11_email_address_m', 'Email address missing.', 'en'),
(421, 'api', 'lg11_logout_successf', 'Logout successfully', 'en'),
(422, 'api', 'lg11_invalid_user_to', 'Invalid user token', 'en'),
(423, 'api', 'lg11_user_token_is_m', 'user token is missing.', 'en'),
(424, 'api', 'lg11_subscription_li', 'Subscription listed successfully', 'en'),
(425, 'api', 'lg11_new_provide_det', 'New provide details added successfully', 'en'),
(426, 'api', 'lg11_new_service_cre', 'New service created by', 'en'),
(427, 'api', 'lg11_this_title_alre', 'This title already exists', 'en'),
(428, 'api', 'lg11_something_is_wr', 'Something is wrong', 'en'),
(429, 'api', 'lg11_please_try_agai', 'please try again later', 'en'),
(430, 'api', 'lg11_required_input_', 'Required input is missing', 'en'),
(431, 'api', 'lg11_provide_details', 'Provide details update successfully', 'en'),
(432, 'api', 'lg11_service_details', 'Service details', 'en'),
(433, 'api', 'lg11_service_id_is_m', 'Service Id is missing', 'en'),
(434, 'api', 'lg11_service_removed', 'Service removed successfully', 'en'),
(435, 'api', 'lg11_request_removed', 'Request removed successfully', 'en'),
(436, 'api', 'lg11_provider_listed', 'Provider listed successfully', 'en'),
(437, 'api', 'lg11_invalid_page', 'Invalid page', 'en'),
(438, 'api', 'lg11_no_records_foun', 'No records found', 'en'),
(439, 'api', 'lg11_my_list', 'My list', 'en'),
(440, 'api', 'lg11_new_request_det', 'New request details added successfully', 'en'),
(441, 'api', 'lg11_request_details', 'Request details update successfully', 'en'),
(442, 'api', 'lg11_update_request_', 'Update request  by', 'en'),
(443, 'api', 'lg11_update_request', 'Update Request', 'en'),
(444, 'api', 'lg11_request_id_is_m', 'Request Id is missing', 'en'),
(445, 'api', 'lg11_new_subscriber_', 'New subscriber details has been added successfully', 'en'),
(446, 'api', 'lg11_profile', 'Profile', 'en'),
(447, 'api', 'lg11_profile_image_a', 'Profile image and IC card image save successfully', 'en'),
(448, 'api', 'lg11_required_images', 'Required images missing', 'en'),
(449, 'api', 'lg11_new_user_detail', 'New user details added successfully', 'en'),
(450, 'api', 'lg11_this_user_alrea', 'This user already exists', 'en'),
(451, 'api', 'lg11_profile_image_', 'Profile Image -', 'en'),
(452, 'api', 'lg11_ic_card_image_', 'IC Card Image -', 'en'),
(453, 'api', 'lg11_new_request_has', 'New request has been accept successfully', 'en'),
(454, 'api', 'lg11_not_yet_subscri', 'Not yet subscribed', 'en'),
(455, 'api', 'lg11_the_request_has', 'The request has been completed successfully', 'en'),
(456, 'api', 'lg11_request_listed_', 'Request listed successfully', 'en'),
(457, 'api', 'lg11_history_listed_', 'history listed successfully', 'en'),
(458, 'api', 'lg11_language_listed', 'Language listed successfully', 'en'),
(459, 'api', 'lg11_your_profile_up', 'Your profile updated successfully', 'en'),
(460, 'api', 'lg11_colours_listed_', 'Colours listed successfully', 'en'),
(461, 'api', 'lg11_notification_se', 'Notification send successfully', 'en'),
(462, 'api', 'lg11_chat_history_li', 'Chat history list', 'en'),
(463, 'api', 'lg11_success', 'Success', 'en'),
(464, 'api', 'lg11_stripe_payment_', 'Stripe payment success', 'en'),
(465, 'api', 'lg11_request_listed_', 'Permintaan tersenarai berjaya', 'ma'),
(466, 'api', 'lg11_no_records_foun', 'Tiada rekod dijumpai', 'ma'),
(467, 'api', 'lg11_records_found', 'Rekod ditemui', 'ma'),
(468, 'api', 'lg11_new_service', 'New Service', 'en'),
(469, 'common_used_texts', 'lg7_do_you_want_to_', 'Do you want to change your app language?', 'en'),
(470, 'common_used_texts', 'lg7_do_you_want_to_', ' Adakah anda mahu menukar bahasa apl anda?', 'ma'),
(471, 'api', 'lg11_update_service', 'Update Service', 'en'),
(472, 'common_used_texts', 'lg7_choose_a_langua', 'Choose a language', 'en'),
(473, 'common_used_texts', 'lg7_choose_a_langua', ' Pilih bahasa', 'ma'),
(474, 'api', 'lg11_new_request_cre', 'New request created by', 'en'),
(475, 'api', 'lg11_new_request', 'New Request', 'en'),
(476, 'common_used_texts', 'lg7_do_you_want_to_1', 'Do you want to change your location?', 'en'),
(477, 'api', 'lg11_required_input_1', 'Required input missing', 'en'),
(478, 'api', 'lg11_stripe_payment_1', 'Stripe payment issue', 'en'),
(479, 'common_used_texts', 'lg7_do_you_want_to_1', 'Adakah anda ingin menukar lokasi anda?', 'ma'),
(480, 'common_used_texts', 'lg7_choose_location', 'Choose Location', 'en'),
(481, 'common_used_texts', 'lg7_choose_location', ' Pilih Lokasi', 'ma'),
(482, 'api', 'lg11_stripe_payment_1', ' Isu pembayaran jalur', 'ma'),
(483, 'api', 'lg11_required_input_1', ' Input yang diperlukan hilang', 'ma'),
(484, 'api', 'lg11_new_request', ' Permintaan Baru', 'ma'),
(485, 'api', 'lg11_new_request_cre', ' Permintaan baharu dibuat oleh', 'ma'),
(486, 'api', 'lg11_update_service', 'Perkhidmatan Kemas Kini', 'ma'),
(487, 'api', 'lg11_new_service', 'Perkhidmatan Baru', 'ma'),
(488, 'api', 'lg11_stripe_payment_', ' Kejayaan pembayaran jalur', 'ma'),
(489, 'api', 'lg11_success', 'Kejayaan', 'ma'),
(490, 'api', 'lg11_chat_history_li', ' Senarai sejarah sembang', 'ma'),
(491, 'api', 'lg11_notification_se', ' Pemberitahuan menghantar berjaya', 'ma'),
(492, 'api', 'lg11_colours_listed_', ' Warna berjaya disenaraikan', 'ma'),
(493, 'api', 'lg11_your_profile_up', 'Profil anda berjaya dikemas kini', 'ma'),
(494, 'api', 'lg11_language_listed', ' Bahasa yang berjaya disenaraikan', 'ma'),
(495, 'api', 'lg11_history_listed_', ' sejarah berjaya disenaraikan', 'ma'),
(496, 'api', 'lg11_the_request_has', ' Permintaan ini telah berjaya diselesaikan', 'ma'),
(497, 'api', 'lg11_not_yet_subscri', ' Belum melanggan', 'ma'),
(498, 'api', 'lg11_new_request_has', ' Permintaan baru telah diterima dengan jayanya', 'ma'),
(499, 'api', 'lg11_ic_card_image_', ' Imej Kad IC -', 'ma'),
(500, 'api', 'lg11_profile_image_', ' Profil Imej -', 'ma'),
(501, 'api', 'lg11_this_user_alrea', 'Pengguna ini sudah wujud', 'ma'),
(502, 'api', 'lg11_new_user_detail', ' Butiran pengguna baru ditambah dengan jayanya', 'ma'),
(503, 'api', 'lg11_required_images', 'Imej yang diperlukan hilang', 'ma'),
(504, 'api', 'lg11_profile_image_a', ' Imej profil dan imej kad IC disimpan berjaya', 'ma'),
(505, 'api', 'lg11_profile', ' Profil', 'ma'),
(506, 'api', 'lg11_new_subscriber_', 'Butiran pelanggan baru telah berjaya ditambah', 'ma'),
(507, 'api', 'lg11_request_id_is_m', 'Permintaan Id hilang', 'ma'),
(508, 'api', 'lg11_update_request', ' Kemasukan Permintaan', 'ma'),
(509, 'api', 'lg11_update_request_', ' Kemas kini permintaan oleh', 'ma'),
(510, 'api', 'lg11_request_details', ' Kemasukan butiran kemasukan dengan jayanya', 'ma'),
(511, 'api', 'lg11_new_request_det', ' Butiran permintaan baru ditambah dengan jayanya', 'ma'),
(512, 'api', 'lg11_my_list', ' Senarai saya', 'ma'),
(513, 'api', 'lg11_invalid_page', ' Halaman tidak sah', 'ma'),
(514, 'api', 'lg11_provider_listed', 'Penyedia disenaraikan berjaya', 'ma'),
(515, 'api', 'lg11_request_removed', ' Permintaan dikeluarkan dengan jayanya', 'ma'),
(516, 'api', 'lg11_service_removed', ' Perkhidmatan dikeluarkan dengan jayanya', 'ma'),
(517, 'api', 'lg11_service_id_is_m', 'Id Perkhidmatan tiada', 'ma'),
(518, 'api', 'lg11_service_details', 'Butiran perkhidmatan', 'ma'),
(519, 'api', 'lg11_provide_details', ' Sediakan kemas kini terperinci dengan jayanya', 'ma'),
(520, 'api', 'lg11_required_input_', ' Input yang diperlukan hilang', 'ma'),
(521, 'api', 'lg11_please_try_agai', ' sila cuba sebentar lagi', 'ma'),
(522, 'api', 'lg11_something_is_wr', ' Sesuatu yang salah', 'ma'),
(523, 'api', 'lg11_this_title_alre', ' Tajuk ini sudah wujud', 'ma'),
(524, 'api', 'lg11_new_service_cre', ' Perkhidmatan baru yang dibuat oleh', 'ma'),
(525, 'api', 'lg11_new_provide_det', ' Baru memberikan maklumat yang ditambah dengan jayanya', 'ma'),
(526, 'api', 'lg11_subscription_li', ' Langganan tersenarai berjaya', 'ma'),
(527, 'api', 'lg11_user_token_is_m', ' token pengguna hilang.', 'ma'),
(528, 'api', 'lg11_invalid_user_to', ' Token pengguna tidak sah', 'ma'),
(529, 'api', 'lg11_logout_successf', ' Log keluar berjaya', 'ma'),
(530, 'api', 'lg11_email_address_m', ' Alamat e-mel hilang.', 'ma'),
(531, 'api', 'lg11_email_address_n1', 'Alamat e-mel belum mendaftar', 'ma'),
(532, 'api', 'lg11_password_reset_', ' Pautan semula kata laluan dihantar ke alamat e-mel berdaftar anda', 'ma'),
(533, 'api', 'lg11_email_address_n', ' Alamat e-mel belum didaftarkan', 'ma'),
(534, 'api', 'lg11_login_type_is_n', 'Jenis log masuk tidak sah', 'ma'),
(535, 'api', 'lg11_login_tokenid_i', ' Masuk tokenid hilang.', 'ma'),
(536, 'api', 'lg11_invalid_toeknid', ' Tohnid tidak sah', 'ma'),
(537, 'api', 'lg11_login_username_', ' Nama pengguna atau kata laluan masuk tiada.', 'ma'),
(538, 'api', 'lg11_invalid_login_d', ' Butiran log masuk tidak sah', 'ma'),
(539, 'api', 'lg11_validation_erro', ' ralat pengesahan', 'ma'),
(540, 'common_used_texts', 'lg7_enter_your_emai', 'Enter your E-mail Id', 'en'),
(541, 'common_used_texts', 'lg7_enter_your_emai', 'Masukkan Id E-mel anda', 'ma'),
(542, 'common_used_texts', 'lg7_do_you_know_you', 'Do you know your password?', 'en'),
(543, 'common_used_texts', 'lg7_do_you_know_you', 'Adakah anda tahu kata laluan anda?', 'ma'),
(544, 'common_used_texts', 'lg7_login', 'LogIn', 'en'),
(545, 'common_used_texts', 'lg7_login', ' Log masuk', 'ma'),
(546, 'subscriptions', 'lg5_payment_process', 'Payment Process', 'en'),
(547, 'subscription', 'lg9_payment_process', 'Payment', 'en'),
(548, 'subscription', 'lg9_payment_process', 'Pembayaran', 'ma'),
(549, 'subscription', 'lg9_upload_your_pic', 'UPLOAD YOUR PICTURE', 'en'),
(550, 'subscription', 'lg9_upload_your_pic', 'UPLOAD GAMBAR ANDA', 'ma'),
(551, 'subscription', 'lg9_takeupload', 'Take/Upload', 'en'),
(552, 'subscription', 'lg9_takeupload', 'Ambil/Muat naik', 'ma'),
(553, 'subscription', 'lg9_your_picture_th', 'your picture that you want to use with ServReP', 'en'),
(554, 'subscription', 'lg9_your_picture_th', 'gambar anda yang anda mahu gunakan dengan ServReP', 'ma'),
(555, 'subscription', 'lg9_upload_your_id', 'UPLOAD YOUR ID', 'en'),
(556, 'subscription', 'lg9_upload_your_id', 'UPLOAD ID ANDA', 'ma'),
(557, 'subscription', 'lg9_your_id_that_yo', 'your ID that you want to use with ServRep', 'en'),
(558, 'subscription', 'lg9_your_id_that_yo', 'ID anda yang anda mahu gunakan dengan ServRep', 'ma'),
(559, 'subscription', 'lg9_continue', 'Continue', 'en'),
(560, 'subscription', 'lg9_continue', 'Teruskan', 'ma'),
(561, 'subscription', 'lg9_thank_you', 'Thank You!', 'en'),
(562, 'subscription', 'lg9_thank_you', 'Terima kasih!', 'ma'),
(563, 'subscription', 'lg9_youre_going_to_', 'You\'re going to do great things with ServReP', 'en'),
(564, 'subscription', 'lg9_youre_going_to_', 'Anda akan melakukan perkara yang hebat dengan ServReP', 'ma'),
(565, 'subscription', 'lg9_lets_get_starte', 'Let\'s Get Started', 'en'),
(566, 'subscription', 'lg9_lets_get_starte', 'Mari kita mulakan', 'ma'),
(567, 'sign_up', 'lg1_this_username_i', 'This username is already exist', 'en'),
(568, 'sign_up', 'lg1_this_username_i', 'Nama pengguna ini sudah wujud', 'ma'),
(569, 'sign_up', 'lg1_this_email_addr', 'This email address is already exist', 'en'),
(570, 'sign_up', 'lg1_this_email_addr', 'Alamat e-mel ini sudah ada', 'ma'),
(571, 'sign_up', 'lg1_please_choose_p', 'Please choose profile image', 'en'),
(572, 'sign_up', 'lg1_please_choose_p', 'Sila pilih imej profil', 'ma'),
(573, 'web_validation', 'lg12_wrong_login_cre', 'Wrong login credentials.', 'en'),
(574, 'web_validation', 'lg12_logged_out_succ', 'Logged out successfully', 'en'),
(575, 'web_validation', 'lg12_logged_out_succ', 'Log keluar berjaya', 'ma'),
(576, 'web_validation', 'lg12_wrong_login_cre', 'Kelayakan log masuk salah.', 'ma'),
(577, 'web_validation', 'lg12_you_have_been_r', 'You have been registered successfully', 'en'),
(578, 'web_validation', 'lg12_something_wrong', 'Something wrong', 'en'),
(579, 'web_validation', 'lg12_please_try_agai', 'Please try again', 'en'),
(580, 'web_validation', 'lg12_you_have_been_r', 'Anda telah berjaya didaftarkan', 'ma'),
(581, 'web_validation', 'lg12_something_wrong', 'Sesuatu yang salah', 'ma'),
(582, 'web_validation', 'lg12_please_try_agai', 'Sila cuba lagi', 'ma'),
(583, 'web_validation', 'lg12_the_email_has_b', 'The email has been expired', 'en'),
(584, 'web_validation', 'lg12_the_password_up', 'The password updated successfully', 'en'),
(585, 'web_validation', 'lg12_please_login_ag', 'Please login again.', 'en'),
(586, 'web_validation', 'lg12_password_reset_', 'Password reset link send to your registered email address', 'en'),
(587, 'web_validation', 'lg12_invalid_usernam', 'Invalid username or email address', 'en'),
(588, 'web_validation', 'lg12_the_email_has_b', 'E-mel telah tamat tempoh', 'ma'),
(589, 'web_validation', 'lg12_the_password_up', 'Kata laluan berjaya dikemas kini', 'ma'),
(590, 'web_validation', 'lg12_please_login_ag', 'Sila log masuk semula.', 'ma'),
(591, 'web_validation', 'lg12_password_reset_', 'Pautan semula kata laluan dihantar ke alamat e-mel berdaftar anda', 'ma'),
(592, 'web_validation', 'lg12_invalid_usernam', 'Nama pengguna atau alamat e-mel tidak sah', 'ma'),
(593, 'forgot_password', 'lg13_forgot_password', 'Forgot Password', 'en'),
(594, 'forgot_password', 'lg13_enter_your_deta', 'Enter your details below', 'en'),
(595, 'forgot_password', 'lg13_username_or_ema', 'Username or Email', 'en'),
(596, 'forgot_password', 'lg13_please_enter_yo', 'Please enter your username or email address', 'en'),
(597, 'forgot_password', 'lg13_username_or_ema1', 'Username or email address is not correct', 'en'),
(598, 'forgot_password', 'lg13_reset_password', 'Reset Password', 'en'),
(599, 'forgot_password', 'lg13_back_to_login', 'Back to Login', 'en'),
(600, 'forgot_password', 'lg13_forgot_password', 'Lupa kata laluan', 'ma'),
(601, 'forgot_password', 'lg13_enter_your_deta', 'Masukkan butiran anda di bawah', 'ma'),
(602, 'forgot_password', 'lg13_username_or_ema', 'Nama pengguna atau e-mel', 'ma'),
(603, 'forgot_password', 'lg13_please_enter_yo', 'Sila masukkan nama pengguna atau alamat e-mel anda', 'ma'),
(604, 'forgot_password', 'lg13_username_or_ema1', 'Nama pengguna atau alamat e-mel tidak betul', 'ma'),
(605, 'forgot_password', 'lg13_reset_password', 'Menetapkan semula kata laluan', 'ma'),
(606, 'forgot_password', 'lg13_back_to_login', 'Kembali ke Log masuk', 'ma'),
(607, 'navigation', 'lg3_add_request', 'Add Request', 'en'),
(608, 'navigation', 'lg3_add_request', 'Tambah Permintaan', 'ma'),
(609, 'navigation', 'lg3_add_service', 'Add Service', 'en'),
(610, 'navigation', 'lg3_add_service', 'Tambah Perkhidmatan', 'ma'),
(611, 'navigation', 'lg3_request_a_servi', 'Request a Service', 'en'),
(612, 'navigation', 'lg3_provide_a_servi', 'Provide a Service', 'en'),
(613, 'navigation', 'lg3_change_location', 'Change Location', 'en'),
(614, 'navigation', 'lg3_toggle_navigati', 'Toggle navigation', 'en'),
(615, 'navigation', 'lg3_request_a_servi', 'Minta Perkhidmatan', 'ma'),
(616, 'navigation', 'lg3_provide_a_servi', 'Menyediakan Perkhidmatan', 'ma'),
(617, 'navigation', 'lg3_change_location', 'Tukar Lokasi', 'ma'),
(618, 'navigation', 'lg3_toggle_navigati', 'Togol navigasi', 'ma'),
(619, 'navigation', 'lg3_all_rights_rese', 'All rights reserved.', 'en'),
(620, 'navigation', 'lg3_all_rights_rese', 'Hak cipta terpelihara.', 'ma'),
(621, 'request_and_provider_list', 'lg6_request_a_servi', 'Request a service', 'en'),
(622, 'request_and_provider_list', 'lg6_request_a_servi', 'Meminta perkhidmatan', 'ma'),
(623, 'request_and_provider_list', 'lg6_title_already_e', 'Title already exists', 'en'),
(624, 'request_and_provider_list', 'lg6_title_already_e', 'Tajuk sudah wujud', 'ma'),
(625, 'request_and_provider_list', 'lg6_description_poi', 'Description Point', 'en'),
(626, 'request_and_provider_list', 'lg6_description_poi', 'Titik Huraian', 'ma'),
(627, 'web_validation', 'lg12_new_request_has', 'New request has been created successfully', 'en'),
(628, 'web_validation', 'lg12_new_request_has', 'Permintaan baru telah berjaya dibuat', 'ma'),
(629, 'web_validation', 'lg12_your_request_ha', 'Your request has been updated successfully', 'en'),
(630, 'web_validation', 'lg12_your_request_ha', 'Permintaan anda telah berjaya dikemas kini', 'ma'),
(631, 'web_validation', 'lg12_sorry', 'Sorry', 'en'),
(632, 'web_validation', 'lg12_something_went_', 'Something went wrong', 'en'),
(633, 'web_validation', 'lg12_sorry', 'Maaf', 'ma'),
(634, 'web_validation', 'lg12_something_went_', 'Ada yang salah', 'ma'),
(635, 'web_validation', 'lg12_request_has_bee', 'Request has been accepted', 'en'),
(636, 'web_validation', 'lg12_request_has_bee', 'Permintaan telah diterima', 'ma'),
(637, 'web_validation', 'lg12_your_request_ha1', 'Your request has been deleted', 'en'),
(638, 'web_validation', 'lg12_you_cant_delete', 'you can\'t delete this request', 'en'),
(639, 'web_validation', 'lg12_someone_accepte', 'someone accepted this request', 'en'),
(640, 'web_validation', 'lg12_your_request_ha1', 'Permintaan telah diterima', 'ma'),
(641, 'web_validation', 'lg12_you_cant_delete', 'anda tidak boleh memadamkan permintaan ini', 'ma'),
(642, 'web_validation', 'lg12_someone_accepte', 'seseorang menerima permintaan ini', 'ma'),
(643, 'common_used_texts', 'lg7_change_language', 'Change Language', 'en'),
(644, 'common_used_texts', 'lg7_change_language', 'Tukar bahasa', 'ma'),
(645, 'common_used_texts', 'lg7_change_location', 'Change Location', 'en'),
(646, 'request_and_provider_list', 'lg6_provide_a_servi', 'Provide a service', 'en'),
(647, 'request_and_provider_list', 'lg6_provide_a_servi', 'Menyediakan perkhidmatan', 'ma'),
(648, 'common_used_texts', 'lg7_change_location', 'Tukar Lokasi', 'ma'),
(649, 'request_and_provider_list', 'lg6_start_date', 'Start date', 'en'),
(650, 'request_and_provider_list', 'lg6_end_date', 'End Date', 'en'),
(651, 'request_and_provider_list', 'lg6_start_date', 'Tarikh mula', 'ma'),
(652, 'request_and_provider_list', 'lg6_end_date', 'Tarikh tamat', 'ma'),
(653, 'web_validation', 'lg12_new_service_has', 'New service has been created successfully', 'en'),
(654, 'web_validation', 'lg12_new_service_has', 'Perkhidmatan baru telah berjaya dibuat', 'ma'),
(655, 'web_validation', 'lg12_your_service_ha', 'Your service has been updated successfully', 'en'),
(656, 'web_validation', 'lg12_your_service_ha', 'Perkhidmatan anda telah berjaya dikemas kini', 'ma'),
(657, 'web_validation', 'lg12_request_complet', 'Request completed successfully', 'en'),
(658, 'web_validation', 'lg12_request_complet', 'Permintaan selesai dengan jayanya', 'ma'),
(659, 'web_validation', 'lg12_request_decline', 'Request declined successfully', 'en'),
(660, 'web_validation', 'lg12_request_decline', 'Permintaan menurun dengan jayanya', 'ma'),
(661, 'web_validation', 'lg12_your_service_ha1', 'Your service has been deleted', 'en'),
(662, 'web_validation', 'lg12_your_service_ha1', 'Perkhidmatan anda telah dipadam', 'ma'),
(663, 'home', 'lg14_search_your_nee', 'Search your needs here', 'en'),
(664, 'home', 'lg14_search_your_nee', 'Cari keperluan anda di sini', 'ma'),
(665, 'home', 'lg14_what_is_servrep', 'What is servrep?', 'en'),
(666, 'home', 'lg14_who_is_it_for', 'Who is it for?', 'en'),
(667, 'home', 'lg14_why_servrep', 'Why Servrep?', 'en'),
(668, 'home', 'lg14_service_request', 'Service Requests', 'en'),
(669, 'home', 'lg14_appointment', 'Appointment', 'en'),
(670, 'home', 'lg14_no_details_were', 'No details were found', 'en'),
(671, 'home', 'lg14_service_provide', 'Service Providers', 'en'),
(672, 'home', 'lg14_chat', 'CHAT', 'en'),
(673, 'home', 'lg14_yes', 'Yes', 'en'),
(674, 'home', 'lg14_cancel', 'Cancel', 'en'),
(675, 'home', 'lg14_please_do_subsc', 'Please do subscribe to accept this request', 'en'),
(676, 'home', 'lg14_are_you_sure_wa', 'Are you sure want to subscribe?', 'en'),
(677, 'home', 'lg14_subscribe', 'Subscribe', 'en'),
(678, 'home', 'lg14_what_is_servrep', 'Apa servrep?', 'ma'),
(679, 'home', 'lg14_who_is_it_for', 'Ini untuk siapa?', 'ma'),
(680, 'home', 'lg14_why_servrep', 'Mengapa Pengusaha?', 'ma'),
(681, 'home', 'lg14_service_request', 'Permintaan Perkhidmatan', 'ma'),
(682, 'home', 'lg14_appointment', 'Pelantikan', 'ma'),
(683, 'home', 'lg14_no_details_were', 'Tiada butiran ditemui', 'ma'),
(684, 'home', 'lg14_service_provide', 'Penyedia Perkhidmatan', 'ma'),
(685, 'home', 'lg14_chat', 'Sembang', 'ma'),
(686, 'home', 'lg14_yes', 'Ya', 'ma'),
(687, 'home', 'lg14_cancel', 'Batalkan', 'ma'),
(688, 'home', 'lg14_please_do_subsc', 'Sila langgan untuk menerima permintaan ini', 'ma'),
(689, 'home', 'lg14_are_you_sure_wa', 'Adakah anda pasti ingin melanggan?', 'ma'),
(690, 'home', 'lg14_subscribe', 'Langgan', 'ma'),
(691, 'home', 'lg14_search_a_servic', 'Search a service...', 'en'),
(692, 'home', 'lg14_search_a_servic', 'Cari perkhidmatan ...', 'ma'),
(693, 'home', 'lg14_contact_number', 'Contact Number', 'en'),
(694, 'home', 'lg14_contact_number', 'Nombor telefon', 'ma'),
(695, 'request_and_provider_list', 'lg6_requests', 'Requests', 'en'),
(696, 'request_and_provider_list', 'lg6_requests', 'Permintaan', 'ma'),
(697, 'request_and_provider_list', 'lg6_search', 'Search', 'en'),
(698, 'request_and_provider_list', 'lg6_search', 'Carian', 'ma'),
(699, 'request_and_provider_list', 'lg6_min_price', 'Min Price', 'en'),
(700, 'request_and_provider_list', 'lg6_min_price', 'Min Harga', 'ma'),
(701, 'request_and_provider_list', 'lg6_max_price', 'Max Price', 'en'),
(702, 'request_and_provider_list', 'lg6_advanced_search', 'Advanced Search', 'en'),
(703, 'request_and_provider_list', 'lg6_reset', 'Reset', 'en'),
(704, 'request_and_provider_list', 'lg6_max_price', 'Harga Maks', 'ma'),
(705, 'request_and_provider_list', 'lg6_advanced_search', 'carian terperinci', 'ma'),
(706, 'request_and_provider_list', 'lg6_reset', 'Tetapkan semula', 'ma'),
(707, 'request_and_provider_list', 'lg6_no_details_were', 'No details were found', 'en'),
(708, 'request_and_provider_list', 'lg6_no_details_were', 'Tiada butiran ditemui', 'ma'),
(709, 'request_and_provider_list', 'lg6_my_requests', 'My Requests', 'en');
INSERT INTO `language_management` (`sno`, `page_key`, `lang_key`, `lang_value`, `language`) VALUES
(710, 'request_and_provider_list', 'lg6_my_requests', 'Permintaan saya', 'ma'),
(711, 'request_and_provider_list', 'lg6_edit', 'Edit', 'en'),
(712, 'request_and_provider_list', 'lg6_delete', 'Delete', 'en'),
(713, 'request_and_provider_list', 'lg6_edit', 'Edit', 'ma'),
(714, 'request_and_provider_list', 'lg6_delete', 'Padam', 'ma'),
(715, 'request_and_provider_list', 'lg6_yes', 'Yes', 'en'),
(716, 'request_and_provider_list', 'lg6_cancel', 'Cancel', 'en'),
(717, 'request_and_provider_list', 'lg6_yes', 'Ya', 'ma'),
(718, 'request_and_provider_list', 'lg6_cancel', 'Batalkan', 'ma'),
(719, 'request_and_provider_list', 'lg6_delete_request', 'Delete Request', 'en'),
(720, 'request_and_provider_list', 'lg6_are_you_sure_wa', 'Are you sure want to delete this request?', 'en'),
(721, 'request_and_provider_list', 'lg6_delete_request', 'Hapuskan Permintaan', 'ma'),
(722, 'request_and_provider_list', 'lg6_are_you_sure_wa', 'Adakah anda pasti mahu memadamkan permintaan ini?', 'ma'),
(723, 'request_and_provider_list', 'lg6_expecting_fee1', 'Expecting Fee', 'en'),
(724, 'request_and_provider_list', 'lg6_expecting_fee1', 'Mengharapkan Yuran', 'ma'),
(725, 'request_and_provider_list', 'lg6_are_you_sure_wa1', 'Are you sure want to accept this request?', 'en'),
(726, 'request_and_provider_list', 'lg6_are_you_sure_wa1', 'Adakah anda pasti mahu menerima permintaan ini?', 'ma'),
(727, 'request_and_provider_list', 'lg6_subscribe', 'Subscribe', 'en'),
(728, 'request_and_provider_list', 'lg6_please_do_subsc', 'Please do subscribe to accept this request', 'en'),
(729, 'request_and_provider_list', 'lg6_are_you_sure_wa2', 'Are you sure want to subscribe?', 'en'),
(730, 'request_and_provider_list', 'lg6_subscribe', 'Langgan', 'ma'),
(731, 'request_and_provider_list', 'lg6_please_do_subsc', 'Sila langgan untuk menerima permintaan ini', 'ma'),
(732, 'request_and_provider_list', 'lg6_services', 'Services', 'en'),
(733, 'request_and_provider_list', 'lg6_services', 'Perkhidmatan', 'ma'),
(734, 'request_and_provider_list', 'lg6_my_services', 'My Services', 'en'),
(735, 'request_and_provider_list', 'lg6_my_services', 'Perkhidmatan Saya', 'ma'),
(737, 'request_and_provider_list', 'lg6_delete_service', 'Delete Service', 'en'),
(738, 'request_and_provider_list', 'lg6_delete_service', 'Padam Perkhidmatan', 'ma'),
(739, 'request_and_provider_list', 'lg6_history', 'History', 'en'),
(740, 'request_and_provider_list', 'lg6_history', 'Sejarah', 'ma'),
(741, 'forgot_password', 'lg13_new_password', 'New Password', 'en'),
(742, 'forgot_password', 'lg13_confirm_passwor', 'Confirm Password', 'en'),
(743, 'forgot_password', 'lg13_submit', 'Submit', 'en'),
(744, 'forgot_password', 'lg13_new_password', 'Kata laluan baharu', 'ma'),
(745, 'forgot_password', 'lg13_confirm_passwor', 'Sahkan Kata Laluan', 'ma'),
(746, 'forgot_password', 'lg13_submit', 'Hantar', 'ma'),
(747, 'forgot_password', 'lg13_please_enter_yo1', 'Please enter your New password', 'en'),
(748, 'forgot_password', 'lg13_please_reenter_', 'Please Re-enter your New password', 'en'),
(749, 'forgot_password', 'lg13_the_new_passwor', 'The new password and its confirm are not the same', 'en'),
(750, 'forgot_password', 'lg13_please_enter_yo1', 'Sila masukkan kata laluan Baharu anda', 'ma'),
(751, 'forgot_password', 'lg13_please_reenter_', 'Sila masukkan semula kata laluan baru anda', 'ma'),
(752, 'forgot_password', 'lg13_the_new_passwor', 'Kata laluan baru dan pengesahannya tidak sama', 'ma'),
(753, 'forgot_password', 'lg13_password_should', 'Password should have min 8 chars', 'en'),
(754, 'forgot_password', 'lg13_uppercase', 'uppercase', 'en'),
(755, 'forgot_password', 'lg13_lowercase', 'lowercase', 'en'),
(756, 'forgot_password', 'lg13_number_and_spec', 'number and special character', 'en'),
(757, 'forgot_password', 'lg13_password_should', 'Kata laluan harus mempunyai min 8 aksara', 'ma'),
(758, 'forgot_password', 'lg13_uppercase', 'huruf besar', 'ma'),
(759, 'forgot_password', 'lg13_lowercase', 'huruf kecil', 'ma'),
(760, 'forgot_password', 'lg13_number_and_spec', 'nombor dan watak istimewa', 'ma'),
(761, 'profile', 'lg4_my_profile', 'My Profile', 'en'),
(762, 'profile', 'lg4_edit_profile', 'Edit Profile', 'en'),
(763, 'profile', 'lg4_my_profile', 'Profil saya', 'ma'),
(764, 'profile', 'lg4_edit_profile', 'Sunting profil', 'ma'),
(765, 'profile', 'lg4_phone_number', 'Phone Number', 'en'),
(766, 'profile', 'lg4_phone_number', 'Nombor telefon', 'ma'),
(767, 'common_used_texts', 'lg7_chat_history', 'Chat History', 'en'),
(768, 'common_used_texts', 'lg7_chat_history', 'Sejarah Sembang', 'ma'),
(769, 'common_used_texts', 'lg7_no_details_were', 'No details were found', 'en'),
(770, 'common_used_texts', 'lg7_no_details_were', 'Tiada butiran ditemui', 'ma'),
(771, 'profile', 'lg4_submit', 'submit', 'en'),
(772, 'profile', 'lg4_submit', 'hantar', 'ma'),
(773, 'common_used_texts', 'lg7_type_something', 'Type something...', 'en'),
(774, 'common_used_texts', 'lg7_type_something', 'Taip sesuatu ...', 'ma'),
(775, 'profile', 'lg4_current_passwor', 'Current Password', 'en'),
(776, 'profile', 'lg4_new_password', 'New Password', 'en'),
(777, 'profile', 'lg4_confirm_passwor', 'Confirm Password', 'en'),
(778, 'profile', 'lg4_please_enter_cu', 'Please enter current password', 'en'),
(779, 'profile', 'lg4_please_enter_ne', 'Please enter new password', 'en'),
(780, 'profile', 'lg4_please_enter_re', 'Please enter repeat password', 'en'),
(781, 'profile', 'lg4_please_enter_yo', 'Please enter your mobile number', 'en'),
(782, 'profile', 'lg4_repeat_password', 'Repeat password is not matched', 'en'),
(783, 'profile', 'lg4_password_should', 'Password should have min 8 chars uppercase lowercase number and special character', 'en'),
(784, 'profile', 'lg4_current_passwor', 'Kata Laluan Semasa', 'ma'),
(785, 'profile', 'lg4_new_password', 'Kata laluan baharu', 'ma'),
(786, 'profile', 'lg4_confirm_passwor', 'Sahkan Kata Laluan', 'ma'),
(787, 'profile', 'lg4_please_enter_cu', 'Sila masukkan kata laluan semasa', 'ma'),
(788, 'profile', 'lg4_please_enter_ne', 'Sila masukkan kata laluan baru', 'ma'),
(789, 'profile', 'lg4_please_enter_re', 'Sila masukkan kata laluan berulang', 'ma'),
(790, 'profile', 'lg4_please_enter_yo', 'Sila masukkan nombor telefon bimbit anda', 'ma'),
(791, 'profile', 'lg4_repeat_password', 'Ulang kata laluan tidak sepadan', 'ma'),
(792, 'profile', 'lg4_password_should', 'Kata laluan harus mempunyai min huruf kecil huruf kecil dan aksara khas', 'ma'),
(793, 'profile', 'lg4_please_enter_di', 'Please enter different password', 'en'),
(794, 'profile', 'lg4_please_enter_di', 'Sila masukkan kata laluan yang berlainan', 'ma'),
(795, 'common_used_texts', 'lg7_subscription_li', 'Subscription List', 'en'),
(796, 'common_used_texts', 'lg7_subscription_li', 'Senarai Langganan', 'ma'),
(797, 'common_used_texts', 'lg7_you_have_been_s', 'You have been subscribed successfully!', 'en'),
(798, 'common_used_texts', 'lg7_you_have_been_s', 'Anda telah berjaya dilanggan!', 'ma'),
(799, 'common_used_texts', 'lg7_map', 'Map', 'en'),
(800, 'common_used_texts', 'lg7_drag_and_point_', 'Drag and point to get latitude and longitude', 'en'),
(801, 'common_used_texts', 'lg7_set_location', 'Set Location', 'en'),
(802, 'common_used_texts', 'lg7_add_location', 'Add Location', 'en'),
(803, 'common_used_texts', 'lg7_map', 'Peta', 'ma'),
(804, 'common_used_texts', 'lg7_drag_and_point_', 'Seret dan titik untuk mendapatkan latitud dan longitud', 'ma'),
(805, 'common_used_texts', 'lg7_set_location', 'Tetapkan Lokasi', 'ma'),
(806, 'common_used_texts', 'lg7_add_location', 'Tambah lokasi', 'ma'),
(807, 'common_used_texts', 'lg7_close', 'Close', 'en'),
(808, 'common_used_texts', 'lg7_close', 'Tutup', 'ma'),
(809, 'common_used_texts', 'lg7_search', 'Search', 'en'),
(810, 'common_used_texts', 'lg7_search', 'Carian', 'ma'),
(811, 'common_used_texts', 'lg7_accept_request', 'accept request', 'en'),
(812, 'common_used_texts', 'lg7_accept_request', 'terima permintaan', 'ma'),
(814, 'request_and_provider_list', 'lg6_subscribed', 'Subscribed', 'en'),
(815, 'request_and_provider_list', 'lg6_buy', 'Buy', 'en'),
(816, 'request_and_provider_list', 'lg6_subscribed', 'Melanggan', 'ma'),
(817, 'request_and_provider_list', 'lg6_buy', 'Beli', 'ma'),
(818, 'common_used_texts', 'lg7_subscribed', 'Subscribed', 'en'),
(819, 'common_used_texts', 'lg7_buy', 'Buy', 'en'),
(820, 'subscriptions', 'lg5_subscribed', 'Subscribed', 'en'),
(821, 'subscriptions', 'lg5_buy', 'Buy', 'en'),
(822, 'subscriptions', 'lg5_payment_process', 'Proses Pembayaran', 'ma'),
(823, 'subscriptions', 'lg5_subscribed', 'Melanggan', 'ma'),
(824, 'subscriptions', 'lg5_buy', 'Beli', 'ma'),
(825, 'sign_up', 'lg1_please_enter_va', 'Please enter valid email address', 'en'),
(826, 'sign_up', 'lg1_please_enter_va', 'Sila masukkan alamat e-mel yang sah', 'ma'),
(827, 'request_and_provider_list', 'lg6_please_do_subsc1', 'Please do subscribe to add service', 'en'),
(828, 'request_and_provider_list', 'lg6_please_do_subsc1', 'Sila langgan untuk menambahkan perkhidmatan', 'ma'),
(830, 'request_and_provider_list', 'lg6_filter', 'Filter', 'en'),
(831, 'request_and_provider_list', 'lg6_filter', 'Penapis', 'ma'),
(833, 'request_and_provider_list', 'lg6_are_you_sure_wa2', 'Adakah anda pasti ingin melanggan?', 'ma'),
(838, 'common_used_texts', 'lg7_buy', 'Beli', 'ma'),
(839, 'common_used_texts', 'lg7_subscribed', 'Melanggan', 'ma'),
(843, 'web_validation', 'lg7_you_have_been_s', 'Anda telah berjaya dilanggan!', 'ma'),
(844, 'web_validation', 'lg7_you_have_been_s', 'You have been subscribed successfully!', 'en'),
(849, 'request_and_provider_list', 'lg6_price', 'Price', 'en'),
(850, 'request_and_provider_list', 'lg6_price', 'Harga', 'ma'),
(851, 'sign_up', 'lg1_profile_image', 'Profile Image', 'en'),
(852, 'sign_up', 'lg1_select_image', 'Select Image', 'en'),
(853, 'sign_up', 'lg1_please_upload_a', 'Please Upload an Image', 'en'),
(854, 'sign_up', 'lg1_yes', 'Yes', 'en'),
(855, 'sign_up', 'lg1_save_changes', 'Save Changes', 'en'),
(856, 'sign_up', 'lg1_ic_image', 'IC Imagea', 'en'),
(857, 'sign_up', 'lg1_ic_image', 'Imej IC', 'ma'),
(858, 'sign_up', 'lg1_save_changes', 'Simpan Perubahan', 'ma'),
(859, 'sign_up', 'lg1_yes', 'Ya', 'ma'),
(860, 'sign_up', 'lg1_please_upload_a', 'Sila Muat naik Imej', 'ma'),
(861, 'sign_up', 'lg1_select_image', 'Pilih Imej', 'ma'),
(862, 'sign_up', 'lg1_profile_image', 'Imej Profil', 'ma'),
(863, 'profile', 'lg4_profile_image', 'Profile Image', 'en'),
(864, 'profile', 'lg4_select_image', 'Select Image', 'en'),
(865, 'profile', 'lg4_please_upload_a', 'Please Upload an Image', 'en'),
(866, 'profile', 'lg4_yes', 'Yes', 'en'),
(867, 'profile', 'lg4_save_changes', 'Save Changes', 'en'),
(868, 'profile', 'lg4_ic_image', 'ID Image', 'en'),
(869, 'profile', 'lg4_ic_image', 'Imej ID', 'ma'),
(870, 'profile', 'lg4_save_changes', 'Simpan Perubahan', 'ma'),
(871, 'profile', 'lg4_yes', 'Ya', 'ma'),
(872, 'profile', 'lg4_please_upload_a', 'Sila Muat naik Imej', 'ma'),
(873, 'profile', 'lg4_select_image', 'Pilih Imej', 'ma'),
(874, 'profile', 'lg4_profile_image', 'Imej Profil', 'ma'),
(876, 'profile', 'lg4_id_card', 'ID Card', 'en'),
(877, 'profile', 'lg4_id_card', 'Kad ID', 'ma'),
(878, 'request_and_provider_list', 'lg6_are_you_sure_wa3', 'Are you sure want to complete this request?', 'en'),
(879, 'request_and_provider_list', 'lg6_are_you_sure_wa3', 'Adakah anda pasti mahu melengkapkan permintaan ini?', 'ma'),
(880, 'api', 'lg11_user_details_no', 'User details not yet registered in our our system', 'en'),
(881, 'api', 'lg11_user_details_no', 'Butiran pengguna tidak lagi didaftarkan dalam sistem kami', 'ma'),
(882, 'common_used_texts', 'lg7_appointment_dat', 'Appointment Date', 'en'),
(883, 'common_used_texts', 'lg7_appointment_dat', 'Tarikh Pelantikan', 'ma'),
(884, 'common_used_texts', 'lg7_time', 'Time', 'en'),
(885, 'common_used_texts', 'lg7_time', 'Masa', 'ma'),
(886, 'common_used_texts', 'lg7_price', 'Price', 'en'),
(887, 'common_used_texts', 'lg7_price', 'Harga', 'ma'),
(888, 'common_used_texts', 'lg7_to', 'To', 'en'),
(889, 'common_used_texts', 'lg7_to', 'Untuk', 'ma'),
(890, 'common_used_texts', 'lg7_advanced_search', 'Advanced Search', 'en'),
(891, 'common_used_texts', 'lg7_advanced_search', 'carian terperinci', 'ma'),
(892, 'subscription', 'lg9_go_to_subscript', 'GO TO SUBSCRIPTION', 'en'),
(893, 'subscription', 'lg9_go_to_subscript', 'PERGI KE SUBSCRIPTION', 'ma'),
(894, 'subscription', 'lg9_thank_you_for_u', 'Thank you for upgrading your account!', 'en'),
(895, 'subscription', 'lg9_thank_you_for_u', ' Terima kasih kerana menaik taraf akaun anda!', 'ma'),
(896, 'subscription', 'lg9_buy_subscriptio', 'BUY SUBSCRIPTION', 'en'),
(897, 'subscription', 'lg9_buy_subscriptio', ' BELI LANGGANAN', 'ma'),
(898, 'request_and_provider_list', 'lg6_are_you_sure_wa4', 'Are you sure want to delete this service?', 'en'),
(899, 'request_and_provider_list', 'lg6_are_you_sure_wa4', 'Adakah anda pasti mahu memadamkan perkhidmatan ini?', 'ma'),
(900, 'request_and_provider_list', 'lg6_please_do_subsc2', 'Please do subscribe to chat', 'en'),
(901, 'request_and_provider_list', 'lg6_please_do_subsc2', 'Sila melanggan sembang', 'ma'),
(902, 'home', 'lg14_please_do_subsc1', 'Please do subscribe to chat', 'en'),
(903, 'home', 'lg14_please_do_subsc1', 'Sila melanggan sembang', 'ma'),
(904, 'home', 'lg14_search_request', 'Search request', 'en'),
(905, 'home', 'lg14_search_request', 'Permintaan carian', 'ma'),
(906, 'web_validation', 'lg12_existed_service', 'Existed service updated', 'en'),
(907, 'web_validation', 'lg12_new_service_add', 'New service added', 'en'),
(908, 'web_validation', 'lg12_new_service_add', 'Perkhidmatan baru ditambah', 'ma'),
(909, 'web_validation', 'lg12_existed_service', 'Perkhidmatan terdahulu dikemas kini', 'ma'),
(910, 'web_validation', 'lg12_accepted_your_r', 'has been accepted your request', 'en'),
(911, 'web_validation', 'lg12_accepted_your_r', 'telah diterima permintaan anda', 'ma'),
(912, 'request_and_provider_list', 'lg6_requestor', 'Requestor', 'en'),
(913, 'request_and_provider_list', 'lg6_requestor', 'Peminta', 'ma'),
(914, 'common_used_texts', 'lg7_turn_on_locatio', 'Turn On Location', 'en'),
(915, 'common_used_texts', 'lg7_turn_on_locatio', ' Hidupkan Lokasi', 'ma'),
(916, 'common_used_texts', 'lg7_location_access', 'LOCATION ACCESS', 'en'),
(917, 'common_used_texts', 'lg7_location_access', 'ACCESS LOKASI', 'ma'),
(918, 'common_used_texts', 'lg7_let_us_know_whe', 'Let us know where are you so we can recommend nearby services. Application would like to use your current location.', 'en'),
(919, 'common_used_texts', 'lg7_let_us_know_whe', 'Marilah kita tahu di mana anda jadi kami boleh mengesyorkan perkhidmatan berdekatan. Permohonan ingin menggunakan lokasi semasa anda.', 'ma'),
(920, 'home', 'lg14_why_servpro', 'why servpro', 'en'),
(921, 'home', 'lg14_what_is_servpro', 'what is servpro', 'en');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `p_id` int(11) NOT NULL,
  `page_key` varchar(255) NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateways`
--

CREATE TABLE `payment_gateways` (
  `id` int(11) NOT NULL,
  `gateway_name` varchar(50) NOT NULL,
  `gateway_type` varchar(20) NOT NULL,
  `api_key` varchar(50) NOT NULL,
  `value` varchar(70) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '(0 Inactive, 1 Active)',
  `created_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `provider_details`
--

CREATE TABLE `provider_details` (
  `p_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `description_details` longtext NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `availability` text NOT NULL,
  `latitude` varchar(20) NOT NULL,
  `location` varchar(250) NOT NULL,
  `longitude` varchar(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1-active,2-inactive,3-process,4-pending,5-complete',
  `delete_status` int(11) NOT NULL DEFAULT '0' COMMENT '1-delete,0 not delete',
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `request_accept_details`
--

CREATE TABLE `request_accept_details` (
  `id` int(11) NOT NULL,
  `acceptor_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `requester_id` int(11) NOT NULL,
  `status` int(11) NOT NULL COMMENT '1- accept,2- complete,3- decline',
  `accept_date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `request_details`
--

CREATE TABLE `request_details` (
  `r_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(256) CHARACTER SET utf8 NOT NULL,
  `description` longtext CHARACTER SET utf8 NOT NULL,
  `location` varchar(150) NOT NULL,
  `request_date` date NOT NULL,
  `request_time` time NOT NULL,
  `currency_code` char(5) NOT NULL DEFAULT 'Rm',
  `proposed_fee` double(10,2) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `latitude` varchar(20) NOT NULL,
  `longitude` varchar(20) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1-pending,2-expired',
  `delete_status` int(11) NOT NULL DEFAULT '0' COMMENT '1-delete,0 not delete',
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_details`
--

CREATE TABLE `subscription_details` (
  `id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `subscriber_id` int(11) NOT NULL,
  `subscription_date` datetime NOT NULL,
  `expiry_date_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_fee`
--

CREATE TABLE `subscription_fee` (
  `id` int(11) NOT NULL,
  `subscription_name` varchar(150) NOT NULL,
  `fee` double(10,2) NOT NULL,
  `currency_code` char(5) NOT NULL,
  `duration` int(11) NOT NULL COMMENT 'Duration in months',
  `fee_description` tinytext NOT NULL,
  `status` int(11) NOT NULL COMMENT '0-inactive,1-active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subscription_fee`
--

INSERT INTO `subscription_fee` (`id`, `subscription_name`, `fee`, `currency_code`, `duration`, `fee_description`, `status`) VALUES
(1, 'starter', 2.00, 'Rm', 1, 'per month', 1),
(2, 'Silver', 10.00, 'Rm', 6, 'per 6 month', 1),
(3, 'Gold', 15.00, 'Rm', 3, 'Per 3 Months', 1);

-- --------------------------------------------------------

--
-- Table structure for table `subscription_payment`
--

CREATE TABLE `subscription_payment` (
  `id` int(11) NOT NULL,
  `sub_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `subscriber_id` int(11) NOT NULL,
  `subscription_date` datetime NOT NULL,
  `tokenid` text NOT NULL,
  `payment_details` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` smallint(6) NOT NULL,
  `key` varchar(250) NOT NULL,
  `value` mediumtext NOT NULL,
  `system` tinyint(150) NOT NULL DEFAULT '1',
  `groups` varchar(150) NOT NULL,
  `update_date` date NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(150) NOT NULL,
  `forgot` varchar(256) NOT NULL,
  `email` varchar(150) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `profile_img` varchar(256) NOT NULL,
  `ic_card_image` varchar(256) NOT NULL,
  `latitude` varchar(20) NOT NULL,
  `longitude` varchar(20) NOT NULL,
  `mobile_no` varchar(20) NOT NULL,
  `role` tinyint(4) NOT NULL COMMENT '1-admin,2-user',
  `is_active` int(11) NOT NULL DEFAULT '0' COMMENT '0-inactive,1-active',
  `verified` int(11) NOT NULL DEFAULT '0' COMMENT '0-inactive,1-active',
  `unique_code` varchar(150) NOT NULL,
  `last_login` datetime NOT NULL,
  `token_valid` datetime NOT NULL,
  `tokenid` varchar(256) NOT NULL,
  `tokenid_gplus` text NOT NULL,
  `created` datetime NOT NULL,
  `register_through` int(11) NOT NULL COMMENT '1-normal,2-fb, 3-gplus'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `forgot`, `email`, `full_name`, `profile_img`, `ic_card_image`, `latitude`, `longitude`, `mobile_no`, `role`, `is_active`, `verified`, `unique_code`, `last_login`, `token_valid`, `tokenid`, `tokenid_gplus`, `created`, `register_through`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '', 'admin@admin.com', '', 'uploads/profile_img/1544530153.png', '', '', '', '', 1, 1, 1, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '0000-00-00 00:00:00', 0),
(2, 'benz', 'f3db6b7fb38fc1ec6a9fd58deabf0b3b', '', 'benz@dreamguys.co.in', 'benz', 'uploads/profile_img/1544505567.jpg', 'uploads/ic_card_image/', '', '', '9025348861', 2, 1, 1, '2ETaj75RdSSzhSr', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '', '', '2018-12-10 10:05:49', 1),
(3, 'BalajiRamasamy', '', '', 'balaji@dreamguys.co.in', 'BalajiRamasamy', '', '', '', '', '9865163926', 2, 1, 1, '3F8ALecIGv5ngXs', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '115842821409737685348', '', '2018-12-11 12:50:08', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_from` (`chat_from`),
  ADD KEY `chat_to` (`chat_to`);

--
-- Indexes for table `colour_settings`
--
ALTER TABLE `colour_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_details`
--
ALTER TABLE `device_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `language_management`
--
ALTER TABLE `language_management`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`p_id`);

--
-- Indexes for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider_details`
--
ALTER TABLE `provider_details`
  ADD PRIMARY KEY (`p_id`),
  ADD KEY `provider_details` (`user_id`);

--
-- Indexes for table `request_accept_details`
--
ALTER TABLE `request_accept_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_details`
--
ALTER TABLE `request_details`
  ADD PRIMARY KEY (`r_id`),
  ADD KEY `request_details` (`user_id`);

--
-- Indexes for table `subscription_details`
--
ALTER TABLE `subscription_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_fee`
--
ALTER TABLE `subscription_fee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscription_payment`
--
ALTER TABLE `subscription_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `colour_settings`
--
ALTER TABLE `colour_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `device_details`
--
ALTER TABLE `device_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `language_management`
--
ALTER TABLE `language_management`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=922;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_gateways`
--
ALTER TABLE `payment_gateways`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_details`
--
ALTER TABLE `provider_details`
  MODIFY `p_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request_accept_details`
--
ALTER TABLE `request_accept_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request_details`
--
ALTER TABLE `request_details`
  MODIFY `r_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_details`
--
ALTER TABLE `subscription_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscription_fee`
--
ALTER TABLE `subscription_fee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subscription_payment`
--
ALTER TABLE `subscription_payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `provider_details`
--
ALTER TABLE `provider_details`
  ADD CONSTRAINT `provider_details` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `request_details`
--
ALTER TABLE `request_details`
  ADD CONSTRAINT `request_details` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
