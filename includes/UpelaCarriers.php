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

    public function __construct($db, $module) {
        $this->db = $db;
        $this->module_name = $module;
    }

    public function updateCarrierUpela($id, $psid){
        $query = 'update `'._DB_PREFIX_.'upela_services` us     
         set id_carrier = '.$psid. ' where id_service = '.$id;


        return $this->db->executes($query);
    }

    /**
     * Get Carriers list
     * @param $origin
     * @param $where
     * @return array
     */
    public function getCarriers($origin = false, $where = false, $id = false) {
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

        $query .= 'ORDER BY us.label';
        return $this->db->executes($query);
    }

    /**
     * Get Carriers list
     * @param $origin
     * @param $where
     * @return array
     */
    public function getCarriersForTpl($origin = false, $where = false, $id = false) {
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
                'delay_text' => $carrier['delay_text'],
            );
        }

        return $res;
    }


    /**
     * @return bool
     */
    public function createCarriers($servicesId) {
        $this->quickLog('createCarriers2');

        $psCarrier = new Carrier();

        foreach ($servicesId as $serviceId) {
            $carrierInfo =  $this->getCarriers(false,false, $serviceId);

            $this->createCarrier($carrierInfo);
        }

        //die();

    }

    /**
     * Adds new carrier linked to the module
     * @todo Add logo
     * @todo delete / desactivate other carriers
     * @param array $aDefinition Carrier definition, see upela::$aCarrierDefinitions
     * @return int
     */
    protected function createCarrier($aDefinition) {
        $this->quickLog('createCarrier: '.$aDefinition['label']);

        $carrier = new Carrier();
        $carrier->name = $aDefinition['label'];
        $carrier->active = TRUE;
        $carrier->deleted = 0;
        $carrier->shipping_handling = FALSE;
        $carrier->range_behavior = 0;
        $carrier->delay[Configuration::get('PS_LANG_DEFAULT')] = $aDefinition['delay_text'];
        $carrier->shipping_external = TRUE;
        $carrier->is_module = TRUE;
        $carrier->external_module_name = $this->module_name;
        $carrier->need_range = TRUE;

        if ($carrier->add()) {
            $this->addCarrierToGroups($carrier);
            $this->addCarrierToZones($carrier);
            $oRangeWeight = $this->addCarrierRangeWeight($carrier);
            $this->addDeliveryPrice($carrier, $oRangeWeight);
            $this->copyCarrierImage($carrier);

            $this->updateCarrierUpela($aDefinition['id_service'], $carrier->id);

            return $carrier->id;
        }
        return 0;
    }

    /**
     * Sets carrier properties. Generates multilang / translated properties if necessary
     * @param Carrier $oCarrier
     * @param array $aDefinition <string : property name => mixed : property value>
     */
    protected function setCarrierProperties($oCarrier, $aDefinition) {

        foreach ($aDefinition as $sKey => $mValue) {

            if ($sKey === 'url') {
                $mValue = $this->api->getBaseUrl().$mValue;
            }

            if ($sKey === 'delay') {
                $mValue = array_fill_keys($this->getLanguagesIds(), $this->l($mValue));
            }

            if ($sKey === 'name') {
                $mValue = $this->l($mValue);
            }
            $oCarrier->{$sKey} = $mValue;
        }

    }

    /**
     * Adds carrier to all groups
     * @param Carrier $oCarrier
     */
    protected function addCarrierToGroups($oCarrier) {

        $aGroups = array();

        foreach (Group::getGroups($this->context->language->id) as $aGroup) {
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

        $oRangeWeight = new RangeWeight();

        $oRangeWeight->id_carrier = $oCarrier->id;

        $oRangeWeight->delimiter1 = 0;

        $oRangeWeight->delimiter2 = 99999;

        $oRangeWeight->add();

        return $oRangeWeight;

    }

    protected function addDeliveryPrice(Carrier $oCarrier, $oRangeWeight) {
        foreach ($oCarrier->getZones() as $aZone) {
            Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'delivery` (`id_carrier`, `id_range_price`, `id_range_weight`, `id_zone`, `price`)
				VALUES ('.(int)($oCarrier->id).', NULL, '.(int)($oRangeWeight->id).', '.(int)($aZone['id_zone']).', 0)');
        }
    }

    /**
     * Copies default image as carrier logo
     * @param Carrier $oCarrier
     * @return boolean
     */
    protected function copyCarrierImage($oCarrier) {

        $sSource = _PS_MODULE_DIR_.$this->name.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'img'.DIRECTORY_SEPARATOR.'carriers'.DIRECTORY_SEPARATOR.'carrier-upela.jpg';

        $sDestination = _PS_SHIP_IMG_DIR_.$oCarrier->id.'.jpg';

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