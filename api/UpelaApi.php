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

class UpelaApi
{
    const URL_API = 'https://api.upela.com/';
    const URL_API_TEST = 'https://dev.upela.com/';
    const URL_UPELA = 'https://www.upela.com/';
    const URL_UPELA_TEST = 'https://dev.upela.com/';

    const API_MODE_PROD = 'prod';
    const API_MODE_TEST = 'test';
    const API_POST = 'post';
    const API_GET = 'get';
    const API_VERSION = 'v3';
    const API_PTF_VERSION = 'v5';

    protected $host;
    protected $url_upela;
    protected $endpoint;
    protected $action;
    protected $logs = array();
    protected $debug = false;
    protected $user = '';
    protected $passwd = '';
    protected $api_key;
    protected $id;
    protected $name;
    protected $mode;

    /**
     * UpelaApi constructor.
     *
     * @param array|bool  $user
     * @param null|string $mode
     */
    public function __construct($user, $mode)
    {
        $this->setMode($mode);

        if ($user) {
            $this->setName($user['name']);
            $this->setId($user['id']);
            $this->setCredentials($user['login'], $user['password']);
        }
    }

    /**
     * @param $user
     * @param $password
     */
    protected function setCredentials($user, $password)
    {
        $this->user = $user;
        $this->passwd = $password;
    }

    /**
     * @return mixed
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param mixed $mode
     */
    public function setMode($mode)
    {
        if ($mode == self::API_MODE_PROD) {
            $this->host = self::URL_API;
            $this->url_upela = self::URL_UPELA;
        } else {
            $this->host = self::URL_API_TEST;
            $this->url_upela = self::URL_UPELA_TEST;
        }

        $this->mode = $mode;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->passwd;
    }

    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUrlconnection()
    {
        $url = $this->url_upela.'admin/user/actions_spec.php?action=login_as&id=';
        $url .= $this->getId().'&url='.$this->url_upela.'store/orders.php';
        return $url;
    }

