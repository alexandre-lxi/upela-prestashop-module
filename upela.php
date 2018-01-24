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

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once 'api/UpelaApi.php';

class Upela extends Module
{

    protected $errors = array();
    private $mode = null;
    private $api = null;
    private $upela_helper = null;
    private $isConnected = false;
    private $carriers;
    /**
     * @var array
     */
    private $postErrors = array();
    private $postSuccess = array();

    /**
     * Upela constructor.
     */
    public function __construct()
    {
        $this->name = 'upela';
        $this->tab = 'shipping_logistics';
        $this->version = '2.0.0';
        $this->author = 'Upela';
        $this->need_instance = 1;
        $this->bootstrap = true;
        $this->module_key = '909a230701b42c01ef40630cccc65b82';

        parent::__construct();

        require_once(_PS_MODULE_DIR_.'/upela/includes/UpelaHelper.php');
        require_once(_PS_MODULE_DIR_.'/upela/includes/UpelaCarriers.php');

        $this->initAPI();

        $this->displayName = $this->l('Upela');
        $this->description = $this->l(
            'The best way to ship a parcel Save on shipping costs, never on service quality.'
        );

        $this->ps_versions_compliancy = array(
            'min' => '1.5',
            'max' => _PS_VERSION_
        );

        $this->carriers = new UpelaCarriers(Db::getInstance(), $this->name, $this->api);
        $this->upela_helper = new UpelaHelper(Db::getInstance());
    }

    /**
     * API Initialization
     */
    private function initAPI()
    {
        $this->loadMode();
        $hasAccountConnected = $this->hasAccountConnected();

        if ($hasAccountConnected) {
            $this->api = new UpelaApi($this->getUserConnected(), $this->mode);
            $this->isConnected = true;
        } else {
            $this->api = new UpelaApi(false, $this->mode);
        }
    }

    /**
     * Mode initialization
     */
    private function loadMode()
    {
        $apiMode = Configuration::get('UPELA_API_MODE');

        if ($apiMode == 'prod') {
            $this->mode = UpelaApi::API_MODE_PROD;
        } else {
            $this->mode = UpelaApi::API_MODE_TEST;
        }
    }

    /**
     * True if Account is define in configuration
     * @return bool
     */
    private function hasAccountConnected()
    {
        return Configuration::get('UPELA_USER_LOGIN') &&
            Configuration::get('UPELA_USER_PASSWORD') &&
            Configuration::get('UPELA_USER_ID') &&
            Configuration::get('UPELA_USER_NAME');
    }

    /**
     * @return array
     */
    private function getUserConnected()
    {
        return array(
            'login'     => Configuration::get('UPELA_USER_LOGIN'),
            'password'  => Configuration::get('UPELA_USER_PASSWORD'),
            'id'        => Configuration::get('UPELA_USER_ID'),
            'name'      => Configuration::get('UPELA_USER_NAME'),
            'firstname' => Configuration::get('UPELA_STORE_FIRSTNAME')
        );
    }

    /**
     * @return UpelaAPI::MODE
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param null $mode
     */
    public function setMode($mode)
    {
        if (($this->mode != $mode) && ($this->isConnected)) {
            $this->dumpConfigurations();
            $this->isConnected = false;
        }

        Configuration::updateValue('UPELA_API_MODE', $mode);

        $this->api->setMode($mode);
        $this->mode = $mode;
    }

    /**
     * @return bool
     */
    public function isAccountConnected()
    {
        return $this->isConnected;
    }

    /**
     * @return bool
     */
    public function install()
    {
        Logger::addLog('UPELA: Install module');

        return parent::install() &&
            Configuration::updateValue('UPELA_API_MODE', UpelaApi::API_MODE_PROD) &&
            //$this->installTab('AdminUpela', Tab::getIdFromClassName('AdminParentShipping'), 'Upela') &&
            $this->registerHook('displayAdminOrder') &&
            $this->registerHook('displayCarrierExtraContent') &&
            $this->registerHook('header') &&
            $this->registerHook('displayOrderConfirmation') &&
            $this->installDb() &&
            $this->dumpConfigurations();
    }

