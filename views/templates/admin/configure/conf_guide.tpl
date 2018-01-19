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


    <div class="row">
        <div class="panel section8">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1">
                    <div class="panel-content part__content">
                        <h2 class="text-center">{l s='How to set up your UPELA account?' mod='upela'} </h2>
                        <h4 class="text-center pb40">{l s='In your Prestashop store' mod='upela'}</h4>
                        <div class="row pb40">
                            <div class="col-md-8">
                                <p>{l s='3) Register or log in to your Upela account' mod='upela'}</p>
                            </div>
                            <div class="col-md-4">
                                {if !$upela_user_connected}
                                    <a href="{$upela_register_link|escape:'htmlall':'UTF-8'}"
                                       class="part__button button--white">
                                        <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-14-u.png">
                                        {l s='Create a Business Account' mod='upela'}
                                    </a>
                                    <a href="{$upela_login_link|escape:'htmlall':'UTF-8'}"
                                       class="part__button">
                                        <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-13-w.png">
                                        {l s='Log Into Your Account' mod='upela'}
                                    </a>
                                    <br>
                                {/if}
                            </div>
                        </div>
                        <div class="row pb40">
                            <div class="col-md-8">
                                <p>{l s='4) Open Upela directly from your store' mod='upela'}</p>
                            </div>
                            <div class="col-md-4">
                                <a href="" onClick="this.href='{$upela_user_link|escape:'htmlall':'UTF-8'}'"
                                   class="part__button" target="_blank">
                                    {l s='Go to Upela.com' mod='upela'}
                                    <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/icons/icon-06-w.png"
                                         alt="Upela - icon">
                                </a>
                            </div>

                        </div>
                        <h4 class="text-center pb40">{l s='In your Upela Customer Area, \"My Stores\" menu' mod='upela'}</h4>
                        <div class="row pb40">
                            <div class="col-md-6">
                                <p>{l s='1) All orders placed on your eShops are automatically imported with their status' mod='upela'}</p>
                                <ul class="part__list">
                                    <li><i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                        {l s='You' mod='upela'} <span
                                                class="light-accent-font">{l s='generate' mod='upela'}</span> {l s='the shipping labels and ' mod='upela'}
                                        <span class="light-accent-font">{l s='ship' mod='upela'}</span> {l s='your parcels.' mod='upela'}
                                    </li>
                                    <li><i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                        {l s='Our shipping module automatically updates your respective stores with the new order status (sent) and  the tracking number.' mod='upela'}
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-6">
                                <img src="{$_path|escape:'htmlall':'UTF-8'}views/img/manual-screen3.png"
                                     class="part__img">
                            </div>
                        </div>
                        <div class="row pb40">

                            <p>{l s='2) Ship your order' mod='upela'}</p>
                            <ul class="part__list">
                                <li><i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                    {l s='The shipping and delivery addresses are automatically pre-filled' mod='upela'}
                                </li>
                                <li><i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                    {l s='Complete the information about your sending, including the description of your parcels (quantity, unit weight, dimension)' mod='upela'}
                                </li>
                                <li><i class="fa fa-caret-right listStyle" aria-hidden="true"></i>
                                    {l s='Click on \"Compare the offers\" to finalize your order' mod='upela'}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
