-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql302.infinityfree.com
-- Generation Time: Dec 08, 2025 at 04:58 PM
-- Server version: 11.4.7-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `dde_progreso` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `dde_progreso`;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `if0_40123270_dde_progreso`
--

-- --------------------------------------------------------

--
-- Table structure for table `basica_centro_cuenta`
--

CREATE TABLE `basica_centro_cuenta` (
  `id` int(11) NOT NULL,
  `nombramiento_id` int(11) NOT NULL,
  `centro_educativo` varchar(255) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `lugar` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basica_centro_cuenta`
--

INSERT INTO `basica_centro_cuenta` (`id`, `nombramiento_id`, `centro_educativo`, `cargo`, `lugar`, `municipio`) VALUES
(1, 1, 'Eduardo Hernandez Chevez', 'Consejeria', 'El Progreso', 'Yoro');

-- --------------------------------------------------------

--
-- Table structure for table `basica_centro_reubicacion`
--

CREATE TABLE `basica_centro_reubicacion` (
  `id` int(11) NOT NULL,
  `nombramiento_id` int(11) NOT NULL,
  `centro_educativo` varchar(255) DEFAULT NULL,
  `cargo` varchar(100) DEFAULT NULL,
  `lugar` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basica_centro_reubicacion`
--

INSERT INTO `basica_centro_reubicacion` (`id`, `nombramiento_id`, `centro_educativo`, `cargo`, `lugar`, `municipio`) VALUES
(1, 1, 'Jose Ramon Rosa', 'Sub-Director', 'El Progreso', 'Yoro');

-- --------------------------------------------------------

--
-- Table structure for table `basica_estructura_presupuestaria`
--

CREATE TABLE `basica_estructura_presupuestaria` (
  `id` int(11) NOT NULL,
  `nombramiento_id` int(11) NOT NULL,
  `estructura_inst` varchar(100) DEFAULT NULL,
  `estructura_programa` varchar(100) DEFAULT NULL,
  `estructura_subprograma` varchar(100) DEFAULT NULL,
  `estructura_fondo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basica_estructura_presupuestaria`
--

INSERT INTO `basica_estructura_presupuestaria` (`id`, `nombramiento_id`, `estructura_inst`, `estructura_programa`, `estructura_subprograma`, `estructura_fondo`) VALUES
(1, 1, '06', '13', '01', '11');

-- --------------------------------------------------------

--
-- Table structure for table `basica_estructura_siarhd`
--

CREATE TABLE `basica_estructura_siarhd` (
  `id` int(11) NOT NULL,
  `nombramiento_id` int(11) NOT NULL,
  `siarhd_depto` varchar(100) DEFAULT NULL,
  `siarhd_muni` varchar(100) DEFAULT NULL,
  `siarhd_centro` varchar(255) DEFAULT NULL,
  `siarhd_num_plaza` varchar(50) DEFAULT NULL,
  `siarhd_num_horas` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basica_estructura_siarhd`
--

INSERT INTO `basica_estructura_siarhd` (`id`, `nombramiento_id`, `siarhd_depto`, `siarhd_muni`, `siarhd_centro`, `siarhd_num_plaza`, `siarhd_num_horas`) VALUES
(1, 1, 'Yoro', 'prueba', 'pruebaprueba', '23', '120');

-- --------------------------------------------------------

--
-- Table structure for table `basica_firmas_validacion`
--

CREATE TABLE `basica_firmas_validacion` (
  `id` int(11) NOT NULL,
  `nombramiento_id` int(11) NOT NULL,
  `firma_docente` varchar(255) DEFAULT NULL,
  `telefono_contacto` varchar(50) DEFAULT NULL,
  `vb_director_centro` varchar(255) DEFAULT NULL,
  `vb_director_distrital` varchar(255) DEFAULT NULL,
  `firma_revisa` varchar(255) DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basica_firmas_validacion`
--

INSERT INTO `basica_firmas_validacion` (`id`, `nombramiento_id`, `firma_docente`, `telefono_contacto`, `vb_director_centro`, `vb_director_distrital`, `firma_revisa`, `fecha_entrega`) VALUES
(1, 1, 'Other', '99994343', 'firma', 'firma', 'Other', '2025-11-20');

-- --------------------------------------------------------

--
-- Table structure for table `basica_info_profesional`
--

CREATE TABLE `basica_info_profesional` (
  `id` int(11) NOT NULL,
  `nombramiento_id` int(11) NOT NULL,
  `colegio_magisterial` varchar(255) DEFAULT NULL,
  `sdgthd` varchar(50) DEFAULT NULL,
  `colegiacion_numero` varchar(50) DEFAULT NULL,
  `jornada_trabajo` enum('PARCIAL','COMPLETA','EXCLUSIVA') DEFAULT NULL,
  `requisito` enum('CONCURSO','EXONERADO(A)','COMPLETACIÓN') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basica_info_profesional`
--

INSERT INTO `basica_info_profesional` (`id`, `nombramiento_id`, `colegio_magisterial`, `sdgthd`, `colegiacion_numero`, `jornada_trabajo`, `requisito`) VALUES
(1, 1, 'CEM Gubernamental A Distancia Perla del Ulua', '1804-50005-000108', '12343434343', 'COMPLETA', 'CONCURSO');

-- --------------------------------------------------------

--
-- Table structure for table `basica_justificacion`
--

CREATE TABLE `basica_justificacion` (
  `id` int(11) NOT NULL,
  `nombramiento_id` int(11) NOT NULL,
  `sustituye_a` varchar(255) DEFAULT NULL,
  `identidad_sustituido` varchar(20) DEFAULT NULL,
  `motivo_vacante` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `basica_justificacion`
--

INSERT INTO `basica_justificacion` (`id`, `nombramiento_id`, `sustituye_a`, `identidad_sustituido`, `motivo_vacante`) VALUES
(1, 1, 'Luis Alberto Rosado Machado', '1804-1995-01070', 'asi quedo esto es prueba');

-- --------------------------------------------------------

--
-- Table structure for table `carga_academica_media`
--

CREATE TABLE `carga_academica_media` (
  `id` int(11) NOT NULL,
  `traslado_id` int(11) DEFAULT NULL,
  `asignatura` varchar(150) DEFAULT NULL,
  `num_horas` int(11) DEFAULT NULL,
  `modalidad` varchar(100) DEFAULT NULL,
  `cursos` varchar(100) DEFAULT NULL,
  `grupos` varchar(100) DEFAULT NULL,
  `jornada_academica` varchar(100) DEFAULT NULL,
  `programa` varchar(100) DEFAULT NULL,
  `subprograma` varchar(100) DEFAULT NULL,
  `fondo` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carga_academica_media`
--

INSERT INTO `carga_academica_media` (`id`, `traslado_id`, `asignatura`, `num_horas`, `modalidad`, `cursos`, `grupos`, `jornada_academica`, `programa`, `subprograma`, `fondo`) VALUES
(1, 1, 'Español', 30, 'presencial', 'tercero', '3', 'Matutina', '232', '222', '333'),
(2, 1, 'Español3', 30, 'presencial', 'tercero', '4', 'Matutina', '333', '1114', '3334');

-- --------------------------------------------------------

--
-- Table structure for table `carga_academica_nombramiento_media`
--

CREATE TABLE `carga_academica_nombramiento_media` (
  `id` int(11) NOT NULL,
  `id_formato` int(11) NOT NULL,
  `asignatura` varchar(200) DEFAULT NULL,
  `horas` int(11) DEFAULT NULL,
  `modalidad` varchar(150) DEFAULT NULL,
  `cursos` varchar(200) DEFAULT NULL,
  `grupos` varchar(100) DEFAULT NULL,
  `jornada_academica` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carga_academica_nombramiento_media`
--

INSERT INTO `carga_academica_nombramiento_media` (`id`, `id_formato`, `asignatura`, `horas`, `modalidad`, `cursos`, `grupos`, `jornada_academica`, `created_at`) VALUES
(11, 18, 'Español', 20, 'presencial', 'tercero', '3', 'Matutina', '2025-11-09 22:06:23'),
(12, 18, 'Ingles', 20, 'presencial', 'tercero', '3', 'Matutina', '2025-11-09 22:06:23'),
(13, 18, 'Fisica', 30, 'presencial', 'tercero', '3', 'Matutina', '2025-11-09 22:06:23'),
(14, 19, 'Español', 30, 'presencial', 'tercero', '3', 'Matutina', '2025-11-09 22:29:49'),
(15, 19, 'Fisica 2', 40, 'presencial', 'tercero', '3', 'Matutina', '2025-11-09 22:29:49'),
(16, 19, 'Ingles 3', 10, 'presencial', 'tercero', '3', 'Matutina', '2025-11-09 22:29:49');

-- --------------------------------------------------------

--
-- Table structure for table `centros_educativos_media`
--

CREATE TABLE `centros_educativos_media` (
  `id` int(11) NOT NULL,
  `traslado_id` int(11) DEFAULT NULL,
  `tipo` enum('ORIGEN','REUBICACION','DESTINO') DEFAULT NULL,
  `centro_educativo` varchar(150) DEFAULT NULL,
  `cargo` varchar(150) DEFAULT NULL,
  `lugar` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `depto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `centros_educativos_media`
--

INSERT INTO `centros_educativos_media` (`id`, `traslado_id`, `tipo`, `centro_educativo`, `cargo`, `lugar`, `municipio`, `depto`) VALUES
(1, 1, 'ORIGEN', 'CEM Gubernamental A Distancia Perla del Ulua', 'sdsdsdsds', 'qweqweqw', 'El Progreso', 'Yoro'),
(2, 1, 'REUBICACION', 'pedro', 'Sub-Director', 'El Progreso', 'Yoro', NULL),
(3, 1, 'DESTINO', 'prueba', 'weqweqwe', 'rrreee', 'En blanco', 'wewewew');

-- --------------------------------------------------------

--
-- Table structure for table `docentes`
--

CREATE TABLE `docentes` (
  `id_docente` int(11) NOT NULL,
  `identidad` varchar(20) NOT NULL,
  `nombre_docente` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `docentes`
--

INSERT INTO `docentes` (`id_docente`, `identidad`, `nombre_docente`) VALUES
(1, '0501-1977-06109', 'Jose Antonio Smith Mejia'),
(2, '1804-1996-02019', 'Tania Maria Moreno Madrid'),
(3, '0506-1995-01206', 'Katerin Paola Cantarero Figueroa'),
(4, '1804-1992-02535', 'Ingris Nazaret Arias Castro'),
(5, '1808-1987-00422', 'Juana Yunith Rodriguez Moreira'),
(6, '1804-1992-01431', 'Erika Yaeliz Antunez Roldan'),
(7, '1804-1995-01070', 'Luis Alberto Rosado Machado'),
(8, '0501-1965-06412', 'Juan Carlos Alfaro Castillo'),
(9, '1804-1989-06926', 'Wendy Odalis Cruz Torres'),
(10, '1622-1964-00177', 'Lucas Alexander Pineda Mejia'),
(11, '1804-1967-00504', 'Saul Jeovanni Hernandez'),
(12, '0501-1994-03189', 'Jose Antonio Mejia Godoy'),
(13, '0801-1994-23822', 'Leticia Raquel Santos Cruz'),
(17, 'No tiene', 'Karla Lorena Hernandez'),
(20, '0501-1977-06110', 'Mirian Liseth Quintanilla Delgado');

-- --------------------------------------------------------

--
-- Table structure for table `estructuras`
--

CREATE TABLE `estructuras` (
  `id_estructura` int(11) NOT NULL,
  `codigo` varchar(50) NOT NULL,
  `nivel` varchar(50) DEFAULT NULL,
  `tipo_plaza` enum('NOMBRAMIENTO','TRASLADO','CANCELACION') DEFAULT NULL,
  `asignada` tinyint(1) DEFAULT 0,
  `id_docente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `estructura_presupuestaria_media`
--

CREATE TABLE `estructura_presupuestaria_media` (
  `id` int(11) NOT NULL,
  `traslado_id` int(11) DEFAULT NULL,
  `estructura_inst` varchar(20) DEFAULT NULL,
  `estructura_programa` varchar(20) DEFAULT NULL,
  `estructura_subprograma` varchar(20) DEFAULT NULL,
  `estructura_fondo` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estructura_presupuestaria_media`
--

INSERT INTO `estructura_presupuestaria_media` (`id`, `traslado_id`, `estructura_inst`, `estructura_programa`, `estructura_subprograma`, `estructura_fondo`) VALUES
(1, 1, '06', '13', '01', '11'),
(2, 1, '06', '13', '01', '11');

-- --------------------------------------------------------

--
-- Table structure for table `estructura_presupuestaria_nombramiento_media`
--

CREATE TABLE `estructura_presupuestaria_nombramiento_media` (
  `id` int(11) NOT NULL,
  `id_formato` int(11) NOT NULL,
  `estructura_inst` varchar(50) DEFAULT NULL,
  `estructura_programa` varchar(50) DEFAULT NULL,
  `estructura_subprograma` varchar(50) DEFAULT NULL,
  `estructura_fondo` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estructura_presupuestaria_nombramiento_media`
--

INSERT INTO `estructura_presupuestaria_nombramiento_media` (`id`, `id_formato`, `estructura_inst`, `estructura_programa`, `estructura_subprograma`, `estructura_fondo`, `created_at`) VALUES
(9, 18, '06', '13', '01', '11', '2025-11-09 22:06:22'),
(10, 19, '06', '13', '01', '11', '2025-11-09 22:29:49');

-- --------------------------------------------------------

--
-- Table structure for table `estructura_siarhd_media`
--

CREATE TABLE `estructura_siarhd_media` (
  `id` int(11) NOT NULL,
  `traslado_id` int(11) DEFAULT NULL,
  `depto` varchar(100) DEFAULT NULL,
  `muni` varchar(100) DEFAULT NULL,
  `centro_educativo` varchar(150) DEFAULT NULL,
  `num_plaza` int(11) DEFAULT NULL,
  `num_horas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `estructura_siarhd_media`
--

INSERT INTO `estructura_siarhd_media` (`id`, `traslado_id`, `depto`, `muni`, `centro_educativo`, `num_plaza`, `num_horas`) VALUES
(1, 1, '18', '4', '06', 44, 20);

-- --------------------------------------------------------

--
-- Table structure for table `firmas_validacion_media`
--

CREATE TABLE `firmas_validacion_media` (
  `id` int(11) NOT NULL,
  `traslado_id` int(11) DEFAULT NULL,
  `firma_docente` varchar(150) DEFAULT NULL,
  `telefono_contacto` varchar(20) DEFAULT NULL,
  `vb_director_centro` varchar(150) DEFAULT NULL,
  `vb_director_distrital` varchar(150) DEFAULT NULL,
  `firma_revisa` varchar(150) DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `firmas_validacion_media`
--

INSERT INTO `firmas_validacion_media` (`id`, `traslado_id`, `firma_docente`, `telefono_contacto`, `vb_director_centro`, `vb_director_distrital`, `firma_revisa`, `fecha_entrega`) VALUES
(1, 1, 'Other', '99994343', 'firma', 'firma', 'Other', '2025-11-15');

-- --------------------------------------------------------

--
-- Table structure for table `formato_asignatura_cancelacion`
--

CREATE TABLE `formato_asignatura_cancelacion` (
  `id` int(11) NOT NULL,
  `formato_cancelacion_id` int(11) NOT NULL,
  `asignatura` varchar(255) DEFAULT NULL,
  `horas` int(11) DEFAULT NULL,
  `curso` varchar(50) DEFAULT NULL,
  `grupo` varchar(50) DEFAULT NULL,
  `jornada` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formato_asignatura_cancelacion`
--

INSERT INTO `formato_asignatura_cancelacion` (`id`, `formato_cancelacion_id`, `asignatura`, `horas`, `curso`, `grupo`, `jornada`) VALUES
(13, 1, 'Español', 20, 'Tercero', 'A', 'Matutina'),
(14, 1, 'Español 2', 20, 'Segundo', 'B', 'Matutina');

-- --------------------------------------------------------

--
-- Table structure for table `formato_cancelacion`
--

CREATE TABLE `formato_cancelacion` (
  `id` int(11) NOT NULL,
  `no_identidad` varchar(20) NOT NULL,
  `tipo_accion` enum('Suspensión','Cancelación','Destitución') NOT NULL,
  `nombre_propuesto` varchar(150) DEFAULT NULL,
  `clave_srhd` varchar(50) DEFAULT NULL,
  `nombre_cargo` varchar(100) DEFAULT NULL,
  `nombre_centro_educativo` varchar(150) DEFAULT NULL,
  `codigo_centro` varchar(50) DEFAULT NULL,
  `nivel_educativo` varchar(50) DEFAULT NULL,
  `lugar` varchar(100) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `total_horas_semanales` int(11) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `revisado_por` varchar(100) DEFAULT NULL,
  `firma_director_departamental` varchar(150) DEFAULT NULL,
  `acuerdo_nombramiento` tinyint(1) DEFAULT 0,
  `datos_cancelacion` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formato_cancelacion`
--

INSERT INTO `formato_cancelacion` (`id`, `no_identidad`, `tipo_accion`, `nombre_propuesto`, `clave_srhd`, `nombre_cargo`, `nombre_centro_educativo`, `codigo_centro`, `nivel_educativo`, `lugar`, `municipio`, `departamento`, `total_horas_semanales`, `fecha`, `revisado_por`, `firma_director_departamental`, `acuerdo_nombramiento`, `datos_cancelacion`, `created_at`, `updated_at`) VALUES
(1, '0501-1977-061111', 'Cancelación', 'Mirian Liseth Quintanilla Delgad', 'PTQ00149', 'Docente en Servicio Estricto', 'Juan Lindo', '180700030B09', 'Básica', 'Balsamo', 'Olanchito', 'Yoro', 36, '2025-11-20', 'Pedro', 'Other', 1, 1, '2025-11-20 04:16:41', '2025-11-20 04:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `formato_cancelacion_detalle`
--

CREATE TABLE `formato_cancelacion_detalle` (
  `id` int(11) NOT NULL,
  `formato_cancelacion_id` int(11) NOT NULL,
  `cancelacion_por` varchar(255) DEFAULT NULL,
  `cancelacion_a_partir` date DEFAULT NULL,
  `cancelacion_de` date DEFAULT NULL,
  `cancelacion_del` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formato_cancelacion_detalle`
--

INSERT INTO `formato_cancelacion_detalle` (`id`, `formato_cancelacion_id`, `cancelacion_por`, `cancelacion_a_partir`, `cancelacion_de`, `cancelacion_del`) VALUES
(6, 1, 'Renuncia', '2025-10-28', '2025-11-11', '2025-11-30');

-- --------------------------------------------------------

--
-- Table structure for table `formato_ciclo_cancelacion`
--

CREATE TABLE `formato_ciclo_cancelacion` (
  `id` int(11) NOT NULL,
  `formato_cancelacion_id` int(11) NOT NULL,
  `ciclo_comun` tinyint(1) DEFAULT 0,
  `ciclo_basico_tecnico` tinyint(1) DEFAULT 0,
  `bachillerato_tc` tinyint(1) DEFAULT 0,
  `otros_ciclo` tinyint(1) DEFAULT 0,
  `comercio` tinyint(1) DEFAULT 0,
  `sat` tinyint(1) DEFAULT 0,
  `semed` tinyint(1) DEFAULT 0,
  `iii_ciclo_cebot` tinyint(1) DEFAULT 0,
  `basica` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formato_ciclo_cancelacion`
--

INSERT INTO `formato_ciclo_cancelacion` (`id`, `formato_cancelacion_id`, `ciclo_comun`, `ciclo_basico_tecnico`, `bachillerato_tc`, `otros_ciclo`, `comercio`, `sat`, `semed`, `iii_ciclo_cebot`, `basica`) VALUES
(1, 1, 0, 0, 0, 0, 0, 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `formato_destitucion_cancelacion`
--

CREATE TABLE `formato_destitucion_cancelacion` (
  `id` int(11) NOT NULL,
  `formato_cancelacion_id` int(11) NOT NULL,
  `destitucion_por` varchar(255) DEFAULT NULL,
  `destitucion_a_partir` date DEFAULT NULL,
  `destitucion_de` date DEFAULT NULL,
  `destitucion_del` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `formato_estructura_presupuestaria_cancelacion`
--

CREATE TABLE `formato_estructura_presupuestaria_cancelacion` (
  `id` int(11) NOT NULL,
  `formato_cancelacion_id` int(11) NOT NULL,
  `estructura_inst` varchar(50) DEFAULT NULL,
  `estructura_prog` varchar(50) DEFAULT NULL,
  `estructura_subprog` varchar(50) DEFAULT NULL,
  `estructura_fnd` varchar(50) DEFAULT NULL,
  `siarhd_depto` varchar(50) DEFAULT NULL,
  `siarhd_munic` varchar(50) DEFAULT NULL,
  `siarhd_centro` varchar(100) DEFAULT NULL,
  `siarhd_plaza` int(11) DEFAULT NULL,
  `siarhd_cargo` varchar(100) DEFAULT NULL,
  `siarhd_jornada` varchar(50) DEFAULT NULL,
  `siarhd_hrs_sem` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formato_estructura_presupuestaria_cancelacion`
--

INSERT INTO `formato_estructura_presupuestaria_cancelacion` (`id`, `formato_cancelacion_id`, `estructura_inst`, `estructura_prog`, `estructura_subprog`, `estructura_fnd`, `siarhd_depto`, `siarhd_munic`, `siarhd_centro`, `siarhd_plaza`, `siarhd_cargo`, `siarhd_jornada`, `siarhd_hrs_sem`) VALUES
(6, 1, '6', '12', '1', '11', '18', '07', '20024', 8, '0', 'Vespertina', 36);

-- --------------------------------------------------------

--
-- Table structure for table `formato_nombramiento_basica`
--

CREATE TABLE `formato_nombramiento_basica` (
  `id` int(11) NOT NULL,
  `tipo_nombramiento` enum('INTERINO','PERMANENTE') NOT NULL,
  `nombre_docente` varchar(255) NOT NULL,
  `identidad` varchar(20) NOT NULL,
  `nivel_educativo` enum('PRE-BASICA','BASICA') NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `numero_horas` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formato_nombramiento_basica`
--

INSERT INTO `formato_nombramiento_basica` (`id`, `tipo_nombramiento`, `nombre_docente`, `identidad`, `nivel_educativo`, `fecha_inicio`, `fecha_vencimiento`, `numero_horas`, `fecha_registro`) VALUES
(1, 'INTERINO', 'Karla Lorena Hernandez', '0501-1977-06109', 'BASICA', '2025-10-28', '2025-11-13', 80, '2025-11-20 09:28:12');

-- --------------------------------------------------------

--
-- Table structure for table `formato_nombramiento_media`
--

CREATE TABLE `formato_nombramiento_media` (
  `id` int(11) NOT NULL,
  `tipo_nombramiento` enum('INTERINO','PERMANENTE') NOT NULL,
  `nombre_docente` varchar(200) NOT NULL,
  `identidad` varchar(20) NOT NULL,
  `colegio_magisterial` varchar(150) DEFAULT NULL,
  `sdgthd` varchar(100) DEFAULT NULL,
  `colegiacion_numero` varchar(50) DEFAULT NULL,
  `jornada_trabajo` enum('PARCIAL','COMPLETA','EXCLUSIVA') NOT NULL,
  `requisito` enum('CONCURSO','EXONERADO(A)','COMPLETACIÓN') NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `numero_horas` int(11) DEFAULT NULL,
  `centro_cuenta` varchar(200) DEFAULT NULL,
  `cargo_cuenta` varchar(150) DEFAULT NULL,
  `lugar_cuenta` varchar(150) DEFAULT NULL,
  `municipio_cuenta` varchar(150) DEFAULT NULL,
  `centro_reubicacion` varchar(200) DEFAULT NULL,
  `lugar_reubicacion` varchar(150) DEFAULT NULL,
  `cargo_reubicacion` varchar(150) DEFAULT NULL,
  `municipio_reubicacion` varchar(150) DEFAULT NULL,
  `sustituye_a` varchar(200) DEFAULT NULL,
  `identidad_sustituido` varchar(20) DEFAULT NULL,
  `motivo_vacante` varchar(250) DEFAULT NULL,
  `firma_docente` varchar(200) DEFAULT NULL,
  `vb_director_centro` varchar(200) DEFAULT NULL,
  `firma_revisa` varchar(200) DEFAULT NULL,
  `telefono_contacto` varchar(20) DEFAULT NULL,
  `vb_director_distrital` varchar(200) DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formato_nombramiento_media`
--

INSERT INTO `formato_nombramiento_media` (`id`, `tipo_nombramiento`, `nombre_docente`, `identidad`, `colegio_magisterial`, `sdgthd`, `colegiacion_numero`, `jornada_trabajo`, `requisito`, `fecha_inicio`, `fecha_vencimiento`, `numero_horas`, `centro_cuenta`, `cargo_cuenta`, `lugar_cuenta`, `municipio_cuenta`, `centro_reubicacion`, `lugar_reubicacion`, `cargo_reubicacion`, `municipio_reubicacion`, `sustituye_a`, `identidad_sustituido`, `motivo_vacante`, `firma_docente`, `vb_director_centro`, `firma_revisa`, `telefono_contacto`, `vb_director_distrital`, `fecha_entrega`, `fecha_registro`) VALUES
(18, 'INTERINO', 'Karla Lorena Hernandez', '0501-1977-06109', 'CEM Gubernamental A Distancia Perla del Ulua', '1804-50005-000108', '12343434343', 'COMPLETA', 'COMPLETACIÓN', '2025-10-28', '2025-11-28', 60, 'el progreso', 'Consejeria', 'El Progreso', 'Yoro', 'pedro', 'El Progreso', 'Sub-Director', 'Yoro', 'Luis Alberto Rosado Machado', '1804-1995-01070', 'Porque si', 'Other', 'firma', 'Other', '99994343', 'firma', '2025-11-06', '2025-11-07 08:49:30'),
(19, 'PERMANENTE', 'Juan Carlos Alfaro Castillo', '0501-1965-06412', 'Perla del Ulua', '1804-50005-000108', '12343434343', 'PARCIAL', 'CONCURSO', '2025-05-07', '2025-12-06', 80, 'el progreso', 'Consejeria', 'El Progreso', 'Yoro', 'pedro', 'El Progreso', 'Sub-Director', 'Yoro', 'Luis Alberto Rosado Machado', '1804-1995-01070', 'Porque si', 'Other', 'firma', 'Other', '66633334444', 'firma', '2025-11-19', '2025-11-09 22:29:49');

-- --------------------------------------------------------

--
-- Table structure for table `formato_suspension_cancelacion`
--

CREATE TABLE `formato_suspension_cancelacion` (
  `id` int(11) NOT NULL,
  `formato_cancelacion_id` int(11) NOT NULL,
  `suspension_por` varchar(255) DEFAULT NULL,
  `suspension_a_partir` date DEFAULT NULL,
  `suspension_de` date DEFAULT NULL,
  `suspension_del` date DEFAULT NULL,
  `suspension_al` date DEFAULT NULL,
  `suspension_de2` date DEFAULT NULL,
  `suspension_del2` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `formato_traslado_basica`
--

CREATE TABLE `formato_traslado_basica` (
  `id` int(11) NOT NULL,
  `tipo_nombramiento` varchar(100) NOT NULL,
  `nombre_docente` varchar(200) NOT NULL,
  `identidad` varchar(20) NOT NULL,
  `colegio_magisterial` varchar(150) DEFAULT NULL,
  `sdgthd` varchar(100) DEFAULT NULL,
  `colegiacion_numero` varchar(50) DEFAULT NULL,
  `nivel_educativo` varchar(100) DEFAULT NULL,
  `jornada_trabajo` varchar(100) DEFAULT NULL,
  `requisito` varchar(200) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `numero_horas` int(11) DEFAULT NULL,
  `centro_educativo_cuenta` varchar(200) DEFAULT NULL,
  `cargo_cuenta` varchar(150) DEFAULT NULL,
  `lugar_cuenta` varchar(150) DEFAULT NULL,
  `municipio_cuenta` varchar(150) DEFAULT NULL,
  `estructura_inst` varchar(50) DEFAULT NULL,
  `estructura_programa` varchar(50) DEFAULT NULL,
  `estructura_subprograma` varchar(50) DEFAULT NULL,
  `estructura_fondo` varchar(50) DEFAULT NULL,
  `siarhd_depto` varchar(100) DEFAULT NULL,
  `siarhd_muni` varchar(100) DEFAULT NULL,
  `siarhd_centro` varchar(200) DEFAULT NULL,
  `siarhd_num_plaza` varchar(50) DEFAULT NULL,
  `siarhd_num_horas` int(11) DEFAULT NULL,
  `sustituye_a` varchar(200) DEFAULT NULL,
  `identidad_sustituido` varchar(20) DEFAULT NULL,
  `motivo_vacante` varchar(250) DEFAULT NULL,
  `firma_docente` varchar(200) DEFAULT NULL,
  `vb_director_centro` varchar(200) DEFAULT NULL,
  `firma_revisa` varchar(200) DEFAULT NULL,
  `telefono_contacto` varchar(20) DEFAULT NULL,
  `vb_director_distrital` varchar(200) DEFAULT NULL,
  `fecha_entrega` date DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formato_traslado_basica`
--

INSERT INTO `formato_traslado_basica` (`id`, `tipo_nombramiento`, `nombre_docente`, `identidad`, `colegio_magisterial`, `sdgthd`, `colegiacion_numero`, `nivel_educativo`, `jornada_trabajo`, `requisito`, `fecha_inicio`, `fecha_vencimiento`, `numero_horas`, `centro_educativo_cuenta`, `cargo_cuenta`, `lugar_cuenta`, `municipio_cuenta`, `estructura_inst`, `estructura_programa`, `estructura_subprograma`, `estructura_fondo`, `siarhd_depto`, `siarhd_muni`, `siarhd_centro`, `siarhd_num_plaza`, `siarhd_num_horas`, `sustituye_a`, `identidad_sustituido`, `motivo_vacante`, `firma_docente`, `vb_director_centro`, `firma_revisa`, `telefono_contacto`, `vb_director_distrital`, `fecha_entrega`, `fecha_registro`) VALUES
(1, 'PERMANENTE', 'Katerin Paola', '0506-1995-01206', 'CEM Gubernamental A Distancia Perla del Ulua', '1804-50005-000108', '12343434343', 'PRE-BASICA', 'PARCIAL', 'EXONERADO(A)', '2025-05-01', '2025-08-07', 99, 'Eduardo Hernandez Chevez', 'Consejeria', 'El Progreso', 'Yoro', 'Jose Melecio ', 'prueba', 'prueba33', 'prueba44', 'Yoro', 'prueba', 'pruebaprueba', '23', 120, 'Luis Alberto Rosado Machado', '1804-1995-01070', 'Porque si', 'Other', 'firma', 'Other', '99994343', 'firma', '2025-10-08', '2025-10-08 07:26:24');

-- --------------------------------------------------------

--
-- Table structure for table `formato_traslado_media`
--

CREATE TABLE `formato_traslado_media` (
  `id` int(11) NOT NULL,
  `tipo_nombramiento` enum('INTERINO','PERMANENTE') DEFAULT NULL,
  `nombre_docente` varchar(150) NOT NULL,
  `identidad` varchar(20) DEFAULT NULL,
  `colegio_magisterial` varchar(100) DEFAULT NULL,
  `sdgthd` varchar(50) DEFAULT NULL,
  `colegiacion_numero` varchar(30) DEFAULT NULL,
  `jornada_trabajo` enum('PARCIAL','COMPLETA','EXCLUSIVA') DEFAULT NULL,
  `requisito` enum('CONCURSO','EXONERADO(A)','COMPLETACIÓN') DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `numero_horas` int(11) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `formato_traslado_media`
--

INSERT INTO `formato_traslado_media` (`id`, `tipo_nombramiento`, `nombre_docente`, `identidad`, `colegio_magisterial`, `sdgthd`, `colegiacion_numero`, `jornada_trabajo`, `requisito`, `fecha_inicio`, `fecha_vencimiento`, `numero_horas`, `fecha_registro`) VALUES
(1, 'INTERINO', 'Jose Antonio Smith Mejia', '0501-1977-06109', 'CEM Gubernamental A Distancia Perla del Ulua', '1804-50005-000108', '12343434343', 'PARCIAL', 'EXONERADO(A)', '2025-10-27', '2025-12-06', 60, '2025-11-19 11:32:45');

-- --------------------------------------------------------

--
-- Table structure for table `justificacion_media`
--

CREATE TABLE `justificacion_media` (
  `id` int(11) NOT NULL,
  `traslado_id` int(11) DEFAULT NULL,
  `sustituye_a` varchar(150) DEFAULT NULL,
  `identidad_sustituido` varchar(20) DEFAULT NULL,
  `motivo_vacante` varchar(255) DEFAULT NULL,
  `centro_educativo` varchar(150) DEFAULT NULL,
  `num_plaza` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `justificacion_media`
--

INSERT INTO `justificacion_media` (`id`, `traslado_id`, `sustituye_a`, `identidad_sustituido`, `motivo_vacante`, `centro_educativo`, `num_plaza`) VALUES
(1, 1, 'Luis Alberto Rosado Machado', '1804-1995-01070', 'Porque si', 'Perla', '232323');

-- --------------------------------------------------------

--
-- Table structure for table `nombramientos`
--

CREATE TABLE `nombramientos` (
  `id_nombramiento` int(11) NOT NULL,
  `nombre_nombramiento` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nombramientos`
--

INSERT INTO `nombramientos` (`id_nombramiento`, `nombre_nombramiento`) VALUES
(2, 'Licencia con goce de sueldo'),
(3, 'Licencia sin goce de sueldo'),
(1, 'Nombramiento Interino');

-- --------------------------------------------------------

--
-- Table structure for table `siarhd_estructura_nombramiento_media`
--

CREATE TABLE `siarhd_estructura_nombramiento_media` (
  `id` int(11) NOT NULL,
  `id_formato` int(11) NOT NULL,
  `siarhd_depto` varchar(100) DEFAULT NULL,
  `siarhd_muni` varchar(100) DEFAULT NULL,
  `siarhd_centro` varchar(200) DEFAULT NULL,
  `siarhd_num_plaza` varchar(50) DEFAULT NULL,
  `siarhd_num_horas` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siarhd_estructura_nombramiento_media`
--

INSERT INTO `siarhd_estructura_nombramiento_media` (`id`, `id_formato`, `siarhd_depto`, `siarhd_muni`, `siarhd_centro`, `siarhd_num_plaza`, `siarhd_num_horas`, `created_at`) VALUES
(13, 18, '18', '4', '06', '44', 0, '2025-11-09 22:06:23'),
(14, 19, '18', '4', '06', '44', 30, '2025-11-09 22:29:49');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `rol` enum('admin','usuario') DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `contrasena`, `nombre_completo`, `rol`) VALUES
(6, 'admin2', '$2y$10$Q.DSpGiKtIpfYQwfdPmPKu1CuFjSjiWkJ7ui.98Ja7GVvRJjmZhU.', 'Jose Pineda', 'admin'),
(7, 'admin', '$2b$12$oPFO5pg4aiNbLXJ5gFyeT.moeB3RtGATKJRGb8MSRJW5tqErDNaHS', 'Administrador', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `basica_centro_cuenta`
--
ALTER TABLE `basica_centro_cuenta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nombramiento_id` (`nombramiento_id`);

--
-- Indexes for table `basica_centro_reubicacion`
--
ALTER TABLE `basica_centro_reubicacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nombramiento_id` (`nombramiento_id`);

--
-- Indexes for table `basica_estructura_presupuestaria`
--
ALTER TABLE `basica_estructura_presupuestaria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nombramiento_id` (`nombramiento_id`);

--
-- Indexes for table `basica_estructura_siarhd`
--
ALTER TABLE `basica_estructura_siarhd`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nombramiento_id` (`nombramiento_id`);

--
-- Indexes for table `basica_firmas_validacion`
--
ALTER TABLE `basica_firmas_validacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nombramiento_id` (`nombramiento_id`);

--
-- Indexes for table `basica_info_profesional`
--
ALTER TABLE `basica_info_profesional`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nombramiento_id` (`nombramiento_id`);

--
-- Indexes for table `basica_justificacion`
--
ALTER TABLE `basica_justificacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `nombramiento_id` (`nombramiento_id`);

--
-- Indexes for table `carga_academica_media`
--
ALTER TABLE `carga_academica_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `traslado_id` (`traslado_id`);

--
-- Indexes for table `carga_academica_nombramiento_media`
--
ALTER TABLE `carga_academica_nombramiento_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_formato` (`id_formato`);

--
-- Indexes for table `centros_educativos_media`
--
ALTER TABLE `centros_educativos_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `traslado_id` (`traslado_id`);

--
-- Indexes for table `docentes`
--
ALTER TABLE `docentes`
  ADD PRIMARY KEY (`id_docente`),
  ADD UNIQUE KEY `identidad` (`identidad`);

--
-- Indexes for table `estructuras`
--
ALTER TABLE `estructuras`
  ADD PRIMARY KEY (`id_estructura`),
  ADD UNIQUE KEY `codigo` (`codigo`),
  ADD KEY `id_docente` (`id_docente`);

--
-- Indexes for table `estructura_presupuestaria_media`
--
ALTER TABLE `estructura_presupuestaria_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `traslado_id` (`traslado_id`);

--
-- Indexes for table `estructura_presupuestaria_nombramiento_media`
--
ALTER TABLE `estructura_presupuestaria_nombramiento_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_formato` (`id_formato`);

--
-- Indexes for table `estructura_siarhd_media`
--
ALTER TABLE `estructura_siarhd_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `traslado_id` (`traslado_id`);

--
-- Indexes for table `firmas_validacion_media`
--
ALTER TABLE `firmas_validacion_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `traslado_id` (`traslado_id`);

--
-- Indexes for table `formato_asignatura_cancelacion`
--
ALTER TABLE `formato_asignatura_cancelacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formato_cancelacion_id` (`formato_cancelacion_id`);

--
-- Indexes for table `formato_cancelacion`
--
ALTER TABLE `formato_cancelacion`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `formato_cancelacion_detalle`
--
ALTER TABLE `formato_cancelacion_detalle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formato_cancelacion_id` (`formato_cancelacion_id`);

--
-- Indexes for table `formato_ciclo_cancelacion`
--
ALTER TABLE `formato_ciclo_cancelacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formato_cancelacion_id` (`formato_cancelacion_id`);

--
-- Indexes for table `formato_destitucion_cancelacion`
--
ALTER TABLE `formato_destitucion_cancelacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formato_cancelacion_id` (`formato_cancelacion_id`);

--
-- Indexes for table `formato_estructura_presupuestaria_cancelacion`
--
ALTER TABLE `formato_estructura_presupuestaria_cancelacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formato_cancelacion_id` (`formato_cancelacion_id`);

--
-- Indexes for table `formato_nombramiento_basica`
--
ALTER TABLE `formato_nombramiento_basica`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identidad` (`identidad`);

--
-- Indexes for table `formato_nombramiento_media`
--
ALTER TABLE `formato_nombramiento_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `formato_suspension_cancelacion`
--
ALTER TABLE `formato_suspension_cancelacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `formato_cancelacion_id` (`formato_cancelacion_id`);

--
-- Indexes for table `formato_traslado_basica`
--
ALTER TABLE `formato_traslado_basica`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identidad` (`identidad`);

--
-- Indexes for table `formato_traslado_media`
--
ALTER TABLE `formato_traslado_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `justificacion_media`
--
ALTER TABLE `justificacion_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `traslado_id` (`traslado_id`);

--
-- Indexes for table `nombramientos`
--
ALTER TABLE `nombramientos`
  ADD PRIMARY KEY (`id_nombramiento`),
  ADD UNIQUE KEY `nombre_nombramiento` (`nombre_nombramiento`);

--
-- Indexes for table `siarhd_estructura_nombramiento_media`
--
ALTER TABLE `siarhd_estructura_nombramiento_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_formato` (`id_formato`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `basica_centro_cuenta`
--
ALTER TABLE `basica_centro_cuenta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `basica_centro_reubicacion`
--
ALTER TABLE `basica_centro_reubicacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `basica_estructura_presupuestaria`
--
ALTER TABLE `basica_estructura_presupuestaria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `basica_estructura_siarhd`
--
ALTER TABLE `basica_estructura_siarhd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `basica_firmas_validacion`
--
ALTER TABLE `basica_firmas_validacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `basica_info_profesional`
--
ALTER TABLE `basica_info_profesional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `basica_justificacion`
--
ALTER TABLE `basica_justificacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `carga_academica_media`
--
ALTER TABLE `carga_academica_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `carga_academica_nombramiento_media`
--
ALTER TABLE `carga_academica_nombramiento_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `centros_educativos_media`
--
ALTER TABLE `centros_educativos_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `docentes`
--
ALTER TABLE `docentes`
  MODIFY `id_docente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `estructuras`
--
ALTER TABLE `estructuras`
  MODIFY `id_estructura` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `estructura_presupuestaria_media`
--
ALTER TABLE `estructura_presupuestaria_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `estructura_presupuestaria_nombramiento_media`
--
ALTER TABLE `estructura_presupuestaria_nombramiento_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `estructura_siarhd_media`
--
ALTER TABLE `estructura_siarhd_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `firmas_validacion_media`
--
ALTER TABLE `firmas_validacion_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `formato_asignatura_cancelacion`
--
ALTER TABLE `formato_asignatura_cancelacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `formato_cancelacion`
--
ALTER TABLE `formato_cancelacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `formato_cancelacion_detalle`
--
ALTER TABLE `formato_cancelacion_detalle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `formato_ciclo_cancelacion`
--
ALTER TABLE `formato_ciclo_cancelacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `formato_destitucion_cancelacion`
--
ALTER TABLE `formato_destitucion_cancelacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `formato_estructura_presupuestaria_cancelacion`
--
ALTER TABLE `formato_estructura_presupuestaria_cancelacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `formato_nombramiento_basica`
--
ALTER TABLE `formato_nombramiento_basica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `formato_nombramiento_media`
--
ALTER TABLE `formato_nombramiento_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `formato_suspension_cancelacion`
--
ALTER TABLE `formato_suspension_cancelacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `formato_traslado_basica`
--
ALTER TABLE `formato_traslado_basica`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `formato_traslado_media`
--
ALTER TABLE `formato_traslado_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `justificacion_media`
--
ALTER TABLE `justificacion_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `nombramientos`
--
ALTER TABLE `nombramientos`
  MODIFY `id_nombramiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `siarhd_estructura_nombramiento_media`
--
ALTER TABLE `siarhd_estructura_nombramiento_media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `basica_centro_cuenta`
--
ALTER TABLE `basica_centro_cuenta`
  ADD CONSTRAINT `basica_centro_cuenta_ibfk_1` FOREIGN KEY (`nombramiento_id`) REFERENCES `formato_nombramiento_basica` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `basica_centro_reubicacion`
--
ALTER TABLE `basica_centro_reubicacion`
  ADD CONSTRAINT `basica_centro_reubicacion_ibfk_1` FOREIGN KEY (`nombramiento_id`) REFERENCES `formato_nombramiento_basica` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `basica_estructura_presupuestaria`
--
ALTER TABLE `basica_estructura_presupuestaria`
  ADD CONSTRAINT `basica_estructura_presupuestaria_ibfk_1` FOREIGN KEY (`nombramiento_id`) REFERENCES `formato_nombramiento_basica` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `basica_estructura_siarhd`
--
ALTER TABLE `basica_estructura_siarhd`
  ADD CONSTRAINT `basica_estructura_siarhd_ibfk_1` FOREIGN KEY (`nombramiento_id`) REFERENCES `formato_nombramiento_basica` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `basica_firmas_validacion`
--
ALTER TABLE `basica_firmas_validacion`
  ADD CONSTRAINT `basica_firmas_validacion_ibfk_1` FOREIGN KEY (`nombramiento_id`) REFERENCES `formato_nombramiento_basica` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `basica_info_profesional`
--
ALTER TABLE `basica_info_profesional`
  ADD CONSTRAINT `basica_info_profesional_ibfk_1` FOREIGN KEY (`nombramiento_id`) REFERENCES `formato_nombramiento_basica` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `basica_justificacion`
--
ALTER TABLE `basica_justificacion`
  ADD CONSTRAINT `basica_justificacion_ibfk_1` FOREIGN KEY (`nombramiento_id`) REFERENCES `formato_nombramiento_basica` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carga_academica_media`
--
ALTER TABLE `carga_academica_media`
  ADD CONSTRAINT `carga_academica_media_ibfk_1` FOREIGN KEY (`traslado_id`) REFERENCES `formato_traslado_media` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carga_academica_nombramiento_media`
--
ALTER TABLE `carga_academica_nombramiento_media`
  ADD CONSTRAINT `carga_academica_nombramiento_media_ibfk_1` FOREIGN KEY (`id_formato`) REFERENCES `formato_nombramiento_media` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `centros_educativos_media`
--
ALTER TABLE `centros_educativos_media`
  ADD CONSTRAINT `centros_educativos_media_ibfk_1` FOREIGN KEY (`traslado_id`) REFERENCES `formato_traslado_media` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `estructuras`
--
ALTER TABLE `estructuras`
  ADD CONSTRAINT `estructuras_ibfk_1` FOREIGN KEY (`id_docente`) REFERENCES `docentes` (`id_docente`);

--
-- Constraints for table `estructura_presupuestaria_media`
--
ALTER TABLE `estructura_presupuestaria_media`
  ADD CONSTRAINT `estructura_presupuestaria_media_ibfk_1` FOREIGN KEY (`traslado_id`) REFERENCES `formato_traslado_media` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `estructura_presupuestaria_nombramiento_media`
--
ALTER TABLE `estructura_presupuestaria_nombramiento_media`
  ADD CONSTRAINT `estructura_presupuestaria_nombramiento_media_ibfk_1` FOREIGN KEY (`id_formato`) REFERENCES `formato_nombramiento_media` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `estructura_siarhd_media`
--
ALTER TABLE `estructura_siarhd_media`
  ADD CONSTRAINT `estructura_siarhd_media_ibfk_1` FOREIGN KEY (`traslado_id`) REFERENCES `formato_traslado_media` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `firmas_validacion_media`
--
ALTER TABLE `firmas_validacion_media`
  ADD CONSTRAINT `firmas_validacion_media_ibfk_1` FOREIGN KEY (`traslado_id`) REFERENCES `formato_traslado_media` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `formato_asignatura_cancelacion`
--
ALTER TABLE `formato_asignatura_cancelacion`
  ADD CONSTRAINT `formato_asignatura_cancelacion_ibfk_1` FOREIGN KEY (`formato_cancelacion_id`) REFERENCES `formato_cancelacion` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `formato_cancelacion_detalle`
--
ALTER TABLE `formato_cancelacion_detalle`
  ADD CONSTRAINT `formato_cancelacion_detalle_ibfk_1` FOREIGN KEY (`formato_cancelacion_id`) REFERENCES `formato_cancelacion` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `formato_ciclo_cancelacion`
--
ALTER TABLE `formato_ciclo_cancelacion`
  ADD CONSTRAINT `formato_ciclo_cancelacion_ibfk_1` FOREIGN KEY (`formato_cancelacion_id`) REFERENCES `formato_cancelacion` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `formato_destitucion_cancelacion`
--
ALTER TABLE `formato_destitucion_cancelacion`
  ADD CONSTRAINT `formato_destitucion_cancelacion_ibfk_1` FOREIGN KEY (`formato_cancelacion_id`) REFERENCES `formato_cancelacion` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `formato_estructura_presupuestaria_cancelacion`
--
ALTER TABLE `formato_estructura_presupuestaria_cancelacion`
  ADD CONSTRAINT `formato_estructura_presupuestaria_cancelacion_ibfk_1` FOREIGN KEY (`formato_cancelacion_id`) REFERENCES `formato_cancelacion` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `formato_suspension_cancelacion`
--
ALTER TABLE `formato_suspension_cancelacion`
  ADD CONSTRAINT `formato_suspension_cancelacion_ibfk_1` FOREIGN KEY (`formato_cancelacion_id`) REFERENCES `formato_cancelacion` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `justificacion_media`
--
ALTER TABLE `justificacion_media`
  ADD CONSTRAINT `justificacion_media_ibfk_1` FOREIGN KEY (`traslado_id`) REFERENCES `formato_traslado_media` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `siarhd_estructura_nombramiento_media`
--
ALTER TABLE `siarhd_estructura_nombramiento_media`
  ADD CONSTRAINT `siarhd_estructura_nombramiento_media_ibfk_1` FOREIGN KEY (`id_formato`) REFERENCES `formato_nombramiento_media` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
