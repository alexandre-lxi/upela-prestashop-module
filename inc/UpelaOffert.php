<?php

class UpelaOffert extends ObjectModel {

	public $id_cart;
	public $id_carrier;
	public $id_address;
	public $postal_code;

	public $weight;
	public $packages;
	public $choosen;

	public $id_clients;
	public $id_commandes;
	public $id_expeditions;
	public $id_offre;
	public $code_service;
	public $service;
	public $code_transporteur;
	public $transporteur;
	public $date_livraison;
	public $prix_ht;
	public $tva;
	public $prix_ttc;
	public $code_devises;

	public $date_add;
	public $date_upd;

	public static $definition = array(
			'table' => 'upela_offert',
			'primary' => 'id_upela_offert',
			'fields' => array(
					'id_cart' 			=> 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
					'id_carrier' 		=> 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
					'id_address' 		=> 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
					'postal_code' 		=> 	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
					'weight'			=> 	array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
					'packages' 			=> 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
					'choosen' 			=> 	array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
					'id_clients' 		=> 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
					'id_commandes' 		=> 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId',),
					'id_expeditions'	=> 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
					'id_offre' 			=> 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
					'code_service' 		=> 	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
					'service' 			=> 	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
					'code_transporteur' => 	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
					'transporteur'		=> 	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
					'date_livraison' 	=> 	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
					'prix_ht' 			=> 	array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
					'tva' 				=> 	array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
					'prix_ttc'			=> 	array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
					'code_devises'		=> 	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
					'date_add' 			=> 	array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
					'date_upd' 			=> 	array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat')
			),
	);

	public static function getByCartAndCarrierId($nCartId, $nCarrierId){

		$sQuery = sprintf('SELECT id_upela_offert FROM `%1$supela_offert` uo WHERE uo.id_cart=%2$d AND uo.id_carrier=%3$d', _DB_PREFIX_, $nCartId, $nCarrierId);

		$nUpelaOffertId = Db::getInstance()->getValue($sQuery);

		if ($nUpelaOffertId) {

			return new UpelaOffert($nUpelaOffertId);

		} // if

		return null;

	}

	public function getFormattedDate($nIdLang = null){
		
		$sDate = $this->date_livraison ? substr($this->date_livraison, 0, 10) : '';
		
		$nTimestamp = strtotime($sDate);
		
		if ($nTimestamp && date('Y-m-d', $nTimestamp) === $sDate) {
					
			if ($nIdLang === null) {
				
				$nIdLang = (int)Context::getContext()->cookie->id_lang;
				
			} // if
			
			if ($nIdLang) {
				
				$aLang = Language::getLanguage($nIdLang);
				
				if ($aLang && $aLang['date_format_lite']) {
					
					$sDate =  date($aLang['date_format_lite'], $nTimestamp);
					
				} // if
				
			} // if
		
		} // if
				
		return $sDate;
		
	}
	
}