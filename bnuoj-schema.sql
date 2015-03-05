-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2013 年 11 月 24 日 14:17
-- 服务器版本: 5.1.66
-- PHP 版本: 5.3.3-7+squeeze15

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 数据库: `bnuoj`
--
DROP DATABASE `bnuoj`;
CREATE DATABASE `bnuoj` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `bnuoj`;

-- --------------------------------------------------------

--
-- 表的结构 `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(2048) NOT NULL,
  `parent` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `challenge`
--

DROP TABLE IF EXISTS `challenge`;
CREATE TABLE IF NOT EXISTS `challenge` (
  `cha_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(500) NOT NULL,
  `runid` int(11) NOT NULL,
  `data_type` int(11) NOT NULL,
  `data_detail` text NOT NULL,
  `data_lang` int(11) NOT NULL,
  `cha_result` varchar(500) NOT NULL,
  `cha_detail` text NOT NULL,
  `cha_time` datetime NOT NULL,
  `cid` int(11) NOT NULL,
  PRIMARY KEY (`cha_id`),
  KEY `cha_time` (`cha_time`),
  KEY `cid` (`cid`),
  KEY `username` (`username`(333))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(4096) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

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
  `isvirtual` smallint(6) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `report` mediumtext NOT NULL,
  `mboard_make` datetime NOT NULL,
  `allp` varchar(1000) NOT NULL,
  `type` int(11) NOT NULL,
  `has_cha` tinyint(1) NOT NULL,
  `challenge_end_time` datetime NOT NULL,
  `challenge_start_time` datetime NOT NULL,
  `password` varchar(2048) NOT NULL,
  `owner_viewable` tinyint(1) NOT NULL,
  PRIMARY KEY (`cid`),
  KEY `allp` (`allp`(333)),
  KEY `start_time` (`start_time`),
  KEY `end_time` (`end_time`),
  KEY `lock_board_time` (`lock_board_time`),
  KEY `isprivate` (`isprivate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Contest List';

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

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
  `type` int(11) NOT NULL,
  `base` int(11) NOT NULL,
  `minp` int(11) NOT NULL,
  `para_a` double NOT NULL,
  `para_b` double NOT NULL,
  `para_c` double NOT NULL,
  `para_d` double NOT NULL,
  `para_e` double NOT NULL,
  `para_f` double NOT NULL,
  PRIMARY KEY (`cpid`),
  KEY `cid` (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='Contest, its problems and their status';

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `discuss`
--

DROP TABLE IF EXISTS `discuss`;
CREATE TABLE IF NOT EXISTS `discuss` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fid` int(10) NOT NULL,
  `rid` int(10) NOT NULL,
  `time` datetime NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `uname` varchar(255) NOT NULL,
  `pid` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `mail`
--

DROP TABLE IF EXISTS `mail`;
CREATE TABLE IF NOT EXISTS `mail` (
  `mailid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sender` varchar(255) NOT NULL,
  `reciever` varchar(255) NOT NULL,
  `title` text NOT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='News List';

-- --------------------------------------------------------

--
-- 表的结构 `ojinfo`
--

DROP TABLE IF EXISTS `ojinfo`;
CREATE TABLE IF NOT EXISTS `ojinfo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `int64io` varchar(255) NOT NULL,
  `javaclass` varchar(255) NOT NULL,
  `status` varchar(1024) NOT NULL,
  `supportlang` varchar(1024) NOT NULL,
  `lastcheck` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- 表的结构 `problem`
--

DROP TABLE IF EXISTS `problem`;
CREATE TABLE IF NOT EXISTS `problem` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(255) NOT NULL,
  `description` longtext NOT NULL,
  `input` text NOT NULL,
  `output` text NOT NULL,
  `sample_in` text NOT NULL,
  `sample_out` text NOT NULL,
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
  `special_judge_status` smallint(6) NOT NULL DEFAULT '0' COMMENT 'have special judger?',
  `basic_solver_value` int(10) unsigned NOT NULL COMMENT 'the basic value for submitting a solver to this problem',
  `ac_value` int(10) unsigned NOT NULL COMMENT 'value for acceptting this problem',
  `time_limit` int(10) unsigned NOT NULL,
  `case_time_limit` int(10) unsigned NOT NULL,
  `memory_limit` int(10) unsigned NOT NULL DEFAULT '0',
  `hint` text NOT NULL,
  `source` text NOT NULL,
  `author` text NOT NULL,
  `hide` tinyint(1) NOT NULL,
  `vid` char(50) NOT NULL,
  `vname` char(50) NOT NULL,
  `isvirtual` tinyint(1) NOT NULL,
  `vacnum` int(11) NOT NULL,
  `vtotalnum` int(11) NOT NULL,
  `ignore_noc` tinyint(1) NOT NULL,
  `vacpnum` int(11) NOT NULL,
  `vtotalpnum` int(11) NOT NULL,
  `is_interactive` tinyint(1) NOT NULL,
  PRIMARY KEY (`pid`),
  KEY `vname` (`vname`),
  KEY `isvirtual` (`isvirtual`),
  KEY `vid` (`vid`),
  KEY `hide` (`hide`),
  KEY `title` (`title`),
  FULLTEXT KEY `source` (`source`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Problem list';

-- --------------------------------------------------------

--
-- 表的结构 `problem_category`
--

DROP TABLE IF EXISTS `problem_category`;
CREATE TABLE IF NOT EXISTS `problem_category` (
  `pcid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `catid` int(11) NOT NULL,
  `weight` int(11) NOT NULL,
  PRIMARY KEY (`pcid`),
  KEY `catid` (`catid`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 替换视图以便查看 `ranklist`
--
DROP VIEW IF EXISTS `ranklist`;
CREATE TABLE IF NOT EXISTS `ranklist` (
`uid` int(10) unsigned
,`username` varchar(255)
,`nickname` varchar(1024)
,`local_ac` int(11)
,`total_ac` int(10) unsigned
,`total_submit` int(10) unsigned
);
-- --------------------------------------------------------

--
-- 表的结构 `replay_status`
--

DROP TABLE IF EXISTS `replay_status`;
CREATE TABLE IF NOT EXISTS `replay_status` (
  `runid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL,
  `result` varchar(100) DEFAULT NULL,
  `time_submit` datetime DEFAULT NULL,
  `contest_belong` int(10) unsigned NOT NULL,
  `username` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`runid`),
  KEY `pid` (`pid`),
  KEY `result` (`result`),
  KEY `time_submit` (`time_submit`),
  KEY `contest_belong` (`contest_belong`),
  KEY `username` (`username`(333))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Replay Status';

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
  `result` varchar(100) DEFAULT NULL,
  `memory_used` int(11) DEFAULT NULL,
  `time_used` int(11) DEFAULT NULL,
  `time_submit` datetime DEFAULT NULL,
  `contest_belong` int(10) unsigned NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `source` mediumtext,
  `language` int(10) unsigned NOT NULL COMMENT '1cpp 2c 3java 4pas',
  `ce_info` text,
  `ipaddr` varchar(255) DEFAULT NULL,
  `isshared` tinyint(1) NOT NULL,
  `jnum` smallint(6) NOT NULL,
  PRIMARY KEY (`runid`),
  KEY `pid` (`pid`),
  KEY `result` (`result`),
  KEY `time_submit` (`time_submit`),
  KEY `contest_belong` (`contest_belong`),
  KEY `username` (`username`),
  KEY `isshared` (`isshared`),
  KEY `language` (`language`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Problem Status';

-- --------------------------------------------------------

--
-- 表的结构 `time_bbs`
--

DROP TABLE IF EXISTS `time_bbs`;
CREATE TABLE IF NOT EXISTS `time_bbs` (
  `rid` int(10) NOT NULL,
  `time` datetime NOT NULL,
  `pid` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `isroot` int(11) NOT NULL,
  `ipaddr` varchar(255) DEFAULT NULL,
  `local_ac` int(11) NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `username` (`username`),
  KEY `nickname` (`nickname`(333)),
  KEY `password` (`password`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='User List';

-- --------------------------------------------------------

--
-- 表的结构 `usertag`
--

DROP TABLE IF EXISTS `usertag`;
CREATE TABLE IF NOT EXISTS `usertag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `username` varchar(2048) NOT NULL,
  `catid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `username` (`username`(333)),
  KEY `catid` (`catid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `vurl`
--

DROP TABLE IF EXISTS `vurl`;
CREATE TABLE IF NOT EXISTS `vurl` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `voj` varchar(50) NOT NULL,
  `vid` varchar(50) NOT NULL,
  `url` varchar(2048) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `voj` (`voj`,`vid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 视图结构 `ranklist`
--
DROP TABLE IF EXISTS `ranklist`;

CREATE VIEW `ranklist` AS select `user`.`uid` AS `uid`,`user`.`username` AS `username`,`user`.`nickname` AS `nickname`,`user`.`local_ac` AS `local_ac`,`user`.`total_ac` AS `total_ac`,`user`.`total_submit` AS `total_submit` from `user` order by `user`.`local_ac` desc,`user`.`total_ac` desc,`user`.`total_submit`;