    /**
     * @return string
     */
    public function getUrlparameter()
    {
        $url = $this->url_upela.'admin/user/actions_spec.php?action=login_as&id=';
        $url .= $this->getId().'&url='.$this->url_upela.'mon-compte/prelevement-sepa';
        return $url;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->url_upela;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param $username
     * @param $password
     *
     * @return mixed
     */
    public function getUserId($username, $password)
    {
        $this->action = self::API_POST;
        $this->endpoint = 'api/'.self::API_VERSION.'/login/';

        $data = array(
            'account' => array(
                'login' => $username,
                'password' => $password
            )
        );

        $ret = $this->makeCall($this->getBody($data), null, true);


        if (isset($ret['success'])) {
            if ($ret['success']) {
                $this->setCredentials($username, $password);
                $this->setId($ret['customer_id']);
                $this->setName($ret['lastname']);
            }
        }

        return $ret;
    }

    /**
     * Make call
     *
     * @param  mixed  $body        body content
     * @param  mixed  $http_header header content
     * @param  string $user        User login
     * @param  string $passwd      User password
     *
     * @return mixed               Call
     */
    protected function makeCall($body = null, $http_header = null, $urlOrApi = false, $vardump = false)
    {
        // init uri to call
        if ($urlOrApi) {
            $uri_to_call = $this->url_upela.$this->endpoint;
        } else {
            $uri_to_call = $this->host.$this->endpoint;
        }

        if ( $vardump ) {
            var_dump($body);
            var_dump($uri_to_call);
            die();
        }
        if ($body != null) {
            if ($http_header) {
                $stream_context = array(
                    'http' => array(
                        'method' => 'POST',
                        'content' => $body,
                        'header' => $http_header
                    )
                );
            } else {
                $stream_context = array(
                    'http' => array(
                        'method' => 'POST',
                        'content' => $body
                    )
                );
            }
        } else {
            $stream_context = array();
        }

        $response = $this->fileGetContents($uri_to_call, $stream_context);

        if ($response != false) {
            $response = Tools::jsonDecode($response, true);
        }
        return $response;
    }

    /**
     * @param string $url
     * @param null   $stream_context
     * @param int    $curl_timeout
     *
     * @return bool|mixed
     */
    private function fileGetContents($url, $opts = null, $curl_timeout = '20L')
    {
        if (function_exists('curl_init')) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($curl, CURLOPT_TIMEOUT, $curl_timeout);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

            if ($opts != null) {
                if (isset($opts['http']['method']) && Tools::strtolower($opts['http']['method']) == self::API_POST) {
                    curl_setopt($curl, CURLOPT_POST, true);
                    if (isset($opts['http']['content'])) {
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $opts['http']['content']);
                    }
                    if (isset($opts['http']['header'])) {
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                            "Content-type: application/json",
                            "Content-Length: ".Tools::strlen($opts['http']['content']),
                            "Authorization: ".$opts['http']['header']
                        ));
                    } else {
                        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                            "Content-type: application/json",
                            "Content-Length: ".Tools::strlen($opts['http']['content'])/*,
                            "Authorization: ".Configuration::get('UPELA_API_KEY')*/
                        ));
                    }
                } else {
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                        "Content-type: application/json"/*,
                        "Authorization: ".Configuration::get('UPELA_API_KEY')*/
                    ));
                }
            }

            $content = curl_exec($curl);

            curl_close($curl);


            return $content;
        } else {
            return false;
        }
    }

    /**
     * @param array $fields
     *
     * @return bool|string
     */
    protected function getBody(array $fields)
    {
        $return = true;

        // if fields not empty
        if (empty($fields)) {
            $return = false;
        }

        // if not empty
        if ($return) {
            return json_encode($fields);
        }

        return $return;
    }

    /**
     * @param $username
     * @return bool
     */
    public function getUserExists($username)
    {
        $this->action = self::API_GET;
        $this->endpoint = self::API_PTF_VERSION.'/users/'.$username;

        $ret = $this->makeCall();

        return isset($ret['id']);
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getStores($userId)
    {
        $this->action = self::API_GET;
        $this->endpoint = self::API_PTF_VERSION.'/store/'.$userId;

        $ret = $this->makeCall();

        return $ret;
    }

    /**
     * @values array user
     *
     * @return bool
     */
    public function createAccount($values)
    {
        $this->action = self::API_POST;
        $this->endpoint = self::API_PTF_VERSION.'/user';

        $data = array(
            'login' => $values['login'],
            'pseudo' => $values['pseudo'],
            'email' => $values['email'],
            'password' => $values['password'],
            'gender' => 'M',
            'module' => $values['module'],
            'lastname' => $values['lastname'],
            'firstname' => $values['firstname'],
            'company' => $values['company'],
            'pro' => '1'
        );

        $ret = $this->makeCall($this->getBody($data), null, false, false);

        $result = false;

        if (isset($ret['id'])) {
            //Add company address

            $this->action = self::API_POST;
            $this->endpoint = 'api/'.self::API_VERSION.'/get_country_id/';
            $data = array(
                'iso' => $values['country']
            );

            $countryId = $this->makeCall($this->getBody($data), null, true);
            $countryId = $countryId['id'];

            $this->action = self::API_POST;

            $this->endpoint = self::API_PTF_VERSION.'/address/'.$ret['id'].'/new';

            $data = array(
                'alias' => 'Address',
                'company' => $values['company'],
                'pro' => $values['pro'],
                'gender' => 'M',
                'lastname' => $values['lastname'],
                'firstname' => $values['firstname'],
                'address1' => $values['address_1'],
                'address2' => $values['address_2'],
                'address3' => $values['address_3'],
                'postcode' => $values['zipcode'],
                'city' => $values['city'],
                'countryId' => $countryId,
                'stateId' => '',
                'state' => '',
                'departmentId' => '',
                'phone' => $values['phone'],
                'phoneMobile' => '',
                'fax' => '',
                'email' => $values['email'],
                'vatNumber' => $values['immat'],
                'siret' => $values['siret'],
                'naf' => '',
            );

            $address_ret = $this->makeCall($this->getBody($data), null, false, false);

            if (isset($address_ret['id'])) {
                $result = array(
                    'user_id' => $ret['id'],
                    'address_id' => $address_ret['id']
                );
            }
        }
        return $result;
    }

    /**
     * @param $values array
     *
     * @return mixed
     */
    public function createStore($values)
    {
        //add store address
        $this->action = self::API_POST;
        $this->endpoint = 'api/'.self::API_VERSION.'/get_country_id/';

        $data = array(
            'iso' => $values['store_country']
        );
        $store_countryId = $this->makeCall($this->getBody($data), null, true);
        $store_countryId = $store_countryId['id'];

        $this->action = self::API_POST;

        $this->endpoint = self::API_PTF_VERSION.'/address/'.$values['user_id'].'/new';

        $data = array(
            'alias' => $values['store_name'],
            'company' => $values['company_name'],
            'pro' => $values['pro'],
            'gender' => 'M',
            'lastname' => $values['lastname'],
            'firstname' => $values['firstname'],
            'address1' => $values['store_address_1'],
            'address2' => $values['store_address_2'],
            'address3' => $values['store_address_3'],
            'postcode' => $values['store_zipcode'],
            'city' => $values['store_city'],
            'countryId' => $store_countryId,
            'stateId' => '',
            'state' => '',
            'departmentId' => '',
            'phone' => $values['phone'],
            'phoneMobile' => '',
            'fax' => '',
            'email' => $values['email'],
            'vatNumber' => '',
            'siret' => '',
            'naf' => '',
        );

        $store_address_ret = $this->makeCall($this->getBody($data));

        if (isset($store_address_ret['id'])) {
            $data = array(
                'name' => $values['name'],
                'address_id' => $store_address_ret['id'],
                'module_url' => $values['module_url'],
                'type' => $values['type'],
                'username' => $values['username'],
                'apiKey' => $values['apikey'],
                'statusList' => $values['statusList']
            );

            $this->action = self::API_POST;
            $this->endpoint = self::API_PTF_VERSION.'/store/'.$values['user_id'].'/new';

            $ret = $this->makeCall($this->getBody($data));

            if (isset($ret['id'])) {
                return $ret;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getCredentials()
    {
        return array('login' => $this->user, 'password' => $this->passwd);
    }

    /**
     * @param $addressFrom
     * @param $addressTo
     * @param $parcel
     * @return mixed
     */
    public function getPrices($addressFrom, $addressTo, $parcel){
        $date = date('Y-m-d');
        $date = date('Y-m-d', strtotime($date. ' + 2 days'));

        $this->action = self::API_POST;
        $this->endpoint = 'api/'.self::API_VERSION.'/rate/';

        $data = array(
            'account'=>$this->getCredentials(),
            'ship_from' => array(
                'country_code' => $addressFrom['country'],
                'postcode' => $addressFrom['cp'],
                'city' => $addressFrom['city'],
                'pro' => 1,
            ),
            'ship_to' => array(
                'country_code' => $addressTo['country'],
                'postcode' => $addressTo['cp'],
                'city' => $addressTo['city'],
                'pro' => 1,
            ),
            'parcels' => array(
                array(
                    'number' => 1,
                    'weight' => (int)$parcel['weight'],
                    'x' => (int)$parcel['length'],
                    'y' => (int)$parcel['width'],
                    'z' => (int)$parcel['height'],)
            ),
            'shipment_date' => $date,
            'unit' => 'fr',
            'selection'=> 'all',
            'type' => 'parcel'
        );

        $prices = $this->makeCall($this->getBody($data), null, true, false);

        return $prices;
    }

    /**
     * API Get information payment
     * @return array
     */
    public function getPayments(){
        $ret = array(
            'info' => false,
            'method'=> '',
            'avalaible' => false,
            'amount'=>0,
            'voucher'=>false,
            'vamount'=>0
        );

        $this->action = self::API_POST;
        $this->endpoint = 'api/'.self::API_VERSION.'/get_payments/';

        $data = array(
            'account'=>$this->getCredentials()
        );

        $paymentInfos = $this->makeCall($this->getBody($data), null, true, false);

        if (isset($paymentInfos['success']) && ($paymentInfos['success'] === true)) {
            $paymentInfos = $paymentInfos['paiments_info'];

            if ($paymentInfos['cb']['activated']){
                $ret['method'] = 'CB';
                $ret['avalaible'] = (float)$paymentInfos['cb']['amount'] > 0;
                $ret['amount'] = (float)$paymentInfos['cb']['amount'] ;
            }
            if ($paymentInfos['sepa']['activated']){
                $ret['method'] = 'SEPA';
                $ret['avalaible'] = true;
                $ret['amount'] = (float)$paymentInfos['sepa']['amount'] ;
            }
            if ($paymentInfos['voucher']['activated']){
                $ret['voucher'] = (float)$paymentInfos['voucher']['amount'] > 0;
                $ret['vamount'] = (float)$paymentInfos['voucher']['amount'] ;

                if ($ret['avalaible'] === false){
                    if ($ret['voucher'])
                        $ret['avalaible'] = true;
                }
            }

            $ret['info'] = true;
            return $ret;
        }else{
            $ret['info'] = false;
            return $ret;
        }
    }


    public function Ship($shipFrom, $shipTo, $dropoffTo, $service, $parcel ){
        $date = date('Y-m-d');
        $date = date('Y-m-d', strtotime($date. ' + 2 days'));

        $this->action = self::API_POST;
        $this->endpoint = 'api/'.self::API_VERSION.'directShipping/';

        $data = array(
            'account'=>$this->getCredentials(),
            'carrier_code'=>$service['carrier_code'],
            'service_code'=> $service['service_code'],
            'ship_from'=>array(
                'company'=>'1',
                'name'=> $shipFrom['name'],
                'phone'=> $shipFrom['phone'],
                'email'=> $shipFrom['email'],
                'address1'=> $shipFrom['address1'],
                'address2'=> $shipFrom['address2'],
                'address3'=> null,
                'country_code'=> $shipFrom['country_code'],
                'postcode'=> $shipFrom['postcode'],
                'city'=> $shipFrom['city'],
                'pro'=> '1'),
            'ship_to'=>array(
                'company'=>'1',
                'name'=> $shipTo['name'],
                'phone'=> $shipTo['phone'],
                'email'=> $shipTo['email'],
                'address1'=> $shipTo['address1'],
                'address2'=> $shipTo['address2'],
                'address3'=> null,
                'country_code'=> $shipTo['country_code'],
                'postcode'=> $shipTo['postcode'],
                'city'=> $shipTo['city'],
                'pro'=> '1'),
            'dropoff_to'=> ($dropoffTo['active']==false)?null:
                array(
                'dropoff_location_id'=>$dropoffTo['name'],
                'name'=> $dropoffTo['name'],
                'phone'=> $dropoffTo['phone'],
                'email'=> $dropoffTo['email'],
                'address1'=> $dropoffTo['address1'],
                'address2'=> '',
                'address3'=> null,
                'country_code'=> $dropoffTo['country_code'],
                'postcode'=> $dropoffTo['postcode'],
                'city'=> $dropoffTo['city'],
                ),
            'parcels' => array(
                array(
                    'number' => 1,
                    'weight' => (int)$parcel['weight'],
                    'x' => (int)$parcel['length'],
                    'y' => (int)$parcel['width'],
                    'z' => (int)$parcel['height'],)
            ),
            'shipment_date' => $date,
            'unit' => 'fr',
            'selection'=> 'all',
            'type' => 'parcel',
            'reason'=> 'Commercial',
            'content' => $parcel['content'],
            'labelFormat'=> 'PDF'
        );

        $ship = $this->makeCall($this->getBody($data), null, true, false);

        return $ship;
    }

    public function ShipDirect(Array $info ){
        $this->action = self::API_POST;
        $this->endpoint = 'api/'.self::API_VERSION.'/directShipping/';
        $data = $info;
        $data['account'] =$this->getCredentials();
        print_r($data);

        $ship = $this->makeCall($this->getBody($data), null, true, false);
        return json_encode($ship);
    }

}
