-- REQUEST --
CREATE TABLE IF NOT EXISTS `ps_upela_order_points` (
	`ps_id_order` int(10) unsigned NOT NULL,
	`dp_company` varchar(30) NOT NULL,
	`dp_name` VARCHAR (30) NOT NULL,
	`dp_address1` VARCHAR (35) NOT NULL,
	`dp_address2` VARCHAR (35) NOT NULL,
	`dp_address3` VARCHAR (35) NOT NULL,
	`dp_postcode` VARCHAR (30) NOT NULL,
	`dp_city`     VARCHAR (35) NOT NULL,
	`dp_country`  VARCHAR (3) NOT NULL,
	`dp_id`       VARCHAR (20) NOT NULL,
	PRIMARY KEY (`ps_id_order`)
) DEFAULT CHARSET=utf8;

-- REQUEST --
CREATE TABLE IF NOT EXISTS `ps_upela_services` (
  `id_service` int(3) NOT NULL AUTO_INCREMENT,
  `id_carrier` int(11) NOT NULL DEFAULT 0,
  `id_upela` int(11) NOT NULL DEFAULT 0,
  `label` TEXT NOT NULL,
  `desc_store` TEXT NOT NULL,
  `service_name` TEXT NOT NULL,
  `is_pickup_point` int(1) NOT NULL,
  `is_dropoff_point` int(1) NOT NULL,
  PRIMARY KEY (`id_service`),
  KEY `id_carrier` (`id_carrier`)
) DEFAULT CHARSET=utf8;

INSERT INTO `ps_upela_services` (`id_service`,  `id_carrier`,  `id_upela`, `label` ,  `desc_store` ,  `service_name`,
  `is_pickup_point`,  `is_dropoff_point`) VALUES
(1, 23, 274, 'Mondial Relay',  'Mondial Relay (C.pourToi®)', 'Mondial Relay', 1,1);