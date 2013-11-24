-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 11 月 24 日 14:20
-- 服务器版本: 5.1.66
-- PHP 版本: 5.3.3-7+squeeze15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `bnuojoi`
--
DROP DATABASE `bnuojoi`;
CREATE DATABASE `bnuojoi` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `bnuojoi`;

-- --------------------------------------------------------

--
-- 表的结构 `contest`
--

DROP TABLE IF EXISTS `contest`;
CREATE TABLE IF NOT EXISTS `contest` (
  `cid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `isprivate` tinyint(1) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `lock_board_time` datetime NOT NULL,
  `hide_others` tinyint(1) NOT NULL,
  `board_make` datetime NOT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Contest List';

-- --------------------------------------------------------

--
-- 表的结构 `contest_clarify`
--

DROP TABLE IF EXISTS `contest_clarify`;
CREATE TABLE IF NOT EXISTS `contest_clarify` (
  `ccid` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `question` text NOT NULL,
  `reply` text NOT NULL,
  `username` varchar(255) NOT NULL,
  `ispublic` tinyint(1) NOT NULL,
  PRIMARY KEY (`ccid`),
  KEY `cid` (`cid`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `contest_problem`
--

DROP TABLE IF EXISTS `contest_problem`;
CREATE TABLE IF NOT EXISTS `contest_problem` (
  `cpid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `lable` varchar(20) NOT NULL,
  PRIMARY KEY (`cpid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Contest, its problems and their status';

-- --------------------------------------------------------

--
-- 表的结构 `contest_user`
--

DROP TABLE IF EXISTS `contest_user`;
CREATE TABLE IF NOT EXISTS `contest_user` (
  `cuid` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(10) unsigned NOT NULL,
  `username` varchar(255) NOT NULL,
  PRIMARY KEY (`cuid`),
  KEY `cuid` (`cuid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `mail`
--

DROP TABLE IF EXISTS `mail`;
CREATE TABLE IF NOT EXISTS `mail` (
  `mailid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender` varchar(255) NOT NULL,
  `reciever` varchar(255) NOT NULL,
  `title` varchar(1024) NOT NULL,
  `content` text NOT NULL,
  `mail_time` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`mailid`),
  KEY `sender` (`sender`),
  KEY `reciever` (`reciever`),
  KEY `mail_time` (`mail_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Mail List';

-- --------------------------------------------------------

--
-- 表的结构 `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `newsid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time_added` datetime NOT NULL,
  `title` varchar(1024) DEFAULT NULL,
  `content` text,
  `author` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`newsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='News List';

-- --------------------------------------------------------

--
-- 表的结构 `problem`
--

DROP TABLE IF EXISTS `problem`;
CREATE TABLE IF NOT EXISTS `problem` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `input` text,
  `output` text,
  `sample_in` text,
  `sample_out` text,
  `number_of_testcase` int(10) unsigned NOT NULL,
  `total_submit` int(10) unsigned NOT NULL,
  `total_ac` int(10) unsigned NOT NULL,
  `total_wa` int(10) unsigned NOT NULL,
  `total_re` int(10) unsigned NOT NULL,
  `total_ce` int(10) unsigned NOT NULL,
  `total_tle` int(10) unsigned NOT NULL,
  `total_mle` int(10) unsigned NOT NULL,
  `total_pe` int(10) unsigned NOT NULL,
  `total_ole` int(10) unsigned NOT NULL,
  `total_rf` int(10) unsigned NOT NULL,
  `special_judge_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'have special judger?',
  `basic_solver_value` int(10) unsigned NOT NULL COMMENT 'the basic value for submitting a solver to this problem',
  `ac_value` int(10) unsigned NOT NULL COMMENT 'value for acceptting this problem',
  `time_limit` int(10) unsigned NOT NULL,
  `case_time_limit` int(10) unsigned NOT NULL,
  `memory_limit` int(10) unsigned NOT NULL DEFAULT '0',
  `hint` text,
  `source` text,
  `hide` tinyint(1) NOT NULL,
  `vid` int(11) NOT NULL,
  `vname` varchar(50) NOT NULL,
  `isvirtual` tinyint(1) NOT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Problem list';

-- --------------------------------------------------------

--
-- 替换视图以便查看 `ranklist`
--
DROP VIEW IF EXISTS `ranklist`;
CREATE TABLE IF NOT EXISTS `ranklist` (
`uid` int(10) unsigned
,`username` varchar(255)
,`nickname` varchar(1024)
,`total_ac` int(10) unsigned
,`total_submit` int(10) unsigned
);
-- --------------------------------------------------------

--
-- 表的结构 `solver`
--

DROP TABLE IF EXISTS `solver`;
CREATE TABLE IF NOT EXISTS `solver` (
  `solverid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `value` int(10) unsigned NOT NULL,
  `filename` varchar(1024) NOT NULL,
  `owner` varchar(255) NOT NULL,
  PRIMARY KEY (`solverid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Solver List';

-- --------------------------------------------------------

--
-- 表的结构 `solverlist`
--

DROP TABLE IF EXISTS `solverlist`;
CREATE TABLE IF NOT EXISTS `solverlist` (
  `uid` int(10) unsigned NOT NULL,
  `solverid` int(10) unsigned NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Solver Bought';

-- --------------------------------------------------------

--
-- 表的结构 `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `runid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `result` varchar(5000) DEFAULT NULL,
  `memory_used` int(11) DEFAULT NULL,
  `time_used` int(11) DEFAULT NULL,
  `time_submit` datetime DEFAULT NULL,
  `contest_belong` int(10) unsigned NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `source` text,
  `language` int(10) unsigned NOT NULL COMMENT '1cpp 2c 3java 4pas',
  `ce_info` text,
  `ipaddr` varchar(255) DEFAULT NULL,
  `isshared` tinyint(1) NOT NULL,
  PRIMARY KEY (`runid`),
  KEY `pid` (`pid`),
  KEY `result` (`result`(333)),
  KEY `time_submit` (`time_submit`),
  KEY `contest_belong` (`contest_belong`),
  KEY `username` (`username`),
  KEY `isshared` (`isshared`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Problem Status';

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `nickname` varchar(1024) DEFAULT NULL,
  `password` char(50) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `school` varchar(255) DEFAULT NULL,
  `total_submit` int(10) unsigned NOT NULL,
  `total_ac` int(10) unsigned NOT NULL,
  `register_time` datetime NOT NULL,
  `last_login_time` datetime NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `total_value` int(10) unsigned NOT NULL,
  `lock_status` tinyint(1) NOT NULL DEFAULT '0',
  `isroot` tinyint(1) NOT NULL,
  `ipaddr` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`uid`),
  KEY `username` (`username`),
  KEY `nickname` (`nickname`(333)),
  KEY `password` (`password`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='User List';

-- --------------------------------------------------------

--
-- 视图结构 `ranklist`
--
DROP TABLE IF EXISTS `ranklist`;

CREATE VIEW `ranklist` AS select `user`.`uid` AS `uid`,`user`.`username` AS `username`,`user`.`nickname` AS `nickname`,`user`.`total_ac` AS `total_ac`,`user`.`total_submit` AS `total_submit` from `user` order by `user`.`total_ac` desc,`user`.`total_submit`;
