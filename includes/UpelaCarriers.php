<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16/01/18
 * Time: 04:06
 */

class UpelaCarriers
{
    protected $module_name;
    private $db;
    private $api;
    private $prices;
    const VALUE_CHEAPEST = 'cheapest';
    const VALUE_FASTEST = 'fastest';
    const MSG_DELAY = 'MSG_DELAY';
    const OPT_CHEAPEST_CARRIER_ID = 'UPELA_CHEAPEST_CARRIER_ID';
    const OPT_FASTEST_CARRIER_ID = 'UPELA_FASTEST_CARRIER_ID';
    const MSG_CARRIER_NAME_CHEAPEST = 'MSG_CARRIER_NAME_CHEAPEST';
    const MSG_CARRIER_NAME_FASTEST = 'MSG_CARRIER_NAME_FASTEST';

    /**
     * Definition of default upela carrier
     * @var array
     */
    protected $aCarrierDefinitions = array(
        self::VALUE_CHEAPEST => array(
            'name' => self::MSG_CARRIER_NAME_CHEAPEST,
            'url' => 'fr/suivi?code=@',
            'delay' => self::MSG_DELAY,
            'active' => 1,
            'deleted' => 0,
            'shipping_handling' => 1,
            'range_behavior' => 0,
            'is_module' => 0,
            'is_free' => 0,
            'shipping_method' => Carrier::SHIPPING_METHOD_WEIGHT,
            'shipping_external' => 1,
            'external_module_name' => 'upela',
            'need_range' => 0,
            'max_width' => 0,
            'max_height' => 0,
            'max_depth' => 0,
            'max_weighth' => 0,
            'grade' => 8
        ),
        self::VALUE_FASTEST => array(
            'name' => self::MSG_CARRIER_NAME_FASTEST,
            'url' => 'fr/suivi?code=@',
            'delay' => self::MSG_DELAY,
            'active' => 1,
            'deleted' => 0,
            'shipping_handling' => 1,
            'range_behavior' => 0,
            'is_module' => 0,
            'is_free' => 0,
            'shipping_method' => Carrier::SHIPPING_METHOD_WEIGHT,
            'shipping_external' => 1,
            'external_module_name' => 'upela',
            'need_range' => 0,
            'max_width' => 0,
            'max_height' => 0,
            'max_depth' => 0,
            'max_weighth' => 0,
            'grade' => 9
        ));

    public function __construct($db, $module, $api) {
        $this->db = $db;
        $this->module_name = $module;
        $this->api = $api;
    }

    public function updateCarrierUpela($id, $psid, $active = 1){
        $query = 'update `'._DB_PREFIX_.'upela_services` us     
         set id_carrier = '.$psid. ', is_active='.$active.' where id_service = '.$id;

        return $this->db->execute($query);
    }

    /**
     * Get Carriers list
     * @param $origin
     * @param $where
     * @return array
     */
    public function getCarriers($origin = false, $where = false, $id = false, $idreference = false) {
        $query = '
         SELECT *
         FROM `'._DB_PREFIX_.'upela_services` us     
         LEFT JOIN `'._DB_PREFIX_.'carrier` c
         ON c.`id_reference` = us.`id_carrier` AND c.`deleted` = 0 AND c.`external_module_name` = "upela" and c.id_reference <> 0
         WHERE 1';

        // Get By Origin
        if ($origin !== false) {
            $query .= ' AND us.`origine_point` = "'.$origin.'" ';
        }

        if ($where !== false) {
            $query .= ' '.$where.' ';
        }

        if ($id !== false) {
            $query .= ' AND us.id_service =' .$id . ' ';
        }

        if ($idreference !== false){
            $query .= ' AND c.id_carrier ='.$idreference. ' ';
        }

        $query .= ' ORDER BY us.label';
        return $this->db->executes($query);
    }


