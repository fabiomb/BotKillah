--
-- Estructura de tabla para la tabla `relacion`
--

CREATE TABLE IF NOT EXISTS `relacion` (
  `id_str_inicio` varchar(200) CHARACTER SET utf8 NOT NULL,
  `id_str_destino` varchar(200) CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `relacion`
--
--ALTER TABLE `relacion`
--  ADD UNIQUE KEY `id_str_inicio` (`id_str_inicio`,`id_str_destino`);

ALTER TABLE `tuit` CHANGE `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT;

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

