<?php

class UpelaPickupDemand extends ObjectModel {

	public $date_send;
	public $code_transporteur;
	public $api_return;
	
	public $date_add;
	public $date_upd;

	public static $definition = array(
			'table' => 'upela_pickup_demand',
			'primary' => 'id_upela_pickup_demand',
			'fields' => array(
					'code_transporteur'	=> 	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
					'api_return' 		=> 	array('type' => self::TYPE_STRING, 'validate' => 'isString'),
					'date_send' 		=> 	array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
					'date_add' 			=> 	array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
					'date_upd' 			=> 	array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat')
			),
	);

	public static function getByDate($sDate){
		
		$sQuery = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' WHERE DATE(date_send) = "' . pSql($sDate) . '"';
		
		return Db::getInstance()->executeS($sQuery);
		
	}
	
	public static function getByExpeditor($sExpeditorCode){
	
		$sQuery = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' WHERE code_transporteur = "' . pSql($sExpeditorCode) . '"';
	
		return Db::getInstance()->executeS($sQuery);
	
	}
	
	public static function getByDateAndExpeditor($sDate, $sExpeditorCode){
	
		$sQuery = 'SELECT * FROM ' . _DB_PREFIX_ . self::$definition['table'] . ' WHERE  DATE(date_send) = "' . pSql($sDate) . '" AND code_transporteur = "' . pSql($sExpeditorCode) . '"';
	
		return Db::getInstance()->executeS($sQuery);
	
	}
	
	public function getFormattedDate($nIdLang = null){
		
		$sDate = $this->date_send ? substr($this->date_send, 0, 10) : '';
		
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