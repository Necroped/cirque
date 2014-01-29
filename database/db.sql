-- Cirque database

-- @author 
--		Antoine De Gieter
-- @digest 
--		This script will drop any database known as `cirque`.
--		Afterwards it will create a new database `cirque` and add tables
--		according to the following structure.
--		Eventually, it will insert a few required basis rows.
-- @structure
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
-- @data
--		creates circuses:
--			`L'Odysée du Cirque`, 
--			`Cirque Pinder`, 
--			`Cirque du Soleil`		
--		creates city:
--			`Belfort`
--		creates country:
--			`No country`
--			`France`
--		creates user:
--			`stowka`
--

drop database if exists cirque;
create database cirque;
use cirque;

--
-- Entity tables
--

create table `country` (
	`id` smallint(2) unsigned auto_increment,
	`name` varchar(16) not null,
	`short_name` varchar(4) not null,
	`latitude` decimal(10, 8) not null comment 'Between -90 and 90 degrees',
	`longitude` decimal(11, 8) not null comment 'Between -180 and 180 degrees',
	primary key (`id`),
	unique key (`name`),
	unique key (`short_name`)
) engine=InnoDB auto_increment=1 default charset=`utf8`;

create table `circus` (
	`id` bigint(10) unsigned auto_increment,
	`name` varchar(32) not null,
	`country` smallint(2) unsigned not null default 1,
	`description` varchar(1024) not null default '',
	`picture` tinyint(1) unsigned not null default 0,
	primary key (`id`),
	unique key (`name`),
	constraint `fk_circus_country`
		foreign key (`country`)
		references country(`id`)
		on delete cascade
		on update cascade
) engine=InnoDB auto_increment=500 default charset=`utf8`;

create table `city` (
	`id` bigint(10) unsigned auto_increment,
	`name` varchar(32) not null,
	`country` smallint(2) unsigned not null,
	`latitude` decimal(10, 8) not null comment 'Between -90 and 90 degrees',
	`longitude` decimal(11, 8) not null comment 'Between -180 and 180 degrees',
	primary key (`id`),
	constraint `fk_city_country`
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
	constraint `fk_event_city`
		foreign key (`city`)
		references city(`id`)
		on delete cascade
		on update cascade,
	constraint `fk_event_circus`
		foreign key (`circus`)
		references circus(`id`)
		on delete cascade
		on update cascade
) engine= InnoDB auto_increment=500 default charset=`utf8`;

create table `user` (
	`id` bigint(10) unsigned auto_increment,
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
	constraint `fk_message_fromUser`
		foreign key (`fromUser`)
		references user(`id`)
		on delete cascade
		on update cascade,
	constraint `fk_message_toUser`
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
	`user` bigint(10) unsigned not null comment 'User who uploaded the picture',
	`valid` tinyint(1) default 1,
	primary key (`id`),
	constraint `fk_picture_event`
		foreign key (`event`)
		references event(`id`)
		on delete cascade
		on update cascade,
	constraint `fk_picture_user`
		foreign key (`user`)
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
	constraint `fk_comment_user`
		foreign key (`user`)
		references user(`id`)
		on delete cascade
		on update cascade,
	constraint `fk_comment_picture`
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
	constraint `fk_followCircus_user`
		foreign key (`user`)
		references user(`id`)
		on delete cascade
		on update cascade,
	constraint `fk_followCircus_circus`
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
	constraint `fk_followUser_follower`
		foreign key (`follower`)
		references user(`id`)
		on delete cascade
		on update cascade,
	constraint `fk_followUser_following`
		foreign key (`following`)
		references user(`id`)
		on delete cascade
		on update cascade
) engine=InnoDB default charset=`utf8`;

create table `manage` (
	`user` bigint(10) unsigned not null,
	`circus` bigint(10) unsigned not null,
	`datetime` datetime not null default current_timestamp,
	primary key (`user`, `circus`),
	constraint `fk_manage_user`
		foreign key (`user`)
		references user(`id`)
		on delete cascade
		on update cascade,
	constraint `fk_manage_circus`
		foreign key (`circus`)
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
	constraint `fk_rate_user`
		foreign key (`user`)
		references user(`id`)
		on delete cascade
		on update cascade,
	constraint `fk_rate_picture`
		foreign key (`picture`)
		references picture(`id`)
		on delete cascade
		on update cascade
) engine=InnoDB default charset=`utf8`;

--
-- Data
--

