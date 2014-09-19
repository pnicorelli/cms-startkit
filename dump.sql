-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generato il: Set 19, 2014 alle 15:47
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
-- Struttura della tabella `about`
--

CREATE TABLE IF NOT EXISTS `about` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titolo` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `abstract` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `testo` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `titolo_eng` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `abstract_eng` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `testo_eng` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `about`
--

INSERT INTO `about` (`id`, `titolo`, `abstract`, `testo`, `titolo_eng`, `abstract_eng`, `testo_eng`) VALUES
(1, 'test', '&lt;p&gt;ddd&lt;/p&gt;', '&lt;p&gt;aaa&lt;/p&gt;', 'test eng', '&lt;p&gt;afaf &lt;strong&gt;eng&lt;/strong&gt;&lt;/p&gt;', '&lt;p&gt;ENG adfasd asf&lt;/p&gt;&lt;p&gt;asf asd&lt;/p&gt;');

-- --------------------------------------------------------

--
-- Struttura della tabella `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipologia` varchar(30) NOT NULL,
  `autore` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `titolo` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `abstract` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `testo` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `titolo_eng` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `abstract_eng` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `testo_eng` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `data` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `blog`
--

INSERT INTO `blog` (`id`, `tipologia`, `autore`, `titolo`, `abstract`, `testo`, `titolo_eng`, `abstract_eng`, `testo_eng`, `data`) VALUES
(2, '', 'test', 'test 2', '', '&lt;p&gt;asdf ddd&lt;/p&gt;', '', '', '', '2014-09-05');

-- --------------------------------------------------------

--
-- Struttura della tabella `file`
--

CREATE TABLE IF NOT EXISTS `file` (
  `idF` int(11) NOT NULL AUTO_INCREMENT,
  `file` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `fk_tabella` int(11) NOT NULL DEFAULT '0',
  `fk_record` int(11) NOT NULL DEFAULT '0',
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
  `etichetta` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT '',
  `visibile_default` char(1) COLLATE utf8_bin NOT NULL,
  `n_file` int(11) NOT NULL DEFAULT '0',
  `width_big` int(11) NOT NULL DEFAULT '0',
  `height_big` int(11) NOT NULL DEFAULT '0',
  `width_thumb` int(11) NOT NULL DEFAULT '0',
  `height_thumb` int(11) NOT NULL DEFAULT '0',
  `mantieni_orig` int(11) NOT NULL,
  `tipo_upload` int(11) NOT NULL,
  `upload_template` varchar(20) COLLATE utf8_bin NOT NULL,
  `cartella_upload` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`idGT`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

--
-- Dump dei dati per la tabella `gestione_tabelle`
--

INSERT INTO `gestione_tabelle` (`idGT`, `tabella`, `campo`, `etichetta`, `visibile_default`, `n_file`, `width_big`, `height_big`, `width_thumb`, `height_thumb`, `mantieni_orig`, `tipo_upload`, `upload_template`, `cartella_upload`) VALUES
(1, 'blog', 'foto', 'foto', 't', 1, 0, 0, 385, 340, 0, 1, 'basic', 'blog');

-- --------------------------------------------------------

--
-- Struttura della tabella `gestione_tabelle_tipologie`
--

CREATE TABLE IF NOT EXISTS `gestione_tabelle_tipologie` (
  `id` int(11) NOT NULL,
  `tipologia` text NOT NULL,
  `template` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `gestione_tabelle_tipologie`
--

INSERT INTO `gestione_tabelle_tipologie` (`id`, `tipologia`, `template`) VALUES
(0, 'Upload semplice', 'basic'),
(1, 'Upload di immagini con integrazione del tool per il crop', 'crop'),
(2, 'Upload di immagini: l''immagine viene ricreata tenendo fisso il lato maggiore', 'basic');

-- --------------------------------------------------------

--
-- Struttura della tabella `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `tag_eng` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `importanza` int(11) NOT NULL,
  `box` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dump dei dati per la tabella `tag`
--

INSERT INTO `tag` (`id`, `tag`, `tag_eng`, `importanza`, `box`) VALUES
(2, 'puglia', 'puglia', 2, 1),
(3, 'dd', '', 1, 0),
(6, '10', '', 1, 0),
(7, 'afds', '', 3, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti_admin`
--

CREATE TABLE IF NOT EXISTS `utenti_admin` (
  `ut_id` int(11) NOT NULL AUTO_INCREMENT,
  `ut_username` varchar(50) NOT NULL,
  `ut_password` varchar(50) NOT NULL,
  PRIMARY KEY (`ut_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `utenti_admin`
--

INSERT INTO `utenti_admin` (`ut_id`, `ut_username`, `ut_password`) VALUES
(1, 'admin', '94fbfd1565b5603193c4f4132b2762b7');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
