-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generato il: Set 11, 2014 alle 12:25
-- Versione del server: 5.5.27-log
-- Versione PHP: 5.4.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `loveatwell`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `file`
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `gestione_tabelle`
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;



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

