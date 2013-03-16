-- phpMyAdmin SQL Dump
-- version 3.1.3
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2009 年 07 月 19 日 02:05
-- 服务器版本: 5.0.77
-- PHP 版本: 5.2.9-1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `bnuoj`
--
CREATE DATABASE `bnuoj` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `bnuoj`;

-- --------------------------------------------------------

--
-- 表的结构 `contest`
--

DROP TABLE IF EXISTS `contest`;
CREATE TABLE IF NOT EXISTS `contest` (
  `cid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
  `description` text,
  `isprivate` tinyint(1) NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `lock_board_time` datetime NOT NULL,
  `hide_others` tinyint(1) NOT NULL,
  `board_make` datetime NOT NULL,
  PRIMARY KEY  (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Contest List' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `contest_clarify`
--

DROP TABLE IF EXISTS `contest_clarify`;
CREATE TABLE IF NOT EXISTS `contest_clarify` (
  `ccid` int(11) NOT NULL auto_increment,
  `cid` int(11) NOT NULL,
  `question` text NOT NULL,
  `reply` text NOT NULL,
  `username` varchar(255) NOT NULL,
  `ispublic` tinyint(1) NOT NULL,
  PRIMARY KEY  (`ccid`),
  KEY `cid` (`cid`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `contest_problem`
--

DROP TABLE IF EXISTS `contest_problem`;
CREATE TABLE IF NOT EXISTS `contest_problem` (
  `cpid` int(10) unsigned NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `lable` varchar(20) NOT NULL,
  PRIMARY KEY  (`cpid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Contest, its problems and their status' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `contest_user`
--

DROP TABLE IF EXISTS `contest_user`;
CREATE TABLE IF NOT EXISTS `contest_user` (
  `cuid` int(11) NOT NULL auto_increment,
  `cid` int(10) unsigned NOT NULL,
  `username` varchar(255) NOT NULL,
  PRIMARY KEY  (`cuid`),
  KEY `cuid` (`cuid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `mail`
--

DROP TABLE IF EXISTS `mail`;
CREATE TABLE IF NOT EXISTS `mail` (
  `mailid` int(10) unsigned NOT NULL auto_increment,
  `sender` varchar(255) NOT NULL,
  `reciever` varchar(255) NOT NULL,
  `title` varchar(1024) NOT NULL,
  `content` text NOT NULL,
  `mail_time` datetime NOT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY  (`mailid`),
  KEY `sender` (`sender`),
  KEY `reciever` (`reciever`),
  KEY `mail_time` (`mail_time`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Mail List' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `newsid` int(10) unsigned NOT NULL auto_increment,
  `time_added` datetime NOT NULL,
  `title` varchar(1024) default NULL,
  `content` text,
  `author` varchar(255) default NULL,
  PRIMARY KEY  (`newsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='News List' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `problem`
--

DROP TABLE IF EXISTS `problem`;
CREATE TABLE IF NOT EXISTS `problem` (
  `pid` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) default NULL,
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
  `special_judge_status` tinyint(1) NOT NULL default '0' COMMENT 'have special judger?',
  `basic_solver_value` int(10) unsigned NOT NULL COMMENT 'the basic value for submitting a solver to this problem',
  `ac_value` int(10) unsigned NOT NULL COMMENT 'value for acceptting this problem',
  `time_limit` int(10) unsigned NOT NULL,
  `case_time_limit` int(10) unsigned NOT NULL,
  `memory_limit` int(10) unsigned NOT NULL default '0',
  `hint` text,
  `source` text,
  `hide` tinyint(1) NOT NULL,
  `vid` int(11) NOT NULL,
  `vname` varchar(50) NOT NULL,
  `isvirtual` tinyint(1) NOT NULL,
  PRIMARY KEY  (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Problem list' AUTO_INCREMENT=1000 ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `ranklist`
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
  `solverid` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL,
  `value` int(10) unsigned NOT NULL,
  `filename` varchar(1024) NOT NULL,
  `owner` varchar(255) NOT NULL,
  PRIMARY KEY  (`solverid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Solver List' AUTO_INCREMENT=1 ;

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
  `runid` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL,
  `result` varchar(50) default NULL,
  `memory_used` int(11) default NULL,
  `time_used` int(11) default NULL,
  `time_submit` datetime default NULL,
  `contest_belong` int(10) unsigned NOT NULL,
  `username` varchar(255) default NULL,
  `source` text,
  `language` int(10) unsigned NOT NULL COMMENT '1cpp 2c 3java 4pas',
  `ce_info` text,
  `ipaddr` varchar(255) default NULL,
  `isshared` tinyint(1) NOT NULL,
  PRIMARY KEY  (`runid`),
  KEY `pid` (`pid`),
  KEY `result` (`result`),
  KEY `time_submit` (`time_submit`),
  KEY `contest_belong` (`contest_belong`),
  KEY `username` (`username`),
  KEY `isshared` (`isshared`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Problem Status' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(255) default NULL,
  `nickname` varchar(1024) default NULL,
  `password` char(50) NOT NULL,
  `email` varchar(255) default NULL,
  `school` varchar(255) default NULL,
  `total_submit` int(10) unsigned NOT NULL,
  `total_ac` int(10) unsigned NOT NULL,
  `register_time` datetime NOT NULL,
  `last_login_time` datetime NOT NULL,
  `photo` varchar(255) default NULL,
  `total_value` int(10) unsigned NOT NULL,
  `lock_status` tinyint(1) NOT NULL default '0',
  `isroot` tinyint(1) NOT NULL,
  `ipaddr` varchar(255) default NULL,
  PRIMARY KEY  (`uid`),
  KEY `username` (`username`),
  KEY `nickname` (`nickname`(333)),
  KEY `password` (`password`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='User List' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure for view `ranklist`
--
DROP TABLE IF EXISTS `ranklist`;

CREATE ALGORITHM=UNDEFINED DEFINER=`yc`@`localhost` SQL SECURITY DEFINER VIEW `ranklist` AS select `user`.`uid` AS `uid`,`user`.`username` AS `username`,`user`.`nickname` AS `nickname`,`user`.`total_ac` AS `total_ac`,`user`.`total_submit` AS `total_submit` from `user` order by `user`.`total_ac` desc,`user`.`total_submit`;

