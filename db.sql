--
-- Cirque database
-- @author Antoine De Gieter
--

drop database cirque;
create database cirque;
use cirque;

create table `user` (
	`id` bigint(20) not null auto_increment,
	`username` varchar(64) not null,
	`email` varchar(64) not null,
	`picture` tinyint(1) not null,
	primary key(`id`),
	unique (`username`),
	unique (`email`)
) engine=InnoDB auto_increment=500 default charset=`utf-8`;

create table `message` (
	`ìd` bigint(10) not null auto_increment,
	`from` bigint(10) not null,
	`to` bigint(10) not null,
	`text` varchar(256)  not null,
	primary key(`id`)
) engine=InnoDB auto_increment=1 default charset=`utf-8`;