lock tables `country` write;
insert into `country` (`id`, `name`, `short_name`, `latitude`, `longitude`) values
(1, 'No country', 'None', '0.00000000', '0.00000000'),
(2, 'Afghanistan', 'AF', '33.93911000', '67.70995300'),
(3, 'Albania', 'AL', '41.15333200', '20.16833100'),
(4, 'Algeria', 'DZ', '28.03388600', '1.65962600'),
(5, 'Andorra', 'AD', '42.50628500', '1.52180100'),
(6, 'Angola', 'AO', '-11.20269200', '17.87388700'),
(7, 'Anguilla', 'AI', '18.22055400', '-63.06861500'),
(8, 'Argentina', 'AR', '-38.41609700', '-63.61667200'),
(9, 'Armenia', 'AM', '40.06909900', '45.03818900'),
(10, 'Aruba', 'AW', '12.52111000', '-69.96833800'),
(11, 'Australia', 'AU', '-25.27439800', '133.77513600'),
(12, 'Austria', 'AT', '47.51623100', '14.55007200'),
(13, 'Azerbaijan', 'AZ', '40.14310500', '47.57692700'),
(14, 'The Bahamas', 'BS', '25.03428000', '-77.39628000'),
(15, 'Bahrain', 'BH', '26.06670000', '50.55770000'),
(16, 'Bangladesh', 'BD', '23.68499400', '90.35633100'),
(17, 'Barbados', 'BB', '13.19388700', '-59.54319800'),
(18, 'Belarus', 'BY', '53.70980700', '27.95338900'),
(19, 'Belgium', 'BE', '50.50388700', '4.46993600'),
(20, 'Belize', 'BZ', '17.18987700', '-88.49765000'),
(21, 'Benin', 'BJ', '9.30769000', '2.31583400'),
(22, 'Bermuda', 'BM', '32.30780000', '-64.75050000'),
(23, 'Bhutan', 'BT', '27.51416200', '90.43360100'),
(24, 'Bolivia', 'BO', '-16.29015400', '-63.58865300'),
(25, 'Botswana', 'BW', '-22.32847400', '24.68486600'),
(26, 'Brazil', 'BR', '-14.23500400', '-51.92528000'),
(27, 'Brunei', 'BN', '4.53527700', '114.72766900'),
(28, 'Bulgaria', 'BG', '42.73388300', '25.48583000'),
(29, 'Burundi', 'BI', '-3.37305600', '29.91888600'),
(30, 'Cambodia', 'KH', '12.56567900', '104.99096300'),
(31, 'Cameroon', 'CM', '7.36972200', '12.35472200'),
(32, 'Canada', 'CA', '56.13036600', '-106.34677100'),
(33, 'Chad', 'TD', '15.45416600', '18.73220700'),
(34, 'Chile', 'CL', '-35.67514700', '-71.54296900'),
(35, 'China', 'CN', '35.86166000', '104.19539700'),
(36, 'Colombia', 'CO', '4.57086800', '-74.29733300'),
(37, 'Comoros', 'KM', '-11.64550000', '43.33330000'),
(38, 'Croatia', 'HR', '45.10000000', '15.20000000'),
(39, 'Cuba', 'CU', '21.52175700', '-77.78116700'),
(40, 'Cyprus', 'CY', '35.12641300', '33.42985900'),
(41, 'Denmark', 'DK', '56.26392000', '9.50178500'),
(42, 'Djibouti', 'DJ', '11.82513800', '42.59027500'),
(43, 'Dominica', 'DM', '15.41499900', '-61.37097600'),
(44, 'Ecuador', 'EC', '-1.83123900', '-78.18340600'),
(45, 'Egypt', 'EG', '26.82055300', '30.80249800'),
(46, 'Eritrea', 'ER', '15.17938400', '39.78233400'),
(47, 'Estonia', 'EE', '58.59527200', '25.01360700'),
(48, 'Ethiopia', 'ET', '9.14500000', '40.48967300'),
(49, 'Fiji', 'FJ', '-17.71337100', '178.06503200'),
(50, 'Finland', 'FI', '61.92411000', '25.74815100'),
(51, 'France', 'FR', '46.22763800', '2.21374900'),
(52, 'Gabon', 'GA', '-0.80368900', '11.60944400'),
(53, 'The Gambia', 'GM', '13.44318200', '-15.31013900'),
(55, 'Germany', 'DE', '51.16569100', '10.45152600'),
(56, 'Ghana', 'GH', '7.94652700', '-1.02319400'),
(57, 'Gibraltar', 'GI', '36.14075100', '-5.35358500'),
(58, 'Greece', 'GR', '39.07420800', '21.82431200'),
(59, 'Greenland', 'GL', '71.70693600', '-42.60430300'),
(60, 'Grenada', 'GD', '12.11650000', '-61.67900000'),
(61, 'Guadeloupe', 'GP', '16.26500000', '-61.55100000'),
(62, 'Guam', 'GU', '13.44430400', '144.79373100'),
(63, 'Guatemala', 'GT', '15.78347100', '-90.23075900'),
(64, 'Guernsey', 'GG', '49.46569100', '-2.58527800'),
(65, 'Guinea', 'GN', '9.94558700', '-9.69664500'),
(66, 'Guinea-Bissau', 'GW', '11.80374900', '-15.18041300'),
(67, 'Guyana', 'GY', '4.86041600', '-58.93018000'),
(68, 'Haiti', 'HT', '18.97118700', '-72.28521500'),
(69, 'Honduras', 'HN', '15.19999900', '-86.24190500'),
(70, 'Hungary', 'HU', '47.16249400', '19.50330400'),
(71, 'Iceland', 'IS', '64.96305100', '-19.02083500'),
(72, 'India', 'IN', '20.59368400', '78.96288000'),
(73, 'Indonesia', 'ID', '-0.78927500', '113.92132700'),
(74, 'Iran', 'IR', '32.42790800', '53.68804600'),
(75, 'Iraq', 'IQ', '33.22319100', '43.67929100'),
(76, 'Ireland', 'IE', '53.41291000', '-8.24389000'),
(77, 'Israel', 'IL', '31.04605100', '34.85161200'),
(78, 'Italy', 'IT', '41.87194000', '12.56738000'),
(79, 'Jamaica', 'JM', '18.10958100', '-77.29750800'),
(80, 'Japan', 'JP', '36.20482400', '138.25292400'),
(81, 'Jersey', 'JE', '49.21443900', '-2.13125000'),
(82, 'Jordan', 'JO', '30.58516400', '36.23841400'),
(83, 'Kazakhstan', 'KZ', '48.01957300', '66.92368400'),
(84, 'Kenya', 'KE', '-0.02355900', '37.90619300'),
(85, 'Kiribati', 'KI', '1.87111500', '-157.36066920'),
(86, 'Kuwait', 'KW', '29.31166000', '47.48176600'),
(87, 'Kyrgyzstan', 'KG', '41.20438000', '74.76609800'),
(88, 'Laos', 'LA', '19.85627000', '102.49549600'),
(89, 'Latvia', 'LV', '56.87963500', '24.60318900'),
(90, 'Lebanon', 'LB', '33.85472100', '35.86228500'),
(91, 'Lesotho', 'LS', '-29.60998800', '28.23360800'),
(92, 'Liberia', 'LR', '6.42805500', '-9.42949900'),
(93, 'Libya', 'LY', '26.33510000', '17.22833100'),
(94, 'Liechtenstein', 'LI', '47.16600000', '9.55537300'),
(95, 'Lithuania', 'LT', '55.16943800', '23.88127500'),
(96, 'Luxembourg', 'LU', '49.81527300', '6.12958300'),
(97, 'Madagascar', 'MG', '-18.76694700', '46.86910700'),
(98, 'Malawi', 'MW', '-13.25430800', '34.30152500'),
(99, 'Malaysia', 'MY', '4.21048400', '101.97576600'),
(100, 'Maldives', 'MV', '1.97724690', '73.53610350'),
(101, 'Mali', 'ML', '17.57069200', '-3.99616600'),
(102, 'Malta', 'MT', '35.93749600', '14.37541600'),
(103, 'Martinique', 'MQ', '14.64152800', '-61.02417400'),
(104, 'Mauritania', 'MR', '21.00789000', '-10.94083500'),
(105, 'Mauritius', 'MU', '-20.34840400', '57.55215200'),
(106, 'Mayotte', 'YT', '-12.82750000', '45.16624400'),
(107, 'Mexico', 'MX', '23.63450100', '-102.55278400'),
(108, 'Micronesia', 'FM', '6.88745740', '158.21507160'),
(109, 'Moldova', 'MD', '47.41163100', '28.36988500'),
(110, 'Monaco', 'MC', '43.73841760', '7.42461580'),
(111, 'Mongolia', 'MN', '46.86249600', '103.84665600'),
(112, 'Montenegro', 'ME', '42.70867800', '19.37439000'),
(113, 'Montserrat', 'MS', '16.74249800', '-62.18736600'),
(114, 'Morocco', 'MA', '31.79170200', '-7.09262000'),
(115, 'Mozambique', 'MZ', '-18.66569500', '35.52956200'),
(116, 'Namibia', 'NA', '-22.95764000', '18.49041000'),
(117, 'Nauru', 'NR', '-0.52277800', '166.93150300'),
(118, 'Nepal', 'NP', '28.39485700', '84.12400800'),
(119, 'The Netherlands', 'NL', '52.13263300', '5.29126600'),
(120, 'Nicaragua', 'NI', '12.86541600', '-85.20722900'),
(121, 'Niger', 'NE', '17.60778900', '8.08166600'),
(122, 'Nigeria', 'NG', '9.08199900', '8.67527700'),
(123, 'Niue', 'NU', '-19.05444500', '-169.86723300'),
(124, 'Norway', 'NO', '60.47202400', '8.46894600'),
(125, 'Oman', 'OM', '21.51258300', '55.92325500'),
(126, 'Pakistan', 'PK', '30.37532100', '69.34511600'),
(127, 'Palau', 'PW', '7.51498000', '134.58252000'),
(128, 'Panama', 'PA', '8.53798100', '-80.78212700'),
(129, 'Paraguay', 'PY', '-23.44250300', '-58.44383200'),
(130, 'Peru', 'PE', '-9.18996700', '-75.01515200'),
(131, 'Philippines', 'PH', '12.87972100', '121.77401700'),
(132, 'Poland', 'PL', '51.91943800', '19.14513600'),
(133, 'Portugal', 'PT', '39.39987200', '-8.22445400'),
(134, 'Qatar', 'QA', '25.35482600', '51.18388400'),
(135, 'Romania', 'RO', '45.94316100', '24.96676000'),
(136, 'Russia', 'RU', '61.52401000', '105.31875600'),
(137, 'Rwanda', 'RW', '-1.94027800', '29.87388800'),
(138, 'Reunion', 'RE', '-21.11514100', '55.53638400'),
(139, 'Samoa', 'WS', '-13.75902900', '-172.10462900'),
(140, 'Senegal', 'SN', '14.49740100', '-14.45236200'),
(141, 'Serbia', 'RS', '44.01652100', '21.00585900'),
(142, 'Seychelles', 'SC', '-4.67957400', '55.49197700'),
(143, 'Singapore', 'SG', '1.35208300', '103.81983600'),
(144, 'Slovakia', 'SK', '48.66902600', '19.69902400'),
(145, 'Slovenia', 'SI', '46.15124100', '14.99546300'),
(146, 'Somalia', 'SO', '5.15214900', '46.19961600'),
(147, 'Spain', 'ES', '40.46366700', '-3.74922000'),
(148, 'Sudan', 'SD', '12.86280700', '30.21763600'),
(149, 'Suriname', 'SR', '3.91930500', '-56.02778300'),
(150, 'Swaziland', 'SZ', '-26.52250300', '31.46586600'),
(151, 'Sweden', 'SE', '60.12816100', '18.64350100'),
(152, 'Switzerland', 'CH', '46.81818800', '8.22751200'),
(153, 'Syria', 'SY', '34.80207500', '38.99681500'),
(154, 'Taiwan', 'TW', '23.69781000', '120.96051500'),
(155, 'Tajikistan', 'TJ', '38.86103400', '71.27609300'),
(156, 'Tanzania', 'TZ', '-6.36902800', '34.88882200'),
(157, 'Thailand', 'TH', '15.87003200', '100.99254100'),
(158, 'Timor-Leste', 'TL', '-8.87421700', '125.72753900'),
(159, 'Togo', 'TG', '8.61954300', '0.82478200'),
(160, 'Tokelau', 'TK', '-9.20020000', '-171.84840000'),
(161, 'Tonga', 'TO', '-21.17898600', '-175.19824200'),
(162, 'Tunisia', 'TN', '33.88691700', '9.53749900'),
(163, 'Turkey', 'TR', '38.96374500', '35.24332200'),
(164, 'Turkmenistan', 'TM', '38.96971900', '59.55627800'),
(165, 'Tuvalu', 'TV', '-7.47844190', '178.67992140'),
(166, 'Uganda', 'UG', '1.37333300', '32.29027500'),
(167, 'Ukraine', 'UA', '48.37943300', '31.16558000'),
(168, 'Uruguay', 'UY', '-32.52277900', '-55.76583500'),
(169, 'Uzbekistan', 'UZ', '41.37749100', '64.58526200'),
(170, 'Vanuatu', 'VU', '-15.37670600', '166.95915800'),
(171, 'Venezuela', 'VE', '6.42375000', '-66.58973000'),
(172, 'Vietnam', 'VN', '14.05832400', '108.27719900'),
(173, 'Yemen', 'YE', '15.55272700', '48.51638800'),
(174, 'Zambia', 'ZM', '-13.13389700', '27.84933200'),
(175, 'Zimbabwe', 'ZW', '-19.01543800', '29.15485700');
unlock tables;

lock tables `circus` write;
insert into circus (name) 
values ("L'Odyssée du Cirque"), ("Cirque Pinder"), ("Cirque du Soleil");
unlock tables;

lock tables `user` write;
insert into user (username, password, firstName, lastName, email)
values ("stowka", sha1("893QQY"), "Antoine", "De Gieter",
"antoine@netproduction.fr");
unlock tables;
