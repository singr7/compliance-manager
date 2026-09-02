-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2018 at 09:34 AM
-- Server version: 10.1.32-MariaDB
-- PHP Version: 5.6.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `panacea`
--

-- --------------------------------------------------------

--
-- Table structure for table `compliance_project`
--

CREATE TABLE `compliance_project` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `process_id` int(11) NOT NULL,
  `qsa_id` int(11) NOT NULL,
  `consultant_id` int(11) NOT NULL,
  `qa_id` int(11) NOT NULL,
  `start_date` varchar(250) NOT NULL,
  `end_date` varchar(250) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_date` date NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `compliance_project`
--

INSERT INTO `compliance_project` (`id`, `service_id`, `customer_id`, `process_id`, `qsa_id`, `consultant_id`, `qa_id`, `start_date`, `end_date`, `status`, `created_date`, `updated`) VALUES
(1, 1, 9, 2, 4, 7, 6, '2018-11-21', '', 0, '2018-11-21', '2018-11-21 05:57:20'),
(3, 1, 9, 3, 4, 7, 6, '2018-11-21', '', 0, '2018-11-21', '2018-11-21 06:08:15'),
(4, 2, 9, 2, 4, 7, 6, '2018-11-21', '', 0, '2018-11-21', '2018-11-21 06:08:29'),
(5, 2, 9, 3, 4, 7, 6, '2018-11-21', '', 0, '2018-11-21', '2018-11-21 06:08:52'),
(6, 3, 9, 2, 4, 7, 6, '2018-11-21', '', 0, '2018-11-21', '2018-11-21 06:09:04'),
(7, 4, 9, 2, 4, 7, 6, '2018-11-21', '', 0, '2018-11-21', '2018-11-21 06:09:17');

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `iso` char(2) NOT NULL,
  `name` varchar(80) NOT NULL,
  `nicename` varchar(80) NOT NULL,
  `phonecode` int(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `iso`, `name`, `nicename`, `phonecode`) VALUES
(1, 'AF', 'AFGHANISTAN', 'Afghanistan', 93),
(2, 'AL', 'ALBANIA', 'Albania', 355),
(3, 'DZ', 'ALGERIA', 'Algeria', 213),
(4, 'AS', 'AMERICAN SAMOA', 'American Samoa', 1684),
(5, 'AD', 'ANDORRA', 'Andorra', 376),
(6, 'AO', 'ANGOLA', 'Angola', 244),
(7, 'AI', 'ANGUILLA', 'Anguilla', 1264),
(8, 'AQ', 'ANTARCTICA', 'Antarctica', 0),
(9, 'AG', 'ANTIGUA AND BARBUDA', 'Antigua and Barbuda', 1268),
(10, 'AR', 'ARGENTINA', 'Argentina', 54),
(11, 'AM', 'ARMENIA', 'Armenia', 374),
(12, 'AW', 'ARUBA', 'Aruba', 297),
(13, 'AU', 'AUSTRALIA', 'Australia', 61),
(14, 'AT', 'AUSTRIA', 'Austria', 43),
(15, 'AZ', 'AZERBAIJAN', 'Azerbaijan', 994),
(16, 'BS', 'BAHAMAS', 'Bahamas', 1242),
(17, 'BH', 'BAHRAIN', 'Bahrain', 973),
(18, 'BD', 'BANGLADESH', 'Bangladesh', 880),
(19, 'BB', 'BARBADOS', 'Barbados', 1246),
(20, 'BY', 'BELARUS', 'Belarus', 375),
(21, 'BE', 'BELGIUM', 'Belgium', 32),
(22, 'BZ', 'BELIZE', 'Belize', 501),
(23, 'BJ', 'BENIN', 'Benin', 229),
(24, 'BM', 'BERMUDA', 'Bermuda', 1441),
(25, 'BT', 'BHUTAN', 'Bhutan', 975),
(26, 'BO', 'BOLIVIA', 'Bolivia', 591),
(27, 'BA', 'BOSNIA AND HERZEGOVINA', 'Bosnia and Herzegovina', 387),
(28, 'BW', 'BOTSWANA', 'Botswana', 267),
(29, 'BV', 'BOUVET ISLAND', 'Bouvet Island', 0),
(30, 'BR', 'BRAZIL', 'Brazil', 55),
(31, 'IO', 'BRITISH INDIAN OCEAN TERRITORY', 'British Indian Ocean Territory', 246),
(32, 'BN', 'BRUNEI DARUSSALAM', 'Brunei Darussalam', 673),
(33, 'BG', 'BULGARIA', 'Bulgaria', 359),
(34, 'BF', 'BURKINA FASO', 'Burkina Faso', 226),
(35, 'BI', 'BURUNDI', 'Burundi', 257),
(36, 'KH', 'CAMBODIA', 'Cambodia', 855),
(37, 'CM', 'CAMEROON', 'Cameroon', 237),
(38, 'CA', 'CANADA', 'Canada', 1),
(39, 'CV', 'CAPE VERDE', 'Cape Verde', 238),
(40, 'KY', 'CAYMAN ISLANDS', 'Cayman Islands', 1345),
(41, 'CF', 'CENTRAL AFRICAN REPUBLIC', 'Central African Republic', 236),
(42, 'TD', 'CHAD', 'Chad', 235),
(43, 'CL', 'CHILE', 'Chile', 56),
(44, 'CN', 'CHINA', 'China', 86),
(45, 'CX', 'CHRISTMAS ISLAND', 'Christmas Island', 61),
(46, 'CC', 'COCOS (KEELING) ISLANDS', 'Cocos (Keeling) Islands', 672),
(47, 'CO', 'COLOMBIA', 'Colombia', 57),
(48, 'KM', 'COMOROS', 'Comoros', 269),
(49, 'CG', 'CONGO', 'Congo', 242),
(50, 'CD', 'CONGO, THE DEMOCRATIC REPUBLIC OF THE', 'Congo, the Democratic Republic of the', 242),
(51, 'CK', 'COOK ISLANDS', 'Cook Islands', 682),
(52, 'CR', 'COSTA RICA', 'Costa Rica', 506),
(53, 'CI', 'COTE D\'IVOIRE', 'Cote D\'Ivoire', 225),
(54, 'HR', 'CROATIA', 'Croatia', 385),
(55, 'CU', 'CUBA', 'Cuba', 53),
(56, 'CY', 'CYPRUS', 'Cyprus', 357),
(57, 'CZ', 'CZECH REPUBLIC', 'Czech Republic', 420),
(58, 'DK', 'DENMARK', 'Denmark', 45),
(59, 'DJ', 'DJIBOUTI', 'Djibouti', 253),
(60, 'DM', 'DOMINICA', 'Dominica', 1767),
(61, 'DO', 'DOMINICAN REPUBLIC', 'Dominican Republic', 1809),
(62, 'EC', 'ECUADOR', 'Ecuador', 593),
(63, 'EG', 'EGYPT', 'Egypt', 20),
(64, 'SV', 'EL SALVADOR', 'El Salvador', 503),
(65, 'GQ', 'EQUATORIAL GUINEA', 'Equatorial Guinea', 240),
(66, 'ER', 'ERITREA', 'Eritrea', 291),
(67, 'EE', 'ESTONIA', 'Estonia', 372),
(68, 'ET', 'ETHIOPIA', 'Ethiopia', 251),
(69, 'FK', 'FALKLAND ISLANDS (MALVINAS)', 'Falkland Islands (Malvinas)', 500),
(70, 'FO', 'FAROE ISLANDS', 'Faroe Islands', 298),
(71, 'FJ', 'FIJI', 'Fiji', 679),
(72, 'FI', 'FINLAND', 'Finland', 358),
(73, 'FR', 'FRANCE', 'France', 33),
(74, 'GF', 'FRENCH GUIANA', 'French Guiana', 594),
(75, 'PF', 'FRENCH POLYNESIA', 'French Polynesia', 689),
(76, 'TF', 'FRENCH SOUTHERN TERRITORIES', 'French Southern Territories', 0),
(77, 'GA', 'GABON', 'Gabon', 241),
(78, 'GM', 'GAMBIA', 'Gambia', 220),
(79, 'GE', 'GEORGIA', 'Georgia', 995),
(80, 'DE', 'GERMANY', 'Germany', 49),
(81, 'GH', 'GHANA', 'Ghana', 233),
(82, 'GI', 'GIBRALTAR', 'Gibraltar', 350),
(83, 'GR', 'GREECE', 'Greece', 30),
(84, 'GL', 'GREENLAND', 'Greenland', 299),
(85, 'GD', 'GRENADA', 'Grenada', 1473),
(86, 'GP', 'GUADELOUPE', 'Guadeloupe', 590),
(87, 'GU', 'GUAM', 'Guam', 1671),
(88, 'GT', 'GUATEMALA', 'Guatemala', 502),
(89, 'GN', 'GUINEA', 'Guinea', 224),
(90, 'GW', 'GUINEA-BISSAU', 'Guinea-Bissau', 245),
(91, 'GY', 'GUYANA', 'Guyana', 592),
(92, 'HT', 'HAITI', 'Haiti', 509),
(93, 'HM', 'HEARD ISLAND AND MCDONALD ISLANDS', 'Heard Island and Mcdonald Islands', 0),
(94, 'VA', 'HOLY SEE (VATICAN CITY STATE)', 'Holy See (Vatican City State)', 39),
(95, 'HN', 'HONDURAS', 'Honduras', 504),
(96, 'HK', 'HONG KONG', 'Hong Kong', 852),
(97, 'HU', 'HUNGARY', 'Hungary', 36),
(98, 'IS', 'ICELAND', 'Iceland', 354),
(99, 'IN', 'INDIA', 'India', 91),
(100, 'ID', 'INDONESIA', 'Indonesia', 62),
(101, 'IR', 'IRAN, ISLAMIC REPUBLIC OF', 'Iran, Islamic Republic of', 98),
(102, 'IQ', 'IRAQ', 'Iraq', 964),
(103, 'IE', 'IRELAND', 'Ireland', 353),
(104, 'IL', 'ISRAEL', 'Israel', 972),
(105, 'IT', 'ITALY', 'Italy', 39),
(106, 'JM', 'JAMAICA', 'Jamaica', 1876),
(107, 'JP', 'JAPAN', 'Japan', 81),
(108, 'JO', 'JORDAN', 'Jordan', 962),
(109, 'KZ', 'KAZAKHSTAN', 'Kazakhstan', 7),
(110, 'KE', 'KENYA', 'Kenya', 254),
(111, 'KI', 'KIRIBATI', 'Kiribati', 686),
(112, 'KP', 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF', 'Korea, Democratic People\'s Republic of', 850),
(113, 'KR', 'KOREA, REPUBLIC OF', 'Korea, Republic of', 82),
(114, 'KW', 'KUWAIT', 'Kuwait', 965),
(115, 'KG', 'KYRGYZSTAN', 'Kyrgyzstan', 996),
(116, 'LA', 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC', 'Lao People\'s Democratic Republic', 856),
(117, 'LV', 'LATVIA', 'Latvia', 371),
(118, 'LB', 'LEBANON', 'Lebanon', 961),
(119, 'LS', 'LESOTHO', 'Lesotho', 266),
(120, 'LR', 'LIBERIA', 'Liberia', 231),
(121, 'LY', 'LIBYAN ARAB JAMAHIRIYA', 'Libyan Arab Jamahiriya', 218),
(122, 'LI', 'LIECHTENSTEIN', 'Liechtenstein', 423),
(123, 'LT', 'LITHUANIA', 'Lithuania', 370),
(124, 'LU', 'LUXEMBOURG', 'Luxembourg', 352),
(125, 'MO', 'MACAO', 'Macao', 853),
(126, 'MK', 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF', 'Macedonia, the Former Yugoslav Republic of', 389),
(127, 'MG', 'MADAGASCAR', 'Madagascar', 261),
(128, 'MW', 'MALAWI', 'Malawi', 265),
(129, 'MY', 'MALAYSIA', 'Malaysia', 60),
(130, 'MV', 'MALDIVES', 'Maldives', 960),
(131, 'ML', 'MALI', 'Mali', 223),
(132, 'MT', 'MALTA', 'Malta', 356),
(133, 'MH', 'MARSHALL ISLANDS', 'Marshall Islands', 692),
(134, 'MQ', 'MARTINIQUE', 'Martinique', 596),
(135, 'MR', 'MAURITANIA', 'Mauritania', 222),
(136, 'MU', 'MAURITIUS', 'Mauritius', 230),
(137, 'YT', 'MAYOTTE', 'Mayotte', 269),
(138, 'MX', 'MEXICO', 'Mexico', 52),
(139, 'FM', 'MICRONESIA, FEDERATED STATES OF', 'Micronesia, Federated States of', 691),
(140, 'MD', 'MOLDOVA, REPUBLIC OF', 'Moldova, Republic of', 373),
(141, 'MC', 'MONACO', 'Monaco', 377),
(142, 'MN', 'MONGOLIA', 'Mongolia', 976),
(143, 'MS', 'MONTSERRAT', 'Montserrat', 1664),
(144, 'MA', 'MOROCCO', 'Morocco', 212),
(145, 'MZ', 'MOZAMBIQUE', 'Mozambique', 258),
(146, 'MM', 'MYANMAR', 'Myanmar', 95),
(147, 'NA', 'NAMIBIA', 'Namibia', 264),
(148, 'NR', 'NAURU', 'Nauru', 674),
(149, 'NP', 'NEPAL', 'Nepal', 977),
(150, 'NL', 'NETHERLANDS', 'Netherlands', 31),
(151, 'AN', 'NETHERLANDS ANTILLES', 'Netherlands Antilles', 599),
(152, 'NC', 'NEW CALEDONIA', 'New Caledonia', 687),
(153, 'NZ', 'NEW ZEALAND', 'New Zealand', 64),
(154, 'NI', 'NICARAGUA', 'Nicaragua', 505),
(155, 'NE', 'NIGER', 'Niger', 227),
(156, 'NG', 'NIGERIA', 'Nigeria', 234),
(157, 'NU', 'NIUE', 'Niue', 683),
(158, 'NF', 'NORFOLK ISLAND', 'Norfolk Island', 672),
(159, 'MP', 'NORTHERN MARIANA ISLANDS', 'Northern Mariana Islands', 1670),
(160, 'NO', 'NORWAY', 'Norway', 47),
(161, 'OM', 'OMAN', 'Oman', 968),
(162, 'PK', 'PAKISTAN', 'Pakistan', 92),
(163, 'PW', 'PALAU', 'Palau', 680),
(164, 'PS', 'PALESTINIAN TERRITORY, OCCUPIED', 'Palestinian Territory, Occupied', 970),
(165, 'PA', 'PANAMA', 'Panama', 507),
(166, 'PG', 'PAPUA NEW GUINEA', 'Papua New Guinea', 675),
(167, 'PY', 'PARAGUAY', 'Paraguay', 595),
(168, 'PE', 'PERU', 'Peru', 51),
(169, 'PH', 'PHILIPPINES', 'Philippines', 63),
(170, 'PN', 'PITCAIRN', 'Pitcairn', 0),
(171, 'PL', 'POLAND', 'Poland', 48),
(172, 'PT', 'PORTUGAL', 'Portugal', 351),
(173, 'PR', 'PUERTO RICO', 'Puerto Rico', 1787),
(174, 'QA', 'QATAR', 'Qatar', 974),
(175, 'RE', 'REUNION', 'Reunion', 262),
(176, 'RO', 'ROMANIA', 'Romania', 40),
(177, 'RU', 'RUSSIAN FEDERATION', 'Russian Federation', 70),
(178, 'RW', 'RWANDA', 'Rwanda', 250),
(179, 'SH', 'SAINT HELENA', 'Saint Helena', 290),
(180, 'KN', 'SAINT KITTS AND NEVIS', 'Saint Kitts and Nevis', 1869),
(181, 'LC', 'SAINT LUCIA', 'Saint Lucia', 1758),
(182, 'PM', 'SAINT PIERRE AND MIQUELON', 'Saint Pierre and Miquelon', 508),
(183, 'VC', 'SAINT VINCENT AND THE GRENADINES', 'Saint Vincent and the Grenadines', 1784),
(184, 'WS', 'SAMOA', 'Samoa', 684),
(185, 'SM', 'SAN MARINO', 'San Marino', 378),
(186, 'ST', 'SAO TOME AND PRINCIPE', 'Sao Tome and Principe', 239),
(187, 'SA', 'SAUDI ARABIA', 'Saudi Arabia', 966),
(188, 'SN', 'SENEGAL', 'Senegal', 221),
(189, 'CS', 'SERBIA AND MONTENEGRO', 'Serbia and Montenegro', 381),
(190, 'SC', 'SEYCHELLES', 'Seychelles', 248),
(191, 'SL', 'SIERRA LEONE', 'Sierra Leone', 232),
(192, 'SG', 'SINGAPORE', 'Singapore', 65),
(193, 'SK', 'SLOVAKIA', 'Slovakia', 421),
(194, 'SI', 'SLOVENIA', 'Slovenia', 386),
(195, 'SB', 'SOLOMON ISLANDS', 'Solomon Islands', 677),
(196, 'SO', 'SOMALIA', 'Somalia', 252),
(197, 'ZA', 'SOUTH AFRICA', 'South Africa', 27),
(198, 'GS', 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS', 'South Georgia and the South Sandwich Islands', 0),
(199, 'ES', 'SPAIN', 'Spain', 34),
(200, 'LK', 'SRI LANKA', 'Sri Lanka', 94),
(201, 'SD', 'SUDAN', 'Sudan', 249),
(202, 'SR', 'SURINAME', 'Suriname', 597),
(203, 'SJ', 'SVALBARD AND JAN MAYEN', 'Svalbard and Jan Mayen', 47),
(204, 'SZ', 'SWAZILAND', 'Swaziland', 268),
(205, 'SE', 'SWEDEN', 'Sweden', 46),
(206, 'CH', 'SWITZERLAND', 'Switzerland', 41),
(207, 'SY', 'SYRIAN ARAB REPUBLIC', 'Syrian Arab Republic', 963),
(208, 'TW', 'TAIWAN, PROVINCE OF CHINA', 'Taiwan, Province of China', 886),
(209, 'TJ', 'TAJIKISTAN', 'Tajikistan', 992),
(210, 'TZ', 'TANZANIA, UNITED REPUBLIC OF', 'Tanzania, United Republic of', 255),
(211, 'TH', 'THAILAND', 'Thailand', 66),
(212, 'TL', 'TIMOR-LESTE', 'Timor-Leste', 670),
(213, 'TG', 'TOGO', 'Togo', 228),
(214, 'TK', 'TOKELAU', 'Tokelau', 690),
(215, 'TO', 'TONGA', 'Tonga', 676),
(216, 'TT', 'TRINIDAD AND TOBAGO', 'Trinidad and Tobago', 1868),
(217, 'TN', 'TUNISIA', 'Tunisia', 216),
(218, 'TR', 'TURKEY', 'Turkey', 90),
(219, 'TM', 'TURKMENISTAN', 'Turkmenistan', 7370),
(220, 'TC', 'TURKS AND CAICOS ISLANDS', 'Turks and Caicos Islands', 1649),
(221, 'TV', 'TUVALU', 'Tuvalu', 688),
(222, 'UG', 'UGANDA', 'Uganda', 256),
(223, 'UA', 'UKRAINE', 'Ukraine', 380),
(224, 'AE', 'UNITED ARAB EMIRATES', 'United Arab Emirates', 971),
(225, 'GB', 'UNITED KINGDOM', 'United Kingdom', 44),
(226, 'US', 'UNITED STATES', 'United States', 1),
(227, 'UM', 'UNITED STATES MINOR OUTLYING ISLANDS', 'United States Minor Outlying Islands', 1),
(228, 'UY', 'URUGUAY', 'Uruguay', 598),
(229, 'UZ', 'UZBEKISTAN', 'Uzbekistan', 998),
(230, 'VU', 'VANUATU', 'Vanuatu', 678),
(231, 'VE', 'VENEZUELA', 'Venezuela', 58),
(232, 'VN', 'VIET NAM', 'Viet Nam', 84),
(233, 'VG', 'VIRGIN ISLANDS, BRITISH', 'Virgin Islands, British', 1284),
(234, 'VI', 'VIRGIN ISLANDS, U.S.', 'Virgin Islands, U.s.', 1340),
(235, 'WF', 'WALLIS AND FUTUNA', 'Wallis and Futuna', 681),
(236, 'EH', 'WESTERN SAHARA', 'Western Sahara', 212),
(237, 'YE', 'YEMEN', 'Yemen', 967),
(238, 'ZM', 'ZAMBIA', 'Zambia', 260),
(239, 'ZW', 'ZIMBABWE', 'Zimbabwe', 263);

-- --------------------------------------------------------

--
-- Table structure for table `process`
--

CREATE TABLE `process` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `process_name` varchar(250) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `created_date` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `process`
--

INSERT INTO `process` (`id`, `customer_id`, `process_name`, `status`, `created_date`, `updated`) VALUES
(1, 10, 'process1', 0, '2018-11-19 14:54:10', '2018-11-20 08:32:11'),
(2, 9, 'Process1', 0, '2018-11-20 06:03:09', '2018-11-20 05:03:09'),
(3, 9, 'Process2', 0, '2018-11-20 06:03:09', '2018-11-20 05:03:09'),
(4, 10, 'Process2', 0, '2018-11-20 08:09:20', '2018-11-20 08:32:09'),
(5, 9, 'Process3', 0, '2018-11-22 09:35:18', '2018-11-22 08:35:18'),
(6, 9, 'Process4', 0, '2018-11-22 09:38:41', '2018-11-22 08:38:41'),
(7, 9, 'Process5', 0, '2018-11-22 09:39:02', '2018-11-22 08:39:02'),
(8, 11, 'test', 0, '2018-11-23 13:15:23', '2018-11-23 07:45:23'),
(9, 12, 'test', 0, '2018-11-23 14:54:12', '2018-11-23 09:24:12');

-- --------------------------------------------------------

--
-- Table structure for table `questionnaire`
--

CREATE TABLE `questionnaire` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `status` enum('1','2') NOT NULL COMMENT '1=active,2-inactive',
  `created` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `questionnaire`
--

INSERT INTO `questionnaire` (`id`, `service_id`, `question`, `status`, `created`, `updated`) VALUES
(1, 1, 'PCI DSS Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:55:25'),
(2, 1, 'PCI DSS Lorem Ipsum is simply dummy text of the printing and type setting industry.', '1', '2018-11-17 00:00:00', '2018-11-22 09:55:30'),
(3, 1, 'PCI DSS Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:55:36'),
(4, 1, 'PCI DSS Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:55:44'),
(5, 1, 'PCI DSS Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:55:52'),
(6, 1, 'PCI DSS Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:55:58'),
(7, 1, 'PCI DSS Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:56:05'),
(8, 1, 'PCI DSS Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:56:11'),
(9, 1, 'PCI DSS Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:56:16'),
(10, 1, 'PCI DSS Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:56:22'),
(11, 2, 'ISO Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:56:35'),
(12, 2, 'ISO Lorem Ipsum is simply dummy text of the printing and type setting industry.', '1', '2018-11-17 00:00:00', '2018-11-22 09:56:41'),
(13, 2, 'ISO Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:56:46'),
(14, 2, 'ISO Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:56:52'),
(15, 2, 'ISO Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:57:23'),
(16, 2, 'ISO Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:57:10'),
(17, 2, 'ISO Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:57:30'),
(18, 2, 'ISO Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:57:36'),
(19, 2, 'ISO Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:57:05'),
(20, 2, 'ISO Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:57:00'),
(21, 3, 'HIPAA Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:57:49'),
(22, 3, 'HIPAA  Lorem Ipsum is simply dummy text of the printing and type setting industry.', '1', '2018-11-17 00:00:00', '2018-11-22 10:02:30'),
(23, 3, 'HIPAA  Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 10:02:36'),
(24, 3, 'HIPAA  Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 10:02:41'),
(25, 3, 'HIPAA  Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 10:02:48'),
(26, 3, 'HIPAA  Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 10:02:53'),
(27, 3, 'HIPAA  Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 10:03:01'),
(28, 3, 'HIPAA  Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 10:03:06'),
(29, 3, 'HIPAA  Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 10:03:12'),
(30, 3, 'HIPAA  Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 10:03:16'),
(31, 4, 'HITRUST Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:54:06'),
(32, 4, 'HITRUST Lorem Ipsum is simply dummy text of the printing and type setting industry.', '1', '2018-11-17 00:00:00', '2018-11-22 09:54:17'),
(33, 4, 'HITRUST Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:54:22'),
(34, 4, 'HITRUST Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:54:27'),
(35, 4, 'HITRUST Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:54:33'),
(36, 4, 'HITRUST Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:54:41'),
(37, 4, 'HITRUST Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:54:59'),
(38, 4, 'HITRUST Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:54:46'),
(39, 4, 'HITRUST Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:55:06'),
(40, 4, 'HITRUST Lorem Ipsum is simply dummy text of the printing and type setting industry. ', '1', '2018-11-17 00:00:00', '2018-11-22 09:55:12');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'active', '2018-08-07 02:06:06', '2018-08-07 10:03:34'),
(2, 'Employee', 'active', '2018-08-07 02:06:06', '2018-08-07 10:04:55'),
(3, 'Supplier', 'active', '2018-08-07 02:06:06', '2018-08-07 10:04:47'),
(4, 'Sub-contractors', 'active', '2018-08-13 00:00:00', '2018-08-13 06:57:12');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '''0''=>''No'',''1''->''Yes''',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `status`, `created`) VALUES
(1, 'PCI DSS', 1, '2018-11-17 06:57:22'),
(2, 'ISO', 1, '2018-11-17 06:57:22'),
(3, 'HIPAA', 1, '2018-11-17 06:57:41'),
(4, 'HITRUST', 1, '2018-11-17 06:57:41');

-- --------------------------------------------------------

--
-- Table structure for table `testing`
--

CREATE TABLE `testing` (
  `id` int(11) NOT NULL,
  `testing_name` varchar(250) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=>Active,1=>Inactive',
  `created_date` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `testing`
--

INSERT INTO `testing` (`id`, `testing_name`, `status`, `created_date`, `updated`) VALUES
(1, 'ASV', 0, '2018-11-23 00:00:00', '2018-11-23 05:49:51'),
(2, 'External PT', 0, '2018-11-23 00:00:00', '2018-11-23 05:49:51'),
(3, 'Internal PT', 0, '2018-11-23 00:00:00', '2018-11-23 05:50:22'),
(4, 'Internal VA', 0, '2018-11-23 00:00:00', '2018-11-23 05:50:22'),
(5, 'Application PT', 0, '2018-11-23 00:00:00', '2018-11-23 05:50:48'),
(6, 'Segmentation PT', 0, '2018-11-23 00:00:00', '2018-11-23 05:50:48'),
(7, 'Card Data Scan', 0, '2018-11-23 00:00:00', '2018-11-23 05:51:14');

-- --------------------------------------------------------

--
-- Table structure for table `testing_project`
--

CREATE TABLE `testing_project` (
  `id` int(11) NOT NULL,
  `testing_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `process_id` int(11) NOT NULL,
  `qsa_id` int(11) NOT NULL,
  `consultant_id` int(11) NOT NULL,
  `qa_id` int(11) NOT NULL,
  `start_date` varchar(250) NOT NULL,
  `end_date` varchar(250) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `testing_project`
