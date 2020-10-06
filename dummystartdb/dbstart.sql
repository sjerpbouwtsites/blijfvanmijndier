-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 01 okt 2020 om 20:57
-- Serverversie: 5.7.14
-- PHP-versie: 7.0.10
-- SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- SET time_zone = "+00:00";
--
-- Database: `bvmd`
--SHOW
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `animals`
--
CREATE TABLE `animals` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `breed_id` int(10) UNSIGNED NOT NULL,
  `animaltype_id` int(10) UNSIGNED NOT NULL,
  `gendertype_id` int(10) UNSIGNED NOT NULL,
  `owner_id` int(10) UNSIGNED DEFAULT NULL,
  `guest_id` int(10) UNSIGNED DEFAULT NULL,
  `shelter_id` int(10) UNSIGNED DEFAULT NULL,
  `chip_number` text,
  `birth_date` date DEFAULT NULL,
  `passport_number` text,
  `registration_date` date DEFAULT NULL,
  `placement_date` date DEFAULT NULL,
  `abused` tinyint(4) DEFAULT NULL,
  `witnessed_abuse` tinyint(4) DEFAULT NULL,
  `updates` tinyint(4) DEFAULT '1',
  `max_hours_alone` text,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `endtype_id` int(10) UNSIGNED DEFAULT NULL,
  `end_description` text
) ENGINE = MyISAM DEFAULT CHARSET = latin1;
--
-- Gegevens worden geëxporteerd voor tabel `animals`
--
INSERT INTO `animals` (
    `id`,
    `name`,
    `breed_id`,
    `animaltype_id`,
    `gendertype_id`,
    `owner_id`,
    `guest_id`,
    `shelter_id`,
    `chip_number`,
    `birth_date`,
    `passport_number`,
    `registration_date`,
    `placement_date`,
    `abused`,
    `witnessed_abuse`,
    `updates`,
    `max_hours_alone`,
    `updated_at`,
    `created_at`,
    `end_date`,
    `endtype_id`,
    `end_description`
  )
