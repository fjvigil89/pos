-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-11-2018 a las 05:16:09
-- Versión del servidor: 10.1.28-MariaDB
-- Versión de PHP: 5.6.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_login`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `phppos_resellers`
--

CREATE TABLE `phppos_resellers` (
  `id` bigint(20) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `payment_type` int(11) NOT NULL,
  `reseller_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `short_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nombre corto de la empresa',
  `system_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Nombre del sistema',
  `url_website` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `domain` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `system_slogan` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `system_logo` longtext COLLATE utf8_unicode_ci NOT NULL,
  `system_logo_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `system_logo_blob` longblob NOT NULL,
  `country` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `whatsapp` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `script_chat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name_demo` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_demo` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `store_demo` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url_apk` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `phppos_resellers`
--

INSERT INTO `phppos_resellers` (`id`, `user_id`, `payment_type`, `reseller_name`, `short_name`, `system_name`, `url_website`, `domain`, `system_slogan`, `system_logo`, `system_logo_type`, `system_logo_blob`, `country`, `city`, `email`, `phone`, `whatsapp`, `script_chat`, `status`, `created_at`, `updated_at`, `name_demo`, `password_demo`, `store_demo`, `url_apk`) VALUES
(1, 3, 0, 'No Aplica', 'SuperAdministrador', 'FacilPOS', '', '', 'FacilPOS', 'logo.png', '', '', 'guatemala', 'dtbrwnerb', '', '454354354343', '', NULL, 1, '2018-10-31 05:34:49', '2018-10-31 05:34:49', NULL, NULL, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `phppos_resellers`
--
ALTER TABLE `phppos_resellers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `store_name` (`reseller_name`),
  ADD UNIQUE KEY `reseller_user_unique` (`user_id`),
  ADD UNIQUE KEY `domain` (`domain`),
  ADD KEY `paymente_type` (`payment_type`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `phppos_resellers`
--
ALTER TABLE `phppos_resellers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `phppos_resellers`
--
ALTER TABLE `phppos_resellers`
  ADD CONSTRAINT `reseller_user_1to1` FOREIGN KEY (`user_id`) REFERENCES `phppos_users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
