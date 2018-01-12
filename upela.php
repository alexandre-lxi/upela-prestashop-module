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
    const VALUE_CHEAPEST = 'cheapest';
    const VALUE_FASTEST = 'fastest';
    const MSG_DELAY = 'MSG_DELAY';
    const OPT_CHEAPEST_CARRIER_ID = 'UPELA_CHEAPEST_CARRIER_ID';
    const OPT_FASTEST_CARRIER_ID = 'UPELA_FASTEST_CARRIER_ID';
    const MSG_CARRIER_NAME_CHEAPEST = 'MSG_CARRIER_NAME_CHEAPEST';
    const MSG_CARRIER_NAME_FASTEST = 'MSG_CARRIER_NAME_FASTEST';
    protected $errors = array();
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
    private $mode = null;
    private $api = null;
    private $isConnected = false;
    /**
     * @var array
     */
    private $postErrors = array();
    private $postSuccess = array();

    /**
     * Upela constructor.
     */
    public function __construct() {
        $this->name = 'upela';
        $this->tab = 'shipping_logistics';
        $this->version = '1.1.0';
        $this->author = 'Upela';
        $this->need_instance = 1;
        $this->bootstrap = true;
        $this->module_key = '909a230701b42c01ef40630cccc65b82';

        parent::__construct();

        $this->initAPI();

        $this->displayName = $this->l('Upela');
        $this->description = $this->l('The best way to ship a parcel Save on shipping costs, never on service quality.');

        $this->ps_versions_compliancy = array('min' => '1.5', 'max' => _PS_VERSION_);

    }

    /**
     * API Initialization
     */
    private function initAPI() {
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
    private function loadMode() {
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
    private function hasAccountConnected() {
        return Configuration::get('UPELA_USER_LOGIN') &&
            Configuration::get('UPELA_USER_PASSWORD') &&
            Configuration::get('UPELA_USER_ID') &&
            Configuration::get('UPELA_USER_NAME');
    }

    /**
     * @return array
     */
    private function getUserConnected() {
        return array(
            'login' => Configuration::get('UPELA_USER_LOGIN'),
            'password' => Configuration::get('UPELA_USER_PASSWORD'),
            'id' => Configuration::get('UPELA_USER_ID'),
            'name' => Configuration::get('UPELA_USER_NAME')
        );
    }

    /**
     * @return UpelaAPI::MODE
     */
    public function getMode() {
        return $this->mode;
    }

    /**
     * @param null $mode
     */
    public function setMode($mode) {
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
    public function isAccountConnected() {
        return $this->isConnected;
    }

    /**
     * @return bool
     */
    public function install() {
        return parent::install() &&
            Configuration::updateValue('UPELA_API_MODE', UpelaApi::API_MODE_PROD) &&
            //$this->installTab('AdminUpela', Tab::getIdFromClassName('AdminParentShipping'), 'Upela') &&
            $this->registerHook('displayAdminOrder') &&
            $this->dumpConfigurations();

    }

    private function installDb(){

    }

    /**
     * @return bool
     */
    public function dumpConfigurations() {
        return Configuration::updateValue('UPELA_USER_LOGIN', '') &&
            Configuration::updateValue('UPELA_USER_PASSWORD', '') &&
            Configuration::updateValue('UPELA_USER_ID', '') &&
            Configuration::updateValue('UPELA_USER_NAME', '');
    }

    /**
     * @param $class_name
     * @param $id_parent
     * @param $name
     *
     * @return mixed
     */
    public function installTab($class_name, $id_parent, $name) {
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
    public function uninstall() {
        return parent::uninstall() &&
            // $this->uninstallTab('AdminUpela') &&
            $this->unregisterHook('displayAdminOrder') &&
            $this->removeConfig();
    }

    /**
     * @return bool
     */
    private function removeConfig() {
        Configuration::deleteByName('UPELA_USER_LOGIN');
        Configuration::deleteByName('UPELA_USER_PASSWORD');
        Configuration::deleteByName('UPELA_USER_ID');
        Configuration::deleteByName('UPELA_USER_NAME');
        Configuration::deleteByName('UPELA_API_MODE');

        return true;
    }

    /**
     * @param $class_name
     *
     * @return bool
     */
    public function uninstallTab($class_name) {
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
    public function getModulePath() {
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
        $smarty->assign('upelaBaseDir', _MODULE_DIR_ . '/upela/');
        $controllerClass = get_class($controller);

        if (method_exists($controller, 'registerJavascript')) {
            $controller->registerJavascript(
                'upela-jquery',
                'https://code.jquery.com/jquery-3.2.1.min.js',
                array('priority' => 100, 'server' => 'remote')
            );
            $controller->registerJavascript(
                'upela-googlemap',
                'https://maps.google.com/maps/api/js?key=AIzaSyBplTpOQbyilWbKmwfImXa2B2VeCTQMosw',
                array('priority' => 100, 'server' => 'remote')
            );
            $controller->registerJavascript(
                'upela-googlemap',
                'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modal.min.js',
                array('priority' => 100, 'server' => 'remote')
            );
            $controller->registerJavascript(
                'upela-googlemap',
                'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modalmanager.min.js',
                array('priority' => 100, 'server' => 'remote')
            );
            $controller->registerJavascript(
                'upela',
                'modules/upela/views/js/upela.js',
                array('priority' => 100, 'server' => 'local')
            );
            $controller->registerStylesheet(
                'upela',
                'modules/upela/views/css/upela.css',
                array('priority' => 100, 'server' => 'local')
            );
        } else {
            $controller->addJs('https://code.jquery.com/jquery-3.2.1.min.js');
            $controller->addJs('https://maps.google.com/maps/api/js?key=AIzaSyBplTpOQbyilWbKmwfImXa2B2VeCTQMosw');
            $controller->addJs('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modal.min.js');
            $controller->addJs('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modalmanager.min.js');
            $controller->addJs(_MODULE_DIR_ . '/upela/views/js/upela.js');
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

        $controller = $this->context->controller;
        // initialisation de l address

        $postcode = null ;
        $city = null ;
        $deleted = null ;

        $sql = 'SELECT  is_dropoff_point,id_up_service FROM '._DB_PREFIX_.'upela_services WHERE id_carrier='.$params['carrier']['id'];
        $results = Db::getInstance()->getRow($sql,false);
        $is_dropoff = $results['is_dropoff_point'];
        $upela_service = $results['id_up_service'];
        $carrier_id = $params['carrier']['id'];

        foreach($this->context->cart->getAddressCollection()  as $address)
        {
            $postcode = $address->postcode ;
            $city =$address->city ;
            $deleted = $address->deleted ;
        }


        if($is_dropoff && !is_null($postcode) && !is_null($city) && !$deleted)
        {
            $this->context->smarty->assign(
                ['address'=>
                     ['postcode'=>$postcode,'city'=>$city,'upela_service'=>$upela_service,'carrier_id'=>$carrier_id]]);
            return $this->display(__FILE__, 'displayCarrierExtraContent.tpl');;
        }

        return '';

       }

    public function hookdisplayAdminOrder($params)
    {

        $this->context->smarty->assign(array(
            'simple_link' => $this->_path,
            'reference' => " ",
            'suivi' => $this->l('Go to Upela.com', 'upela'),
            'iconBtn' => "icon-plus-sign",
            'link_suivi' => ($this->isConnected) ?
                $this->api->getUrlconnection() :
                $this->context->link->getAdminLink('AdminModules').
                '&configure='.$this->name.'&tab_module='.$this->tab.
                '&module_name='.$this->name,
            'img15' => 'views/img/add.gif',
            'target' => ($this->isConnected) ? '_blank' : ''
        ));

        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            $expedition = $this->display(__FILE__, 'expedition15.tpl');
        } else {
            $expedition = $this->display(__FILE__, 'expedition.tpl');
        }

        return $expedition;
    }

    /**
     * @return string
     */
    public function getContent() {
        if (Tools::isSubmit('processChangeMode')) {
            $this->setMode((Tools::getValue('upela_mode')) ? UpelaApi::API_MODE_PROD : UpelaApi::API_MODE_TEST);
            $this->context->smarty->assign(array('postSuccess' =>
                $this->l('Activated mode: ').((Tools::getValue('upela_mode')) ? 'Production ' : 'Test ')));
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
                $this->context->smarty->assign(array('upela_login' => false));
            } else {
                $this->context->smarty->assign(array('upela_login' => true));
                $this->context->smarty->assign(array('postErrors' => $this->postErrors));
            }
            if (count($this->postSuccess)) {
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

        $storeExists = 0;
        $stores = array();
        $user = '';

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
        }

        $this->context->smarty->assign(
            array(
                'upela_register_link' => $this->context->link->getAdminLink('AdminModules').
                    '&configure='.$this->name.'&tab_module='.$this->tab.
                    '&module_name='.$this->name.'&register=1',
                'upela_store_link' => $this->context->link->getAdminLink('AdminModules').
                    '&configure='.$this->name.'&tab_module='.$this->tab.
                    '&module_name='.$this->name.'&newstore=1',
                'upela_login_link' => $this->context->link->getAdminLink('AdminModules').
                    '&configure='.$this->name.'&tab_module='.$this->tab.
                    '&module_name='.$this->name.'&login=1',
                'upela_user_link' => ($this->isConnected) ? $this->api->getUrlconnection() : '',
                'upela_create_account_post_link' => $this->context->link->getAdminLink('AdminModules').
                    '&configure='.$this->name.'&tab_module='.$this->tab.
                    '&module_name='.$this->name,
                'upela_link' => 'https://www.upela.com',
                'upela_link_support' => 'https://addons.prestashop.com/fr/contactez-nous?id_product=26804',
                'errors' => $this->errors,
                '_path' => $this->_path,
                'upela_user_connected' => $this->isConnected,
                'upela_username' => ($this->isConnected) ? $user['name'] : '',
                'upela_user_email' => ($this->isConnected) ? $user['login'] : '',
                'upela_login' => false,
                'upela_nbstores' => count($stores),
                'upela_storeexsists' => $storeExists,
                'country' => Country::getIsoById(Configuration::get('PS_SHOP_COUNTRY_ID'))
            )
        );

        $this->context->controller->addCSS($this->_path.'views/css/back.css');

        if (Tools::isSubmit('login')) {
            //return $this->displayLoginForm();
        }

        $this->context->smarty->assign(array('upela_login' => Tools::isSubmit('login')));

        $fields_form[0]['form'] = array(
            'input' => array(
                array(
                    'type' => 'switch',
                    'label' => $this->l('Live mode'),
                    'name' => 'upela_mode',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on_prod',
                            'value' => true
                        ),
                        array(
                            'id' => 'active_off_ca',
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
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

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

    private function postValidation() {
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

            if (empty($values['webservicekey'])) {
                $this->postErrors[] = $this->l('Webservice key is required');
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

            if (empty($values['webservicekey'])) {
                $this->postErrors[] = $this->l('Webservice key is required');
            }

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

    public function getStoreFormValues() {
        if (!Tools::isSubmit('processStoreCreation')) {
            if (empty(Configuration::get('PS_SHOP_COUNTRY_ID'))) {
                $defaultCountry = Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'));
            } else {
                $defaultCountry = Country::getIsoById(Configuration::get('PS_SHOP_COUNTRY_ID'));
            }

            $return = array(
                'firstname' => $this->context->employee->firstname,
                'lastname' => $this->context->employee->lastname,
                'email' => $this->context->employee->email,
                'store_name' => Configuration::get('PS_SHOP_NAME'),
                'phone' => Configuration::get('BLOCKCONTACTINFOS_PHONE'),
                'store_country' => $defaultCountry,
                'store_address_1' => Configuration::get('PS_SHOP_ADDR1'),
                'store_address_2' => Configuration::get('PS_SHOP_ADDR2'),
                'store_address_3' => '',
                'store_city' => Configuration::get('PS_SHOP_CITY'),
                'store_zipcode' => Configuration::get('PS_SHOP_CODE'),
                'webservicekey' => '',
                'store_business' => true,
            );
        } else {
            $return = array(
                'firstname' => Tools::getValue('firstname'),
                'lastname' => Tools::getValue('lastname'),
                'email' => Tools::getValue('email'),
                'store_name' => Tools::getValue('store_name'),
                'phone' => Tools::getValue('phone'),
                'store_country' => Tools::getValue('store_country'),
                'store_address_1' => Tools::getValue('store_address_1'),
                'store_address_2' => Tools::getValue('store_address_2'),
                'store_address_3' => Tools::getValue('store_address_3'),
                'store_city' => Tools::getValue('store_city'),
                'store_zipcode' => Tools::getValue('store_zipcode'),
                'webservicekey' => Tools::getValue('webservicekey'),
                'store_business' => Tools::getValue('store_business'),
            );
        }

        return $return;
    }

    public function getAccountFormValues() {
        if (!Tools::isSubmit('processAccountCreation')) {

            if (empty(Configuration::get('PS_SHOP_COUNTRY_ID'))) {
                $defaultCountry = Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'));
            } else {
                $defaultCountry = Country::getIsoById(Configuration::get('PS_SHOP_COUNTRY_ID'));
            }

            $return = array(
                'firstname' => $this->context->employee->firstname,
                'lastname' => $this->context->employee->lastname,
                'email' => $this->context->employee->email,
                'store_name' => Configuration::get('PS_SHOP_NAME'),
                'phone' => Configuration::get('BLOCKCONTACTINFOS_PHONE'),
                'store_country' => $defaultCountry,
                'store_address_1' => Configuration::get('PS_SHOP_ADDR1'),
                'store_address_2' => Configuration::get('PS_SHOP_ADDR2'),
                'store_address_3' => '',
                'store_city' => Configuration::get('PS_SHOP_CITY'),
                'store_zipcode' => Configuration::get('PS_SHOP_CODE'),
                'store_business' => true,
                'webservicekey' => '',

                'company_name' => Configuration::get('PS_SHOP_NAME'),
                'company_country' => $defaultCountry,
                'company_address_1' => Configuration::get('PS_SHOP_ADDR1'),
                'company_address_2' => Configuration::get('PS_SHOP_ADDR2'),
                'company_address_3' => '',
                'company_city' => Configuration::get('PS_SHOP_CITY'),
                'company_zipcode' => Configuration::get('PS_SHOP_CODE'),
                'company_business' => true,
                'company_vat' => '',
                'company_siret' => '',
            );
        } else {
            $return = array(
                'firstname' => Tools::getValue('firstname'),
                'lastname' => Tools::getValue('lastname'),
                'email' => Tools::getValue('email'),
                'store_name' => Tools::getValue('store_name'),
                'phone' => Tools::getValue('phone'),
                'password' => Tools::getValue('password'),
                'password_check' => Tools::getValue('passwordcheck'),
                'store_country' => Tools::getValue('store_country'),
                'store_address_1' => Tools::getValue('store_address_1'),
                'store_address_2' => Tools::getValue('store_address_2'),
                'store_address_3' => Tools::getValue('store_address_3'),
                'store_city' => Tools::getValue('store_city'),
                'store_zipcode' => Tools::getValue('store_zipcode'),
                'webservicekey' => Tools::getValue('webservicekey'),
                'store_business' => Tools::getValue('store_business'),
                'company_vat' => Tools::getValue('company_vat'),
                'company_siret' => Tools::getValue('company_siret'),

                'company_name' => Tools::getValue('company_name'),
                'company_country' => Tools::getValue('company_country'),
                'company_address_1' => Tools::getValue('company_address_1'),
                'company_address_2' => Tools::getValue('company_address_2'),
                'company_address_3' => Tools::getValue('company_address_3'),
                'company_city' => Tools::getValue('company_city'),
                'company_zipcode' => Tools::getValue('company_zipcode'),
                'company_business' => Tools::getValue('company_business'),
            );
        }

        return $return;
    }

    private function postProcess() {
        if (Tools::isSubmit('processStoreCreation')) {
            $orderStates = OrderState::getOrderStates($this->context->language->id);
            $statusList = array();

            foreach ($orderStates as $orderState) {
                $statusList[$orderState['id_order_state']] = $orderState['name'];
            }

            $user = $this->getUserConnected();
            $values = $this->getStoreFormValues();

            $data = array(
                'user_id' => $user['id'],
                'name' => $values['store_name'],
                'module_url' => Configuration::get('PS_SHOP_DOMAIN'),
                'type' => 'prestashop',
                'username' => $user['login'],
                'apikey' => $values['webservicekey'],
                'statusList' => Tools::jsonEncode($statusList),
                'store_country' => $values['store_country'],
                'store_name' => $values['store_name'],
                'company_name' => $values['store_name'],
                'lastname' => $values['lastname'],
                'firstname' => $values['firstname'],
                'store_address_1' => $values['store_address_1'],
                'store_address_2' => $values['store_address_2'],
                'store_address_3' => $values['store_address_3'],
                'store_zipcode' => $values['store_zipcode'],
                'store_city' => $values['store_city'],
                'phone' => $values['phone'],
                'email' => $values['email'],
                'pro' => $values['store_business'],
            );

            $retStore = $this->api->createStore($data);

            if (!$retStore) {
                $this->postErrors[] = $this->l('Error when creating the store!', 'upela');
            } else {
                $this->postSuccess[] = $this->l('Store created !');
            }
        } elseif (Tools::isSubmit('processAccountCreation')) {
            $values = $this->getAccountFormValues();

            if ($this->api->getUserExists($values['email'])) {
                $this->postErrors[] = $this->l('Error: Email already exists! Please connect or use another email.', 'upela');

                return false;
            }

            $data = array(
                'login' => $values['email'],
                'pseudo' => $values['firstname'].' '.$values['lastname'],
                'email' => $values['email'],
                'module' => 'prestashop',
                'password' => $values['password'],
                'lastname' => $values['firstname'].' '.$values['lastname'],
                'firstname' => $values['firstname'],
                'company' => $values['company_name'],
                'country' => $values['company_country'],
                'address_1' => $values['company_address_1'],
                'address_2' => $values['company_address_2'],
                'address_3' => $values['company_address_3'],
                'zipcode' => $values['company_zipcode'],
                'city' => $values['company_city'],
                'phone' => $values['phone'],
                'immat' => $values['company_vat'],
                'siret' => $values['company_siret'],
                'pro' => $values['company_business'],
            );

            $retUser = $this->api->createAccount($data);

            if (!$retUser) {
                $this->quickLog(
                    array(
                        'User Creation response : ',
                        print_r($retUser)
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
                'user_id' => $retUser['user_id'],
                'name' => $values['store_name'],
                'module_url' => Configuration::get('PS_SHOP_DOMAIN'),
                'type' => 'prestashop',
                'username' => $values['email'],
                'apikey' => $values['webservicekey'],
                'statusList' => Tools::jsonEncode($statusList),
                'store_country' => $values['store_country'],
                'store_name' => $values['store_name'],
                'company_name' => $values['store_name'],
                'lastname' => $values['lastname'],
                'firstname' => $values['firstname'],
                'store_address_1' => $values['store_address_1'],
                'store_address_2' => $values['store_address_2'],
                'store_address_3' => $values['store_address_3'],
                'store_zipcode' => $values['store_zipcode'],
                'store_city' => $values['store_city'],
                'phone' => $values['phone'],
                'email' => $values['email'],
                'pro' => $values['store_business'],
            );

            $retStore = $this->api->createStore($data);

            if (!$retStore) {
                $this->postErrors[] = $this->l('Error when creating the store!', 'upela');
            } else {
                $this->postSuccess[] = $this->l('Store created !');
            }
        }
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

    /**
     * @return bool
     */
    private function saveUser() {
        return (bool)Configuration::updateValue('UPELA_USER_LOGIN', $this->api->getUser()) &&
            (bool)Configuration::updateValue('UPELA_USER_PASSWORD', $this->api->getPassword()) &&
            (bool)Configuration::updateValue('UPELA_USER_ID', $this->api->getId()) &&
            (bool)Configuration::updateValue('UPELA_USER_NAME', $this->api->getName());
    }

    private function displayRegistrationForm2() {
        // Get default language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        if (empty(Configuration::get('PS_SHOP_COUNTRY_ID'))) {
            $default_Country = Country::getIsoById(Configuration::get('PS_COUNTRY_DEFAULT'));
        } else {
            $default_Country = Country::getIsoById(Configuration::get('PS_SHOP_COUNTRY_ID'));
        }

        // Init Fields form array
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Create your account in less than 2 minutes'),
                'image' => '../modules/'.$this->name.'/views/img/logo-2.png'

            ),
            'tabs' => array(
                'account' => $this->l('Your account'),
                'company' => $this->l('Your company'),
                'store' => $this->l('Your store')
            ),

            'input' => array(
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'firstname',
                    'label' => $this->l('Firstname'),
                    'required' => true,
                    'tab' => 'account'
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'lastname',
                    'label' => $this->l('Lastname'),
                    'required' => true,
                    'tab' => 'account'
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'email',
                    'label' => $this->l('Email'),
                    'required' => true,
                    'tab' => 'account'
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'phone',
                    'label' => $this->l('Phone'),
                    'required' => true,
                    'tab' => 'account'
                ),
                array(
                    'col' => 3,
                    'type' => 'password',
                    'name' => 'password',
                    'label' => $this->l('Password'),
                    'required' => true,
                    'tab' => 'account'
                ),
                array(
                    'col' => 3,
                    'type' => 'password',
                    'name' => 'passwordcheck',
                    'label' => $this->l('Confirm password'),
                    'required' => true,
                    'tab' => 'account'
                ),

                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'company_name',
                    'label' => $this->l('Company name'),
                    'required' => true,
                    'tab' => 'company',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Business address'),
                    'name' => 'company_business',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on_ca',
                            'value' => true,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off_ca',
                            'value' => '0',
                            'label' => $this->l('No')
                        )
                    ),
                    'tab' => 'company',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'company_address_1',
                    'label' => $this->l('Address 1'),
                    'required' => true,
                    'tab' => 'company',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'company_address_2',
                    'label' => $this->l('Address 2'),
                    'required' => false,
                    'tab' => 'company',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'company_address_3',
                    'label' => $this->l('Address 3'),
                    'required' => false,
                    'tab' => 'company',
                ),
                array(
                    'col' => 3,
                    'type' => 'select',
                    'name' => 'company_country',
                    'label' => $this->l('Country'),
                    'required' => true,
                    'options' => array(
                        'query' => Country::getCountries($this->context->language->id),
                        'id' => 'iso_code',
                        'name' => 'name'
                    ),
                    'tab' => 'company',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'company_zipcode',
                    'label' => $this->l('ZIP Code'),
                    'required' => true,
                    'tab' => 'company',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'company_city',
                    'label' => $this->l('City'),
                    'required' => true,
                    'tab' => 'company',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'company_siret',
                    'label' => $this->l('SIRET'),
                    'required' => false,
                    'tab' => 'company',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'company_vat',
                    'label' => $this->l('Company registration #'),
                    'required' => true,
                    'tab' => 'company',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'store_name',
                    'label' => $this->l('Store name'),
                    'required' => true,
                    'tab' => 'store',
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Business address'),
                    'name' => 'store_business',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on_sba',
                            'value' => true,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off_sba',
                            'value' => '0',
                            'label' => $this->l('No')
                        )
                    ),
                    'tab' => 'store',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'store_address_1',
                    'label' => $this->l('Address 1'),
                    'required' => true,
                    'tab' => 'store',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'store_address_2',
                    'label' => $this->l('Address 2'),
                    'required' => false,
                    'tab' => 'store',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'store_address_3',
                    'label' => $this->l('Address 3'),
                    'required' => false,
                    'tab' => 'store',
                ),
                array(
                    'col' => 3,
                    'type' => 'select',
                    'name' => 'store_country',
                    'label' => $this->l('Country'),
                    'required' => true,
                    'options' => array(
                        'query' => Country::getCountries($this->context->language->id),
                        'id' => 'iso_code',
                        'name' => 'name'
                    ),
                    'tab' => 'store',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'store_zipcode',
                    'label' => $this->l('ZIP Code'),
                    'required' => true,
                    'tab' => 'store',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'store_city',
                    'label' => $this->l('City'),
                    'required' => true,
                    'tab' => 'store',
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'webservicekey',
                    'label' => $this->l('Webservice key'),
                    'required' => true,
                    'tab' => 'store',
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
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

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
            'id_language' => $this->context->language->id
        );
        $this->context->smarty->assign('createform', $helper->generateForm($fields_form));

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure/create_form.tpl');

        return $output;
    }

    /**
     * @return mixed
     */
    private function displayCreateStoreForm2() {
        $countries = Country::getCountries($this->context->language->id);
        $lCountries = array();

        foreach ($countries as $country) {
            $lCountries[] = array(
                'iso_code' => $country['iso_code'],
                'name' => $country['name']
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
            'input' => array(
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'firstname',
                    'label' => $this->l('Firstname'),
                    'required' => true,
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'lastname',
                    'label' => $this->l('Lastname'),
                    'required' => true,
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'email',
                    'label' => $this->l('Email'),
                    'required' => true,
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'phone',
                    'label' => $this->l('Phone'),
                    'required' => true,
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'store_name',
                    'label' => $this->l('Store name'),
                    'required' => true,
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Business address'),
                    'name' => 'store_business',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on_sba',
                            'value' => true,
                            'label' => $this->l('Yes')
                        ),
                        array(
                            'id' => 'active_off_sba',
                            'value' => '0',
                            'label' => $this->l('No')
                        )
                    )
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'store_address_1',
                    'label' => $this->l('Address 1'),
                    'required' => true,
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'store_address_2',
                    'label' => $this->l('Address 2'),
                    'required' => false,
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'store_address_3',
                    'label' => $this->l('Address 3'),
                    'required' => false,
                ),
                array(
                    'col' => 3,
                    'type' => 'select',
                    'name' => 'store_country',
                    'label' => $this->l('Country'),
                    'required' => true,
                    'options' => array(
                        'query' => Country::getCountries($this->context->language->id),
                        'id' => 'iso_code',
                        'name' => 'name'
                    )
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'store_zipcode',
                    'label' => $this->l('ZIP Code'),
                    'required' => true,
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'store_city',
                    'label' => $this->l('City'),
                    'required' => true,
                ),
                array(
                    'col' => 3,
                    'type' => 'text',
                    'name' => 'webservicekey',
                    'label' => $this->l('Webservice key'),
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
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;

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
            'id_language' => $this->context->language->id
        );
        $this->context->smarty->assign('createform', $helper->generateForm($fields_form));

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure/create_form.tpl');

        return $output;
    }

    /**
     * @return string
     */
    private function processLoginSubmitted() {
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

        $this->isConnected = true;
        $this->postSuccess[] = $this->l('Connection success!');
    }


    /**
     * @return bool
     */
    protected function createCarriers() {
        $this->quickLog('createCarriers2');

        return  (bool)Configuration::updateValue(self::OPT_CHEAPEST_CARRIER_ID,
                                        $this->createCarrier($this->aCarrierDefinitions[self::VALUE_CHEAPEST])) &&
                (bool)Configuration::updateValue(self::OPT_FASTEST_CARRIER_ID,
                                        $this->createCarrier($this->aCarrierDefinitions[self::VALUE_FASTEST]));

    }

    /**
     * Adds new carrier linked to the module
     * @todo Add logo
     * @todo delete / desactivate other carriers
     * @param array $aDefinition Carrier definition, see upela::$aCarrierDefinitions
     * @return int
     */
    protected function createCarrier($aDefinition) {
        $this->quickLog('createCarrier: '.$aDefinition['name']);

        $oCarrier = new Carrier();
        $this->setCarrierProperties($oCarrier, $aDefinition);

        if ($oCarrier->add()) {
            $this->addCarrierToGroups($oCarrier);
            $this->addCarrierToZones($oCarrier);
            $oRangeWeight = $this->addCarrierRangeWeight($oCarrier);
            $this->addDeliveryPrice($oCarrier, $oRangeWeight);
            $this->copyCarrierImage($oCarrier);
            return $oCarrier->id;
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
     * Get Carriers list
     * @param string $origin
     * @return array
     */
    protected function getCarriers($origin){
        $carriers = array(
            'express' => array(
                'name' => $this->l('Express'),
                'actif' => false,
                'price' => 0
            ),
            'standard' => array(
                'name' => $this->l('Standard'),
                'actif' => false,
                'price' => 0
            )
        );

        return $carriers;
    }
}