VALUES (
    1,
    'Dier 1',
    5,
    24,
    30,
    1,
    NULL,
    1,
    '',
    '2018-01-10',
    '',
    '2020-09-01',
    NULL,
    0,
    0,
    1,
    '',
    '2020-09-29 13:27:39',
    '2020-09-29 12:48:42',
    NULL,
    NULL,
    NULL
  ),
  (
    2,
    'Dier 2',
    75,
    25,
    31,
    1,
    1,
    NULL,
    '',
    '2020-02-04',
    '',
    '2020-09-29',
    NULL,
    0,
    0,
    1,
    '',
    '2020-10-01 18:19:27',
    '2020-09-29 13:47:51',
    NULL,
    NULL,
    NULL
  );
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `animal_table`
--
CREATE TABLE `animal_table` (
  `id` int(10) UNSIGNED NOT NULL,
  `animal_id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE = MyISAM DEFAULT CHARSET = latin1;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `contacts`
--
CREATE TABLE `contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `owner_id` int(10) UNSIGNED NOT NULL,
  `location_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email_address` varchar(100) DEFAULT NULL,
  `phone_number` varchar(100) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE = MyISAM DEFAULT CHARSET = latin1;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `documents`
--
CREATE TABLE `documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `doctype_id` int(10) UNSIGNED NOT NULL,
  `link_id` int(10) UNSIGNED NOT NULL,
  `link_type` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `text` text NOT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE = MyISAM DEFAULT CHARSET = latin1;
--
-- Gegevens worden geëxporteerd voor tabel `documents`
--
INSERT INTO `documents` (
    `id`,
    `doctype_id`,
    `link_id`,
    `link_type`,
    `date`,
    `text`,
    `created_at`,
    `updated_at`
  )
VALUES (
    1,
    56,
    1,
    'animal',
    '2020-09-29',
    'Test van Bas',
    '2020-09-29',
    '2020-09-29'
  );
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `guests`
--
CREATE TABLE `guests` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` text,
  `street` text,
  `house_number` text,
  `postal_code` text,
  `city` text,
  `phone_number` text,
  `email_address` text,
  `max_hours_alone` int(10) NOT NULL,
  `text` text,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE = MyISAM DEFAULT CHARSET = latin1;
--
-- Gegevens worden geëxporteerd voor tabel `guests`
--
INSERT INTO `guests` (
    `id`,
    `name`,
    `street`,
    `house_number`,
    `postal_code`,
    `city`,
    `phone_number`,
    `email_address`,
    `max_hours_alone`,
    `text`,
    `updated_at`,
    `created_at`
  )
VALUES (
    1,
    'Gastgezin 1',
    'Straat 10',
    NULL,
    '1234 AB',
    'Woonplaats',
    '0612345678',
    'mail@mailen.nl',
    0,
    '',
    '2020-09-29 13:03:50',
    '2020-09-29 13:03:50'
  );
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `guest_table`
--
CREATE TABLE `guest_table` (
  `id` int(10) UNSIGNED NOT NULL,
  `guest_id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE = MyISAM DEFAULT CHARSET = latin1;
--
-- Gegevens worden geëxporteerd voor tabel `guest_table`
--
INSERT INTO `guest_table` (
    `id`,
    `guest_id`,
    `table_id`,
    `created_at`,
    `updated_at`
  )
VALUES (1, 1, 16, NULL, NULL),
  (2, 1, 15, NULL, NULL),
  (3, 1, 24, NULL, NULL),
  (4, 1, 25, NULL, NULL);
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `histories`
--
CREATE TABLE `histories` (
  `id` int(10) UNSIGNED NOT NULL,
  `link_type` varchar(50) NOT NULL,
  `link_id` int(10) UNSIGNED NOT NULL,
  `source_type` varchar(50) NOT NULL,
  `source_id` int(10) UNSIGNED NOT NULL,
  `history_date` datetime DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE = MyISAM DEFAULT CHARSET = latin1;
--
-- Gegevens worden geëxporteerd voor tabel `histories`
--
INSERT INTO `histories` (
    `id`,
    `link_type`,
    `link_id`,
    `source_type`,
    `source_id`,
    `history_date`,
    `action`,
    `created_at`,
    `updated_at`
  )
VALUES (
    1,
    'owners',
    1,
    'animals',
    1,
    '2020-09-29 13:15:57',
    'connect',
    '2020-09-29 13:15:57',
    '2020-09-29 13:15:57'
  ),
  (
    2,
    'shelters',
    1,
    'animals',
    1,
    '2020-09-29 13:27:39',
    'connect',
    '2020-09-29 13:27:39',
    '2020-09-29 13:27:39'
  ),
  (
    3,
    'guests',
    1,
    'animals',
    2,
    '2020-09-29 13:48:36',
    'connect',
    '2020-09-29 13:48:36',
    '2020-09-29 13:48:36'
  ),
  (
    4,
    'owners',
    1,
    'animals',
    2,
    '2020-09-29 13:48:47',
    'connect',
    '2020-09-29 13:48:47',
    '2020-09-29 13:48:47'
  ),
  (
    5,
    'guests',
    1,
    'animals',
    2,
    '2020-10-01 18:19:06',
    'unconnect',
    '2020-10-01 18:19:06',
    '2020-10-01 18:19:06'
  ),
  (
    6,
    'guests',
    1,
    'animals',
    2,
    '2020-10-01 18:19:27',
    'connect',
    '2020-10-01 18:19:27',
    '2020-10-01 18:19:27'
  );
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `locations`
--
CREATE TABLE `locations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` text,
  `street` text,
  `house_number` text,
  `postal_code` text,
  `city` text,
  `phone_number` text,
  `email_address` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE = MyISAM DEFAULT CHARSET = latin1;
--
-- Gegevens worden geëxporteerd voor tabel `locations`
--
INSERT INTO `locations` (
    `id`,
    `name`,
    `street`,
    `house_number`,
    `postal_code`,
    `city`,
    `phone_number`,
    `email_address`,
    `created_at`,
    `updated_at`
  )
VALUES (
    1,
    'Opvanglocatie 1',
    'Straat',
    '10',
    '1234 AB',
    'Woonplaats',
    '0612345678',
    'mail@mailen.nl',
    '2020-09-29 12:59:12',
    '2020-09-29 12:59:12'
  );
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `menuitems`
--
CREATE TABLE `menuitems` (
  `id` int(10) UNSIGNED NOT NULL,
  `sequence` int(11) NOT NULL,
  `name` text NOT NULL,
  `url` text NOT NULL,
  `icon` text NOT NULL
) ENGINE = MyISAM DEFAULT CHARSET = latin1;
--
-- Gegevens worden geëxporteerd voor tabel `menuitems`
--
INSERT INTO `menuitems` (`id`, `sequence`, `name`, `url`, `icon`)
VALUES (1, 0, 'Dieren', 'animals', 'fa-paw'),
  (2, 6, 'Tabellen', 'tables', 'fa-cog');
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `owners`
--
CREATE TABLE `owners` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` text,
  `prefix` text,
  `surname` text,
  `street` text,
  `house_number` text,
  `postal_code` text,
  `city` text,
  `phone_number` text,
  `email_address` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE = MyISAM DEFAULT CHARSET = latin1;
--
-- Gegevens worden geëxporteerd voor tabel `owners`
--
INSERT INTO `owners` (
    `id`,
    `name`,
    `prefix`,
    `surname`,
    `street`,
    `house_number`,
    `postal_code`,
    `city`,
    `phone_number`,
    `email_address`,
    `created_at`,
    `updated_at`
  )
VALUES (
    1,
    'E',
    '',
    'Eigenaar 1',
    'Straat',
    '10',
    '1234 AB',
    'Woonplaats',
    '0612345678',
    'mail@mailen.nl',
    '2020-09-29 13:14:57',
    '2020-09-29 13:14:57'
  );
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `shelters`
--
CREATE TABLE `shelters` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `street` text NOT NULL,
  `house_number` text NOT NULL,
  `postal_code` text NOT NULL,
  `city` text NOT NULL,
  `phone_number` text NOT NULL,
  `email_address` text NOT NULL,
  `website` text NOT NULL,
  `contact_person` text NOT NULL,
  `remarks_contract` text NOT NULL,
  `remarks_general` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE = MyISAM DEFAULT CHARSET = latin1;
--
-- Gegevens worden geëxporteerd voor tabel `shelters`
--
INSERT INTO `shelters` (
    `id`,
    `name`,
    `street`,
    `house_number`,
    `postal_code`,
    `city`,
    `phone_number`,
    `email_address`,
    `website`,
    `contact_person`,
    `remarks_contract`,
    `remarks_general`,
    `created_at`,
    `updated_at`
  )
VALUES (
    1,
    'Pension 1',
    'Straat',
    '10',
    '1234 AB',
    'Woonplaats',
    '0612345678',
    'mail@mailen.nl',
    'website.nl',
    'Contactpersoon 1',
    'Afspraak 1\r\nAfspraak 2',
    'Opmerking 1\r\nOpmerking 2',
    '2020-09-29 13:04:16',
    '2020-09-29 14:50:49'
  );
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `tablegroups`
--
CREATE TABLE `tablegroups` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(50) NOT NULL,
  `name` text NOT NULL
) ENGINE = MyISAM DEFAULT CHARSET = latin1;
--
-- Gegevens worden geëxporteerd voor tabel `tablegroups`
--
INSERT INTO `tablegroups` (`id`, `type`, `name`)
VALUES (1, 'breed', 'Rassen'),
  (2, 'behaviour', 'Gedragskenmerken'),
  (3, 'vaccination', 'Vaccinaties'),
  (4, 'animal_type', 'Diersoorten'),
  (5, 'home_type', 'Wooneigenschappen'),
  (6, 'gender_type', 'Geslachtseigenschappen'),
  (7, 'employee', 'Medewerkers'),
  (8, 'doctype', 'Documentsoort'),
  (9, 'end_type', 'Afmeldreden'),
  (10, 'update_type', 'Updatesoort');
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `tables`
--
CREATE TABLE `tables` (
  `id` int(10) UNSIGNED NOT NULL,
  `tablegroup_id` int(10) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `description2` text NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE = MyISAM DEFAULT CHARSET = latin1;
--
-- Gegevens worden geëxporteerd voor tabel `tables`
--
INSERT INTO `tables` (
    `id`,
    `tablegroup_id`,
    `description`,
    `description2`,
    `updated_at`,
    `created_at`
  )
VALUES (1, 1, 'Boxer', '', NULL, NULL),
  (2, 1, 'Engelse Bulldog', '', NULL, NULL),
  (3, 1, 'Labradoodle', '', NULL, NULL),
  (4, 1, 'Maltezer', '', NULL, NULL),
  (
    5,
    1,
    'West Highland White Terrier',
    '',
    NULL,
    NULL
  ),
  (6, 1, 'Sint Bernard', '', NULL, NULL),
  (
    16,
    2,
    'Goed met katten',
    '',
    '2016-12-05 22:04:01',
    '2016-12-05 22:04:01'
  ),
  (
    15,
    2,
    'Goed met honden',
    '',
    '2016-12-05 22:03:17',
    '2016-12-05 22:03:17'
  ),
  (
    17,
    2,
    'Angstig',
    '',
    '2016-12-05 22:04:41',
    '2016-12-05 22:04:41'
  ),
  (
    18,
    2,
    'Voernijd',
    '',
    '2016-12-19 23:39:08',
    '2016-12-19 23:39:08'
  ),
  (
    19,
    3,
    'Rabiës',
    '',
    '2016-12-19 23:45:32',
    '2016-12-19 23:45:32'
  ),
  (
    20,
    3,
    'Hondenziekte',
    '',
    '2016-12-19 23:46:17',
    '2016-12-19 23:46:17'
  ),
  (
    21,
    3,
    'Hepatitis',
    '',
    '2016-12-19 23:47:08',
    '2016-12-19 23:47:08'
  ),
  (
    22,
    3,
    'Parvovirus',
    '',
    '2016-12-19 23:47:41',
    '2016-12-19 23:47:41'
  ),
  (
    23,
    2,
    'Verlatingsangst',
    '',
    '2016-12-20 19:49:45',
    '2016-12-20 19:49:45'
  ),
  (
    24,
    4,
    'Hond',
    '',
    '2016-12-31 13:29:46',
    '2016-12-31 13:29:46'
  ),
  (
    25,
    4,
    'Kat',
    '',
    '2016-12-31 13:30:12',
    '2016-12-31 13:30:12'
  ),
  (
    26,
    5,
    'Flat',
    '',
    '2017-01-05 09:21:41',
    '2017-01-05 09:21:41'
  ),
  (
    27,
    5,
    'Rijtjeshuis',
    '',
    '2017-01-05 09:21:55',
    '2017-01-05 09:21:55'
  ),
  (
    28,
    6,
    'Man (Intact)',
    '',
    '2017-01-05 15:43:02',
    '2017-01-05 09:22:08'
  ),
  (
    29,
    6,
    'Vrouw (Intact)',
    '',
    '2017-01-05 15:43:18',
    '2017-01-05 09:22:17'
  ),
  (
    30,
    6,
    'Man (Gecastreerd)',
    '',
    '2017-01-05 15:44:08',
    '2017-01-05 09:27:17'
  ),
  (
    31,
    6,
    'Vrouw (Gesteriliseerd)',
    '',
    '2017-01-05 15:43:40',
    '2017-01-05 09:27:42'
  ),
  (
    32,
    5,
    'Tuin',
    '',
    '2017-01-05 15:31:25',
    '2017-01-05 15:31:25'
  ),
  (
    34,
    5,
    'Balkon',
    '',
    '2017-01-05 15:31:42',
    '2017-01-05 15:31:42'
  ),
  (
    35,
    2,
    'Goed met kinderen',
    '',
    '2017-01-05 15:50:31',
    '2017-01-05 15:49:46'
  ),
  (
    36,
    1,
    'Europees korthaar',
    '',
    '2019-02-12 18:52:11',
    '2017-05-23 16:38:41'
  ),
  (
    37,
    1,
    'Amerikaanse Stafford',
    '',
    '2017-05-23 18:29:17',
    '2017-05-23 18:29:17'
  ),
  (
    38,
    1,
    'Engelse Stafford',
    '',
    '2017-05-23 18:29:32',
    '2017-05-23 18:29:32'
  ),
  (
    39,
    1,
    'Amerikaanse Bulldog',
    '',
    '2017-05-23 18:30:41',
    '2017-05-23 18:30:41'
  ),
  (
    40,
    3,
    'Moet nog gedaan worden',
    '',
    '2017-05-23 18:31:22',
    '2017-05-23 18:31:22'
  ),
  (
    41,
    3,
    'Niesziekte',
    '',
    '2017-05-23 18:31:54',
    '2017-05-23 18:31:54'
  ),
  (
    42,
    1,
    'Sharpei X Staffordshire terrier',
    '',
    '2019-02-12 18:56:31',
    '2017-05-23 18:39:46'
  ),
  (
    43,
    2,
    'Heeft bijtverleden',
    '',
    '2017-05-23 18:42:54',
    '2017-05-23 18:42:54'
  ),
  (
    44,
    1,
    'Maltezer X Shi Tzu',
    '',
    '2019-02-12 18:53:17',
    '2017-05-23 19:00:43'
  ),
  (
    45,
    1,
    'Flatcoat Retriever',
    '',
    '2019-02-12 19:03:19',
    '2017-06-25 11:48:11'
  ),
  (
    46,
    3,
    'Volledig geent (pension ok)',
    '',
    '2017-06-26 20:03:15',
    '2017-06-26 20:03:15'
  ),
  (
    47,
    1,
    'Pers',
    '',
    '2017-06-26 21:21:12',
    '2017-06-26 21:21:12'
  ),
  (
    48,
    1,
    'Ondefinieerbare kruising',
    '',
    '2017-06-26 21:29:43',
    '2017-06-26 21:29:43'
  ),
  (
    49,
    2,
    'Kan niet met katten',
    '',
    '2017-06-29 13:05:37',
    '2017-06-29 13:05:37'
  ),
  (
    50,
    2,
    'Kan niet met honden',
    '',
    '2017-06-29 13:16:00',
    '2017-06-29 13:16:00'
  ),
  (
    158,
    7,
    'Medewerker 1',
    '',
    '2020-09-29 13:04:45',
    '2020-09-29 13:04:45'
  ),
  (
    54,
    8,
    'Contract gastgezin',
    '',
    '2017-07-04 19:04:14',
    '2017-07-04 14:38:15'
  ),
  (
    56,
    8,
    'Intakeformulier',
    '',
    '2017-07-04 19:03:20',
    '2017-07-04 19:03:20'
  ),
  (
    57,
    8,
    'Draagvlakformulier',
    '',
    '2017-07-04 19:03:35',
    '2017-07-04 19:03:35'
  ),
  (
    58,
    8,
    'Specificatie dier',
    '',
    '2017-07-04 19:03:51',
    '2017-07-04 19:03:51'
  ),
  (
    59,
    8,
    'Contract eigenaresse',
    '',
    '2017-07-04 19:04:23',
    '2017-07-04 19:04:23'
  ),
  (
    60,
    1,
    'Chihuahua X Dwergkees',
    '',
    '2019-02-12 19:02:51',
    '2017-07-18 10:04:18'
  ),
  (
    61,
    1,
    'Sharpei X Hollandse Herder',
    '',
    '2019-02-12 18:55:44',
    '2017-07-18 14:54:53'
  ),
  (
    62,
    2,
    'Kan niet met kinderen',
    '',
    '2017-07-19 10:46:21',
    '2017-07-18 15:06:41'
  ),
  (
    63,
    1,
    'Vlinderhond X Chihuahua',
    '',
    '2019-02-12 18:56:04',
    '2017-07-18 15:13:06'
  ),
  (
    64,
    1,
    'Shih Tzu',
    '',
    '2017-07-18 15:16:37',
    '2017-07-18 15:16:37'
  ),
  (
    65,
    1,
    'Dwergpincher X Chihuahua',
    '',
    '2019-02-12 18:57:41',
    '2017-07-18 15:26:26'
  ),
  (
    66,
    1,
    'Jack Russell X Teckel',
    '',
    '2019-02-12 18:55:21',
    '2017-07-18 15:34:47'
  ),
  (
    67,
    8,
    'Entingboekje',
    '',
    '2017-07-18 15:42:23',
    '2017-07-18 15:42:23'
  ),
  (
    68,
    1,
    'Shih Tzu X Lhasa Apso',
    '',
    '2019-02-12 19:03:51',
    '2017-07-18 15:50:58'
  ),
  (
    69,
    8,
    'Patientenkaart Dierenarts',
    '',
    '2017-07-18 16:44:12',
    '2017-07-18 16:44:12'
  ),
  (
    70,
    1,
    'Siamees',
    '',
    '2017-07-18 16:54:56',
    '2017-07-18 16:54:56'
  ),
  (
    71,
    1,
    'Maine Coon X Europees korthaar',
    '',
    '2019-02-12 19:03:38',
    '2017-07-18 17:01:09'
  ),
  (
    72,
    1,
    'Siamees X Europees korthaar',
    '',
    '2019-02-12 18:50:10',
    '2017-07-18 17:12:47'
  ),
  (
    73,
    1,
    'Chihuahua',
    '',
    '2017-07-18 17:20:26',
    '2017-07-18 17:20:26'
  ),
  (
    74,
    4,
    'Papegaai',
    '',
    '2017-07-19 07:47:05',
    '2017-07-19 07:47:05'
  ),
  (
    75,
    1,
    'Grijze Roodstaart',
    '',
    '2017-07-19 07:47:38',
    '2017-07-19 07:47:38'
  ),
  (
    76,
    1,
    'Maine Coon X Europees korthaar',
    '',
    '2019-02-12 18:54:02',
    '2017-07-19 08:25:36'
  ),
  (
    77,
    1,
    'Engelse Bulldog',
    '',
    '2017-07-19 08:35:29',
    '2017-07-19 08:35:29'
  ),
  (
    78,
    8,
    'Gedragstherapie',
    '',
    '2017-07-19 08:39:43',
    '2017-07-19 08:39:43'
  ),
  (
    79,
    1,
    'Maine Coon X Ragdoll',
    '',
    '2019-02-12 18:54:26',
    '2017-07-19 09:44:50'
  ),
  (
    80,
    2,
    'Mag niet loslopen',
    '',
    '2019-02-12 19:36:23',
    '2017-07-19 10:15:29'
  ),
  (
    81,
    1,
    'Britse langhaar',
    '',
    '2019-02-12 19:01:48',
    '2017-07-19 10:27:02'
  ),
  (
    82,
    1,
    'Britse korthaar',
    '',
    '2019-02-12 19:01:59',
    '2017-07-19 10:27:25'
  ),
  (
    83,
    5,
    'Appartement',
    '',
    '2017-07-19 10:45:48',
    '2017-07-19 10:45:48'
  ),
  (
    84,
    1,
    'Herdershond X Dingo',
    '',
    '2019-02-12 19:04:07',
    '2017-07-19 10:47:32'
  ),
  (
    85,
    1,
    'Dwergkonijn',
    '',
    '2017-07-19 12:09:42',
    '2017-07-19 12:09:42'
  ),
  (
    86,
    3,
    'Konijn',
    '',
    '2018-02-19 11:45:42',
    '2017-07-19 12:09:50'
  ),
  (
    87,
    5,
    'Vrijstaand huis',
    '',
    '2017-07-19 12:37:55',
    '2017-07-19 12:37:55'
  ),
  (
    88,
    5,
    'Twee onder één kap',
    '',
    '2017-07-19 12:38:33',
    '2017-07-19 12:38:33'
  ),
  (
    89,
    9,
    'Is in oude situatie teruggeplaatst',
    '',
    '2017-07-19 21:52:47',
    '2017-07-19 21:52:47'
  ),
  (
    90,
    9,
    'Bij eigenaresse in nieuwe situatie',
    '',
    '2017-07-19 21:54:36',
    '2017-07-19 21:54:22'
  ),
  (
    91,
    9,
    'Overgenomen door gastgezin',
    '',
    '2017-07-19 21:55:05',
    '2017-07-19 21:55:05'
  ),
  (
    92,
    9,
    'Overleden',
    '',
    '2017-07-19 21:55:26',
    '2017-07-19 21:55:26'
  ),
  (
    93,
    9,
    'Eigenaresse heeft zelf een oplossing gevonden',
    '',
    '2017-07-20 12:14:54',
    '2017-07-20 12:14:54'
  ),
  (
    94,
    2,
    'Mag niet naar buiten',
    '',
    '2017-07-20 13:04:26',
    '2017-07-20 13:04:26'
  ),
  (
    95,
    1,
    'Spaanse kruising',
    '',
    '2017-07-20 20:43:58',
    '2017-07-20 20:43:58'
  ),
  (
    96,
    2,
    'Speciale voeding',
    '',
    '2017-07-21 15:32:35',
    '2017-07-21 15:32:35'
  ),
  (
    97,
    2,
    'Afwachtend met andere honden',
    '',
    '2017-07-25 11:55:44',
    '2017-07-25 11:55:44'
  ),
  (
    98,
    1,
    'Toy Fox terrier',
    '',
    '2017-07-25 12:12:36',
    '2017-07-25 12:12:36'
  ),
  (
    99,
    1,
    'Yorkshire Terrier',
    '',
    '2017-08-06 10:22:53',
    '2017-08-06 10:22:53'
  ),
  (
    101,
    1,
    'Boomer',
    '',
    '2017-09-01 14:06:15',
    '2017-09-01 14:06:15'
  ),
  (
    102,
    3,
    'Booster nodig',
    '',
    '2017-09-14 20:21:04',
    '2017-09-14 20:21:04'
  ),
  (
    103,
    5,
    'Benedenwoning',
    '',
    '2017-09-15 09:47:00',
    '2017-09-15 09:47:00'
  ),
  (
    104,
    5,
    'Bovenwoning',
    '',
    '2017-09-15 09:47:15',
    '2017-09-15 09:47:15'
  ),
  (
    105,
    1,
    'Dwergpincher',
    '',
    '2017-09-25 16:21:31',
    '2017-09-25 16:21:31'
  ),
  (
    106,
    1,
    'Witte herder X Wolfshond',
    '',
    '2019-02-12 18:54:33',
    '2017-09-27 12:09:51'
  ),
  (
    107,
    1,
    'Poedel X Maltezer',
    '',
    '2017-09-27 14:52:44',
    '2017-09-27 14:52:44'
  ),
  (
    108,
    1,
    'Siberische kat',
    '',
    '2017-10-05 13:30:49',
    '2017-10-05 13:30:49'
  ),
  (
    109,
    4,
    'Tamme rat',
    '',
    '2017-10-25 09:40:19',
    '2017-10-25 09:40:19'
  ),
  (
    110,
    1,
    'Japanner',
    '',
    '2017-10-25 09:40:32',
    '2017-10-25 09:40:32'
  ),
  (
    111,
    9,
    'Herplaatst bij ander gezin',
    '',
    '2017-10-25 12:08:42',
    '2017-10-25 12:08:42'
  ),
  (
    112,
    1,
    'Stafford X Bully',
    '',
    '2019-02-12 18:55:02',
    '2017-10-30 07:35:24'
  ),
  (
    113,
    9,
    'Aan expartner teruggegeven',
    '',
    '2017-11-13 16:23:58',
    '2017-11-13 16:23:58'
  ),
  (
    114,
    1,
    'Bengaal',
    '',
    '2017-11-23 12:06:15',
    '2017-11-23 12:06:15'
  ),
  (
    115,
    1,
    'Bombay',
    '',
    '2017-11-23 12:06:28',
    '2017-11-23 12:06:28'
  ),
  (
    116,
    1,
    'Onherleidbare kruising',
    '',
    '2019-02-12 18:50:26',
    '2017-12-02 16:21:08'
  ),
  (
    117,
    1,
    'Chihuahua X Yorkshire Terrier',
    '',
    '2019-02-12 18:54:48',
    '2017-12-07 08:09:49'
  ),
  (
    118,
    1,
    'Chihuahua kruising',
    '',
    '2017-12-07 08:10:01',
    '2017-12-07 08:10:01'
  ),
  (
    119,
    1,
    'Boerboel',
    '',
    '2017-12-15 16:42:18',
    '2017-12-15 16:42:18'
  ),
  (
    120,
    1,
    'Jack Russel ruwhaar',
    '',
    '2018-02-01 13:54:03',
    '2018-02-01 13:54:03'
  ),
  (
    121,
    1,
    'Aidi / Atlashond',
    '',
    '2018-02-19 12:52:39',
    '2018-02-19 12:52:39'
  ),
  (
    122,
    1,
    'Labrador X Golden Retriever',
    '',
    '2019-02-12 18:55:12',
    '2018-03-27 10:52:08'
  ),
  (
    123,
    8,
    'Afstandsverklaring',
    '',
    '2018-05-03 13:13:27',
    '2018-05-03 13:13:27'
  ),
  (
    124,
    1,
    'Blauwe Rus Kruising',
    '',
    '2018-05-07 07:13:03',
    '2018-05-07 07:13:03'
  ),
  (
    125,
    1,
    'Husky',
    '',
    '2019-02-12 18:59:36',
    '2018-05-22 13:53:16'
  ),
  (
    126,
    9,
    'Overige reden',
    '',
    '2018-06-05 08:51:57',
    '2018-06-05 08:51:57'
  ),
  (
    127,
    4,
    'Knaagdieren',
    '',
    '2018-09-06 11:04:12',
    '2018-09-06 11:04:12'
  ),
  (
    128,
    4,
    'Vogels',
    '',
    '2018-09-06 11:04:28',
    '2018-09-06 11:04:28'
  ),
  (
    129,
    4,
    'Amfibieën',
    '',
    '2018-09-06 11:04:47',
    '2018-09-06 11:04:47'
  ),
  (
    130,
    1,
    'Hamster',
    '',
    '2018-10-10 10:39:39',
    '2018-10-10 10:39:39'
  ),
  (
    131,
    1,
    'Dwergkeeshond',
    '',
    '2019-02-12 18:59:06',
    '2018-10-23 12:14:24'
  ),
  (
    132,
    1,
    'Pekinees',
    '',
    '2018-10-26 09:50:28',
    '2018-10-26 09:50:28'
  ),
  (
    133,
    1,
    'Berner Sennen',
    '',
    '2019-02-12 18:49:35',
    '2018-11-30 15:37:49'
  ),
  (
    134,
    1,
    'Duitse herder/husky X Kaukasische herder',
    '',
    '2018-12-05 15:25:16',
    '2018-12-05 15:25:16'
  ),
  (
    135,
    1,
    'Agapornis',
    '',
    '2018-12-14 13:04:47',
    '2018-12-14 13:04:47'
  ),
  (
    136,
    1,
    'Baardagaam',
    '',
    '2018-12-14 13:07:51',
    '2018-12-14 13:07:51'
  ),
  (
    137,
    1,
    'Shi Tzu',
    '',
    '2018-12-20 13:34:54',
    '2018-12-20 13:34:54'
  ),
  (
    139,
    1,
    'Labrador',
    '',
    '2019-01-11 10:46:18',
    '2019-01-11 10:46:18'
  ),
  (
    140,
    1,
    'Cane Corso',
    '',
    '2019-02-12 18:48:12',
    '2019-02-01 15:06:43'
  ),
  (
    141,
    5,
    'Erf',
    '',
    '2019-02-04 11:45:32',
    '2019-02-04 11:45:32'
  ),
  (
    142,
    4,
    'Reptielen',
    '',
    '2019-02-12 19:09:50',
    '2019-02-12 19:09:02'
  ),
  (
    143,
    1,
    'Groenwang parkiet',
    '',
    '2019-02-12 19:19:54',
    '2019-02-12 19:19:54'
  ),
  (
    144,
    1,
    'Duitse staande X Labrador',
    '',
    '2019-02-12 19:23:45',
    '2019-02-12 19:23:45'
  ),
  (
    145,
    4,
    'Konijn',
    '',
    '2019-02-13 16:30:08',
    '2019-02-13 16:30:08'
  ),
  (
    146,
    1,
    'Golden Retriever',
    '',
    '2019-03-19 06:07:59',
    '2019-03-19 06:07:59'
  ),
  (
    147,
    1,
    'Waterschildpad',
    '',
    '2019-04-05 11:05:58',
    '2019-04-05 11:05:58'
  ),
  (
    148,
    1,
    'Valkparkiet',
    '',
    '2019-05-06 14:34:18',
    '2019-05-06 14:34:18'
  ),
  (
    149,
    1,
    'Bordercollie',
    '',
    '2019-05-13 11:44:04',
    '2019-05-13 11:44:04'
  ),
  (
    180,
    10,
    'Contact pension',
    '',
    '2020-10-01 18:15:55',
    '2020-10-01 18:15:55'
  ),
  (
    154,
    4,
    'Aap',
    '',
    '2020-01-20 20:58:02',
    '2020-01-20 20:58:02'
  ),
  (
    179,
    10,
    'Update eigenaar',
    '',
    '2020-05-20 19:36:19',
    '2020-05-20 19:36:19'
  ),
  (
    156,
    10,
    'Contact gastgezin',
    '',
    '2020-05-20 19:36:39',
    '2020-05-20 19:36:39'
  ),
  (
    157,
    10,
    'Contact hulpverlening',
    '',
    '2020-05-20 19:37:01',
    '2020-05-20 19:37:01'
  );
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `updates`
--
CREATE TABLE `updates` (
  `id` int(10) UNSIGNED NOT NULL,
  `animal_id` int(10) UNSIGNED DEFAULT NULL,
  `updatetype_id` int(10) UNSIGNED NOT NULL,
  `employee_id` int(10) UNSIGNED NOT NULL,
  `link_id` int(10) NOT NULL,
  `link_type` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `text` text,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL
) ENGINE = MyISAM DEFAULT CHARSET = latin1;
--
-- Gegevens worden geëxporteerd voor tabel `updates`
--
INSERT INTO `updates` (
    `id`,
    `animal_id`,
    `updatetype_id`,
    `employee_id`,
    `link_id`,
    `link_type`,
    `start_date`,
    `text`,
    `created_at`,
    `updated_at`
  )
VALUES (
    1,
    NULL,
    179,
    158,
    1,
    'animals',
    '2020-09-29',
    'Dit is een update voor dier 1',
    '2020-09-29',
    '2020-09-29'
  ),
  (
    2,
    NULL,
    156,
    158,
    1,
    'guests',
    '2020-10-01',
    'Bla Bla',
    '2020-10-01',
    '2020-10-01'
  ),
  (
    3,
    NULL,
    180,
    158,
    1,
    'shelters',
    '2020-10-01',
    'Het gaat goed',
    '2020-10-01',
    '2020-10-01'
  );
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `vets`
--
CREATE TABLE `vets` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` text,
  `street` text,
  `house_number` text,
  `postal_code` text,
  `city` text,
  `phone_number` text,
  `email_address` text,
  `website` text,
  `contact_person` text,
  `remarks_contract` text,
  `remarks_general` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE = MyISAM DEFAULT CHARSET = latin1;
--
-- Gegevens worden geëxporteerd voor tabel `vets`
--
INSERT INTO `vets` (
    `id`,
    `name`,
    `street`,
    `house_number`,
    `postal_code`,
    `city`,
    `phone_number`,
    `email_address`,
    `website`,
    `contact_person`,
    `remarks_contract`,
    `remarks_general`,
    `created_at`,
    `updated_at`
  )
VALUES (
    1,
    'Dierenarts 1',
    'Straat',
    '10',
    '1234 AB',
    'Woonplaats',
    '0612345678',
    'mail@mailen.nl',
    'website.nl',
    'Contactpersoon 1',
    'Afspraak 1',
    'Opmerking 1',
    '2020-09-29 12:58:51',
    '2020-10-01 17:40:17'
  );
--
-- Indexen voor geëxporteerde tabellen
--
--
-- Indexen voor tabel `animals`
--
ALTER TABLE `animals`
ADD PRIMARY KEY (`id`);
--
-- Indexen voor tabel `animal_table`
--
ALTER TABLE `animal_table`
ADD PRIMARY KEY (`id`);
--
-- Indexen voor tabel `contacts`
--
ALTER TABLE `contacts`
ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);
--
-- Indexen voor tabel `documents`
--
ALTER TABLE `documents`
ADD PRIMARY KEY (`id`),
  ADD KEY `link_type` (`link_type`);
--
-- Indexen voor tabel `guests`
--
ALTER TABLE `guests`
ADD PRIMARY KEY (`id`);
--
-- Indexen voor tabel `guest_table`
--
ALTER TABLE `guest_table`
ADD PRIMARY KEY (`id`);
--
-- Indexen voor tabel `histories`
--
ALTER TABLE `histories`
ADD PRIMARY KEY (`id`),
  ADD KEY `link_type` (`link_type`, `link_id`);
--
-- Indexen voor tabel `locations`
--
ALTER TABLE `locations`
ADD PRIMARY KEY (`id`);
--
-- Indexen voor tabel `menuitems`
--
ALTER TABLE `menuitems`
ADD PRIMARY KEY (`id`);
--
-- Indexen voor tabel `owners`
--
ALTER TABLE `owners`
ADD PRIMARY KEY (`id`);
--
-- Indexen voor tabel `shelters`
--
ALTER TABLE `shelters`
ADD PRIMARY KEY (`id`);
--
-- Indexen voor tabel `tablegroups`
--
ALTER TABLE `tablegroups`
ADD PRIMARY KEY (`id`);
--
-- Indexen voor tabel `tables`
--
ALTER TABLE `tables`
ADD PRIMARY KEY (`id`);
--
-- Indexen voor tabel `updates`
--
ALTER TABLE `updates`
ADD PRIMARY KEY (`id`),
  ADD KEY `link_type` (`link_type`, `link_id`) USING BTREE;
--
-- Indexen voor tabel `vets`
--
ALTER TABLE `vets`
ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--
--
-- AUTO_INCREMENT voor een tabel `animals`
--
ALTER TABLE `animals`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 3;
--
-- AUTO_INCREMENT voor een tabel `animal_table`
--
ALTER TABLE `animal_table`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `contacts`
--
ALTER TABLE `contacts`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT voor een tabel `documents`
--
ALTER TABLE `documents`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 2;
--
-- AUTO_INCREMENT voor een tabel `guests`
--
ALTER TABLE `guests`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 2;
--
-- AUTO_INCREMENT voor een tabel `guest_table`
--
ALTER TABLE `guest_table`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 5;
--
-- AUTO_INCREMENT voor een tabel `histories`
--
ALTER TABLE `histories`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 7;
--
-- AUTO_INCREMENT voor een tabel `locations`
--
ALTER TABLE `locations`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 2;
--
-- AUTO_INCREMENT voor een tabel `menuitems`
--
ALTER TABLE `menuitems`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 3;
--
-- AUTO_INCREMENT voor een tabel `owners`
--
ALTER TABLE `owners`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 2;
--
-- AUTO_INCREMENT voor een tabel `shelters`
--
ALTER TABLE `shelters`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 3;
--
-- AUTO_INCREMENT voor een tabel `tablegroups`
--
ALTER TABLE `tablegroups`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 13;
--
-- AUTO_INCREMENT voor een tabel `tables`
--
ALTER TABLE `tables`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 181;
--
-- AUTO_INCREMENT voor een tabel `updates`
--
ALTER TABLE `updates`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 4;
--
-- AUTO_INCREMENT voor een tabel `vets`
--
ALTER TABLE `vets`
MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  AUTO_INCREMENT = 2;