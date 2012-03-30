-- phpMyAdmin SQL Dump
-- version 2.11.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 06, 2009 at 03:49 PM
-- Server version: 5.0.45
-- PHP Version: 5.1.6

--
-- Database: `Hotel`
--

CREATE DATABASE `Hotel`;
USE Hotel;

--
-- Table structure for table `Rates`
--

CREATE TABLE IF NOT EXISTS `Rates` (
  `ID` int(11) NOT NULL auto_increment,
  `Desc` varchar(50) NOT NULL,
  `Type` varchar(30) NOT NULL,
  `Pref` varchar(10) NOT NULL,
  `Min` float(10,4) NOT NULL,
  `Risp` float(10,4) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Rooms`
--

CREATE TABLE IF NOT EXISTS `Rooms` (
  `ID` int(11) NOT NULL auto_increment,
  `Desc` varchar(50) default NULL,
  `Ext` varchar(5) default NULL,
  `Data` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  UNIQUE KEY `ID` (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `ID` int(11) NOT NULL auto_increment,
  `Room` int(5) NOT NULL,
  `Desc` varchar(50) NOT NULL,
  `Ext` varchar(5) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Checkin` datetime default NULL,
  `Checkout` datetime default NULL,
  `Total` varchar(10) default NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
