{*
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
*}

<fieldset id="expeditionTab" style="margin-top: 15px;">
    <legend>{l s='Ship with Upela' mod='upela'}</legend>
    <table id="shippingUpela">
        <tbody>
        <tr>
            <th style="border:none;"><img src="{$simple_link|escape:'html':'UTF-8'}views/img/logo-pl.svg"
                                          width="150px;"></th>
            <th style="border:none;"><span style="font-weight: normal;">{$reference|escape:'html':'UTF-8'}</span></th>
            <th style="border:none;padding-left: 15px;"><a class="button" target="{$target|escape:'html':'UTF-8'}"
                                                           href="{$link_suivi|escape:'html':'UTF-8'}"><img
                            src="{$simple_link|escape:'html':'UTF-8'}{$img15|escape:'html':'UTF-8'}"> {$suivi|escape:'html':'UTF-8'}
                </a></th>
        </tr>
        </tbody>
    </table>
</fieldset>