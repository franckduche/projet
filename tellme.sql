-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Sam 25 Juillet 2015 à 15:13
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `tellme`
--

-- --------------------------------------------------------

--
-- Structure de la table `choice`
--

CREATE TABLE IF NOT EXISTS `choice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `opinionId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `opinionId` (`opinionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `choice`
--

INSERT INTO `choice` (`id`, `opinionId`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `friendlist`
--

CREATE TABLE IF NOT EXISTS `friendlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId1` int(11) NOT NULL,
  `userId2` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId2` (`userId2`),
  KEY `userId1` (`userId1`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `friendlist`
--

INSERT INTO `friendlist` (`id`, `userId1`, `userId2`) VALUES
(1, 1, 2),
(2, 2, 3);

-- --------------------------------------------------------

--
-- Structure de la table `opinion`
--

CREATE TABLE IF NOT EXISTS `opinion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `date` date NOT NULL,
  `comment` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `opinion`
--

INSERT INTO `opinion` (`id`, `userId`, `date`, `comment`, `type`) VALUES
(1, 1, '2015-07-23', 'aoaoaoaoaoaoaoaoaoo', 'poll'),
(2, 1, '2015-07-23', 'oeoeoeoeoeoeoeoeoeoe', 'choice');

-- --------------------------------------------------------

--
-- Structure de la table `opiniontoanswer`
--

CREATE TABLE IF NOT EXISTS `opiniontoanswer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `opinionId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `answer` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `opinionId` (`opinionId`),
  KEY `userId` (`userId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `opiniontoanswer`
--

INSERT INTO `opiniontoanswer` (`id`, `date`, `opinionId`, `userId`, `answer`) VALUES
(1, '0000-00-00', 1, 2, 0),
(2, '0000-00-00', 2, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `picture`
--

CREATE TABLE IF NOT EXISTS `picture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(100) NOT NULL,
  `filepath` varchar(250) NOT NULL,
  `opinionId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `opinionId` (`opinionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `picture`
--

INSERT INTO `picture` (`id`, `filename`, `filepath`, `opinionId`) VALUES
(1, 'fic1.png', 'path/path/path', 1),
(2, 'fic2.png', 'path/path/path', 2),
(3, 'fic3.png', 'path/path/path', 2);

-- --------------------------------------------------------

--
-- Structure de la table `poll`
--

CREATE TABLE IF NOT EXISTS `poll` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `OpinionId` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `OpinionId` (`OpinionId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `poll`
--

INSERT INTO `poll` (`id`, `OpinionId`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phoneNumber` varchar(15) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phoneNumber` (`phoneNumber`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `phoneNumber`, `nickname`, `password`) VALUES
(1, '0601010101', 'aaaaaa', 'aaaaaa'),
(2, '0602020202', 'bbbbbb', 'bbbbbb'),
(3, '0603030303', 'cccccc', 'cccccc');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `choice`
--
ALTER TABLE `choice`
  ADD CONSTRAINT `choice_ibfk_1` FOREIGN KEY (`opinionId`) REFERENCES `opinion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `friendlist`
--
ALTER TABLE `friendlist`
  ADD CONSTRAINT `friendlist_ibfk_1` FOREIGN KEY (`userId1`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `friendlist_ibfk_2` FOREIGN KEY (`userId2`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `opinion`
--
ALTER TABLE `opinion`
  ADD CONSTRAINT `opinion_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `opiniontoanswer`
--
ALTER TABLE `opiniontoanswer`
  ADD CONSTRAINT `opiniontoanswer_ibfk_1` FOREIGN KEY (`opinionId`) REFERENCES `opinion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `opiniontoanswer_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `picture`
--
ALTER TABLE `picture`
  ADD CONSTRAINT `picture_ibfk_1` FOREIGN KEY (`opinionId`) REFERENCES `opinion` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `poll`
--
ALTER TABLE `poll`
  ADD CONSTRAINT `poll_ibfk_1` FOREIGN KEY (`OpinionId`) REFERENCES `opinion` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
