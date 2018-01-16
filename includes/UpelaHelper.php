<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15/01/18
 * Time: 15:46
 */

class UpelaHelper
{
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
        'upela_services'
    );


    public function getTablesNames() {
        return $this->tables_names;
    }

    public function getPermissions(){
        return $this->permissions;
    }


}