--

INSERT INTO `testing_project` (`id`, `testing_id`, `customer_id`, `process_id`, `qsa_id`, `consultant_id`, `qa_id`, `start_date`, `end_date`, `status`, `created_date`, `updated`) VALUES
(1, 1, 9, 2, 4, 7, 6, '2018-11-23', '', 0, '2018-11-23 12:24:47', '2018-11-23 06:54:47'),
(2, 1, 9, 2, 4, 7, 6, '2018-11-23', '', 0, '2018-11-23 12:25:45', '2018-11-23 06:55:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `company_name` varchar(250) NOT NULL,
  `company_number` varchar(250) NOT NULL,
  `address` text NOT NULL,
  `status` enum('active','inactive','delete') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'inactive',
  `user_type` enum('1','2','3','4','5') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '1=admin,2-qsa,3-qa,4-consultants,5-customer',
  `certificate_key` text NOT NULL,
  `new_certificate_key` text NOT NULL,
  `is_certificate_verified` tinyint(4) NOT NULL DEFAULT '0',
  `unique_id` varchar(250) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login` datetime NOT NULL,
  `permission` longtext NOT NULL,
  `pwdstring` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone_number`, `password`, `company_name`, `company_number`, `address`, `status`, `user_type`, `certificate_key`, `new_certificate_key`, `is_certificate_verified`, `unique_id`, `created_date`, `updated_date`, `last_login`, `permission`, `pwdstring`) VALUES
