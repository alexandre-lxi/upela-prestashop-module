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

    {if $upela_user_connected}
        <div class="panel">
            <div class="row">
                <div class="panel-content part__content">
                    <div class="col-lg-12">
                        <h2 class="col-lg-offset-4">{l s='Store information' mod='upela'}</h2>
                        <br>
                        <form method='POST' action="{$upela_store_update_link|escape:'htmlall':'UTF-8'}"
                              class="form-horizontal">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="store_name" class="col-sm-4">{l s='Store name' mod='upela'}</label>
                                    <div class="col-sm-4">
                                        <input name="store_name" type="text" class="form-control" id="store_name"
                                               placeholder="{l s='Store name' mod='upela'}"
                                               value="{$storeInfos['upela_store_name']|escape:'htmlall':'UTF-8'}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="store_firstname"
                                           class="col-sm-4">{l s='Firstname' mod='upela'}</label>
                                    <div class="col-sm-4">
                                        <input name="store_firstname" type="text" class="form-control" id="store_firstname"
                                               placeholder="{l s='Firstname' mod='upela'}"
                                               value="{$storeInfos['upela_store_firstname']|escape:'htmlall':'UTF-8'}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="store_lastname" class="col-sm-4">{l s='Lastname' mod='upela'}</label>
                                    <div class="col-sm-4">
                                        <input name="store_lastname" type="text" class="form-control" id="store_lastname"
                                               placeholder="{l s='Lastname' mod='upela'}"
                                               value="{$storeInfos['upela_store_lastname']|escape:'htmlall':'UTF-8'}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="store_phone" class="col-sm-4">{l s='Phone' mod='upela'}</label>
                                    <div class="col-sm-4">
                                        <input name="store_phone" type="text" class="form-control" id="store_phone"
                                               placeholder="{l s='Phone' mod='upela'}"
                                               value="{$storeInfos['upela_store_phone']|escape:'htmlall':'UTF-8'}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="store_email" class="col-sm-4">{l s='Email' mod='upela'}</label>
                                    <div class="col-sm-4">
                                        <input name="store_email" type="text" class="form-control" id="store_email"
                                               placeholder="{l s='Email' mod='upela'}"
                                               value="{$storeInfos['upela_store_email']|escape:'htmlall':'UTF-8'}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="store_address1" class="col-sm-4">{l s='Address 1' mod='upela'}</label>
                                    <div class="col-sm-4">
                                        <input name="store_address1" type="text" class="form-control" id="store_address1"
                                               placeholder="{l s='Address 1' mod='upela'}"
                                               value="{$storeInfos['upela_store_address1']|escape:'htmlall':'UTF-8'}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="store_address2" class="col-sm-4">{l s='Address 2' mod='upela'}</label>
                                    <div class="col-sm-4">
                                        <input name="store_address2" type="text" class="form-control" id="store_address2"
                                               placeholder="{l s='Address 2' mod='upela'}"
                                               value="{$storeInfos['upela_store_address2']|escape:'htmlall':'UTF-8'}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="store_address3" class="col-sm-4">{l s='Address 3' mod='upela'}</label>
                                    <div class="col-sm-4">
                                        <input name="store_address3" type="text" class="form-control" id="store_address3"
                                               placeholder="{l s='Address 3' mod='upela'}"
                                               value="{$storeInfos['upela_store_address3']|escape:'htmlall':'UTF-8'}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="store_zipcode" class="col-sm-4">{l s='ZIP Code' mod='upela'}</label>
                                    <div class="col-sm-4">
                                        <input name="store_zipcode" type="text" class="form-control" id="store_zipcode"
                                               placeholder="{l s='Zip code' mod='upela'}"
                                               value="{$storeInfos['upela_store_zipcode']|escape:'htmlall':'UTF-8'}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="store_city" class="col-sm-4">{l s='City' mod='upela'}</label>
                                    <div class="col-sm-4">
                                        <input name="store_city" type="text" class="form-control" id="store_city"
                                               placeholder="{l s='city' mod='upela'}"
                                               value="{$storeInfos['upela_store_city']|escape:'htmlall':'UTF-8'}">
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button name="processStoreUpdate" type="submit"
                                            class="btn btn-primary text-center part__button button--white">
                                        {l s='Save' mod='upela'}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="row">
                <div class="panel-content part__content">
                    <div class="col-lg-6">
                        <h2 class="col-lg-offset-1">{l s='Shipment information' mod='upela'}</h2>
                        <br>
                        <form method='POST' action="{$upela_parameters_link|escape:'htmlall':'UTF-8'}"
                              class="form-horizontal">
                            <h4>{l s='Default parcel informations' mod='upela'}</h4>
                            <div class="form-group">
                                <label for="ship_content"
                                       class="col-sm-4">{l s='Shipments content' mod='upela'}</label>
                                <div class="col-sm-4">
                                    <input name="ship_content" type="text" class="form-control" id="ship_content"
                                           placeholder="{l s='Shipment content' mod='upela'}"
                                           value="{$upela_ship_content|escape:'htmlall':'UTF-8'}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="wt" class="col-sm-4">{l s='Weight (Kg)' mod='upela'}</label>
                                <div class="col-sm-4">
                                    <input name="upela_weight" type="text" class="form-control" id="upela_weight"
                                           placeholder="{l s='Weight (Kg)' mod='upela'}"
                                           value="{$upela_weight|escape:'htmlall':'UTF-8'}">
                                </div>
                            </div>
                            <h5>{l s='Dimensions (cm)' mod='upela'}</h5>
                            <div class="form-group">
                                <label for="wt" class="col-sm-4">{l s='Length' mod='upela'}</label>
                                <div class="col-sm-4">
                                    <input name="upela_length" type="text" class="form-control" id="upela_length"
                                           placeholder="{l s='Length' mod='upela'}"
                                           value="{$upela_length|escape:'htmlall':'UTF-8'}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="wt" class="col-sm-4">{l s='Width' mod='upela'}</label>
                                <div class="col-sm-4">
                                    <input name="upela_width" type="text" class="form-control" id="upela_width"
                                           placeholder="{l s='Width' mod='upela'}"
                                           value="{$upela_width|escape:'htmlall':'UTF-8'}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="wt" class="col-sm-4">{l s='Height' mod='upela'}</label>
                                <div class="col-sm-4">
                                    <input name="upela_height" type="text" class="form-control" id="upela_height"
                                           placeholder="{l s='Height' mod='upela'}"
                                           value="{$upela_height|escape:'htmlall':'UTF-8'}">
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button name="processParameters" type="submit"
                                            class="btn btn-primary text-center part__button button--white pull -right">

                                        {l s='Save' mod='upela'}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-6">
                        <h2 class="col-lg-offset-1">{l s='Payment information' mod='upela'}</h2>
                        <br>
                        {if {$paymentInfos['info']|escape:'htmlall':'UTF-8'} == false}
                            <h4>{l s='No payment informations avalaible !' mod='upela'}</h4>
                        {else}
                            {if {$paymentInfos['avalaible']|escape:'htmlall':'UTF-8'} == false}
                                <h4>
                                    {l s='You can not ship your orders directly. You must switch your account to SEPA payment or credit your account!' mod='upela'}
                                </h4>
                                <br>
                            {/if}

                            <table class="table">
                                <tr>
                                    <td style="font-size: 16px">
                                        {l s='Payment method' mod='upela'}
                                    </td>
                                    <td style="font-size: 16px">
                                        {$paymentInfos['method']|escape:'htmlall':'UTF-8'}
                                    </td>
                                </tr>
                                {if {$paymentInfos['method']|escape:'htmlall':'UTF-8'}=={l s='Credit card' mod='upela'}}
                                    <tr>
                                        <td style="font-size: 16px">
                                            {l s='Amount avalaible' mod='upela'}
                                        </td>
                                        <td style="font-size: 16px">
                                            {$paymentInfos['amount']|escape:'htmlall':'UTF-8'}
                                        </td>
                                    </tr>
                                {/if}
                                {if {$paymentInfos['voucher']|escape:'htmlall':'UTF-8'}==true}
                                    <tr>
                                        <td style="font-size: 16px">
                                            {l s='Voucher amount' mod='upela'}
                                        </td>
                                        <td style="font-size: 16px">
                                            {$paymentInfos['vamount']|escape:'htmlall':'UTF-8'}
                                        </td>
                                    </tr>
                                {/if}
                            </table>
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            <div class="col-lg-offset-1">
                                <div class="row">
                                    <div class="col-sm-offset-3">
                                        <a href="{$upela_param_link|escape:'htmlall':'UTF-8'}"
                                           class="part__button button--white"
                                           target="_blank"
                                           style="text-align: center;">
                                            {l s='Go to payment update' mod='upela'}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        {/if}

                    </div>
                </div>
            </div>
        </div>
    {/if}
    <div class="panel">
        <div class="row">
            <div class="panel-content part__content">
                <div class="col-lg-12">
                    <h2>{l s='Connection parameters to your Upela account' mod='upela'}</h2>
                    <br>
                    <div class="col-lg-6">
                        {if $upela_user_connected}
                            <h4>{l s='Your account has been activated.' mod='upela'}</h4>
                            <br>
                            <form method='POST' action="#"
                                  class="form-horizontal col-lg-offset-1">
                                <div class="form-group">
                                    <label for="email" class="col-sm-4">{l s='Email' mod='upela'}</label>
                                    <div class="col-sm-6">
                                        <input name="upela_email" type="email" class="form-control" id="email"
                                               placeholder="{l s='Email' mod='upela'}" disabled
                                               value="{$upela_user_email|escape:'htmlall':'UTF-8'}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-sm-4">{l s='Password' mod='upela'}</label>
                                    <div class="col-sm-6">
                                        <input name="upela_password" type="password" class="form-control"
                                               id="password"
                                               placeholder="{l s='Password' mod='upela'}" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-offset-3">
                                            <button name="updateLogin" type="submit"
                                                    class="btn btn-primary text-center part__button button--white">
                                                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-13.png">
                                                {l s='Disconnect' mod='upela'}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        {else}
                            <form method='POST' action="{$upela_login_link|escape:'htmlall':'UTF-8'}"
                                  class="form-horizontal col-lg-offset-1">
                                <div class="form-group">
                                    <label for="email" class="col-sm-4">{l s='Email' mod='upela'}</label>
                                    <div class="col-sm-6">
                                        <input name="upela_email" type="email" class="form-control" id="email"
                                               placeholder="{l s='Email' mod='upela'}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-sm-4">{l s='Password' mod='upela'}</label>
                                    <div class="col-sm-6">
                                        <input name="upela_password" type="password" class="form-control"
                                               id="password"
                                               placeholder="{l s='Password' mod='upela'}">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <div class="col-sm-offset-1">
                                            <button name="processLogin" type="submit"
                                                    class="btn btn-primary text-center part__button">
                                                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-13-w.png">
                                                {l s='Log Into your Account' mod='upela'}
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-sm-offset-1">
                                        <a href="{$upela_register_link|escape:'htmlall':'UTF-8'}"
                                           class="part__button button--white">
                                            <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-14-u.png">
                                            {l s='Create a Business Account' mod='upela'}
                                        </a>
                                    </div>
                                </div>

                            </form>
                        {/if}
                    </div>
                    <div class="col-lg-6">
                        <h4 class="col-lg-offset-1">{l s='Stores information' mod='upela'}</h4>
                        <br>
                        <h4 class="col-lg-offset-1">{l s='You currently have:' mod='upela'} {$upela_nbstores|escape:'htmlall':'UTF-8'} {l s='store(s)' mod='upela'}</h4>
                        <h4 class="col-lg-offset-1">{l s='You currently have:' mod='upela'} {$upela_storeexsists|escape:'htmlall':'UTF-8'} {l s='Prestashop store(s)' mod='upela'}</h4>
                        <br>
                        {if $upela_user_connected}
                            <div class="col-lg-offset-1">
                                <div class="row">
                                    <div class="col-sm-offset-3">
                                        <a href="{$upela_store_link|escape:'htmlall':'UTF-8'}"
                                           class="part__button button--white"
                                           style="text-align: center;">
                                            <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-40.png">
                                            {l s='Create store' mod='upela'}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>
