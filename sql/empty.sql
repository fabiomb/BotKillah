-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-07-2014 a las 01:12:13
-- Versión del servidor: 5.6.16
-- Versión de PHP: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `botkillah`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relacion`
--

CREATE TABLE IF NOT EXISTS `relacion` (
  `id_str_inicio` varchar(200) CHARACTER SET utf8 NOT NULL,
  `id_str_destino` varchar(200) CHARACTER SET utf8 NOT NULL,
  UNIQUE KEY `id_str_inicio` (`id_str_inicio`,`id_str_destino`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id_str` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `screen_name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `location` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `followers_count` bigint(20) DEFAULT NULL,
  `friends_count` bigint(20) DEFAULT NULL,
  `created_at` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `statuses_count` bigint(20) DEFAULT NULL,
  `lang` varchar(50) CHARACTER SET utf8 DEFAULT NULL,
  `visto` int(11) NOT NULL DEFAULT '0',
  `esbot` int(11) NOT NULL DEFAULT '0',
  `excluir` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `id_str_2` (`id_str`),
  KEY `id_str` (`id_str`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
