-- Cirque database

-- @author 
--		Antoine De Gieter
-- @digest 
--		This script will drop any database known as `cirque`.
--		Afterwards it will create a new database `cirque` and add tables
--		according to the following structure.
--		Eventually, it will insert a few required basis rows.

-- =========
-- STRUCTURE
-- =========
--		circus
--			describes registered circus entities;
--			circuses can create events and post photographs
--		city
--			describes city entities with its name, country and coordinates:
--		country
--			describes country entities with its name and coordinates
--		event
--			describes event entities;
--			events contain a photo album and happen in a city
--		message
--			describes message entities;
--			messages are sent from a user to another user
--		picture
--			describes picture entities;
--			pictures are have been taken on a date, belong to an event
--			and may have a description
--		user
--			describes a registered user entity
--			regular users can follow and message circuses and users
--
--		followCircus
--			binds a user to a circus
--		followUser
--			binds a user to another one
--
-- ====
-- DATA
-- ====
--		creates circuses:
--			`L'Odysée du Cirque`, 
--			`Cirque Pinder`, 
--			`Cirque du Soleil`		
--		creates city:
--			`Belfort`
--		creates country:
--			`France`
--		creates user:
--			`stowka`
--

drop database cirque;
create database cirque;
use cirque;

--
-- Entity tables
--

create table `circus` (
	`id` bigint(10) not null auto_increment,
	`name` varchar(32) not null,
	`country` smallint(2) default 0,
	`description` varchar(1024) default '',
	`picture` tinyint(1) default 0,
	primary key (`id`),
	unique key (`name`)
) engine=InnoDB auto_increment=500 default charset=`utf8`;

create table `city` (
	`id` bigint(10) not null auto_increment,
	`name` varchar(32) not null,
	`country` bigint(10) not null,
	`latitude` decimal(10, 8) not null,
	`longitude` decimal(11, 8) not null,
	primary key (`id`)
) engine=InnoDB auto_increment=1 default charset=`utf8`;

create table `country` (
	`id` smallint(2) not null auto_increment,
	`name` varchar(16) not null,
	`latitude` decimal(10, 8) not null,
	`longitude` decimal(11, 8) not null,
	primary key (`id`)
) engine= InnoDB auto_increment=1 default charset=`utf8`;

create table `event` (
	`id` bigint(10) not null auto_increment,
	`from` date not null,
	`to` date not null,
	`city` bigint(10) default 0,
	`description` varchar(128) default 'No description',
	`circus` bigint(10) not null,
	primary key (`id`)
) engine= InnoDB auto_increment=500 default charset=`utf8`;

create table `message` (
	`id` bigint(10) not null auto_increment,
	`from` bigint(10) not null,
	`to` bigint(10) not null,
	`text` varchar(256)  not null,
	`datetime` datetime default now(),
	primary key (`id`)
) engine=InnoDB auto_increment=1 default charset=`utf8`;

create table `picture` (
	`id` bigint(10) not null auto_increment,
	`date` timestamp default current_timestamp on update current_timestamp,
	`event` bigint(10) not null,
	`description` varchar(64) default 'No description',
	primary key (`id`)
) engine=InnoDB auto_increment=500 default charset=`utf8`;

create table `user` (
	`id` bigint(20) not null auto_increment,
	`username` varchar(64) not null,
	`password` varchar(40) not null,
	`firstName` varchar(16) not null,
	`lastName` varchar(16) not null,
	`email` varchar(64) not null,
	`picture` tinyint(1) default 0,
	primary key (`id`),
	unique key (`username`),
	unique key (`email`)
) engine=InnoDB auto_increment=500 default charset=`utf8`;

--
-- Association tables
--

create table `followCircus` (
	`user` bigint(10) not null,
	`circus` bigint(10) not null,
	`datetime` datetime default current_timestamp,
	primary key (`user`, `circus`)
) engine=InnoDB default charset=`utf8`;

create table `followUser` (
	`following` bigint(10) not null,
	`follower` bigint(10) not null,
	`datetime` datetime default current_timestamp,
	primary key (`following`, `follower`)
) engine=InnoDB default charset=`utf8`;

--
-- Data
--

insert into circus (name) 
values ("L'Odyssée du Cirque"), ("Cirque Pinder"), ("Cirque du Soleil");

insert into city (name, country, latitude, longitude)
values ("Belfort", 1, 47.37590000, 6.52000000);

insert into country (id, name, latitude, longitude)
values (1, "France", 46.00000000, 2.00000000);

insert into user (username, password, firstName, lastName, email)
values ("stowka", sha1("893QQY"), "Antoine", "De Gieter",
"antoine@netproduction.fr");
