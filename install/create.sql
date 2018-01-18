DROP TABLE IF EXISTS `%%PREFIX%%upela_offert`;

CREATE TABLE IF NOT EXISTS `%%PREFIX%%upela_offert` (
  `id_upela_offert` int(11) NOT NULL AUTO_INCREMENT,
  `id_cart` int(11) NOT NULL,
  `id_carrier` int(11) NOT NULL,
  `id_address` int(11) NOT NULL,
  `postal_code` varchar(20) NOT NULL DEFAULT '',
  `weight` DECIMAL(10,3) NOT NULL,
  `packages` INT(11) NOT NULL DEFAULT 0,
  `choosen` INT(1) NOT NULL DEFAULT 0,
  `id_clients` int(11) NOT NULL,
  `id_commandes` int(11) NOT NULL,
  `id_expeditions` int(11) NOT NULL,
  `id_offre` int(11) NOT NULL,
  `code_service` varchar(250) NOT NULL DEFAULT '',
  `service` varchar(250) NOT NULL DEFAULT '',
  `code_transporteur` varchar(250) NOT NULL DEFAULT '',
  `transporteur` varchar(250) NOT NULL DEFAULT '',
  `date_livraison` varchar(64) NOT NULL DEFAULT '',
  `prix_ht` DECIMAL(10,2) NOT NULL,
  `tva` DECIMAL(10,2) NOT NULL,
  `prix_ttc` DECIMAL(10,2) NOT NULL,
  `code_devises` varchar(3) NOT NULL DEFAULT '',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (id_upela_offert),
  UNIQUE KEY `id_cart` (`id_cart`, `id_carrier`),
  KEY `id_clients` (`id_clients`),
  KEY `id_commandes` (`id_commandes`),
  KEY `id_expeditions` (`id_expeditions`),
  KEY `id_offre` (`id_offre`),
  KEY `date_add` (`date_add`),
  KEY `date_upd` (`date_upd`)
);

DROP TABLE IF EXISTS `%%PREFIX%%upela_pickup_demand`;

CREATE TABLE IF NOT EXISTS `%%PREFIX%%upela_pickup_demand` (
  `id_upela_pickup_demand` int(11) NOT NULL AUTO_INCREMENT,
  `date_send` datetime NOT NULL,
  `code_transporteur`  varchar(250) NOT NULL DEFAULT '',
  `api_return`   varchar(250) NOT NULL DEFAULT '',
  `date_add` datetime NOT NULL,
  `date_upd` datetime NOT NULL,
  PRIMARY KEY (id_upela_pickup_demand),
  KEY `date_send` (`date_send`),
  KEY `code_transporteur` (`code_transporteur`),
  KEY `date_add` (`date_add`),
  KEY `date_upd` (`date_upd`)
);