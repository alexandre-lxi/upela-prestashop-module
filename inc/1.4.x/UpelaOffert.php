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
	
	protected $table 		= 'upela_offert';
	protected $identifier 	= 'id_upela_offert';
	protected $fieldsRequired = array('id_cart', 'id_carrier');
	protected 	$fieldsValidate = array(
		'id_cart' 			=> 	'isUnsignedId',
		'id_carrier' 		=> 	'isUnsignedId',
		'id_address' 		=>  'isUnsignedId',
		'postal_code' 		=> 	'isString',
		'weight'			=> 	'isFloat',
		'packages' 			=> 	'isUnsignedId',
		'choosen' 			=> 	'isBool',
		'id_clients' 		=> 	'isUnsignedId',
		'id_commandes' 		=> 	'isUnsignedId',
		'id_expeditions'	=> 	'isUnsignedId',
		'id_offre' 			=> 	'isUnsignedId',
		'code_service' 		=> 	'isString',
		'service' 			=> 	'isString',
		'code_transporteur' => 	'isString',
		'transporteur'		=> 	'isString',
		'date_livraison' 	=> 	'isString',
		'prix_ht' 			=> 	'isPrice',
		'tva' 				=> 	'isPrice',
		'prix_ttc'			=> 	'isPrice',
		'code_devises'		=> 	'isString',
		'date_add' 			=> 	'isDateFormat',
		'date_upd' 			=> 'isDateFormat'
				
	);

	public function getFields()
	{
		parent::validateFields();
	
		$fields['id_cart'] = (int)($this->id_cart);
		$fields['id_carrier'] = (int)($this->id_carrier);
		$fields['id_address'] = (int)($this->id_address);
		$fields['postal_code'] = pSql($this->postal_code);
		$fields['weight'] = (float)($this->weight);
		$fields['packages'] = (int)($this->packages);
		$fields['choosen'] = (bool)($this->choosen);
		$fields['id_clients'] = (int)($this->id_clients);
		$fields['id_commandes'] = (int)($this->id_commandes);
		$fields['id_expeditions'] = (int)($this->id_expeditions);
		$fields['id_offre'] = (int)($this->id_offre);
		$fields['code_service'] = pSQL($this->code_service);
		$fields['service'] = pSQL($this->service);
		$fields['code_transporteur'] = pSQL($this->code_transporteur);
		$fields['transporteur'] = pSQL($this->transporteur);
		$fields['date_livraison'] = pSQL($this->date_livraison);		
		$fields['prix_ht'] = (float)($this->prix_ht);
		$fields['tva'] = (float)($this->tva);
		$fields['prix_ttc'] = (float)($this->prix_ttc);
		$fields['code_devises'] = pSQL($this->code_devises);
		$fields['date_add'] = pSQL($this->date_add);
		$fields['date_upd'] = pSQL($this->date_upd);
	
		return $fields;
	}
	
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
				
			 	global $cookie;
			 		
			 	$nIdLang = (int)$cookie->id_lang;
					
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