(1, 'Admin', 'panacea@yopmail.com', '1122334455', 'e10adc3949ba59abbe56e057f20f883e', '', '', '', 'active', '1', '45ep722257s13145a3pa7315535a3836s2543ac3453', '45ep722257s13145a3pa7315535a3836s2543ac34533325e121ad39bf422fe21bbd05ab087f', 1, '', '2018-11-15 06:14:15', '2018-11-26 06:47:23', '2018-11-26 12:17:23', '', ''),
(4, 'Qsa', 'qsa@yopmail.com', '112233445566', '', 'papacea', '1122334455', 'Noida', 'inactive', '2', 'a3se4a5a991465p92e654a41923342a599p95242214', '', 0, '', '2018-11-23 14:57:06', '2018-11-23 09:27:06', '0000-00-00 00:00:00', '', ''),
(5, 'Qsa second', 'qsa2@yopmail.com', '1122334455', '263b78d9b5e6142b20c25af3245764f3', 'Panacea', '1122334455', 'noida', 'active', '2', '142s3a41221nc13 21ac11c4 9aeac9na22o2a3oa4o151a2s42a5811a9524312323443939Qn9424esc42s4snc2en5 e3214n8n41c422d93aP49o5nca551a23 39s438a522358a4 1212Q35e2e9na439851d544d5ac4a49335 d9294a51a2ans239242242s a9a3ae522adn2c1a5482ca4a141n2 1 12e3532Pa251a21423a3384344P341Q1c9Qo4a352515cao48saesP3142e4d3253Q4s13css242e34d3c3 13493e13ec51eos44c2dn2c45525ss5242cn1d92512o4s1c4n1Q1351c342na ana5sa12941535943n3a143234ePna1e5a45a49P5n5Qa32552aa43cn4d24e 41515c1233a51s59c2543e3a5s421sd425 34dnQasa1eo4934ooes21c', '', 0, '', '2018-11-16 11:55:45', '2018-11-23 05:05:38', '2018-11-16 11:56:16', '', '1542365745'),
(6, 'Qa', 'qa@yopmail.com', '1122334455', '3637aed6d5aeaa7327181a2a723c5f6b', 'Tekshapers', '1122334455', 'Noida', 'active', '3', '', '', 0, '', '2018-11-17 06:26:26', '2018-11-17 06:46:04', '0000-00-00 00:00:00', '', '1542432386'),
(7, 'consultants', 'consultants@yopmail.com', '1122334455', '41641e90259cab5402222d66a9e3a659', 'Panacea', '1122334455', 'Noida', 'active', '4', '', '', 0, '', '2018-11-17 07:37:20', '2018-11-17 06:44:46', '0000-00-00 00:00:00', '', '1542436640'),
(9, 'customer first', 'customer1@yopmail.com', '1122334455', '2d16420886da8ab65c4a3b4813b9a279', 'tesst', '1122334455', 'noida', 'active', '5', '53 45158te5ce51s5451f2st5413s151it4i35s1125ru5iese2254t4i14f3557135e5545e 44s171ro 751513i1311552e12s183s44c4s5384157itmc122555istem534s5517m4u7813s5155e3s455251c143tes5s4854sm5tuo221t458524i15333o44555e 584ormr3s72it3t12f57233rs2smss5rt51132c52f472i3t8s3t12r51s35so45cms2123tr372 t1s1351455t55114s278stt25ss2t533fc4t5s542tr4s4454m4217m221ro1s4i25452t5sr5 2mt4s112rsc135otc5s31me34s33t74s5c35fm5e443321tt24144t55stc1e74 4ec1r152er5m2e3u7s33c3e4ttott3', '', 0, '1542620931', '0000-00-00 00:00:00', '2018-11-26 07:52:33', '2018-11-26 01:22:33', '', '1542620983'),
(10, 'customer second', 'customer2@yopmail.com', '1122334455', 'dbbf68d4bfc193412b54a10974350bd4', 'Tek', '1122334455', 'noida', 'active', '5', '391s84ee1oe4124o5451c1s253884s124k112cs c5e', '', 0, '1542621048', '0000-00-00 00:00:00', '2018-11-23 07:33:32', '0000-00-00 00:00:00', '', '1542621104'),
(11, 'rere', 'etee@yopmail.com', '111111111111111', '6bb3a77835333609a0148f4668c514e9', 'erer', '111111111111111', 'qwqw', 'active', '5', '', '', 0, '1542959086', '0000-00-00 00:00:00', '2018-11-23 07:45:23', '0000-00-00 00:00:00', '', '1542959123'),
(12, 'Costomer', 'customer3@yopmail.com', '112233445566778', 'f905290b7ab9f39cde99a6ab64339748', 'Test', '112233445566', 'Test', 'active', '5', '', '', 0, '1542964983', '0000-00-00 00:00:00', '2018-11-23 09:24:12', '0000-00-00 00:00:00', '', '1542965052'),
(13, 'Qsa Third', 'qsa3@yopmail.com', '1122334455', '3f3cf5e1ed6cdcef8d3d069506df0f55', 'Test', '1122334455', 'Test', 'active', '2', '', '', 0, '', '2018-11-23 14:55:42', '2018-11-23 09:25:42', '0000-00-00 00:00:00', '', '1542965142'),
(14, 'Qsa', 'qsa4@yopmail.com', '1122334455', '463d2eb7389078401a824f525cc9c488', 'Test', '1122334455', 'Test', 'active', '2', '', '', 0, '', '2018-11-23 14:56:50', '2018-11-23 09:26:50', '0000-00-00 00:00:00', '', '1542965210');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `compliance_project`
--
ALTER TABLE `compliance_project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `process`
--
ALTER TABLE `process`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questionnaire`
--
ALTER TABLE `questionnaire`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testing`
--
ALTER TABLE `testing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testing_project`
--
ALTER TABLE `testing_project`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `compliance_project`
--
ALTER TABLE `compliance_project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;

--
-- AUTO_INCREMENT for table `process`
--
ALTER TABLE `process`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `questionnaire`
--
ALTER TABLE `questionnaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `testing`
--
ALTER TABLE `testing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `testing_project`
--
ALTER TABLE `testing_project`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
