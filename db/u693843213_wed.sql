-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 31-03-2017 a las 18:23:09
-- Versión del servidor: 10.1.21-MariaDB
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u693843213_wed`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mercapagorequests`
--

CREATE TABLE `mercapagorequests` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `amount` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `mercapagorequests`
--

INSERT INTO `mercapagorequests` (`id`, `name`, `email`, `amount`) VALUES
(1, 'Ezequiel', 'zeqk@dasd.com', '100'),
(2, 'eze', 'zeqk.net@gmail.com', '200');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `wishlistthings`
--

CREATE TABLE `wishlistthings` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text,
  `url` text,
  `reserver` varchar(15) DEFAULT NULL,
  `reserverEmail` varchar(100) DEFAULT NULL,
  `cancelationCode` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `wishlistthings`
--

INSERT INTO `wishlistthings` (`id`, `title`, `description`, `url`, `reserver`, `reserverEmail`, `cancelationCode`) VALUES
(21, 'Plancha', NULL, NULL, 'eze', 'zeqk.net@gmail.com', '38613'),
(22, 'escoba', NULL, NULL, NULL, NULL, '');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `mercapagorequests`
--
ALTER TABLE `mercapagorequests`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `wishlistthings`
--
ALTER TABLE `wishlistthings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `mercapagorequests`
--
ALTER TABLE `mercapagorequests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `wishlistthings`
--
ALTER TABLE `wishlistthings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
