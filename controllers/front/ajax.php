<?php
/**
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    Upela <contact@upela.com>
* @copyright 2007-2017 PrestaShop SA / 2011-2015 Upela
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registred Trademark & Property of PrestaShop SA
*/

class UpelaAjaxModuleFrontController extends ModuleFrontController
{
    protected $result;

    public function __construct()
    {
        $this->className = 'FrontUpelaController';
        include_once(_PS_MODULE_DIR_ . '/upela/upela.php');
        parent::__construct();
        $this->result = '';
    }

    public function postProcess()
    {
        $upela = new Upela();
        $option =Tools::getValue('option');


//
        //$option['dp_id'] = Tools::getValue('dp_id');
        //$option['dp_number'] = Tools::getValue('dp_number');
        //$option['dp_name'] = Tools::getValue('dp_name');
        //$option['dp_address1'] = Tools::getValue('dp_address1');
        //$option['dp_address2'] = Tools::getValue('dp_address2');
        //$option['dp_postcode'] = Tools::getValue('dp_postcode');
        //$option['dp_city'] = Tools::getValue('dp_city');
        //$option['dp_country'] = Tools::getValue('dp_country');
//
        //switch ($option) {
        //    case 'set_dropoff':
        //        $this->result = $upela->setPoint($option);
        //        break;
        //}

        $this->result = $option;

        die($this->display());
    }

    public function display()
    {
        echo $this->result;
        //die();
    }

    public function displayContent()
    {
        $this->display();
    }

    public function smartyOutputContent($content)
    {
        $this->display();
    }
}
