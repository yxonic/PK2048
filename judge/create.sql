CREATE TABLE `users` (
   `uid` int(8) unsigned NOT NULL auto_increment,
   `username` char(15) NOT NULL default '',
   `password` char(32) NOT NULL default '',
   `email` varchar(40) NOT NULL default '',
   `regdate` int(10) unsigned NOT NULL default '0',
   PRIMARY KEY (`uid`)
) DEFAULT AUTO_INCREMENT=1;

CREATE TABLE `robots` (
   `botid` int(8) unsigned NOT NULL auto_increment,
   `uid` int(8) unsigned NOT NULL default '0',
   `botname` varchar(15) NOT NULL default '',
   `public` boolean default false,
   `regtime` int(10) unsigned NOT NULL default '0',
   PRIMARY KEY (`botid`)
) DEFAULT AUTO_INCREMENT=1;

CREATE TABLE `logs` (
   `logid` int(10) unsigned NOT NULL auto_increment,
   `masteruid` int(8) unsigned NOT NULL default '0',
   `bot1id` int(8) unsigned NOT NULL default '0',
   `bot2id` int(8) unsigned NOT NULL default '0',
   PRIMARY KEY (`logid`)
) DEFAULT AUTO_INCREMENT=1;