    /**
     * Get Carriers list
     * @param $origin
     * @param $where
     * @return array
     */
    public function getCarriersServices($idReference = false, $byRef = false) {
        $query = '
         SELECT  is_dropoff_point,id_up_service FROM '._DB_PREFIX_.'upela_services';

        // Get By Origin
        if ($idReference !== false) {
            if ($byRef === true){
                $query .= '  WHERE id_carrier = (select id_reference from '._DB_PREFIX_.'carrier 
                        where id_carrier = "'.$idReference.'")';
            }else{
                $query .= '  WHERE id_carrier= "'.$idReference.'" ';
            }
        }
        return $this->db->getRow($query);
    }

    /**
     * Get Active Carriers list
     * @param $origin
     * @param $where
     * @return array
     */
    public function getActiveCarriers() {
        $query = "
         SELECT id_reference, id_carrier
         FROM `"._DB_PREFIX_."carrier`             
         WHERE external_module_name = 'upela'
         and deleted = 0";

        return $this->db->executes($query);
    }

    /**
     * Get Carriers list
     * @param $origin
     * @param $where
     * @return array
     */
    public function getCarriersForTpl($origin = false, $where = false, $id = false, $language = 'fr-fr') {
        $carriers = $this->getCarriers($origin, $where, $id);

        $res = array();

        foreach ($carriers as $carrier) {
            $res[] = array(
                'id_service' => $carrier['id_service'],
                'id_carrier' => $carrier['id_carrier'],
                'label' => $carrier['label'],
                'desc_store'=> $carrier['desc_store'],
                'is_active'=>$carrier['is_active'],
                'is_pickup_point'=>$carrier['is_pickup_point'],
                'is_dropoff_point'=>$carrier['is_dropoff_point'],
                'is_express'=>$carrier['is_express'],
                'delay_text' => UpelaHelper::getTranslation(
                    ($carrier['delay_text']=='')?'a:4:{s:5:"en-us";s:10:"24h to 72h";s:5:"it-it";s:14:"da 24 a 72 ore";s:5:"es-es";s:15:"entre 24h y 72h";s:5:"fr-fr";s:10:"24h à 72h";}':$carrier['delay_text'],
                    $language)
            );
        }

        return $res;
    }

    private function getPrice($upCarrier, $upService){
        $price = 0;

                if (isset($this->prices['offers'])) {
            foreach ($this->prices['offers'] as $offer) {
                if ($offer['carrier_code'] == $upCarrier) {
                    if ($offer['service_code'] == $upService) {
                        $price = ceil($offer['price_excl_tax']);
                        break;
                    }

                    if ((ceil($offer['price_incl_tax']) < $price) || ($price == 0))
                        $price = ceil($offer['price_excl_tax']);
                }
            }
        }

        return $price;
    }

    /**
     * @return bool
     */
    public function createCarriers($servicesId) {
        $ret=true;

        if(count($servicesId)>0) {
            if (empty(Configuration::get('PS_SHOP_COUNTRY_ID'))) {
                $defaultCountry = Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'));
            } else {
                $defaultCountry = Country::getIsoById(Configuration::get('PS_SHOP_COUNTRY_ID'));
            }

            $this->prices = array();

            if (array_key_exists($defaultCountry, UpelaHelper::$countryCities)) {
                $addressFrom = array(
                    'country' => $defaultCountry,
                    'city' => Configuration::get('PS_SHOP_CITY'),
                    'cp' => Configuration::get('PS_SHOP_CODE')
                );
                $addressTo = array(
                    'country' => $defaultCountry,
                    'city' => UpelaHelper::$countryCities[$defaultCountry]['city'],
                    'cp' => UpelaHelper::$countryCities[$defaultCountry]['cp'],
                );
                $parcel = array(
                    'weight' => Configuration::get('UPELA_SHIP_WEIGHT'),
                    'length' => Configuration::get('UPELA_SHIP_LENGTH'),
                    'width' => Configuration::get('UPELA_SHIP_WIDTH'),
                    'height' => Configuration::get('UPELA_SHIP_HEIGHT'),
                );

                $this->prices = $this->api->getPrices($addressFrom, $addressTo, $parcel);
            }

            foreach ($servicesId as $serviceId) {
                if ($serviceId == '')
                    continue;

                $carrierInfo = $this->getCarriers(false, false, $serviceId);
                $carrierId = $this->createCarrier($carrierInfo[0]);
                $this->updateCarrierUpela($carrierInfo[0]['id_service'], $carrierId, 1);
            }

            $activeCarriers = $this->getActiveCarriers();

            foreach ($activeCarriers as $activeCarrier) {
                $toDel = true;

                foreach ($servicesId as $serviceId) {
                    if ($serviceId == '')
                        continue;

                    $carrierInfo =  $this->getCarriers(false,false, $serviceId);

                    if (isset($carrierInfo[0]['id_reference']) && isset($activeCarrier['id_reference'])) {
                        if ($carrierInfo[0]['id_reference'] == $activeCarrier['id_reference']) {
                            $toDel = false;
                            break;
                        }
                    }
                }


                //die;



                if ($toDel){
                    $carrier = new Carrier($activeCarrier['id_carrier']);
                    $carrier->deleted = 1;
                    try{
                        $carrier->save();
                    }catch (Exception $e){}

                    $query = 'update `'._DB_PREFIX_.'upela_services` us
                            set is_active=0, id_carrier=0 where id_carrier = '.$activeCarrier['id_carrier'];

                    $this->db->execute($query);
                }
            }
            //die();
        }

        return $ret;
    }

    /**
     * Adds new carrier linked to the module
     * @param array $aDefinition Carrier definition, see upela::$aCarrierDefinitions
     * @return int
     */
    protected function createCarrier($aDefinition) {
        $langs = Language::getLanguages(true);

        $old_carrier = $this->db->getRow(
            'SELECT * FROM ' . _DB_PREFIX_ . 'carrier 
            WHERE id_reference = "' . (int)$aDefinition['id_reference'] .
            '" AND id_reference <> 0 ORDER BY id_carrier DESC'
        );

        //print_r($old_carrier);

        // if old carrier is not deleted, we keep the current information
        if (isset($old_carrier['id_reference']) && $old_carrier['deleted'] == 0) {
          //  print('OLD');
            return $old_carrier['id_reference'];
        }

        //die;


        $carrier = new Carrier();
        $carrier->name = $aDefinition['label'];
        $carrier->active = TRUE;
        $carrier->deleted = 0;
        $carrier->shipping_handling = TRUE;
        $carrier->range_behavior = 0;
        $carrier->shipping_external = FALSE;
        $carrier->is_module = TRUE;
        $carrier->external_module_name = $this->module_name;
        $carrier->need_range = TRUE;

        if (array_key_exists($aDefinition['label'] , UpelaHelper::$tracking_urls)){
            $carrier->url = UpelaHelper::$tracking_urls[$aDefinition['label']];
        }else{
            $carrier->url = UpelaHelper::$tracking_urls['Upela'];
        }

        $delay_text = ($aDefinition['delay_text'] == '')?
            'a:4:{s:5:"en-us";s:10:"24h to 72h";s:5:"it-it";s:14:"da 24 a 72 ore";s:5:"es-es";s:15:"entre 24h y 72h";s:5:"fr-fr";s:10:"24h à 72h";}'
            :$aDefinition['delay_text'];

        if ($langs && count($langs) > 0) {
            foreach ($langs as $lang) {
                $carrier->delay[$lang['id_lang']] =
                    UpelaHelper::getTranslation($delay_text, $lang['language_code']);
            }
        }

        if ($carrier->add()) {

            if (isset($old_carrier['id_reference']) && $old_carrier['deleted'] == 1) {
                $carrier->copyCarrierData($old_carrier['id_carrier']);
            } else {
                //update carrier reference
                $carrier->id_reference = (int)$carrier->id;
                $carrier->save();
            }

            $this->addCarrierToGroups($carrier);
            $this->addCarrierToZones($carrier);
            $oRangeWeight = $this->addCarrierRangeWeight($carrier);
            $oRangePrice = $this->addCarrierRangePrice($carrier);
            $this->addDeliveryPrice($carrier, $oRangeWeight, $oRangePrice, $aDefinition );

            $this->copyCarrierImage($carrier, $aDefinition['up_code_carrier']);

            return $carrier->id;
        }
        return 0;
    }

    /**
     * Adds carrier to all groups
     * @param Carrier $oCarrier
     */
    protected function addCarrierToGroups($oCarrier) {

        $aGroups = array();

        foreach (Group::getGroups(Configuration::get('PS_LANG_DEFAULT')) as $aGroup) {
            $aGroups[] = (int)$aGroup['id_group'];
        }

        $this->setGroups($oCarrier, $aGroups);
    }

    /**
     * Adds carrier to all active zones
     * @param Carrier $oCarrier
     */
    protected function addCarrierToZones(Carrier $oCarrier) {
        foreach (Zone::getZones(true) as $aZone) {
            $oCarrier->addZone((int)$aZone['id_zone']);
        }
    }

    /**
     * Adds empty weight range to carrier (without it carrier is not visible, even if it doesn't use one)
     * @param Carrier $oCarrier
     * @return RangeWeight
     */
    protected function addCarrierRangeWeight(Carrier $oCarrier) {

        $oRangeWeight = RangeWeight::getRanges($oCarrier->id);

        if (count($oRangeWeight) === 0) {
            $oRangeWeight = new RangeWeight();
            $oRangeWeight->id_carrier = $oCarrier->id;
            $oRangeWeight->delimiter1 = 0;
            $oRangeWeight->delimiter2 = 10000;
            $oRangeWeight->add();
        }

        return $oRangeWeight;
    }

    /**
     * Adds empty weight range to carrier (without it carrier is not visible, even if it doesn't use one)
     * @param Carrier $oCarrier
     * @return RangePrice
     */
    protected function addCarrierRangePrice(Carrier $oCarrier) {
        $oRangePrice = RangePrice::getRanges($oCarrier->id);

        if (count($oRangePrice) === 0) {
            $oRangePrice = new RangePrice();
            $oRangePrice->id_carrier = $oCarrier->id;
            $oRangePrice->delimiter1 = 0;
            $oRangePrice->delimiter2 = 10000;
            $oRangePrice->add();
        }

        return $oRangePrice;
    }

    protected function addDeliveryPrice(Carrier $oCarrier, $oRangeWeight, $oRangePrice, $defCarrier) {
        $upCarrier = $defCarrier['up_code_carrier'];
        $upService = $defCarrier['up_code_service'];

        $price = $this->getPrice($upCarrier, $upService);

        foreach ($oCarrier->getZones() as $aZone) {
            Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'delivery` (`id_carrier`, `id_range_price`, `id_range_weight`, `id_zone`, `price`)
				VALUES ('.(int)($oCarrier->id).', NULL, '.(int)($oRangeWeight->id).', '.(int)($aZone['id_zone']).', '.$price.')');
        }
    }

    /**
     * Copies default image as carrier logo
     * @param Carrier $oCarrier
     * @return boolean
     */
    protected function copyCarrierImage($oCarrier, $carrierCode) {
        $sSource = _PS_MODULE_DIR_.$this->module_name.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'carriers'.DIRECTORY_SEPARATOR.'logo-'.strtolower($carrierCode).'.jpg';
        if (!file_exists($sSource)) {
            $sSource = _PS_MODULE_DIR_.$this->module_name.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'carriers'.DIRECTORY_SEPARATOR.'carrier-upela.jpg';
            $sDestination = _PS_SHIP_IMG_DIR_.$oCarrier->id.'.jpg';
        }else{
            $sDestination = _PS_SHIP_IMG_DIR_.$oCarrier->id.'.jpg';
        }

        return copy($sSource, $sDestination);
    }

    /**
     * Copy of Carrier::setGroups, not disponible before 1.5.4
     * @param Carrier $oCarriers
     * @param array $aGroups
     * @param boolean $bDelete
     * @return boolean
     */
    protected function setGroups($oCarriers, $aGroups, $bDelete = true){
        if ($bDelete){
            Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'carrier_group WHERE id_carrier = '. (int)$oCarriers->id);
        }

        if (!is_array($aGroups) || !count($aGroups)){
            return true;
        }

        $aValues = array();

        foreach ($aGroups as $nGroupId) {
            $aValues[] = sprintf('(%d,%d)', (int)$oCarriers->id, (int)$nGroupId);
        }

        $sQuery = 'INSERT INTO '._DB_PREFIX_.'carrier_group (id_carrier, id_group) VALUES ' . implode(',', $aValues);
        return Db::getInstance()->execute($sQuery);
    }

    /**
     * Gets ids of all languages
     * @return array <int : language id>
     */
    protected function getLanguagesIds(){

        $aResult= array();

        foreach (Language::getLanguages(false) as $aLanguage){

            $aResult[] = (int)$aLanguage['id_lang'];

        } // foreach

        return $aResult;

    }

    /**
     * @param     $msg
     * @param int $level
     */
    private function quickLog($msg, $level = 1) {
        if (version_compare(_PS_VERSION_, '1.6.0.0', '>')) {
            PrestaShopLogger::addLog('UPELA_LOG: '.strip_tags(print_r($msg, true)), $level, null, 'upela');
        } else {
            Logger::addLog('UPELA_LOG: '.strip_tags(print_r($msg, true)), $level, null, 'upela');
        }
    }

}