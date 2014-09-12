-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 12, 2014 at 11:07 AM
-- Server version: 5.5.38-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cassiopea`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `mdate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `slug` varchar(64) COLLATE utf8_bin NOT NULL,
  `title` varchar(256) COLLATE utf8_bin NOT NULL,
  `content` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `articles`
--

-- --------------------------------------------------------

--
-- Table structure for table `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `idF` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fk_tabella` int(11) NOT NULL DEFAULT '0',
  `fk_record` int(11) NOT NULL DEFAULT '0',
  `tipoF` varchar(4) COLLATE utf8_bin NOT NULL DEFAULT '',
  `titoloF` varchar(150) COLLATE utf8_bin NOT NULL DEFAULT '',
  `sorting` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idF`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `file`
--


-- --------------------------------------------------------

--
-- Table structure for table `gestione_tabelle`
--

CREATE TABLE IF NOT EXISTS `gestione_tabelle` (
  `idGT` int(11) NOT NULL AUTO_INCREMENT,
  `tabella` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  `campo` varchar(50) COLLATE utf8_bin NOT NULL,
  `tipo` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  `etichetta` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `visibile_default` char(1) COLLATE utf8_bin NOT NULL,
  `n_file` int(11) NOT NULL DEFAULT '0',
  `width_big` int(11) NOT NULL DEFAULT '0',
  `height_big` int(11) NOT NULL DEFAULT '0',
  `width_thumb` int(11) NOT NULL DEFAULT '0',
  `height_thumb` int(11) NOT NULL DEFAULT '0',
  `mantieni_orig` int(11) NOT NULL,
  `tipo_ridim` int(11) NOT NULL,
  `cartella_upload` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`idGT`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

--
-- Dumping data for table `gestione_tabelle`
--

INSERT INTO `gestione_tabelle` (`idGT`, `tabella`, `campo`, `tipo`, `etichetta`, `visibile_default`, `n_file`, `width_big`, `height_big`, `width_thumb`, `height_thumb`, `mantieni_orig`, `tipo_ridim`, `cartella_upload`) VALUES
(1, 'articles', 'example', 'img', 'immagine', '1', 5, 0, 0, 400, 0, 1, 0, 'articoli'),

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nickname` varchar(64) COLLATE utf8_bin NOT NULL,
  `username` varchar(64) COLLATE utf8_bin NOT NULL,
  `password` varchar(64) COLLATE utf8_bin NOT NULL,
  `group` varchar(64) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nickname`, `username`, `password`, `group`) VALUES
(1, 'administrator', 'admin', 'de145552133225e8148e3be3b73e2fd2', 'admins');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