    /**
     * @return bool
     */
    private function installDb()
    {
        $sql_file = Tools::file_get_contents(_PS_MODULE_DIR_.'/upela/sql/install.sql');
        $sql_file = str_replace('{PREFIXE}', _DB_PREFIX_, $sql_file);

        $query = explode('-- REQUEST --', $sql_file);
        foreach ($query as $q) {
            if ($q <> '') {
                if (Db::getInstance()
                      ->execute($q) === false) {
                    Logger::addLog(
                        '['.$this->l('UPELA').']['.time().'] '.
                        $this->l('installation :  An error occured on the query : ').$q
                    );
                    $this->uninstallDb();

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function uninstallDb()
    {
        $this->carriers->removeCarriers();

        $tables = array();
        foreach ($this->upela_helper->getTablesNames() as $table) {
            Logger::addLog('UPELA: remove '.$table);
            $tables[] = '`'._DB_PREFIX_.$table.'`';
        }
        $remove_tables = 'SET FOREIGN_KEY_CHECKS = 0; DROP TABLE IF EXISTS '.implode(',', $tables);

        return DB::getInstance()
                 ->execute($remove_tables);
    }

    /**
     * @return bool
     */
    public function dumpConfigurations()
    {
        return Configuration::updateValue('UPELA_USER_LOGIN', '') &&
            Configuration::updateValue('UPELA_USER_PASSWORD', '') &&
            Configuration::updateValue('UPELA_USER_ID', '') &&
            Configuration::updateValue('UPELA_USER_NAME', '') &&
            Configuration::updateValue('UPELA_WEBSERVICE_KEY', '');
    }

    /**
     * @param $class_name
     * @param $id_parent
     * @param $name
     *
     * @return mixed
     */
    public function installTab($class_name, $id_parent, $name)
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = $class_name;
        $tab->name = array();
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = $name;
        }

        $tab->id_parent = (int)$id_parent;
        $tab->module = $this->name;

        return $tab->add();
    }

    /**
     * @return bool
     */
    public function uninstall()
    {
        Logger::addLog('UPELA: Uninstall module');

        return parent::uninstall() &&
            // $this->uninstallTab('AdminUpela') &&
            $this->unregisterHook('displayAdminOrder') &&
            $this->unregisterHook('displayCarrierExtraContent') &&
            $this->unregisterHook('header') &&
            $this->uninstallDb() &&
            $this->removeConfig();
    }

    /**
     * @return bool
     */
    private function removeConfig()
    {
        Configuration::deleteByName('UPELA_USER_LOGIN');
        Configuration::deleteByName('UPELA_USER_PASSWORD');
        Configuration::deleteByName('UPELA_USER_ID');
        Configuration::deleteByName('UPELA_USER_NAME');
        Configuration::deleteByName('UPELA_API_MODE');
        Configuration::deleteByName('UPELA_WEBSERVICE_KEY');

        return true;
    }

    /**
     * @param $class_name
     *
     * @return bool
     */
    public function uninstallTab($class_name)
    {
        $id_tab = Tab::getIdFromClassName($class_name);
        if ($id_tab) {
            $tab = new Tab($id_tab);

            return $tab->delete();
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    public function getModulePath()
    {
        return $this->_path;
    }


    /**
     * Header's hook. It displays included JavaScript for GoogleMaps API.
     * @access public
     * @return Displayed Smarty template.
     */
    public function hookHeader($params)
    {
        $smarty = $this->context->smarty;
        $controller = $this->context->controller;
        $smarty->assign('upelaBaseDir', _MODULE_DIR_.'/upela/');

        if (method_exists($controller, 'registerJavascript')) {
            $controller->registerJavascript(
                'upela-jquery',
                'https://code.jquery.com/jquery-3.2.1.min.js',
                array(
                    'priority' => 100,
                    'server'   => 'remote'
                )
            );
            $controller->registerJavascript(
                'upela-googlemap',
                'https://maps.google.com/maps/api/js?key=AIzaSyBplTpOQbyilWbKmwfImXa2B2VeCTQMosw',
                array(
                    'priority' => 100,
                    'server'   => 'remote'
                )
            );
            $controller->registerJavascript(
                'upela-googlemap',
                'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modal.min.js',
                array(
                    'priority' => 100,
                    'server'   => 'remote'
                )
            );
            $controller->registerJavascript(
                'upela-googlemap',
                'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modalmanager.min.js',
                array(
                    'priority' => 100,
                    'server'   => 'remote'
                )
            );
            $controller->registerJavascript(
                'upela',
                'modules/upela/views/js/upela.js',
                array(
                    'priority' => 100,
                    'server'   => 'local'
                )
            );
            $controller->registerStylesheet(
                'upela',
                'modules/upela/views/css/upela.css',
                array(
                    'priority' => 100,
                    'server'   => 'local'
                )
            );
        } else {
            $controller->addJs('https://code.jquery.com/jquery-3.2.1.min.js');
            $controller->addJs('https://maps.google.com/maps/api/js?key=AIzaSyBplTpOQbyilWbKmwfImXa2B2VeCTQMosw');
            $controller->addJs(
                'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modal.min.js'
            );
            $controller->addJs(
                'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modalmanager.min.js'
            );
            $controller->addJs(_MODULE_DIR_.'/upela/views/js/upela.js');
        }

        return $this->display(__FILE__, '/views/templates/hook/header_hook.tpl');
    }

    /**
     * Since Prestashop 1.7, this hook is used to display front-office list of relay points
     * @access public
     * @param array $params Parameters array (cart object, address informations)
     * @return Display template.
     */
    public function hookDisplayCarrierExtraContent(&$params)
    {
        $tpl = '';
        $postcode = null;
        $city = null;
        $country = null;
        $deleted = null;
        $results = $this->carriers->getCarriersServices($params['carrier']['id_reference']);
        $is_dropoff = $results['is_dropoff_point'];
        $upela_service = $results['id_up_service'];
        // ici on utilise le pointeur vers le carrier de reference
        $carrier_id = $params['carrier']['id_reference'];

        foreach ($this->context->cart->getAddressCollection() as $address) {
            $postcode = $address->postcode;
            $city = $address->city;
            $country = $address->country;
            $deleted = $address->deleted;
        }

        $carrier = $this->carriers->getCarriersServices( $params['carrier']['id_reference'] );

        $addressTo = array(
            'postcode'      => $postcode,
            'cp'      => $postcode,
            'city'          => $city,
            'country'          => $country,
            'upela_service' => $upela_service,
            'carrier_id'    => $carrier_id
        );
        $price = $this->carriers->getCarrierPriceByServices(['0'=>$carrier],$addressTo);

        if(count($price['offers']) == 0){
            $this->context->smarty->assign(
                array(
                    'address' =>$addressTo,
                    'hide'=>$params['carrier']['id_reference'],
                )
            );
            $tpl = $this->display(__FILE__, 'displayCarrierExtraContentOffers.tpl');
        }
        else
        {
            if ($is_dropoff && !is_null($postcode) && !is_null($city) && !$deleted) {
                $this->context->smarty->assign(
                    array(
                        'address' =>$addressTo
                    )
                );
                $tpl = $this->display(__FILE__, 'displayCarrierExtraContent.tpl');
            }
        }


        return $tpl;
    }

    /**
     * @param $params
     * @return string
     */
    public function hookdisplayAdminOrder($params)
    {
        $cart_id = $this->context->cart->id;
        $carrierInfo = $this->carriers->getCarriersServices($this->context->cart->id_carrier, true);

        $query = 'select count(*) nb from `'._DB_PREFIX_.'upela_orders` where id_cart_ps = '.(int)$this->context->cart->id;
        $countOrders = Db::getInstance()
                         ->getRow($query);

        if (($carrierInfo['up_code_carrier'] != '') || ((int)$countOrders['nb'] > 0)) {
            $controller = $this->context->controller;

            if (method_exists($controller, 'registerJavascript')) {
                $controller->registerJavascript(
                    'upela-jquery',
                    'https://code.jquery.com/jquery-3.2.1.min.js',
                    array(
                        'priority' => 100,
                        'server'   => 'remote'
                    )
                );
                $controller->registerJavascript(
                    'upela',
                    'modules/upela/views/js/upela.js',
                    array(
                        'priority' => 100,
                        'server'   => 'local'
                    )
                );
                $controller->registerStylesheet(
                    'upela',
                    'modules/upela/views/css/upela.css',
                    array(
                        'priority' => 100,
                        'server'   => 'local'
                    )
                );
            } else {
                $controller->addJs('https://code.jquery.com/jquery-3.2.1.min.js');
                $controller->addJs(_MODULE_DIR_.'/upela/views/js/upela.js');
            }

            if ($carrierInfo['is_dropoff_point'] == true) {
                $dropoffPoint = $this->carriers->getDropoffPointByCart($cart_id);
                $is_dropoff = $dropoffPoint['dp_address1'] != '';
            } else {
                $is_dropoff = false;
            }

            $customer = new Customer($this->context->cart->id_customer);

            $deliveryAdress = new Address((int)$this->context->cart->id_address_delivery);
            $deliveryPhone = ($deliveryAdress->phone_mobile == '') ?
                (($deliveryAdress->phone == '') ?
                    '' :
                    $deliveryAdress->phone) :
                $deliveryAdress->phone_mobile;

            if ($deliveryPhone == '') {
                $deliveryPhone = Configuration::get('UPELA_STORE_PHONE');
            }

            $toCountry = Country::getIsoById($deliveryAdress->id_country);

            $paymentInfo = $this->api->getPayments();

            $infoShipment = array(
                'account'       =>
                    array(
                        'login'    => '####',
                        'password' => '#####',
                    ),
                'carrier_code'  => $carrierInfo['up_code_carrier'],
                'service_code'  => $carrierInfo['up_code_service'],
                'ship_from'     =>
                    array(
                        'company'      => Configuration::get('UPELA_STORE_NAME'),
                        'name'         => Configuration::get('UPELA_STORE_FIRSTNAME').' '.
                            Configuration::get('UPELA_STORE_LASTNAME'),
                        'phone'        => Configuration::get('UPELA_STORE_PHONE'),
                        'email'        => Configuration::get('UPELA_STORE_EMAIL'),
                        'address1'     => Configuration::get('UPELA_STORE_ADDRESS1'),
                        'address2'     => Configuration::get('UPELA_STORE_ADDRESS2'),
                        'address3'     => Configuration::get('UPELA_STORE_COUNTRY'),
                        'country_code' => Configuration::get('UPELA_STORE_COUNTRY'),
                        'postcode'     => Configuration::get('UPELA_STORE_ZIPCODE'),
                        'city'         => Configuration::get('UPELA_STORE_CITY'),
                        'pro'          => Configuration::get('UPELA_STORE_BUSINESS'),
                    ),
                'ship_to'       =>
                    array(
                        'company'      => $deliveryAdress->company,
                        'name'         => $deliveryAdress->firstname.' '.$deliveryAdress->lastname,
                        'phone'        => $deliveryPhone,
                        'email'        => $customer->email,
                        'address1'     => $deliveryAdress->address1,
                        'address2'     => $deliveryAdress->address2,
                        'address3'     => null,
                        'country_code' => $toCountry,
                        'postcode'     => $deliveryAdress->postcode,
                        'city'         => $deliveryAdress->city,
                        'pro'          => ($deliveryAdress->company == '') ? 0 : 1,
                    ),
                'dropoff_to'    => ($is_dropoff) ?
                    array(
                        'dropoff_location_id' => $dropoffPoint['dp_id'],
                        'company'             => $dropoffPoint['dp_company'],
                        'name'                => $dropoffPoint['dp_name'],
                        'phone'               => Configuration::get('BLOCKCONTACTINFOS_PHONE'),
                        'email'               => null,
                        'address1'            => $dropoffPoint['dp_address1'],
                        'address2'            => $dropoffPoint['dp_address2'],
                        'address3'            => null,
                        'country_code'        => $dropoffPoint['dp_country'],
                        'postcode'            => $dropoffPoint['dp_postcode'],
                        'city'                => $dropoffPoint['dp_city'],
                    ) : null,
                'parcels'       =>
                    array(
                        0 =>
                            array(
                                'number' => 1,
                                'weight' => 1,
                                'x'      => 40,
                                'y'      => 30,
                                'z'      => 20,
                            ),
                    ),
                'shipment_date' => date('Y-m-d'),
                'unit'          => 'fr',
                'selection'     => 'all',
                'reason'        => 'Commercial',
                'content'       => 'Default content',
                'labelFormat'   => 'PDF',
                'cart_id'       => $cart_id
            );

            $smartyVariables = array(
                'simple_link'        => $this->_path,
                'reference'          => " ",
                'suivi'              => $this->l('Ship', 'upela'),
                'iconBtn'            => "icon-plus-sign",
                'link_suivi'         => (($this->isConnected) || ($countOrders > 0)) ?
                    $this->context->link->getAdminLink('AdminModules').
                    '&configure='.$this->name.'&tab_module='.$this->tab.
                    '&module_name='.$this->name.'&sendorder=1' :
                    $this->context->link->getAdminLink('AdminModules').
                    '&configure='.$this->name.'&tab_module='.$this->tab.
                    '&module_name='.$this->name,
                'img15'              => 'views/img/add.gif',
                'target'             => (($this->isConnected) || ($countOrders > 0)) ? '_blank' : '',
                'upela_ship_content' => Configuration::get('UPELA_SHIP_CONTENT'),
                'upela_weight'       => Configuration::get('UPELA_SHIP_WEIGHT'),
                'upela_length'       => Configuration::get('UPELA_SHIP_LENGTH'),
                'upela_width'        => Configuration::get('UPELA_SHIP_WIDTH'),
                'upela_height'       => Configuration::get('UPELA_SHIP_HEIGHT'),
                'jsonShipInfo'       => json_encode($infoShipment),
                'paymentInfos'       => $paymentInfo,
                'upela_param_link'   => (($this->isConnected) || ($countOrders > 0)) ? $this->api->getUrlparameter(
                ) : '',
                'is_connected'       => ($this->isConnected || ($countOrders > 0)),
                'waybill_url'        => false
            );

            // get information to know if Upela as send this order

            $upelaInfo = $this->carriers->getUpelaOrderByCartId($cart_id);

            if ((count($upelaInfo) > 0) && ($upelaInfo['waybill_url'] != '')) {
                $smartyVariables['waybill_url'] = $upelaInfo['waybill_url'];
            }

            $this->context->smarty->assign($smartyVariables);

            if (version_compare(_PS_VERSION_, '1.6', '<')) {
                $expedition = $this->display(__FILE__, 'expedition15.tpl');
            } else {
                $expedition = $this->display(__FILE__, 'expedition.tpl');
            }

            return $expedition;
        } else {
            return '';
        }
    }

    /**
     * @param $params
     * @throws PrestaShopDatabaseException
     */
    public function hookDisplayOrderConfirmation($params)
    {
        $order = $params['order'];
        $cart_id = $order->id_cart;
        $carrier_id = $order->id_carrier;
        $service = $this->carriers->getCarriersServices($carrier_id, true);

        if ($service['is_dropoff_point'] == true) {
            if (isset($this->context->cookie->dropoffLocation)) {
                $location = json_decode(str_replace('\\','',$this->context->cookie->dropoffLocation ));
                $data = array(
                    'id_cart_ps'  => (int)$cart_id,
                    'dp_company'  => pSQL(trim($location->name)),
                    'dp_name'     => pSQL(trim($location->name)),
                    'dp_address1' => pSQL(trim($location->address1)),
                    'dp_address2' => pSQL(trim($location->address2)),
                    'dp_address3' => '',
                    'dp_postcode' => pSQL(trim($location->postcode)),
                    'dp_city'     => pSQL(trim($location->city)),
                    'dp_country'  => pSQL($location->country_code),
                    'dp_id'       => pSQL($location->dropoff_location_id)
                );

                Db::getInstance()
                  ->insert('upela_order_points', $data);
            }
        }
    }

    /**
     * @return string
     */
    public function getContent()
    {
        $carrier_select = false;
        $param_select = false;
        $upela_login = false;
        $stores = array();
        $user = '';
        $info = array();

        if (Tools::isSubmit('processChangeMode')) {
            $this->setMode(
                (Tools::getValue('upela_mode')) ?
                    UpelaApi::API_MODE_PROD :
                    UpelaApi::API_MODE_TEST
            );
            $this->context->smarty->assign(
                array(
                    'postSuccess' =>
                        $this->l('Activated mode: ').((Tools::getValue('upela_mode')) ?
                            'Production ' :
                            'Test ')
                )
            );
        }

        if (Tools::isSubmit('processAccountCreation')) {
            $this->postValidation();
            if (!count($this->postErrors)) {
                $this->postProcess();

                if (count($this->postErrors)) {
                    $this->context->smarty->assign(array('postErrors' => $this->postErrors));

                    return $this->displayRegistrationForm2();
                }
            } else {
                $this->context->smarty->assign(array('postErrors' => $this->postErrors));

                return $this->displayRegistrationForm2();
            }
            if (count($this->postSuccess)) {
                $this->context->smarty->assign(array('postSuccess' => $this->postSuccess));
            }
        }

        if (Tools::isSubmit('processStoreCreation')) {
            $this->postValidation();
            if (!count($this->postErrors)) {
                $this->postProcess();
                if (count($this->postErrors)) {
                    $this->context->smarty->assign(array('postErrors' => $this->postErrors));

                    return $this->displayCreateStoreForm2();
                }
            } else {
                $this->context->smarty->assign(array('postErrors' => $this->postErrors));

                return $this->displayCreateStoreForm2();
            }
            if (count($this->postSuccess)) {
                $this->context->smarty->assign(array('postSuccess' => $this->postSuccess));
            }
        }

        /* Login user */
        if (Tools::isSubmit('processLogin')) {
            $this->processLoginSubmitted();
            if (!count($this->postErrors)) {
                $upela_login = false;
                $param_select = false;
            } else {
                $upela_login = true;
                $param_select = true;
                $this->context->smarty->assign(array('postErrors' => $this->postErrors));
            }
            if (count($this->postSuccess)) {
                $upela_login = true;
                $param_select = true;
                $this->context->smarty->assign(array('postSuccess' => $this->postSuccess));
            }
        }

        /* Registration Form */
        if (Tools::isSubmit('register')) {
            return $this->displayRegistrationForm2();
        }

        /* Store Form */
        if (Tools::isSubmit('newstore')) {
            return $this->displayCreateStoreForm2();
        }

        if (Tools::isSubmit('updateLogin')) {
            $this->dumpConfigurations();
            $this->isConnected = false;
            //return $this->displayLoginForm(true);
        }

        if (Tools::isSubmit('updateparameters')) {
            Configuration::updateValue('UPELA_SHIP_CONTENT', Tools::getValue('ship_content'));
            Configuration::updateValue('UPELA_SHIP_WEIGHT', Tools::getValue('upela_weight'));
            Configuration::updateValue('UPELA_SHIP_LENGTH', Tools::getValue('upela_length'));
            Configuration::updateValue('UPELA_SHIP_WIDTH', Tools::getValue('upela_width'));
            Configuration::updateValue('UPELA_SHIP_HEIGHT', Tools::getValue('upela_height'));
            $this->context->smarty->assign(array('postSuccess' => $this->l('Parameters updates!')));
            $param_select = true;
        }

        if (empty(Configuration::get('PS_SHOP_COUNTRY_ID'))) {
            $defaultCountry = Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'));
        } else {
            $defaultCountry = Country::getIsoById(Configuration::get('PS_SHOP_COUNTRY_ID'));
        }

        if (Tools::isSubmit('updatestore')) {
            Configuration::updateValue('UPELA_STORE_FIRSTNAME', Tools::getValue('store_firstname'));
            Configuration::updateValue('UPELA_STORE_LASTNAME', Tools::getValue('store_lastname'));
            Configuration::updateValue('UPELA_STORE_EMAIL', Tools::getValue('store_email'));
            Configuration::updateValue('UPELA_STORE_NAME', Tools::getValue('store_name'));
            Configuration::updateValue('UPELA_STORE_PHONE', Tools::getValue('store_phone'));
            Configuration::updateValue('UPELA_STORE_COUNTRY', $defaultCountry);
            Configuration::updateValue('UPELA_STORE_ADDRESS1', Tools::getValue('store_address1'));
            Configuration::updateValue('UPELA_STORE_ADDRESS2', Tools::getValue('store_address2'));
            Configuration::updateValue('UPELA_STORE_ADDRESS3', Tools::getValue('store_address3'));
            Configuration::updateValue('UPELA_STORE_CITY', Tools::getValue('store_city'));
            Configuration::updateValue('UPELA_STORE_ZIPCODE', Tools::getValue('store_zipcode'));
            Configuration::updateValue('UPELA_STORE_BUSINESS', '1');
            Configuration::updateValue('UPELA_STORE_DEFINE', true);

            $this->context->smarty->assign(array('postSuccess' => $this->l('Store update!')));
            $param_select = true;
        }

        if (Tools::isSubmit('update_carriers')) {
            $offers1 = (array)Tools::getValue('offers1');
            $offers2 = (array)Tools::getValue('offers2');
            $offers3 = (array)Tools::getValue('offers3');

            $offers = array_merge($offers1, $offers2, $offers3);

            if ($this->carriers->createCarriers($offers)) {
                $this->context->smarty->assign(array('postSuccess' => $this->l('Carriers updates!')));
            } else {
                $this->context->smarty->assign(array('postErrors' => $this->l('Carriers updates issue!')));
            }

            $carrier_select = true;
        }

        if (Tools::isSubmit('login')) {
            $param_select = true;
        }

        $storeExists = 0;

        if ($this->isConnected) {
            $user = $this->getUserConnected();

            $stores = $this->api->getStores($user['id']);

            if (isset($stores)) {
                foreach ($stores as $store) {
                    if (isset($store['type']) && ($store['type'] == "prestashop")) {
                        $storeExists++;
                    }
                }
            }
        } else {
            $info[] = $this->l('You must login to use Upela shipping!');
        }

        if (!Configuration::get('UPELA_STORE_DEFINE')) {
            $info[] = $this->l('You must update your store information in the settings!');
        }

        $zone = $this->upela_helper->getCountryZone($defaultCountry);

        $carriersListExpress = $this->carriers->getCarriersForTpl(
            $zone,
            'and is_express=1 and is_dropoff_point=0',
            false,
            Language::getLanguages(true, $this->context->shop->id)
        );
        $carriersListRelay = $this->carriers->getCarriersForTpl(
            $zone,
            'and is_dropoff_point=1 and is_express=0',
            false,
            Language::getLanguages(true, $this->context->shop->id)
        );
        $carriersListOthers = $this->carriers->getCarriersForTpl(
            $zone,
            'and is_dropoff_point=0 and is_express=0',
            false,
            Language::getLanguages(true, $this->context->shop->id)
        );

        if (!file_exists(_PS_ROOT_DIR_.'/controllers/admin/AdminCarrierWizardController.php')) {
            $carrierControllerUrl = $this->context->link->getAdminLink('AdminCarriers').'&updatecarrier';
        } else {
            $carrierControllerUrl = $this->context->link->getAdminLink('AdminCarrierWizard');
        }

        $paymentInfo = $this->api->getPayments();

        if ($paymentInfo['info']) {
            if ($paymentInfo['method'] == 'CB') {
                $paymentInfo['method'] = $this->l('Credit card', 'upela');
            }

            if (!$paymentInfo['avalaible']) {
                $info[] = $this->l(
                    'You can not ship your orders directly. You must switch your account to SEPA payment or credit your account!',
                    'upela'
                );
            }
        } else {
            $info[] = $this->l(
                'Payment informations are not avalaible, you can not ship from PrestaShop!',
                'upela'
            );
        }

        $this->context->smarty->assign(
            array(
                'upela_register_link'            => $this->context->link->getAdminLink('AdminModules').
                    '&configure='.$this->name.'&tab_module='.$this->tab.
                    '&module_name='.$this->name.'&register=1',
                'upela_parameters_link'          => $this->context->link->getAdminLink('AdminModules').
                    '&configure='.$this->name.'&tab_module='.$this->tab.
                    '&module_name='.$this->name.'&updateparameters=1',
                'upela_store_link'               => $this->context->link->getAdminLink('AdminModules').
                    '&configure='.$this->name.'&tab_module='.$this->tab.
                    '&module_name='.$this->name.'&newstore=1',
                'upela_store_update_link'        => $this->context->link->getAdminLink('AdminModules').
                    '&configure='.$this->name.'&tab_module='.$this->tab.
                    '&module_name='.$this->name.'&updatestore=1',
                'upela_login_link'               => $this->context->link->getAdminLink('AdminModules').
                    '&configure='.$this->name.'&tab_module='.$this->tab.
                    '&module_name='.$this->name.'&login=1',
                'upela_update_carrier_link'      => $this->context->link->getAdminLink('AdminModules').
                    '&configure='.$this->name.'&tab_module='.$this->tab.
                    '&module_name='.$this->name.'&update_carriers=1',
                'upela_user_link'                => ($this->isConnected) ? $this->api->getUrlconnection() : '',
                'upela_param_link'               => ($this->isConnected) ? $this->api->getUrlparameter() : '',
                'upela_create_account_post_link' => $this->context->link->getAdminLink('AdminModules').
                    '&configure='.$this->name.'&tab_module='.$this->tab.
                    '&module_name='.$this->name,
                'upela_link'                     => 'https://www.upela.com',
                'upela_link_support'             => 'https://addons.prestashop.com/fr/contactez-nous?id_product=26804',

                'errors'               => $this->errors,
                '_path'                => $this->_path,
                'upela_user_connected' => $this->isConnected,
                'upela_firstname'      => ($this->isConnected) ? $user['firstname'] : '',
                'upela_first'          => ($this->isConnected) ? $user['name'] : '',
                'upela_user_email'     => ($this->isConnected) ? $user['login'] : '',
                'upela_login'          => $upela_login,
                'upela_nbstores'       => count($stores),
                'upela_storeexsists'   => $storeExists,
                'country'              => Country::getIsoById(Configuration::get('PS_SHOP_COUNTRY_ID')),
                'upela_ship_content'   => Configuration::get('UPELA_SHIP_CONTENT'),
                'upela_weight'         => Configuration::get('UPELA_SHIP_WEIGHT'),
                'upela_length'         => Configuration::get('UPELA_SHIP_LENGTH'),
                'upela_width'          => Configuration::get('UPELA_SHIP_WIDTH'),
                'upela_height'         => Configuration::get('UPELA_SHIP_HEIGHT'),
                'carriersListExpress'  => $carriersListExpress,
                'carriersListRelay'    => $carriersListRelay,
                'carriersListOthers'   => $carriersListOthers,
                'isnotpsready'         => getenv('PLATEFORM') != 'PSREADY',
                'carrierControllerUrl' => $carrierControllerUrl,
                'carrier_select'       => $carrier_select,
                'param_select'         => $param_select,
                'paymentInfos'         => $paymentInfo,
                'postInfos'            => $info,
                'tpl_params'           => $this->getTemplatePath(
                    'views/templates/admin/configure/conf_parameters.tpl'
                ),
                'tpl_guide'            => $this->getTemplatePath(
                    'views/templates/admin/configure/conf_guide.tpl'
                ),
                'tpl_carriers'         => $this->getTemplatePath(
                    'views/templates/admin/configure/conf_carriers.tpl'
                ),
                'tpl_home'             => $this->getTemplatePath(
                    'views/templates/admin/configure/conf_home.tpl'
                ),
                'tpl_contact'          => $this->getTemplatePath(
                    'views/templates/admin/configure/conf_contact.tpl'
                ),
                'storeInfos'           => $this->getStoreFormValuesContent(),
            )
        );

        $this->context->controller->addCSS($this->_path.'views/css/back.css');
        $this->context->smarty->assign(array('upela_login' => Tools::isSubmit('login')));
        $fields_form = array();

        $fields_form[0]['form'] = array(
            'input'  => array(
                array(
                    'type'    => 'switch',
                    'label'   => $this->l('Live mode'),
                    'name'    => 'upela_mode',
                    'is_bool' => true,
                    'values'  => array(
                        array(
                            'id'    => 'active_on_prod',
                            'value' => true
                        ),
                        array(
                            'id'    => 'active_off_ca',
                            'value' => '0'
                        )
                    )
                )
            ),
            'submit' => array(
                'title' => $this->l('Change Mode'),
                'class' => 'btn btn-default pull-right'
            )

        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        //$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->currentIndex = $this->context->link->getAdminLink(
                'AdminModules',
                false
            ).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = false;        // false -> remove toolbar
        $helper->toolbar_scroll = false;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'processChangeMode';
        $helper->show_cancel_button = false;

        // Load current value
        $helper->tpl_vars = array(
            'fields_value' => array(
                'upela_mode' => $this->mode == UpelaApi::API_MODE_PROD
            )
        );
        $this->context->smarty->assign('modeform', $helper->generateForm($fields_form));

        return $this->context->smarty->fetch(
            $this->getLocalPath().'views/templates/admin/configure/guest_configure.tpl'
        );
    }

    private function postValidation()
    {
        if (Tools::isSubmit('processStoreCreation')) {
            $values = $this->getStoreFormValues();

            if (!Validate::isName($values['firstname']) || empty($values['firstname'])) {
                $this->postErrors[] = $this->l('Firstname is required');
            }

            if (!Validate::isName($values['lastname']) || empty($values['lastname'])) {
                $this->postErrors[] = $this->l('Lastname is required');
            }
            if (!Validate::isEmail($values['email'])) {
                $this->postErrors[] = $this->l('Valid email is required');
            }

            if (!Validate::isPhoneNumber($values['phone']) || empty($values['phone'])) {
                $this->postErrors[] = $this->l('Incorrect phone number');
            }

            //            if (empty($values['webservicekey'])) {
            //                $this->postErrors[] = $this->l('Webservice key is required');
            //            }

            if (empty($values['store_name'])) {
                $this->postErrors[] = $this->l('Store name is required');
            }

            if (!Validate::isAddress($values['store_address_1']) || empty($values['store_address_1'])) {
                $this->postErrors[] = $this->l('Incorrect address');
            }

            if ($values['store_address_2'] && !Validate::isAddress($values['store_address_2'])) {
                $this->postErrors[] = $this->l('Incorrect address');
            }

            if (!Validate::isZipCodeFormat($values['store_zipcode'])) {
                $this->postErrors[] = $this->l('Incorrect address');
            }

            if (!Validate::isCityName($values['store_city']) || empty($values['store_city'])) {
                $this->postErrors[] = $this->l('Incorrect city name');
            }
        } elseif (Tools::isSubmit('processAccountCreation')) {
            $values = $this->getAccountFormValues();

            if (!Validate::isName($values['firstname']) || empty($values['firstname'])) {
                $this->postErrors[] = $this->l('Firstname is required');
            }

            if (!Validate::isName($values['lastname']) || empty($values['lastname'])) {
                $this->postErrors[] = $this->l('Lastname is required');
            }
            if (!Validate::isEmail($values['email'])) {
                $this->postErrors[] = $this->l('Valid email is required');
            }

            if (!Validate::isPhoneNumber($values['phone']) || empty($values['phone'])) {
                $this->postErrors[] = $this->l('Incorrect phone number');
            }

            if (!Validate::isPasswd($values['password']) || empty($values['password'])) {
                $this->postErrors[] = $this->l('Incorrect password');
            }

            if ($values['password'] != $values['password_check']) {
                $this->postErrors[] = $this->l('Password check failed');
            }

            //            if (empty($values['webservicekey'])) {
            //                $this->postErrors[] = $this->l('Webservice key is required');
            //            }

            if (empty($values['company_name'])) {
                $this->postErrors[] = $this->l('Company name is required');
            }

            if (!Validate::isAddress($values['company_address_1']) || empty($values['company_address_1'])) {
                $this->postErrors[] = $this->l('Incorrect address');
            }

            if ($values['company_address_2'] && !Validate::isAddress($values['company_address_2'])) {
                $this->postErrors[] = $this->l('Incorrect address');
            }

            if (!Validate::isZipCodeFormat($values['company_zipcode'])) {
                $this->postErrors[] = $this->l('Incorrect address');
            }

            if (!Validate::isCityName($values['company_city']) || empty($values['company_city'])) {
                $this->postErrors[] = $this->l('Incorrect city name');
            }

            if (empty($values['store_name'])) {
                $this->postErrors[] = $this->l('Store name is required');
            }

            if (!Validate::isAddress($values['store_address_1']) || empty($values['store_address_1'])) {
                $this->postErrors[] = $this->l('Incorrect address');
            }

            if ($values['store_address_2'] && !Validate::isAddress($values['store_address_2'])) {
                $this->postErrors[] = $this->l('Incorrect address');
            }

            if (!Validate::isZipCodeFormat($values['store_zipcode'])) {
                $this->postErrors[] = $this->l('Incorrect address');
            }

            if (!Validate::isCityName($values['store_city']) || empty($values['store_city'])) {
                $this->postErrors[] = $this->l('Incorrect city name');
            }

            if (empty($values['company_siret']) && empty($values['company_vat'])) {
                $this->postErrors[] = $this->l('Company registration code require', 'upela');
            }
        }
    }

    public function getStoreFormValues()
    {
        if (!Tools::isSubmit('processStoreCreation')) {
            if (empty(Configuration::get('PS_SHOP_COUNTRY_ID'))) {
                $defaultCountry = Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'));
            } else {
                $defaultCountry = Country::getIsoById(Configuration::get('PS_SHOP_COUNTRY_ID'));
            }

            $return = array(
                'firstname'       => $this->context->employee->firstname,
                'lastname'        => $this->context->employee->lastname,
                'email'           => $this->context->employee->email,
                'store_name'      => Configuration::get('PS_SHOP_NAME'),
                'phone'           => Configuration::get('BLOCKCONTACTINFOS_PHONE'),
                'store_country'   => $defaultCountry,
                'store_address_1' => Configuration::get('PS_SHOP_ADDR1'),
                'store_address_2' => Configuration::get('PS_SHOP_ADDR2'),
                'store_address_3' => '',
                'store_city'      => Configuration::get('PS_SHOP_CITY'),
                'store_zipcode'   => Configuration::get('PS_SHOP_CODE'),
                'webservicekey'   => '',
                'store_business'  => true,
            );
        } else {
            $return = array(
                'firstname'       => Tools::getValue('firstname'),
                'lastname'        => Tools::getValue('lastname'),
                'email'           => Tools::getValue('email'),
                'store_name'      => Tools::getValue('store_name'),
                'phone'           => Tools::getValue('phone'),
                'store_country'   => Tools::getValue('store_country'),
                'store_address_1' => Tools::getValue('store_address_1'),
                'store_address_2' => Tools::getValue('store_address_2'),
                'store_address_3' => Tools::getValue('store_address_3'),
                'store_city'      => Tools::getValue('store_city'),
                'store_zipcode'   => Tools::getValue('store_zipcode'),
                'webservicekey'   => $this->getWebServiceKey(),
                'store_business'  => Tools::getValue('store_business'),
            );
        }

        return $return;
    }

    /**
     * Create the WebserviceKey
     * @return string
     */
    private function getWebServiceKey()
    {
        $wskey = Configuration::get('UPELA_WEBSERVICE_KEY');

        $webservice_key = new WebserviceKey();

        if (($wskey != '') && $webservice_key->keyExists($wskey)) {
            return $wskey;
        } else {
            Configuration::updateValue('PS_WEBSERVICE', 1);
            Configuration::updateValue('PS_WEBSERVICE_CGI_HOST', 1);

            Tools::generateHtaccess();

            $key = Tools::passwdGen(32);

            $webservice_key->key = $key;
            $webservice_key->description = 'Upela';

            $webservice_key->add();
            WebserviceKey::setPermissionForAccount($webservice_key->id, $this->upela_helper->getPermissions());

            Configuration::updateValue('UPELA_WEBSERVICE_KEY', $key);

            return $key;
        }
    }

    public function getAccountFormValues()
    {
        if (!Tools::isSubmit('processAccountCreation')) {
            if (empty(Configuration::get('PS_SHOP_COUNTRY_ID'))) {
                $defaultCountry = Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'));
            } else {
                $defaultCountry = Country::getIsoById(Configuration::get('PS_SHOP_COUNTRY_ID'));
            }

            $return = array(
                'firstname'       => $this->context->employee->firstname,
                'lastname'        => $this->context->employee->lastname,
                'email'           => $this->context->employee->email,
                'store_name'      => Configuration::get('PS_SHOP_NAME'),
                'phone'           => Configuration::get('BLOCKCONTACTINFOS_PHONE'),
                'store_country'   => $defaultCountry,
                'store_address_1' => Configuration::get('PS_SHOP_ADDR1'),
                'store_address_2' => Configuration::get('PS_SHOP_ADDR2'),
                'store_address_3' => '',
                'store_city'      => Configuration::get('PS_SHOP_CITY'),
                'store_zipcode'   => Configuration::get('PS_SHOP_CODE'),
                'store_business'  => true,
                'webservicekey'   => '',

                'company_name'      => Configuration::get('PS_SHOP_NAME'),
                'company_country'   => $defaultCountry,
                'company_address_1' => Configuration::get('PS_SHOP_ADDR1'),
                'company_address_2' => Configuration::get('PS_SHOP_ADDR2'),
                'company_address_3' => '',
                'company_city'      => Configuration::get('PS_SHOP_CITY'),
                'company_zipcode'   => Configuration::get('PS_SHOP_CODE'),
                'company_business'  => true,
                'company_vat'       => '',
                'company_siret'     => '',
            );
        } else {
            $return = array(
                'firstname'       => Tools::getValue('firstname'),
                'lastname'        => Tools::getValue('lastname'),
                'email'           => Tools::getValue('email'),
                'store_name'      => Tools::getValue('store_name'),
                'phone'           => Tools::getValue('phone'),
                'password'        => Tools::getValue('password'),
                'password_check'  => Tools::getValue('passwordcheck'),
                'store_country'   => Tools::getValue('store_country'),
                'store_address_1' => Tools::getValue('store_address_1'),
                'store_address_2' => Tools::getValue('store_address_2'),
                'store_address_3' => Tools::getValue('store_address_3'),
                'store_city'      => Tools::getValue('store_city'),
                'store_zipcode'   => Tools::getValue('store_zipcode'),
                'webservicekey'   => $this->getWebServiceKey(),
                'store_business'  => Tools::getValue('store_business'),
                'company_vat'     => Tools::getValue('company_vat'),
                'company_siret'   => Tools::getValue('company_siret'),

                'company_name'      => Tools::getValue('company_name'),
                'company_country'   => Tools::getValue('company_country'),
                'company_address_1' => Tools::getValue('company_address_1'),
                'company_address_2' => Tools::getValue('company_address_2'),
                'company_address_3' => Tools::getValue('company_address_3'),
                'company_city'      => Tools::getValue('company_city'),
                'company_zipcode'   => Tools::getValue('company_zipcode'),
                'company_business'  => Tools::getValue('company_business'),
            );
        }

        return $return;
    }

    private function postProcess()
    {
        if (Tools::isSubmit('processStoreCreation')) {
            $orderStates = OrderState::getOrderStates($this->context->language->id);
            $statusList = array();

            foreach ($orderStates as $orderState) {
                $statusList[$orderState['id_order_state']] = $orderState['name'];
            }

            $user = $this->getUserConnected();
            $values = $this->getStoreFormValues();

            $data = array(
                'user_id'         => $user['id'],
                'name'            => $values['store_name'],
                'module_url'      => Configuration::get('PS_SHOP_DOMAIN'),
                'type'            => 'prestashop',
                'username'        => $user['login'],
                'apikey'          => $values['webservicekey'],
                'statusList'      => Tools::jsonEncode($statusList),
                'store_country'   => $values['store_country'],
                'store_name'      => $values['store_name'],
                'company_name'    => $values['store_name'],
                'lastname'        => $values['lastname'],
                'firstname'       => $values['firstname'],
                'store_address_1' => $values['store_address_1'],
                'store_address_2' => $values['store_address_2'],
                'store_address_3' => $values['store_address_3'],
                'store_zipcode'   => $values['store_zipcode'],
                'store_city'      => $values['store_city'],
                'phone'           => $values['phone'],
                'email'           => $values['email'],
                'pro'             => $values['store_business'],
            );

            $retStore = $this->api->createStore($data);

            if (!$retStore) {
                $this->postErrors[] = $this->l('Error when creating the store!', 'upela');
            } else {
                $this->postSuccess[] = $this->l('Store created !');

                if (!Configuration::get('UPELA_STORE_DEFINE')) {
                    Configuration::updateValue('UPELA_STORE_FIRSTNAME', $values['firstname']);
                    Configuration::updateValue('UPELA_STORE_LASTNAME', $values['lastname']);
                    Configuration::updateValue('UPELA_STORE_EMAIL', $values['email']);
                    Configuration::updateValue('UPELA_STORE_NAME', $values['store_name']);
                    Configuration::updateValue('UPELA_STORE_PHONE', $values['phone']);
                    Configuration::updateValue('UPELA_STORE_COUNTRY', $values['store_country']);
                    Configuration::updateValue('UPELA_STORE_ADDRESS1', $values['store_address_1']);
                    Configuration::updateValue('UPELA_STORE_ADDRESS2', $values['store_address_2']);
                    Configuration::updateValue('UPELA_STORE_ADDRESS3', $values['store_address_3']);
                    Configuration::updateValue('UPELA_STORE_CITY', $values['store_city']);
                    Configuration::updateValue('UPELA_STORE_ZIPCODE', $values['store_zipcode']);
                    Configuration::updateValue('UPELA_STORE_BUSINESS', $values['store_business']);
                    Configuration::updateValue('UPELA_STORE_DEFINE', true);
                }
            }
        } elseif (Tools::isSubmit('processAccountCreation')) {
            $values = $this->getAccountFormValues();

            if ($this->api->getUserExists($values['email'])) {
                $this->postErrors[] = $this->l(
                    'Error: Email already exists! Please connect or use another email.',
                    'upela'
                );

                return false;
            }

            $data = array(
                'login'     => $values['email'],
                'pseudo'    => $values['firstname'].' '.$values['lastname'],
                'email'     => $values['email'],
                'module'    => 'prestashop',
                'password'  => $values['password'],
                'lastname'  => $values['firstname'].' '.$values['lastname'],
                'firstname' => $values['firstname'],
                'company'   => $values['company_name'],
                'country'   => $values['company_country'],
                'address_1' => $values['company_address_1'],
                'address_2' => $values['company_address_2'],
                'address_3' => $values['company_address_3'],
                'zipcode'   => $values['company_zipcode'],
                'city'      => $values['company_city'],
                'phone'     => $values['phone'],
                'immat'     => $values['company_vat'],
                'siret'     => $values['company_siret'],
                'pro'       => $values['company_business'],
            );

            $retUser = $this->api->createAccount($data);

            if (!$retUser) {
                $this->quickLog(
                    array(
                        'User Creation response : ',
                        //print_r($retUser)
                    )
                );
                $this->postErrors[] = $this->l('Error when creating the user!', 'upela');

                return false;
            } else {
                $this->postSuccess[] = $this->l('Welcome to Upela, your account has been created!');

                $user = $this->api->getUserId($values['email'], $values['password']);

                if (!$user['success']) {
                    $this->quickLog(
                        array(
                            'User Creation response : ',
                            $user
                        )
                    );
                    $this->postErrors[] = $this->l('Error user connection!', 'upela');

                    return false;
                } else {
                    $this->isConnected = true;
                    $this->saveUser();
                }
            }

            $orderStates = OrderState::getOrderStates($this->context->language->id);
            $statusList = array();

            foreach ($orderStates as $orderState) {
                $statusList[$orderState['id_order_state']] = $orderState['name'];
            }

            $data = array(
                'user_id'         => $retUser['user_id'],
                'name'            => $values['store_name'],
                'module_url'      => Configuration::get('PS_SHOP_DOMAIN'),
                'type'            => 'prestashop',
                'username'        => $values['email'],
                'apikey'          => $values['webservicekey'],
                'statusList'      => Tools::jsonEncode($statusList),
                'store_country'   => $values['store_country'],
                'store_name'      => $values['store_name'],
                'company_name'    => $values['store_name'],
                'lastname'        => $values['lastname'],
                'firstname'       => $values['firstname'],
                'store_address_1' => $values['store_address_1'],
                'store_address_2' => $values['store_address_2'],
                'store_address_3' => $values['store_address_3'],
                'store_zipcode'   => $values['store_zipcode'],
                'store_city'      => $values['store_city'],
                'phone'           => $values['phone'],
                'email'           => $values['email'],
                'pro'             => $values['store_business'],
            );

            $retStore = $this->api->createStore($data);

            if (!$retStore) {
                $this->postErrors[] = $this->l('Error when creating the store!', 'upela');
            } else {
                $this->postSuccess[] = $this->l('Store created !');
                if (!Configuration::get('UPELA_STORE_DEFINE')) {
                    Configuration::updateValue('UPELA_STORE_FIRSTNAME', $values['firstname']);
                    Configuration::updateValue('UPELA_STORE_LASTNAME', $values['lastname']);
                    Configuration::updateValue('UPELA_STORE_EMAIL', $values['email']);
                    Configuration::updateValue('UPELA_STORE_NAME', $values['store_name']);
                    Configuration::updateValue('UPELA_STORE_PHONE', $values['phone']);
                    Configuration::updateValue('UPELA_STORE_COUNTRY', $values['store_country']);
                    Configuration::updateValue('UPELA_STORE_ADDRESS1', $values['store_address_1']);
                    Configuration::updateValue('UPELA_STORE_ADDRESS2', $values['store_address_2']);
                    Configuration::updateValue('UPELA_STORE_ADDRESS3', $values['store_address_3']);
                    Configuration::updateValue('UPELA_STORE_CITY', $values['store_city']);
                    Configuration::updateValue('UPELA_STORE_ZIPCODE', $values['store_zipcode']);
                    Configuration::updateValue('UPELA_STORE_BUSINESS', $values['store_business']);
                    Configuration::updateValue('UPELA_STORE_DEFINE', true);
                }
            }
        }
    }

    /**
     * @param     $msg
     * @param int $level
     */
    private function quickLog($msg, $level = 1)
    {
        if (version_compare(_PS_VERSION_, '1.6.0.0', '>')) {
            PrestaShopLogger::addLog(
                'UPELA_LOG: '.strip_tags(print_r($msg, true)),
                $level,
                null,
                'upela'
            );
        } else {
            Logger::addLog('UPELA_LOG: '.strip_tags(print_r($msg, true)), $level, null, 'upela');
        }
    }

    /**
     * @return bool
     */
    private function saveUser()
    {
        return (bool)Configuration::updateValue('UPELA_USER_LOGIN', $this->api->getUser()) &&
            (bool)Configuration::updateValue('UPELA_USER_PASSWORD', $this->api->getPassword()) &&
            (bool)Configuration::updateValue('UPELA_USER_ID', $this->api->getId()) &&
            (bool)Configuration::updateValue('UPELA_USER_NAME', $this->api->getName());
    }

    private function displayRegistrationForm2()
    {
        $fields_form = array();

        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Create your account in less than 2 minutes'),
                'image' => '../modules/'.$this->name.'/views/img/logo-2.png'

            ),
            'tabs'   => array(
                'account' => $this->l('Your account'),
                'company' => $this->l('Your company'),
                'store'   => $this->l('Your store')
            ),

            'input'  => array(
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'firstname',
                    'label'    => $this->l('Firstname'),
                    'required' => true,
                    'tab'      => 'account'
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'lastname',
                    'label'    => $this->l('Lastname'),
                    'required' => true,
                    'tab'      => 'account'
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'email',
                    'label'    => $this->l('Email'),
                    'required' => true,
                    'tab'      => 'account'
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'phone',
                    'label'    => $this->l('Phone'),
                    'required' => true,
                    'tab'      => 'account'
                ),
                array(
                    'col'      => 3,
                    'type'     => 'password',
                    'name'     => 'password',
                    'label'    => $this->l('Password'),
                    'required' => true,
                    'tab'      => 'account'
                ),
                array(
                    'col'      => 3,
                    'type'     => 'password',
                    'name'     => 'passwordcheck',
                    'label'    => $this->l('Confirm password'),
                    'required' => true,
                    'tab'      => 'account'
                ),

                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'company_name',
                    'label'    => $this->l('Company name'),
                    'required' => true,
                    'tab'      => 'company',
                ),
                array(
                    'type'    => 'switch',
                    'label'   => $this->l('Business address'),
                    'name'    => 'company_business',
                    'is_bool' => true,
                    'values'  => array(
                        array(
                            'id'    => 'active_on_ca',
                            'value' => true,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off_ca',
                            'value' => '0',
                            'label' => $this->l('No')
                        )
                    ),
                    'tab'     => 'company',
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'company_address_1',
                    'label'    => $this->l('Address 1'),
                    'required' => true,
                    'tab'      => 'company',
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'company_address_2',
                    'label'    => $this->l('Address 2'),
                    'required' => false,
                    'tab'      => 'company',
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'company_address_3',
                    'label'    => $this->l('Address 3'),
                    'required' => false,
                    'tab'      => 'company',
                ),
                array(
                    'col'      => 3,
                    'type'     => 'select',
                    'name'     => 'company_country',
                    'label'    => $this->l('Country'),
                    'required' => true,
                    'options'  => array(
                        'query' => Country::getCountries($this->context->language->id),
                        'id'    => 'iso_code',
                        'name'  => 'name'
                    ),
                    'tab'      => 'company',
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'company_zipcode',
                    'label'    => $this->l('ZIP Code'),
                    'required' => true,
                    'tab'      => 'company',
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'company_city',
                    'label'    => $this->l('City'),
                    'required' => true,
                    'tab'      => 'company',
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'company_siret',
                    'label'    => $this->l('SIRET'),
                    'required' => false,
                    'tab'      => 'company',
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'company_vat',
                    'label'    => $this->l('Company registration #'),
                    'required' => true,
                    'tab'      => 'company',
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'store_name',
                    'label'    => $this->l('Store name'),
                    'required' => true,
                    'tab'      => 'store',
                ),
                array(
                    'type'    => 'switch',
                    'label'   => $this->l('Business address'),
                    'name'    => 'store_business',
                    'is_bool' => true,
                    'values'  => array(
                        array(
                            'id'    => 'active_on_sba',
                            'value' => true,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off_sba',
                            'value' => '0',
                            'label' => $this->l('No')
                        )
                    ),
                    'tab'     => 'store',
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'store_address_1',
                    'label'    => $this->l('Address 1'),
                    'required' => true,
                    'tab'      => 'store',
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'store_address_2',
                    'label'    => $this->l('Address 2'),
                    'required' => false,
                    'tab'      => 'store',
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'store_address_3',
                    'label'    => $this->l('Address 3'),
                    'required' => false,
                    'tab'      => 'store',
                ),
                array(
                    'col'      => 3,
                    'type'     => 'select',
                    'name'     => 'store_country',
                    'label'    => $this->l('Country'),
                    'required' => true,
                    'options'  => array(
                        'query' => Country::getCountries($this->context->language->id),
                        'id'    => 'iso_code',
                        'name'  => 'name'
                    ),
                    'tab'      => 'store',
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'store_zipcode',
                    'label'    => $this->l('ZIP Code'),
                    'required' => true,
                    'tab'      => 'store',
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'store_city',
                    'label'    => $this->l('City'),
                    'required' => true,
                    'tab'      => 'store',
                ),
            ),
            'submit' => array(
                'title' => $this->l('   Create Account   ')
            )
        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->context->link->getAdminLink(
                'AdminModules',
                false
            ).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'processAccountCreation';
        $helper->show_cancel_button = true;

        // Load current value
        $helper->tpl_vars = array(
            'fields_value' => $this->getAccountFormValues(),
            'id_language'  => $this->context->language->id
        );
        $this->context->smarty->assign('createform', $helper->generateForm($fields_form));

        $output = $this->context->smarty->fetch(
            $this->local_path.'views/templates/admin/configure/create_form.tpl'
        );

        return $output;
    }

    /**
     * @return mixed
     */
    private function displayCreateStoreForm2()
    {
        $fields_form = array();

        $countries = Country::getCountries($this->context->language->id);
        $lCountries = array();

        foreach ($countries as $country) {
            $lCountries[] = array(
                'iso_code' => $country['iso_code'],
                'name'     => $country['name']
            );
        }

        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        // Init Fields form array
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Create your store in less than 1 minute'),
                'image' => '../modules/'.$this->name.'/views/img/logo-2.png'

            ),
            'input'  => array(
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'firstname',
                    'label'    => $this->l('Firstname'),
                    'required' => true,
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'lastname',
                    'label'    => $this->l('Lastname'),
                    'required' => true,
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'email',
                    'label'    => $this->l('Email'),
                    'required' => true,
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'phone',
                    'label'    => $this->l('Phone'),
                    'required' => true,
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'store_name',
                    'label'    => $this->l('Store name'),
                    'required' => true,
                ),
                array(
                    'type'    => 'switch',
                    'label'   => $this->l('Business address'),
                    'name'    => 'store_business',
                    'is_bool' => true,
                    'values'  => array(
                        array(
                            'id'    => 'active_on_sba',
                            'value' => true,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id'    => 'active_off_sba',
                            'value' => '0',
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'store_address_1',
                    'label'    => $this->l('Address 1'),
                    'required' => true,
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'store_address_2',
                    'label'    => $this->l('Address 2'),
                    'required' => false,
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'store_address_3',
                    'label'    => $this->l('Address 3'),
                    'required' => false,
                ),
                array(
                    'col'      => 3,
                    'type'     => 'select',
                    'name'     => 'store_country',
                    'label'    => $this->l('Country'),
                    'required' => true,
                    'options'  => array(
                        'query' => Country::getCountries($this->context->language->id),
                        'id'    => 'iso_code',
                        'name'  => 'name'
                    )
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'store_zipcode',
                    'label'    => $this->l('ZIP Code'),
                    'required' => true,
                ),
                array(
                    'col'      => 3,
                    'type'     => 'text',
                    'name'     => 'store_city',
                    'label'    => $this->l('City'),
                    'required' => true,
                ),
            ),
            'submit' => array(
                'title' => $this->l('   Create Store   ')
            )

        );

        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        //$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->currentIndex = $this->context->link->getAdminLink(
                'AdminModules',
                false
            ).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'processStoreCreation';
        $helper->show_cancel_button = true;

