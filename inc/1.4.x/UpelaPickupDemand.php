<?php

class UpelaPickupDemand extends ObjectModel {

	public $date_send;
	public $code_transporteur;
	public $api_return;
	
	public $date_add;
	public $date_upd;

	protected $table 		= 'upela_pickup_demand';
	protected $identifier 	= 'id_upela_pickup_demand';
	protected $fieldsRequired = array();
	protected 	$fieldsValidate = array(
			'code_transporteur'	=> 	'isString',
			'api_return' 		=> 	'isString',
			'date_send'			=> 	'isDateFormat',
			'date_add' 			=> 	'isDateFormat',
			'date_upd' 			=> 'isDateFormat'
	
	);
	
	public function getFields()
	{
		parent::validateFields();
	

		$fields['code_transporteur'] = pSql($this->code_transporteur);
		$fields['api_return'] = pSQL($this->api_return);
		$fields['date_send'] = pSQL($this->date_send);
		$fields['date_add'] = pSQL($this->date_add);
		$fields['date_upd'] = pSQL($this->date_upd);
	
		return $fields;
	}
	public static function getByDate($sDate){
		
		$sQuery = 'SELECT * FROM ' . _DB_PREFIX_ . 'upela_pickup_demand WHERE DATE(date_send) = "' . pSql($sDate) . '"';
		
		return Db::getInstance()->executeS($sQuery);
		
	}
	
	public static function getByExpeditor($sExpeditorCode){
	
		$sQuery = 'SELECT * FROM ' . _DB_PREFIX_ . 'upela_pickup_demand WHERE code_transporteur = "' . pSql($sExpeditorCode) . '"';
	
		return Db::getInstance()->executeS($sQuery);
	
	}
	
	public static function getByDateAndExpeditor($sDate, $sExpeditorCode){
	
		$sQuery = 'SELECT * FROM ' . _DB_PREFIX_ .  'upela_pickup_demand WHERE  DATE(date_send) = "' . pSql($sDate) . '" AND code_transporteur = "' . pSql($sExpeditorCode) . '"';
	
		return Db::getInstance()->executeS($sQuery);
	
	}
	
	public function getFormattedDate($nIdLang = null){
		
		$sDate = $this->date_send ? substr($this->date_send, 0, 10) : '';
		
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