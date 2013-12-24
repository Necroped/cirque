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
--		comment
--			binds a user to a picture with some text
--		followCircus
--			binds a user to a circus
--		followUser
--			binds a user to another one
--		manage
--			binds a user to a circus on a management relationship
--		rate
--			binds a user to a picture with a grade
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
	`id` bigint(10) unsigned auto_increment,
	`name` varchar(32) not null,
	`country` smallint(2) unsigned not null default 0,
	`description` varchar(1024) not null default '',
	`picture` tinyint(1) unsigned not null default 0,
	primary key (`id`),
	unique key (`name`)
) engine=InnoDB auto_increment=500 default charset=`utf8`;

create table `country` (
	`id` smallint(2) unsigned auto_increment,
	`name` varchar(16) not null,
	`latitude` decimal(10, 8) not null comment 'Between -90 and 90 degrees',
	`longitude` decimal(11, 8) not null comment 'Between -180 and 180 degrees',
	primary key (`id`)
) engine= InnoDB auto_increment=1 default charset=`utf8`;

create table `city` (
	`id` bigint(10) unsigned auto_increment,
	`name` varchar(32) not null,
	`country` smallint(2) unsigned not null,
	`latitude` decimal(10, 8) not null comment 'Between -90 and 90 degrees',
	`longitude` decimal(11, 8) not null comment 'Between -180 and 180 degrees',
	primary key (`id`),
	constraint `fk_country_id`
		foreign key (`country`)
		references country(`id`)
		on delete cascade
		on update cascade
) engine=InnoDB auto_increment=1 default charset=`utf8`;

create table `event` (
	`id` bigint(10) unsigned auto_increment,
	`fromDate` date not null,
	`toDate` date not null,
	`city` bigint(10) unsigned not null default 0,
	`description` varchar(128) not null default 'No description',
	`circus` bigint(10) unsigned not null,
	primary key (`id`),
	constraint `fk_city_id`
		foreign key (`city`)
		references city(`id`)
		on delete cascade
		on update cascade,
	constraint `fk_circus_id`
		foreign key (`circus`)
		references circus(`id`)
		on delete cascade
		on update cascade
) engine= InnoDB auto_increment=500 default charset=`utf8`;

create table `user` (
	`id` bigint(20) unsigned auto_increment,
	`username` varchar(64) not null,
	`password` varchar(40) not null,
	`firstName` varchar(16) not null,
	`lastName` varchar(16) not null,
	`email` varchar(64) not null,
	`picture` tinyint(1) unsigned not null default 0,
	primary key (`id`),
	unique key (`username`),
	unique key (`email`)
) engine=InnoDB auto_increment=500 default charset=`utf8`;

create table `message` (
	`id` bigint(10) unsigned auto_increment,
	`fromUser` bigint(10) unsigned not null,
	`toUser` bigint(10) unsigned not null,
	`text` varchar(256) not null,
	`datetime` datetime not null default current_timestamp,
	primary key (`id`),
	constraint `fk_from_user`
		foreign key (`fromUser`)
		references user(`id`)
		on delete cascade
		on update cascade,
	constraint `fk_to_user`
		foreign key (`toUser`)
		references user(`id`)
		on delete cascade
		on update cascade
) engine=InnoDB auto_increment=1 default charset=`utf8`;

create table `picture` (
	`id` bigint(10) unsigned auto_increment,
	`date` datetime not null default current_timestamp on update current_timestamp,
	`event` bigint(10) unsigned not null,
	`description` varchar(64) not null default 'No description',
	`user` bigint(10) not null default 0 comment 'User who uploaded the picture',
	`valid` tinyint(1) default 1,
	primary key (`id`),
	constraint `fk_picture_id`
		foreign key (`event`)
		references event(`id`)
		on delete cascade
		on update cascade,
	constraint `fk_picture_user`
		foreign key `user`
		references user(`id`)
		on delete cascade
		on update cascade
) engine=InnoDB auto_increment=500 default charset=`utf8`;

--
-- Association tables
--
create table `comment`(
	`user` bigint(10) unsigned not null,
	`picture` bigint(10) unsigned not null,
	`text` varchar(256) not null,
	primary key (`user`, `picture`),
	constraint `fk_comment_user_id`
		foreign key (`user`)
		references user(`id`)
		on delete cascade
		on update cascade,
	constraint `fk_comment_picture_id`
		foreign key (`picture`)
		references picture(`id`)
		on delete cascade
		on update cascade
) engine=InnoDB default charset=`utf8`;

create table `followCircus` (
	`user` bigint(10) unsigned not null,
	`circus` bigint(10) unsigned not null,
	`datetime` datetime not null default current_timestamp,
	primary key (`user`, `circus`),
	constraint `fk_followCircus_user_id`
		foreign key (`user`)
		references user(`id`)
		on delete cascade
		on update cascade,
	constraint `fk_followCircus_circus_id`
		foreign key (`circus`)
		references circus(`id`)
		on delete cascade
		on update cascade
) engine=InnoDB default charset=`utf8`;

create table `followUser` (
	`follower` bigint(10) unsigned not null,
	`following` bigint(10) unsigned not null,
	`datetime` datetime not null default current_timestamp,
	primary key (`following`, `follower`),
	constraint `fk_followUser_follower_id`
		foreign key (`follower`)
		references user(`id`)
		on delete cascade
		on update cascade,
	constraint `fk_followUser_following_id`
		foreign key (`following`)
		references user(`id`)
		on delete cascade
		on update cascade
) engine=InnoDB default charset=`utf8`;

create table `manage` (
	`user` bigint(10) unsigned not null,
	`circus` bigint(10) unsigned not null,
	`datetime` datetime not null, default current_timestamp,
	primary key (`user`, `circus`),
	constraint `fk_manage_user`
		foreign key `user`
		references user(`id`)
		on delete cascade
		on update cascade,
	constraint `fk_manage_circus`
		foreign key `circus`
		references circus(`id`)
		on delete cascade
		on update cascade
) engine=InnoDB default charset=`utf8`;

create table `rate` (
	`user` bigint(10) unsigned not null,
	`picture` bigint(10) unsigned not null,
	`date` datetime not null default current_timestamp on update current_timestamp,
	`grade` tinyint(1) unsigned not null comment 'Between 0 and 10',
	primary key (`user`, `picture`),
	constraint `fk_rate_user_id`
		foreign key (`user`)
		references user(`id`)
		on delete cascade
		on update cascade,
	constraint `fk_rate_picture_id`
		foreign key (`picture`)
		references picture(`id`)
		on delete cascade
		on update cascade
) engine=InnoDB default charset=`utf8`;

--
-- Data
--

lock tables `circus` write;
insert into circus (name) 
values ("L'Odyssée du Cirque"), ("Cirque Pinder"), ("Cirque du Soleil");
unlock tables;

lock tables `country` write;
insert into country (id, name, latitude, longitude)
values (1, "France", 46.00000000, 2.00000000);
unlock tables;

lock tables `city` write;
insert into city (name, country, latitude, longitude)
values ("Belfort", 1, 47.37590000, 6.52000000);
unlock tables;

lock tables `user` write;
insert into user (username, password, firstName, lastName, email)
values ("stowka", sha1("893QQY"), "Antoine", "De Gieter",
"antoine@netproduction.fr");
unlock tables;