        // Load current value
        $helper->tpl_vars = array(
            'fields_value' => $this->getStoreFormValues(),
            //'languages' => $this->context->controller->getLanguages(),
            'id_language'  => $this->context->language->id
        );
        $this->context->smarty->assign('createform', $helper->generateForm($fields_form));

        $output = $this->context->smarty->fetch(
            $this->local_path.'views/templates/admin/configure/create_form.tpl'
        );

        return $output;
    }

    /**
     * @return string
     */
    private function processLoginSubmitted()
    {
        // Get vars
        $username = Tools::getValue('upela_email');
        $password = Tools::getValue('upela_password');

        // Connect user with credentials
        $userConnection = $this->api->getUserId($username, $password);

        if (!isset($userConnection['success'])) {
            $this->postErrors[] = $this->l('Error when log in to your account!', 'upela');

            return false;
        }

        if (!$userConnection['success']) {
            $this->postErrors[] = $this->l('Error when log in to your account!', 'upela').' '.
                $this->l('Message: ', 'upela').$userConnection['errors']['login'];

            return false;
        }

        if (!$this->saveUser()) {
            $this->postErrors[] = $this->l('Error when log in to your account!', 'upela');

            return false;
        }

        $this->getWebServiceKey();
        $this->isConnected = true;
        $this->postSuccess[] = $this->l('Connection success!');
    }

    public function getStoreFormValuesContent()
    {
        if ((Configuration::get('UPELA_STORE_DEFINE') == true)) {
            $return = array(
                'upela_store_firstname' => Configuration::get('UPELA_STORE_FIRSTNAME'),
                'upela_store_lastname'  => Configuration::get('UPELA_STORE_LASTNAME'),
                'upela_store_email'     => Configuration::get('UPELA_STORE_EMAIL'),
                'upela_store_name'      => Configuration::get('UPELA_STORE_NAME'),
                'upela_store_phone'     => Configuration::get('UPELA_STORE_PHONE'),
                'upela_store_country'   => Configuration::get('UPELA_STORE_COUNTRY'),
                'upela_store_address1'  => Configuration::get('UPELA_STORE_ADDRESS1'),
                'upela_store_address2'  => Configuration::get('UPELA_STORE_ADDRESS2'),
                'upela_store_address3'  => Configuration::get('UPELA_STORE_ADDRESS3'),
                'upela_store_city'      => Configuration::get('UPELA_STORE_CITY'),
                'upela_store_zipcode'   => Configuration::get('UPELA_STORE_ZIPCODE'),
                'upela_store_business'  => Configuration::get('UPELA_STORE_BUSINESS'),
            );
        } else {
            if (empty(Configuration::get('PS_SHOP_COUNTRY_ID'))) {
                $defaultCountry = Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'));
            } else {
                $defaultCountry = Country::getIsoById(Configuration::get('PS_SHOP_COUNTRY_ID'));
            }

            $return = array(
                'upela_store_firstname' => $this->context->employee->firstname,
                'upela_store_lastname'  => $this->context->employee->lastname,
                'upela_store_email'     => $this->context->employee->email,
                'upela_store_name'      => Configuration::get('PS_SHOP_NAME'),
                'upela_store_phone'     => Configuration::get('BLOCKCONTACTINFOS_PHONE'),
                'upela_store_country'   => $defaultCountry,
                'upela_store_address1'  => Configuration::get('PS_SHOP_ADDR1'),
                'upela_store_address2'  => Configuration::get('PS_SHOP_ADDR2'),
                'upela_store_address3'  => '',
                'upela_store_city'      => Configuration::get('PS_SHOP_CITY'),
                'upela_store_zipcode'   => Configuration::get('PS_SHOP_CODE'),
                'upela_store_business'  => true,
            );
        }

        return $return;
    }

    public function shipDirect(array $data)
    {
        $ret = $this->api->shipDirect($data);

        if (isset($ret['success']) && $ret['success']) {
            $datas = array(
                'id_cart_ps'      => (int)$data['cart_id'],
                'customer_id'     => (int)$ret['customer_id'],
                'shipment_id'     => (int)$ret['shipment_id'],
                'order_id'        => (int)$ret['order_id'],
                'carrier_code'    => pSQL($data['carrier_code']),
                'carrier_name'    => pSQL($data['carrier_code']),
                'waybill_code'    => pSQL($ret['waybill']['code']),
                'waybill_url'     => pSQL($ret['waybill']['url']),
                'tracking_number' => pSQL($ret['tracking_number']),
            );

            try {
                Db::getInstance()
                  ->insert('upela_orders', $datas);
            } catch (Exception $e) {
            }

            $order = Order::getByCartId($data['cart_id']);
            $order->setCurrentState(4);
            $order->setWsShippingNumber($ret['tracking_number']);
        }

        return json_encode($ret);
    }
}
