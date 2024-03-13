-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 12-03-2024 a las 03:36:47
-- Versión del servidor: 8.0.17
-- Versión de PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `proyecto_14768`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas`
--

CREATE TABLE `asignaturas` (
  `ID_asignatura` int(11) NOT NULL,
  `DEPARTAMENTOS_ID_departamento` int(11) NOT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `asignaturas`
--

INSERT INTO `asignaturas` (`ID_asignatura`, `DEPARTAMENTOS_ID_departamento`, `codigo`, `nombre`) VALUES
(1, 1, 'A0J01', 'FUNDAMENTOS DE PROGRAMACIÓN'),
(3, 2, 'A0J02', 'COMPUTACIÓN GRÁFICA'),
(4, 3, 'A0J01', 'COMPUTACIÓN DIGITAL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignaturas_vigentes`
--

CREATE TABLE `asignaturas_vigentes` (
  `ID_asignatura_vigente` int(11) NOT NULL,
  `PERIODOS_ID_periodo` int(11) NOT NULL,
  `ASIGNATURAS_ID_asignatura` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `asignaturas_vigentes`
--

INSERT INTO `asignaturas_vigentes` (`ID_asignatura_vigente`, `PERIODOS_ID_periodo`, `ASIGNATURAS_ID_asignatura`) VALUES
(1, 1, 1),
(3, 1, 1),
(5, 2, 1),
(6, 2, 1),
(2, 1, 3),
(4, 1, 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas`
--

CREATE TABLE `aulas` (
  `ID_aula` int(11) NOT NULL,
  `codigo` varchar(4) DEFAULT NULL,
  `bloque` varchar(1) DEFAULT NULL,
  `piso` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `aulas`
--

INSERT INTO `aulas` (`ID_aula`, `codigo`, `bloque`, `piso`) VALUES
(1, '201', 'H', 2),
(2, '202', 'H', 2),
(3, '301', 'G', 3),
(4, '302', 'G', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas_disponibles`
--

CREATE TABLE `aulas_disponibles` (
  `ID_aula_disponible` int(11) NOT NULL,
  `AULAS_ID_aula` int(11) NOT NULL,
  `PERIODOS_ID_periodo` int(11) NOT NULL,
  `capacidad` int(11) DEFAULT '24'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `aulas_disponibles`
--

INSERT INTO `aulas_disponibles` (`ID_aula_disponible`, `AULAS_ID_aula`, `PERIODOS_ID_periodo`, `capacidad`) VALUES
(3, 1, 1, 24),
(5, 2, 1, 24),
(6, 1, 2, 24),
(7, 3, 1, 25),
(9, 4, 1, 24),
(10, 1, 1, 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `aulas_horarios`
--

CREATE TABLE `aulas_horarios` (
  `ID_aulas_horarios` int(11) NOT NULL,
  `HORARIOS_DISPONIBLES_ID_horario_disponible` int(11) NOT NULL,
  `AULAS_DISPONIBLES_ID_aula_disponible` int(11) NOT NULL,
  `estado` int(1) DEFAULT '0',
  `dia` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `aulas_horarios`
--

INSERT INTO `aulas_horarios` (`ID_aulas_horarios`, `HORARIOS_DISPONIBLES_ID_horario_disponible`, `AULAS_DISPONIBLES_ID_aula_disponible`, `estado`, `dia`) VALUES
(226, 1, 3, 0, 'Lunes'),
(227, 1, 3, 0, 'Martes'),
(228, 1, 3, 0, 'Miércoles'),
(229, 1, 3, 0, 'Jueves'),
(230, 1, 3, 0, 'Viernes'),
(231, 2, 3, 0, 'Lunes'),
(232, 2, 3, 0, 'Martes'),
(233, 2, 3, 0, 'Miércoles'),
(234, 2, 3, 0, 'Jueves'),
(235, 2, 3, 0, 'Viernes'),
(236, 3, 3, 0, 'Lunes'),
(237, 3, 3, 0, 'Martes'),
(238, 3, 3, 0, 'Miércoles'),
(239, 3, 3, 0, 'Jueves'),
(240, 3, 3, 0, 'Viernes'),
(241, 8, 3, 0, 'Lunes'),
(242, 8, 3, 0, 'Martes'),
(243, 8, 3, 0, 'Miércoles'),
(244, 8, 3, 0, 'Jueves'),
(245, 8, 3, 0, 'Viernes'),
(246, 1, 5, 0, 'Lunes'),
(247, 1, 5, 0, 'Martes'),
(248, 1, 5, 0, 'Miércoles'),
(249, 1, 5, 0, 'Jueves'),
(250, 1, 5, 0, 'Viernes'),
(251, 2, 5, 0, 'Lunes'),
(252, 2, 5, 0, 'Martes'),
(253, 2, 5, 0, 'Miércoles'),
(254, 2, 5, 0, 'Jueves'),
(255, 2, 5, 0, 'Viernes'),
(256, 3, 5, 0, 'Lunes'),
(257, 3, 5, 0, 'Martes'),
(258, 3, 5, 0, 'Miércoles'),
(259, 3, 5, 0, 'Jueves'),
(260, 3, 5, 0, 'Viernes'),
(261, 8, 5, 0, 'Lunes'),
(262, 8, 5, 0, 'Martes'),
(263, 8, 5, 0, 'Miércoles'),
(264, 8, 5, 0, 'Jueves'),
(265, 8, 5, 0, 'Viernes'),
(266, 1, 7, 0, 'Lunes'),
(267, 1, 7, 0, 'Martes'),
(268, 1, 7, 0, 'Miércoles'),
(269, 1, 7, 0, 'Jueves'),
(270, 1, 7, 0, 'Viernes'),
(271, 2, 7, 0, 'Lunes'),
(272, 2, 7, 0, 'Martes'),
(273, 2, 7, 0, 'Miércoles'),
(274, 2, 7, 0, 'Jueves'),
(275, 2, 7, 0, 'Viernes'),
(276, 3, 7, 0, 'Lunes'),
(277, 3, 7, 0, 'Martes'),
(278, 3, 7, 0, 'Miércoles'),
(279, 3, 7, 0, 'Jueves'),
(280, 3, 7, 0, 'Viernes'),
(281, 8, 7, 0, 'Lunes'),
(282, 8, 7, 0, 'Martes'),
(283, 8, 7, 0, 'Miércoles'),
(284, 8, 7, 0, 'Jueves'),
(285, 8, 7, 0, 'Viernes'),
(286, 1, 9, 0, 'Lunes'),
(287, 1, 9, 0, 'Martes'),
(288, 1, 9, 0, 'Miércoles'),
(289, 1, 9, 0, 'Jueves'),
(290, 1, 9, 0, 'Viernes'),
(291, 2, 9, 0, 'Lunes'),
(292, 2, 9, 0, 'Martes'),
(293, 2, 9, 0, 'Miércoles'),
(294, 2, 9, 0, 'Jueves'),
(295, 2, 9, 0, 'Viernes'),
(296, 3, 9, 0, 'Lunes'),
(297, 3, 9, 0, 'Martes'),
(298, 3, 9, 0, 'Miércoles'),
(299, 3, 9, 0, 'Jueves'),
(300, 3, 9, 0, 'Viernes'),
(301, 8, 9, 0, 'Lunes'),
(302, 8, 9, 0, 'Martes'),
(303, 8, 9, 0, 'Miércoles'),
(304, 8, 9, 0, 'Jueves'),
(305, 8, 9, 0, 'Viernes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `ID_carrera` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `carreras`
--

INSERT INTO `carreras` (`ID_carrera`, `nombre`) VALUES
(1, 'INGENIERÍA DE SOFTWARE'),
(2, 'INGENIERÍA EN TECNOLOGÍAS DE LA INFORMACIÓN');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras_vigentes`
--

CREATE TABLE `carreras_vigentes` (
  `ID_carrera_vigente` int(11) NOT NULL,
  `PERIODOS_ID_periodo` int(11) NOT NULL,
  `CARRERAS_ID_carrera` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `carreras_vigentes`
--

INSERT INTO `carreras_vigentes` (`ID_carrera_vigente`, `PERIODOS_ID_periodo`, `CARRERAS_ID_carrera`) VALUES
(1, 2, 1),
(2, 2, 1),
(3, 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `ID_departamento` int(11) NOT NULL,
  `codigo` varchar(10) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`ID_departamento`, `codigo`, `nombre`) VALUES
(1, 'COMP', 'CIENCIAS DE LA COMPUTACIÓN'),
(2, 'EXCT', 'CIENCIAS EXACTAS'),
(3, 'SEGD', 'SEGURIDAD Y DEFENSA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

CREATE TABLE `docentes` (
  `ID_docente` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `correo` varchar(100) DEFAULT NULL,
  `estado` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`ID_docente`, `nombre`, `correo`, `estado`) VALUES
(1, 'Eleana Jeréz', 'eijerez@espe.edu.ec', 1),
(2, 'César Villacis', 'cjvillacis@espe.edu.ec', 1),
(3, 'Nahir Carrera', 'ndcarrera2@espe.edu.ec', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes_activos`
--

CREATE TABLE `docentes_activos` (
  `ID_docente_activo` int(11) NOT NULL,
  `DOCENTES_ID_docente` int(11) NOT NULL,
  `PERIODOS_ID_periodo` int(11) NOT NULL,
  `jornada` varchar(45) DEFAULT NULL,
  `horas_presenciales` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `docentes_activos`
--

INSERT INTO `docentes_activos` (`ID_docente_activo`, `DOCENTES_ID_docente`, `PERIODOS_ID_periodo`, `jornada`, `horas_presenciales`) VALUES
(1, 1, 1, 'Medio tiempo', 25),
(2, 2, 1, 'Tiempo completo', 30),
(3, 1, 1, 'Tiempo completo', 40);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios`
--

CREATE TABLE `horarios` (
  `ID_horario` int(11) NOT NULL,
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `horarios`
--

INSERT INTO `horarios` (`ID_horario`, `hora_inicio`, `hora_fin`) VALUES
(1, '07:00:00', '08:59:00'),
(2, '09:00:00', '10:59:00'),
(3, '11:00:00', '12:59:00'),
(4, '13:30:00', '15:30:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_disponibles`
--

CREATE TABLE `horarios_disponibles` (
  `ID_horario_disponible` int(11) NOT NULL,
  `HORARIOS_ID_horario` int(11) NOT NULL,
  `PERIODOS_ID_periodo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `horarios_disponibles`
--

INSERT INTO `horarios_disponibles` (`ID_horario_disponible`, `HORARIOS_ID_horario`, `PERIODOS_ID_periodo`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1),
(8, 4, 1),
(4, 1, 2),
(5, 2, 2),
(6, 3, 2),
(7, 3, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `novedades`
--

CREATE TABLE `novedades` (
  `ID_novedad` int(11) NOT NULL,
  `RESERVA_AULA_ID_reserva` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `descripción` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `novedades`
--

INSERT INTO `novedades` (`ID_novedad`, `RESERVA_AULA_ID_reserva`, `fecha`, `descripción`) VALUES
(1, 24, '2024-03-11', 'Computador dañado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nrcs`
--

CREATE TABLE `nrcs` (
  `ID_NRC` int(11) NOT NULL,
  `ASIGNATURAS_VIGENTES_ID_asignatura_vigente` int(11) NOT NULL,
  `DOCENTES_ACTIVOS_ID_docente_activo` int(11) NOT NULL,
  `HORARIOS_DISPONIBLES_ID_horario_disponible` int(11) NOT NULL,
  `CARRERAS_VIGENTES_ID_carrera_vigente` int(11) NOT NULL,
  `PERIODOS_ID_periodo` int(11) NOT NULL,
  `codigo` int(11) DEFAULT NULL,
  `nivel` int(11) DEFAULT NULL,
  `horas_semanales` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `nrcs`
--

INSERT INTO `nrcs` (`ID_NRC`, `ASIGNATURAS_VIGENTES_ID_asignatura_vigente`, `DOCENTES_ACTIVOS_ID_docente_activo`, `HORARIOS_DISPONIBLES_ID_horario_disponible`, `CARRERAS_VIGENTES_ID_carrera_vigente`, `PERIODOS_ID_periodo`, `codigo`, `nivel`, `horas_semanales`) VALUES
(1, 1, 1, 1, 1, 1, 14772, 4, 6),
(4, 1, 2, 2, 3, 1, 2222, 4, 4),
(7, 4, 1, 1, 1, 1, 19999, 4, 6),
(8, 1, 2, 4, 3, 2, 1598, 2, 8),
(9, 1, 1, 1, 1, 1, 12345, 3, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE `perfiles` (
  `ID_perfil` int(11) NOT NULL,
  `tipo` varchar(45) DEFAULT NULL,
  `permisos` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `perfiles`
--

INSERT INTO `perfiles` (`ID_perfil`, `tipo`, `permisos`) VALUES
(1, 'Administrador', 'ALL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodos`
--

CREATE TABLE `periodos` (
  `ID_periodo` int(11) NOT NULL,
  `codigo` varchar(6) DEFAULT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `periodos`
--

INSERT INTO `periodos` (`ID_periodo`, `codigo`, `nombre`, `fecha_inicio`, `fecha_fin`) VALUES
(1, '202351', 'PREGRADO SII - OCT23 MAR24', '2023-10-06', '2024-03-08'),
(2, '202450', 'PREGRADO SI - ABR24 SEP24', '2024-04-15', '2024-09-06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reserva_aula`
--

CREATE TABLE `reserva_aula` (
  `ID_reserva` int(11) NOT NULL,
  `NRCS_ID_NRC` int(11) NOT NULL,
  `AULAS_HORARIOS_ID_aulas_horarios` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `reserva_aula`
--

INSERT INTO `reserva_aula` (`ID_reserva`, `NRCS_ID_NRC`, `AULAS_HORARIOS_ID_aulas_horarios`) VALUES
(24, 1, 226),
(25, 1, 228),
(26, 1, 230),
(36, 1, 236),
(37, 1, 238),
(38, 1, 240),
(34, 4, 227),
(35, 4, 229),
(39, 4, 227),
(40, 4, 229),
(21, 7, 231),
(22, 7, 233),
(23, 7, 235),
(27, 7, 226),
(28, 7, 228),
(29, 7, 230),
(41, 7, 246),
(42, 7, 248),
(43, 7, 250),
(30, 9, 231),
(31, 9, 232),
(32, 9, 233),
(33, 9, 234);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `ID_usuario` int(11) NOT NULL,
  `nombre` varchar(45) DEFAULT NULL,
  `contraseña` varchar(100) DEFAULT NULL,
  `PERFILES_ID_perfil` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`ID_usuario`, `nombre`, `contraseña`, `PERFILES_ID_perfil`) VALUES
(1, 'ndcarrera2', 'Abcd1234', 1),
(2, 'admin', '$2y$10$KQOQOvHk.j/U6e90Y5./GunAo4PRJXSMYvViz/bKF.LxaOepQb9R2', 1),
(4, 'pepe', '$2y$10$fbOdz.4u53zWv.O8nn5Dael9IxRx3ld169T8JhyAKgp8oWSkp8k66', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  ADD PRIMARY KEY (`ID_asignatura`,`DEPARTAMENTOS_ID_departamento`),
  ADD KEY `fk_ASIGNATURAS_DEPARTAMENTOS1_idx` (`DEPARTAMENTOS_ID_departamento`);

--
-- Indices de la tabla `asignaturas_vigentes`
--
ALTER TABLE `asignaturas_vigentes`
  ADD PRIMARY KEY (`ID_asignatura_vigente`,`PERIODOS_ID_periodo`,`ASIGNATURAS_ID_asignatura`),
  ADD KEY `fk_PERIODOS_has_ASIGNATURAS_ASIGNATURAS1_idx` (`ASIGNATURAS_ID_asignatura`),
  ADD KEY `fk_PERIODOS_has_ASIGNATURAS_PERIODOS1_idx` (`PERIODOS_ID_periodo`);

--
-- Indices de la tabla `aulas`
--
ALTER TABLE `aulas`
  ADD PRIMARY KEY (`ID_aula`);

--
-- Indices de la tabla `aulas_disponibles`
--
ALTER TABLE `aulas_disponibles`
  ADD PRIMARY KEY (`ID_aula_disponible`,`AULAS_ID_aula`,`PERIODOS_ID_periodo`),
  ADD KEY `fk_AULAS_has_PERIODOS_PERIODOS1_idx` (`PERIODOS_ID_periodo`),
  ADD KEY `fk_AULAS_has_PERIODOS_AULAS1_idx` (`AULAS_ID_aula`);

--
-- Indices de la tabla `aulas_horarios`
--
ALTER TABLE `aulas_horarios`
  ADD PRIMARY KEY (`ID_aulas_horarios`,`HORARIOS_DISPONIBLES_ID_horario_disponible`,`AULAS_DISPONIBLES_ID_aula_disponible`),
  ADD KEY `fk_HORARIOS_DISPONIBLES_has_AULAS_DISPONIBLES_AULAS_DISPONI_idx` (`AULAS_DISPONIBLES_ID_aula_disponible`),
  ADD KEY `fk_HORARIOS_DISPONIBLES_has_AULAS_DISPONIBLES_HORARIOS_DISP_idx` (`HORARIOS_DISPONIBLES_ID_horario_disponible`);

--
-- Indices de la tabla `carreras`
--
ALTER TABLE `carreras`
  ADD PRIMARY KEY (`ID_carrera`);

--
-- Indices de la tabla `carreras_vigentes`
--
ALTER TABLE `carreras_vigentes`
  ADD PRIMARY KEY (`ID_carrera_vigente`,`PERIODOS_ID_periodo`,`CARRERAS_ID_carrera`),
  ADD KEY `fk_PERIODOS_has_CARRERAS_CARRERAS1_idx` (`CARRERAS_ID_carrera`),
  ADD KEY `fk_PERIODOS_has_CARRERAS_PERIODOS1_idx` (`PERIODOS_ID_periodo`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`ID_departamento`);

--
-- Indices de la tabla `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`ID_docente`);

--
-- Indices de la tabla `docentes_activos`
--
ALTER TABLE `docentes_activos`
  ADD PRIMARY KEY (`ID_docente_activo`,`DOCENTES_ID_docente`,`PERIODOS_ID_periodo`),
  ADD KEY `fk_DOCENTES_has_PERIODOS_PERIODOS1_idx` (`PERIODOS_ID_periodo`),
  ADD KEY `fk_DOCENTES_has_PERIODOS_DOCENTES1_idx` (`DOCENTES_ID_docente`);

--
-- Indices de la tabla `horarios`
--
ALTER TABLE `horarios`
  ADD PRIMARY KEY (`ID_horario`);

--
-- Indices de la tabla `horarios_disponibles`
--
ALTER TABLE `horarios_disponibles`
  ADD PRIMARY KEY (`ID_horario_disponible`,`HORARIOS_ID_horario`,`PERIODOS_ID_periodo`),
  ADD KEY `fk_HORARIOS_has_PERIODOS_PERIODOS1_idx` (`PERIODOS_ID_periodo`),
  ADD KEY `fk_HORARIOS_has_PERIODOS_HORARIOS1_idx` (`HORARIOS_ID_horario`);

--
-- Indices de la tabla `novedades`
--
ALTER TABLE `novedades`
  ADD PRIMARY KEY (`ID_novedad`),
  ADD KEY `RESERVA_AULA_ID_reserva` (`RESERVA_AULA_ID_reserva`);

--
-- Indices de la tabla `nrcs`
--
ALTER TABLE `nrcs`
  ADD PRIMARY KEY (`ID_NRC`,`ASIGNATURAS_VIGENTES_ID_asignatura_vigente`,`DOCENTES_ACTIVOS_ID_docente_activo`,`HORARIOS_DISPONIBLES_ID_horario_disponible`,`CARRERAS_VIGENTES_ID_carrera_vigente`,`PERIODOS_ID_periodo`),
  ADD KEY `fk_NRCS_PERIODOS1_idx` (`PERIODOS_ID_periodo`),
  ADD KEY `fk_NRCS_DOCENTES_ACTIVOS1_idx` (`DOCENTES_ACTIVOS_ID_docente_activo`),
  ADD KEY `fk_NRCS_HORARIOS_DISPONIBLES1_idx` (`HORARIOS_DISPONIBLES_ID_horario_disponible`),
  ADD KEY `fk_NRCS_CARRERAS_VIGENTES1_idx` (`CARRERAS_VIGENTES_ID_carrera_vigente`),
  ADD KEY `fk_NRCS_ASIGNATURAS_VIGENTES1_idx` (`ASIGNATURAS_VIGENTES_ID_asignatura_vigente`);

--
-- Indices de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`ID_perfil`);

--
-- Indices de la tabla `periodos`
--
ALTER TABLE `periodos`
  ADD PRIMARY KEY (`ID_periodo`);

--
-- Indices de la tabla `reserva_aula`
--
ALTER TABLE `reserva_aula`
  ADD PRIMARY KEY (`ID_reserva`,`NRCS_ID_NRC`,`AULAS_HORARIOS_ID_aulas_horarios`),
  ADD KEY `fk_RESERVA_AULA_NRCS1_idx` (`NRCS_ID_NRC`),
  ADD KEY `fk_RESERVA_AULA_AULAS_HORARIOS1_idx` (`AULAS_HORARIOS_ID_aulas_horarios`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`ID_usuario`,`PERFILES_ID_perfil`),
  ADD KEY `fk_USUARIOS_PERFILES1_idx` (`PERFILES_ID_perfil`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asignaturas`
--
ALTER TABLE `asignaturas`
  MODIFY `ID_asignatura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `asignaturas_vigentes`
--
ALTER TABLE `asignaturas_vigentes`
  MODIFY `ID_asignatura_vigente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `aulas`
--
ALTER TABLE `aulas`
  MODIFY `ID_aula` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `aulas_disponibles`
--
ALTER TABLE `aulas_disponibles`
  MODIFY `ID_aula_disponible` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `aulas_horarios`
--
ALTER TABLE `aulas_horarios`
  MODIFY `ID_aulas_horarios` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=306;

--
-- AUTO_INCREMENT de la tabla `carreras`
--
ALTER TABLE `carreras`
  MODIFY `ID_carrera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `carreras_vigentes`
--
ALTER TABLE `carreras_vigentes`
  MODIFY `ID_carrera_vigente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `ID_departamento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `docentes`
--
ALTER TABLE `docentes`
  MODIFY `ID_docente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `docentes_activos`
--
ALTER TABLE `docentes_activos`
  MODIFY `ID_docente_activo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `horarios`
--
ALTER TABLE `horarios`
  MODIFY `ID_horario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `horarios_disponibles`
--
ALTER TABLE `horarios_disponibles`
  MODIFY `ID_horario_disponible` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `novedades`
--
ALTER TABLE `novedades`
  MODIFY `ID_novedad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `nrcs`
--
ALTER TABLE `nrcs`
  MODIFY `ID_NRC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `ID_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `periodos`
--
ALTER TABLE `periodos`
  MODIFY `ID_periodo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `reserva_aula`
--
ALTER TABLE `reserva_aula`
  MODIFY `ID_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `ID_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asignaturas_vigentes`
--
ALTER TABLE `asignaturas_vigentes`
  ADD CONSTRAINT `fk_PERIODOS_has_ASIGNATURAS_ASIGNATURAS1` FOREIGN KEY (`ASIGNATURAS_ID_asignatura`) REFERENCES `asignaturas` (`ID_asignatura`),
  ADD CONSTRAINT `fk_PERIODOS_has_ASIGNATURAS_PERIODOS1` FOREIGN KEY (`PERIODOS_ID_periodo`) REFERENCES `periodos` (`ID_periodo`);

--
-- Filtros para la tabla `aulas_disponibles`
--
ALTER TABLE `aulas_disponibles`
  ADD CONSTRAINT `fk_AULAS_has_PERIODOS_AULAS1` FOREIGN KEY (`AULAS_ID_aula`) REFERENCES `aulas` (`ID_aula`),
  ADD CONSTRAINT `fk_AULAS_has_PERIODOS_PERIODOS1` FOREIGN KEY (`PERIODOS_ID_periodo`) REFERENCES `periodos` (`ID_periodo`);

--
-- Filtros para la tabla `aulas_horarios`
--
ALTER TABLE `aulas_horarios`
  ADD CONSTRAINT `fk_HORARIOS_DISPONIBLES_has_AULAS_DISPONIBLES_AULAS_DISPONIBL1` FOREIGN KEY (`AULAS_DISPONIBLES_ID_aula_disponible`) REFERENCES `aulas_disponibles` (`ID_aula_disponible`),
  ADD CONSTRAINT `fk_HORARIOS_DISPONIBLES_has_AULAS_DISPONIBLES_HORARIOS_DISPON1` FOREIGN KEY (`HORARIOS_DISPONIBLES_ID_horario_disponible`) REFERENCES `horarios_disponibles` (`ID_horario_disponible`);

--
-- Filtros para la tabla `carreras_vigentes`
--
ALTER TABLE `carreras_vigentes`
  ADD CONSTRAINT `fk_PERIODOS_has_CARRERAS_CARRERAS1` FOREIGN KEY (`CARRERAS_ID_carrera`) REFERENCES `carreras` (`ID_carrera`),
  ADD CONSTRAINT `fk_PERIODOS_has_CARRERAS_PERIODOS1` FOREIGN KEY (`PERIODOS_ID_periodo`) REFERENCES `periodos` (`ID_periodo`);

--
-- Filtros para la tabla `docentes_activos`
--
ALTER TABLE `docentes_activos`
  ADD CONSTRAINT `fk_DOCENTES_has_PERIODOS_DOCENTES1` FOREIGN KEY (`DOCENTES_ID_docente`) REFERENCES `docentes` (`ID_docente`),
  ADD CONSTRAINT `fk_DOCENTES_has_PERIODOS_PERIODOS1` FOREIGN KEY (`PERIODOS_ID_periodo`) REFERENCES `periodos` (`ID_periodo`);

--
-- Filtros para la tabla `horarios_disponibles`
--
ALTER TABLE `horarios_disponibles`
  ADD CONSTRAINT `fk_HORARIOS_has_PERIODOS_HORARIOS1` FOREIGN KEY (`HORARIOS_ID_horario`) REFERENCES `horarios` (`ID_horario`),
  ADD CONSTRAINT `fk_HORARIOS_has_PERIODOS_PERIODOS1` FOREIGN KEY (`PERIODOS_ID_periodo`) REFERENCES `periodos` (`ID_periodo`);

--
-- Filtros para la tabla `novedades`
--
ALTER TABLE `novedades`
  ADD CONSTRAINT `novedades_ibfk_1` FOREIGN KEY (`RESERVA_AULA_ID_reserva`) REFERENCES `reserva_aula` (`ID_reserva`);

--
-- Filtros para la tabla `nrcs`
--
ALTER TABLE `nrcs`
  ADD CONSTRAINT `fk_NRCS_ASIGNATURAS_VIGENTES1` FOREIGN KEY (`ASIGNATURAS_VIGENTES_ID_asignatura_vigente`) REFERENCES `asignaturas_vigentes` (`ID_asignatura_vigente`),
  ADD CONSTRAINT `fk_NRCS_CARRERAS_VIGENTES1` FOREIGN KEY (`CARRERAS_VIGENTES_ID_carrera_vigente`) REFERENCES `carreras_vigentes` (`ID_carrera_vigente`),
  ADD CONSTRAINT `fk_NRCS_DOCENTES_ACTIVOS1` FOREIGN KEY (`DOCENTES_ACTIVOS_ID_docente_activo`) REFERENCES `docentes_activos` (`ID_docente_activo`),
  ADD CONSTRAINT `fk_NRCS_HORARIOS_DISPONIBLES1` FOREIGN KEY (`HORARIOS_DISPONIBLES_ID_horario_disponible`) REFERENCES `horarios_disponibles` (`ID_horario_disponible`),
  ADD CONSTRAINT `fk_NRCS_PERIODOS1` FOREIGN KEY (`PERIODOS_ID_periodo`) REFERENCES `periodos` (`ID_periodo`);

--
-- Filtros para la tabla `reserva_aula`
--
ALTER TABLE `reserva_aula`
  ADD CONSTRAINT `fk_RESERVA_AULA_AULAS_HORARIOS1` FOREIGN KEY (`AULAS_HORARIOS_ID_aulas_horarios`) REFERENCES `aulas_horarios` (`ID_aulas_horarios`),
  ADD CONSTRAINT `fk_RESERVA_AULA_NRCS1` FOREIGN KEY (`NRCS_ID_NRC`) REFERENCES `nrcs` (`ID_NRC`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_USUARIOS_PERFILES1` FOREIGN KEY (`PERFILES_ID_perfil`) REFERENCES `perfiles` (`ID_perfil`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
