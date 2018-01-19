<?php
/**
 * 2007-2016 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    UPELA
 * @copyright 2017-2018 MPG Upela
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class UpelaHelper
{
    public static $countryCities = array(
        'FR' => array(
            'cp' => '13001',
            'city' => 'Marseille'
        ),
        'ES' => array(
            'cp' => '08001',
            'city' => 'Barcelona'
        ),
        'DE' => array(
            'cp' => '80331',
            'city' => 'Munich'
        ),
        'IT' => array(
            'cp' => '00100',
            'city' => 'Rome'
        )
    );

    public static $tracking_urls = array(
        "Chronopost" => "http://www.chronopost.fr/expedier/inputLTNumbersNoJahia.do?lang=fr_FR&listeNumeros=@",
        "Mondial Relay" => "http://www.mondialrelay.fr/suivi-de-colis/?NumeroExpedition=@&CodePostal=",
        "Colissimo" => "http://www.colissimo.fr/portail_colissimo/suivreResultat.do?parcelnumber=@",
        "TNT" => "http://www.tnt.fr/public/suivi_colis/recherche/visubontransport.do?radiochoixrecherche=BT&bonTransport=@",
        "TNT International" => "http://www.tnt.fr/public/suivi_colis/recherche/visubontransport.do?radiochoixrecherche=BT&bonTransport=@",
        "TNT Relais Colis" => "http://www.tnt.fr/public/suivi_colis/recherche/visubontransport.do?radiochoixrecherche=BT&bonTransport=@",
        "FedEx" => "https://www.fedex.com/apps/fedextrack/?tracknumbers=@",
        "FedEx2" => "https://www.fedex.com/apps/fedextrack/?tracknumbers=@",
        "DHL+" => "http://www.dhl.fr/content/fr/fr/dhl_express/suivi_expedition.shtml?brand=DHL&AWB=@",
        "DP Group" => "http://www.dhl.fr/content/fr/fr/dhl_express/suivi_expedition.shtml?brand=DHL&AWB=@",
        "UPS" =>
            "http://wwwapps.ups.com/etracking/tracking.cgi?InquiryNumber1=@&loc=fr_FR&TypeOfInquiryNumber=T",
        "UPS Access Point" =>
            "https://wwwapps.ups.com/WebTracking/track?HTMLVersion=5.0&loc=fr_FR&Requester=UPSHome&WBPM_lid=homepage%252Fct1.html_pnl_trk&track.x=Suivi&trackNums=@",
        "Seur" => "http://www.seur.com/seguimiento-online.do",
        "Correos" => "http://aplicacionesweb.correos.es/localizadorenvios/track.asp?numero=@",
        "Correos Express" => "http://aplicacionesweb.correos.es/localizadorenvios/track.asp?numero=@",
        "DPD" => "http://www.dpd.fr/traces_@",
        "DPD Relais" => "http://www.dpd.fr/traces_@",
        "Nexive" => "https://www.formulacerta.it/Tracking-Spedizioni-Nexive.aspx?b=@",
        "Sda" => "https://www.sda.it/SITO_SDA-WEB/dispatcher?button=+&id_ldv=@&invoker=home&LEN=ENG&execute2=ActionTracking.doGetTrackingHome",
        "Asm" => "http://www.asmred.com/extranet/public/ExpedicionASM.aspx?codigo=@&cpDst=",
        "Brt" => "https://vas.brt.it/vas/sped_det_new.htm?brtCode=@",
        "Upela" => "https://www.upela.com/en/tracking/?code=@"
    );
    /**
     * Webservices presmissions of the module
     * @var array
     * @access protected
     */
    protected $permissions = array(
        'addresses' => array('GET' => 'on'),
        'carriers' => array('GET' => 'on'),
        'configurations' => array('GET' => 'on'),
        'countries' => array('GET' => 'on'),
        'customers' => array('GET' => 'on'),
        'order_carriers' => array('GET' => 'on', 'PUT' => 'on'),
        'order_histories' => array('GET' => 'on', 'POST' => 'on'),
        'order_states' => array('GET' => 'on'),
        'orders' => array('GET' => 'on'),
        'products' => array('GET' => 'on'),
        'states' => array('GET' => 'on'),
    );
    /**
     * SQL tables names of the module
     * @var array
     * @access protected
     */
    protected $tables_names = array(
        'upela_order_points',
        'upela_services',
        'upela_country_zone',
        'upela_orders'
    );
    private $db;
    private $hasDb;

    /**
     * Default tracking urls.
     * @access public
     * @var array
     */
    // @codingStandardsIgnoreStart
    public function __construct()
    {
        $ctp = func_num_args();
        $args = func_get_args();

        if ($ctp == 1) {
            $this->db = $args[0];
            $this->hasDb = true;
        } else {
            $this->hasDb = false;
        }
    }

    /**
     * Gets translation for custom multilingual field, returns fr (or en) if translation is empty or does not exist
     * @param string $serialized_field : the serialized multilingual field
     * @param string $lang : the target language, 5 characters string (fr-fr, en-us, ...)
     * @return mixed $translation
     */
    public static function getTranslation($serialized_field, $language)
    {
        $field = @unserialize($serialized_field);
        // sometimes iso_code is in uppercase
        $language = Tools::strtolower($language);

        if ($field == false) {
            return $serialized_field;
        } else {
            if (!isset($field[$language])) {
                // return any target language
                foreach ($field as $lang => $value) {
                    if ((Tools::strtolower(Tools::substr($language, 0, 2)) ==
                            Tools::strtolower(Tools::substr($lang, 0, 2))) && $value != '') {
                        return $value;
                    }
                }
                // return any french language
                foreach ($field as $lang => $value) {
                    if ('fr' == Tools::strtolower(Tools::substr($lang, 0, 2))) {
                        return $value;
                    }
                }
                // return any english language
                foreach ($field as $lang => $value) {
                    if ('en' == Tools::strtolower(Tools::substr($lang, 0, 2))) {
                        return $value;
                    }
                }
                // return any target language if empty
                foreach ($field as $lang => $value) {
                    if (Tools::strtolower(Tools::substr($language, 0, 2)) ==
                        Tools::strtolower(Tools::substr($lang, 0, 2))) {
                        return $value;
                    }
                }

                // return any language
                return current($field);
            } else {
                return $field[$language];
            }
        }
    }

    /**
     * @return array
     */
    public function getTablesNames()
    {
        return $this->tables_names;
    }

    /**
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param $coCode
     * @return string
     */
    public function getCountryZone($coCode)
    {
        if ($this->hasDb) {
            $query = "
         SELECT cz_zo
         FROM `"._DB_PREFIX_."upela_country_zone`
         WHERE cz_co = '$coCode'";

            $res = $this->db->executes($query);

            if (count($res) == 0) {
                return 'ROW';
            } else {
                $res = $res[0];

                return $res['cz_zo'];
            }
        } else {
            return 'ROW';
        }
    }


}