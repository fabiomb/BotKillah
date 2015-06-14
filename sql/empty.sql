-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-06-2015 a las 22:23:29
-- Versión del servidor: 5.6.24
-- Versión de PHP: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `botkillah1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `relacion`
--

CREATE TABLE IF NOT EXISTS `relacion` (
  `id_str_inicio` varchar(200) CHARACTER SET utf8 NOT NULL,
  `id_str_destino` varchar(200) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tuit`
--

CREATE TABLE IF NOT EXISTS `tuit` (
  `id` bigint(20) NOT NULL,
  `id_str` varchar(200) NOT NULL,
  `screen_name` varchar(200) NOT NULL,
  `fecha` varchar(200) NOT NULL,
  `texto` text NOT NULL,
  `id_tuit` varchar(200) NOT NULL,
  `favorited` int(11) DEFAULT NULL,
  `retweeted` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=1477 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

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
  `excluir` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `relacion`
--
ALTER TABLE `relacion`
  ADD UNIQUE KEY `id_str_inicio` (`id_str_inicio`,`id_str_destino`);

--
-- Indices de la tabla `tuit`
--
ALTER TABLE `tuit`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD UNIQUE KEY `id_str_2` (`id_str`), ADD KEY `id_str` (`id_str`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tuit`
--
ALTER TABLE `tuit`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1477;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
